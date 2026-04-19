<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_verified_non_admin_cannot_access_admin(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_editor_with_backoffice_permissions_can_access_admin_dashboard_but_not_user_management(): void
    {
        Permission::findOrCreate('manage-posts');
        $editorRole = Role::findOrCreate('editor');
        $editorRole->givePermissionTo('manage-posts');

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($editorRole);

        $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_user_with_view_dashboard_permission_can_access_dashboard_only(): void
    {
        Permission::findOrCreate('view-dashboard');

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        $user->givePermissionTo('view-dashboard');

        $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.posts.index'))
            ->assertForbidden();
    }

    public function test_verified_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_unverified_admin_is_redirected_to_email_verification(): void
    {
        $user = User::factory()->admin()->unverified()->create();

        $response = $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.dashboard'));

        $response->assertRedirect(route('verification.notice'));
    }
}
