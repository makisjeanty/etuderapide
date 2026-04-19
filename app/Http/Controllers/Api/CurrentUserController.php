<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->isAdmin(),
                'email_verified' => $user->hasVerifiedEmail(),
                'roles' => $user->getRoleNames()->values()->all(),
                'abilities' => $user->currentAccessToken()?->abilities ?? [],
            ],
        ]);
    }
}
