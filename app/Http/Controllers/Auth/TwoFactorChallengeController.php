<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TwoFactorChallengeController extends Controller
{
    public function index()
    {
        // Se ainda não tem um código válido enviado, envia um
        $user = Auth::user();
        if (! $user->twoFactorCodes()->where('expires_at', '>', now())->exists()) {
            $this->generateAndSendCode($user);
        }

        return view('auth.2fa');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();
        $code = $user->twoFactorCodes()
            ->where('expires_at', '>', now())
            ->first();

        if (! $code || ! Hash::check($request->code, $code->code)) {
            return back()->withErrors(['code' => 'O código informado é inválido ou expirou.']);
        }

        // Sucesso: marca a sessão como verificada
        session(['2fa_verified' => true]);
        $code->delete(); // Remove o código usado

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resend()
    {
        $this->generateAndSendCode(Auth::user());

        return back()->with('success', 'Um novo código foi enviado para seu e-mail.');
    }

    protected function generateAndSendCode($user)
    {
        // Limpa códigos antigos
        $user->twoFactorCodes()->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->twoFactorCodes()->create([
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code));
    }
}
