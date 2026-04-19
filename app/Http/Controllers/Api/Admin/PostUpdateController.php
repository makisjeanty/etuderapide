<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;

class PostUpdateController extends Controller
{
    public function __invoke(UpdatePostRequest $request, Post $post): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published'] && empty($data['published_at']) && ! $post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);
        $post->load(['category:id,name,slug,type', 'author:id,name,email', 'tags:id,name,slug']);

        AuditLogger::record($request->user(), 'post.updated', $post::class, $post->id, [
            'title' => $post->title,
        ], $request);

        return response()->json([
            'data' => PostIndexController::serializePost($post) + [
                'body' => $post->body,
            ],
        ]);
    }
}
