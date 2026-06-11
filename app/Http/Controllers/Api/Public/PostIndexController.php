<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostIndexController extends BaseApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $posts = Post::query()
            ->with(['category:id,name,slug,type', 'author:id,name', 'tags:id,name,slug'])
            ->where('is_published', true)
            ->filterBySearch($request->string('search')->toString())
            ->filterByCategory($request->integer('category_id') ?: null)
            ->filterByTag($request->string('tag')->toString())
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();

        return $this->respondWithPagination($posts, PostResource::class);
    }
}
