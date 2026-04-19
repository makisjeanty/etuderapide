<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostIndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $posts = Post::query()
            ->with(['category:id,name,slug,type', 'author:id,name', 'tags:id,name,slug'])
            ->where('is_published', true)
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', $search)
                        ->orWhere('slug', 'like', $search);
                });
            })
            ->when($request->filled('category_id'), fn ($builder) => $builder->where('category_id', $request->integer('category_id')))
            ->when($request->filled('tag'), function ($builder) use ($request) {
                $tag = $request->string('tag')->toString();

                $builder->whereHas('tags', fn ($tagQuery) => $tagQuery->where('slug', $tag)->orWhere('name', $tag));
            })
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();

        $data = $posts->getCollection()->map(fn (Post $post) => [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => str($post->body)->limit(180)->toString(),
            'published_at' => $post->published_at?->toIso8601String(),
            'featured_image' => $post->featured_image,
            'seo_title' => $post->seo_title,
            'seo_description' => $post->seo_description,
            'category' => $post->category ? [
                'id' => $post->category->id,
                'name' => $post->category->name,
                'slug' => $post->category->slug,
            ] : null,
            'author' => $post->author ? [
                'id' => $post->author->id,
                'name' => $post->author->name,
            ] : null,
            'tags' => $post->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])->values()->all(),
        ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'count' => $data->count(),
            ],
        ]);
    }
}
