<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostShowController extends BaseApiController
{
    public function __invoke(string $slug): JsonResponse
    {
        $post = Post::query()
            ->with(['category:id,name,slug,type', 'author:id,name', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return $this->respondWithResource($post, PostResource::class);
    }
}
