<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'abilities' => ['nullable', 'array'],
            'abilities.*' => ['string'],
        ]);

        $throttleKey = strtolower($validated['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return response()->json([
                'message' => trans('auth.throttle', [
                    'seconds' => RateLimiter::availableIn($throttleKey),
                    'minutes' => ceil(RateLimiter::availableIn($throttleKey) / 60),
                ]),
            ], 429);
        }

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! check_password($validated['password'], (string) $user->password)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        RateLimiter::clear($throttleKey);

        $abilities = $this->resolveAbilities($user, $validated['abilities'] ?? null);

        $token = $user->createToken(
            $validated['device_name'] ?: 'api-token',
            $abilities
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'abilities' => $token->accessToken->abilities ?? [],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->isAdmin(),
                'email_verified' => $user->hasVerifiedEmail(),
            ],
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

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
