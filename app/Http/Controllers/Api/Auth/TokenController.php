<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'code' => ['nullable', 'string', 'size:6'],
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

        // 2FA check for privileged users
        if ($user->canAccessAdminPanel()) {
            $code = $request->input('code');

            if (! $code) {
                // Generate and send 2FA code
                $this->generateAndSendCode($user);

                return response()->json([
                    'error' => 'Two-factor authentication required',
                    '2fa_required' => true,
                ], 403);
            }

            $storedCode = $user->twoFactorCodes()
                ->where('expires_at', '>', now())
                ->first();

            if (! $storedCode || ! Hash::check($code, $storedCode->code)) {
                return response()->json([
                    'error' => 'Invalid or expired two-factor code',
                    '2fa_required' => true,
                ], 403);
            }

            // Success - delete the used code
            $storedCode->delete();
        }

        $abilities = $this->resolveAbilities($user, $validated['abilities'] ?? null);

        // Append 2fa:verified for privileged users who successfully verified
        if ($user->canAccessAdminPanel()) {
            $abilities[] = '2fa:verified';
        }

        $token = $user->createToken(
            $validated['device_name'] ?: 'api-token',
            $abilities,
            now()->addDays(30)
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

    protected function generateAndSendCode(User $user): void
    {
        $user->twoFactorCodes()->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->twoFactorCodes()->create([
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code));
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
