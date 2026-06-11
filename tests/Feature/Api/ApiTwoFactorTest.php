<?php

namespace Tests\Feature\Api;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_privileged_user_api_login_bypasses_2fa(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => 'user@example.com',
            'password' => 'password',
            'device_name' => 'client',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'user']);

        $this->assertFalse($response->json('user.is_admin'));
        Mail::assertNothingSent();
    }

    public function test_privileged_user_api_login_without_code_returns_403_and_sends_mail(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson(route('api.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'client',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('2fa_required', true)
            ->assertJsonStructure(['error', '2fa_required']);

        Mail::assertSent(TwoFactorCodeMail::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email) && ! empty($mail->code);
        });

        $this->assertDatabaseHas('two_factor_codes', [
            'user_id' => $admin->id,
        ]);
    }

    public function test_privileged_user_api_login_with_incorrect_code_returns_403(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        // Trigger code generation
        $this->postJson(route('api.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'client',
        ])->assertStatus(403);

        // Submit incorrect code
        $response = $this->postJson(route('api.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'client',
            'code' => '999999',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('2fa_required', true)
            ->assertJsonPath('error', 'Invalid or expired two-factor code');
    }

    public function test_privileged_user_api_login_with_correct_code_succeeds_and_returns_2fa_verified_token(): void
    {
        Mail::fake();

        $admin = User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        // Trigger code generation
        $this->postJson(route('api.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'client',
        ])->assertStatus(403);

        // Fetch generated code from sent email
        $sentMail = null;
        Mail::assertSent(TwoFactorCodeMail::class, function ($mail) use (&$sentMail) {
            $sentMail = $mail;

            return true;
        });

        $this->assertNotNull($sentMail);
        $code = $sentMail->code;

        // Submit correct code
        $response = $this->postJson(route('api.login'), [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'client',
            'code' => $code,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'abilities']);

        $this->assertContains('2fa:verified', $response->json('abilities'));

        // The code should be deleted after successful usage
        $this->assertDatabaseMissing('two_factor_codes', [
            'user_id' => $admin->id,
        ]);
    }

    public function test_api_token_management_enforces_2fa_verified_for_privileged_users(): void
    {
        $admin = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        // Token without 2fa:verified
        $unverifiedToken = $admin->createToken('unverified', $admin->apiAbilities());

        Sanctum::actingAs($admin, $admin->apiAbilities(), 'sanctum');
        $admin->withAccessToken($unverifiedToken->accessToken);

        $this->postJson(route('api.tokens.store'), [
            'name' => 'new-token',
            'abilities' => ['profile:read'],
        ])
            ->assertStatus(403)
            ->assertJsonPath('error', 'Forbidden: Two-factor authentication required for token operations');

        // Token with 2fa:verified
        $verifiedToken = $admin->createToken('verified', array_merge($admin->apiAbilities(), ['2fa:verified']));

        Sanctum::actingAs($admin, array_merge($admin->apiAbilities(), ['2fa:verified']), 'sanctum');
        $admin->withAccessToken($verifiedToken->accessToken);

        $this->postJson(route('api.tokens.store'), [
            'name' => 'new-token',
            'abilities' => ['profile:read'],
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'new-token');
    }
}
