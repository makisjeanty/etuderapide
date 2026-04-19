<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_issue_api_token_and_fetch_current_user(): void
    {
        $user = User::factory()->create([
            'email' => 'api@example.com',
            'email_verified_at' => now(),
        ]);

        $loginResponse = $this->postJson(route('api.login'), [
            'email' => 'api@example.com',
            'password' => 'password',
            'device_name' => 'phpunit',
            'abilities' => ['profile:read'],
        ]);

        $loginResponse->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'api@example.com')
            ->assertJsonPath('abilities.0', 'profile:read');

        $token = $loginResponse->json('token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson(route('api.me'))
            ->assertOk()
            ->assertJsonPath('data.email', 'api@example.com')
            ->assertJsonPath('data.email_verified', true)
            ->assertJsonPath('data.abilities.0', 'profile:read');
    }

    public function test_api_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'api@example.com',
        ]);

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(422);
    }

    public function test_authenticated_user_can_revoke_current_token(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('phpunit', $user->apiAbilities());

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson(route('api.logout'))
            ->assertNoContent();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_authenticated_user_can_list_and_revoke_own_named_tokens(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $current = $user->createToken('current-device', $user->apiAbilities());
        $secondary = $user->createToken('secondary-device', $user->apiAbilities());

        Sanctum::actingAs($user, $user->apiAbilities(), 'sanctum');
        $user->withAccessToken($current->accessToken);

        $this->getJson(route('api.tokens.index'))
            ->assertOk()
            ->assertJsonFragment(['name' => 'current-device'])
            ->assertJsonFragment(['name' => 'secondary-device'])
            ->assertJsonFragment(['is_current' => true]);

        $this->deleteJson(route('api.tokens.destroy', ['token' => $secondary->accessToken->id]))
            ->assertNoContent();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $secondary->accessToken->id,
        ]);
    }

    public function test_authenticated_user_can_create_named_token_with_selected_abilities(): void
    {
        $user = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        $current = $user->createToken('current-device', $user->apiAbilities());

        Sanctum::actingAs($user, $user->apiAbilities(), 'sanctum');
        $user->withAccessToken($current->accessToken);

        $this->postJson(route('api.tokens.store'), [
            'name' => 'reporting-script',
            'abilities' => ['profile:read', 'dashboard:read'],
        ])->assertCreated()
            ->assertJsonPath('data.name', 'reporting-script')
            ->assertJsonPath('abilities.0', 'profile:read')
            ->assertJsonPath('abilities.1', 'dashboard:read');
    }

    public function test_api_login_rejects_requested_abilities_outside_user_scope(): void
    {
        $user = User::factory()->create([
            'email' => 'api@example.com',
            'email_verified_at' => now(),
        ]);

        $this->postJson(route('api.login'), [
            'email' => 'api@example.com',
            'password' => 'password',
            'abilities' => ['users:manage'],
        ])->assertStatus(422)
            ->assertJsonValidationErrors('abilities');
    }

    public function test_v1_login_and_me_routes_work(): void
    {
        User::factory()->create([
            'email' => 'v1@example.com',
            'email_verified_at' => now(),
        ]);

        $loginResponse = $this->postJson(route('api.v1.login'), [
            'email' => 'v1@example.com',
            'password' => 'password',
            'device_name' => 'v1-client',
        ]);

        $loginResponse->assertOk()
            ->assertJsonPath('user.email', 'v1@example.com');

        $token = $loginResponse->json('token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson(route('api.v1.me'))
            ->assertOk()
            ->assertJsonPath('data.email', 'v1@example.com');
    }
}
