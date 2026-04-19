<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class TokenManagementController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'abilities' => ['nullable', 'array'],
            'abilities.*' => ['string'],
        ]);

        $abilities = $this->resolveAbilities($user, $validated['abilities'] ?? null);
        $token = $user->createToken($validated['name'], $abilities);

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'abilities' => $token->accessToken->abilities ?? [],
            'data' => [
                'id' => $token->accessToken->id,
                'name' => $token->accessToken->name,
                'created_at' => $token->accessToken->created_at?->toIso8601String(),
            ],
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $currentTokenId = $request->user()?->currentAccessToken()?->id;

        $tokens = $request->user()
            ->tokens()
            ->latest()
            ->get()
            ->map(fn (PersonalAccessToken $token) => [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities ?? [],
                'last_used_at' => $token->last_used_at?->toIso8601String(),
                'created_at' => $token->created_at?->toIso8601String(),
                'expires_at' => $token->expires_at?->toIso8601String(),
                'is_current' => $token->id === $currentTokenId,
            ]);

        return response()->json([
            'data' => $tokens,
        ]);
    }

    public function destroy(Request $request, string $token): JsonResponse
    {
        $tokenModel = $request->user()
            ->tokens()
            ->whereKey($token)
            ->firstOrFail();

        $tokenModel->delete();

        return response()->json([], 204);
    }

    protected function resolveAbilities(User $user, ?array $requestedAbilities): array
    {
        $availableAbilities = $user->apiAbilities();

        if ($requestedAbilities === null || $requestedAbilities === []) {
            return $availableAbilities;
        }

        $requestedAbilities = array_values(array_unique($requestedAbilities));
        $invalidAbilities = array_values(array_diff($requestedAbilities, $availableAbilities));

        if ($invalidAbilities !== []) {
            throw ValidationException::withMessages([
                'abilities' => ['Requested abilities are not allowed: '.implode(', ', $invalidAbilities)],
            ]);
        }

        return $requestedAbilities;
    }
}
