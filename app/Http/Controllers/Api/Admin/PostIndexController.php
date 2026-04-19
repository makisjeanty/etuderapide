<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostIndexController extends Controller
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
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', $search)
                        ->orWhere('slug', 'like', $search);
                });
            })
            ->when($request->filled('category_id'), fn ($builder) => $builder->where('category_id', $request->integer('category_id')))
            ->when($request->has('is_published'), fn ($builder) => $builder->where('is_published', $request->boolean('is_published')))
            ->when($request->filled('created_from'), fn ($builder) => $builder->whereDate('created_at', '>=', $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString()))
            ->when($request->filled('created_to'), fn ($builder) => $builder->whereDate('created_at', '<=', $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()))
            ->when($request->filled('published_from'), fn ($builder) => $builder->whereDate('published_at', '>=', $request->date('published_from')?->toDateString() ?? $request->string('published_from')->toString()))
            ->when($request->filled('published_to'), fn ($builder) => $builder->whereDate('published_at', '<=', $request->date('published_to')?->toDateString() ?? $request->string('published_to')->toString()))
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $posts = $query->paginate($perPage)->withQueryString();
        $data = $posts->getCollection()->map(fn (Post $post) => $this->serializePost($post));

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'count' => $data->count(),
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }

    public static function serializePost(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'is_published' => $post->is_published,
            'published_at' => $post->published_at?->toIso8601String(),
            'seo_title' => $post->seo_title,
            'seo_description' => $post->seo_description,
            'featured_image' => $post->featured_image,
            'category' => $post->category ? [
                'id' => $post->category->id,
                'name' => $post->category->name,
                'slug' => $post->category->slug,
                'type' => $post->category->type,
            ] : null,
            'author' => $post->author ? [
                'id' => $post->author->id,
                'name' => $post->author->name,
                'email' => $post->author->email,
            ] : null,
            'tags' => $post->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])->values()->all(),
            'created_at' => $post->created_at?->toIso8601String(),
            'updated_at' => $post->updated_at?->toIso8601String(),
        ];
    }
}
