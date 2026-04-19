<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostShowController extends Controller
{
    public function __invoke(Request $request, Post $post): JsonResponse
    {
        abort_unless(
            $request->user()?->canManagePosts() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $post->loadMissing(['category:id,name,slug,type', 'author:id,name,email', 'tags:id,name,slug']);

        return response()->json([
            'data' => PostIndexController::serializePost($post) + [
                'body' => $post->body,
            ],
        ]);
    }
}
