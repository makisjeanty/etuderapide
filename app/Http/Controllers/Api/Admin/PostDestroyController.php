<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostDestroyController extends Controller
{
    public function __invoke(Request $request, Post $post): JsonResponse
    {
        abort_unless(
            $request->user()?->canManagePosts() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $id = $post->id;
        $title = $post->title;
        $post->delete();

        AuditLogger::record($request->user(), 'post.deleted', Post::class, $id, [
            'title' => $title,
        ], $request);

        return response()->json([], 204);
    }
}
