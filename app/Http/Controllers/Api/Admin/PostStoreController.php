<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;

class PostStoreController extends Controller
{
    public function __invoke(StorePostRequest $request): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);
        $post->load(['category:id,name,slug,type', 'author:id,name,email', 'tags:id,name,slug']);

        AuditLogger::record($request->user(), 'post.created', $post::class, $post->id, [
            'title' => $post->title,
        ], $request);

        return response()->json([
            'data' => PostIndexController::serializePost($post) + [
                'body' => $post->body,
            ],
        ], 201);
    }
}
