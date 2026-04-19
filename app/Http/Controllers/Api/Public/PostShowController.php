<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostShowController extends Controller
{
    public function __invoke(string $slug): JsonResponse
    {
        $post = Post::query()
            ->with(['category:id,name,slug,type', 'author:id,name', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'body' => $post->body,
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
            ],
        ]);
    }
}
