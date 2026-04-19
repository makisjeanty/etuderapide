<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if (! check_password((string) $value, (string) $request->user()->password)) {
                        $fail(__('auth.password'));
                    }
                },
            ],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => hash_password($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
