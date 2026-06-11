<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostIndexController extends BaseApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless(
            $request->user()?->canManagePosts() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $perPage = max(1, min((int) $request->integer('per_page', $request->integer('limit', 10)), 50));
        $sortBy = $request->string('sort_by', 'published_at')->toString();
        $sortDirection = strtolower($request->string('sort_direction', 'desc')->toString()) === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['created_at', 'updated_at', 'published_at', 'title'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'published_at';
        }

        $query = Post::query()
            ->with(['category:id,name,slug,type', 'author:id,name,email', 'tags:id,name,slug'])
            ->filterBySearch($request->string('search')->toString())
            ->filterByCategory($request->integer('category_id') ?: null)
            ->filterByPublished($request->has('is_published') ? $request->boolean('is_published') : null)
            ->filterByDateRange(
                $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString(),
                $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()
            )
            ->filterByPublishedRange(
                $request->date('published_from')?->toDateString() ?? $request->string('published_from')->toString(),
                $request->date('published_to')?->toDateString() ?? $request->string('published_to')->toString()
            )
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $posts = $query->paginate($perPage)->withQueryString();

        return $this->respondWithPagination($posts, PostResource::class, [
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
        ]);
    }
}
