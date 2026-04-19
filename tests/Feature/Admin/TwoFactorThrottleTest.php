<?php

namespace Tests\Feature\Admin;

use App\Mail\TwoFactorCodeMail;
use App\Models\TwoFactorCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TwoFactorThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_code_submission_is_rate_limited(): void
    {
        $user = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->actingAs($user)
                ->from(route('admin.2fa.index'))
                ->post(route('admin.2fa.store'), [
                    'code' => '000000',
                ])
                ->assertRedirect(route('admin.2fa.index'));
        }

        $this->actingAs($user)
            ->post(route('admin.2fa.store'), [
                'code' => '000000',
            ])
            ->assertStatus(429);
    }

    public function test_two_factor_resend_is_rate_limited(): void
    {
        Mail::fake();

        $user = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->actingAs($user)
                ->from(route('admin.2fa.index'))
                ->post(route('admin.2fa.resend'))
                ->assertRedirect(route('admin.2fa.index'));
        }

        $this->actingAs($user)
            ->post(route('admin.2fa.resend'))
            ->assertStatus(429);
    }

    public function test_two_factor_codes_are_hashed_in_storage(): void
    {
        Mail::fake();

        $user = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.2fa.index'))
            ->assertOk();

        $storedCode = TwoFactorCode::where('user_id', $user->id)->firstOrFail();
        $sentCode = null;

        Mail::assertSent(TwoFactorCodeMail::class, function (TwoFactorCodeMail $mail) use (&$sentCode) {
            $sentCode = $mail->code;

            return true;
        });

        $this->assertNotSame($sentCode, $storedCode->code);
        $this->assertTrue(Hash::check($sentCode, $storedCode->code));
    }
}
