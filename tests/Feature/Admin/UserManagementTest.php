<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function userManager(): User
    {
        Permission::findOrCreate('manage-users');
        $role = Role::findOrCreate('manager');
        $role->givePermissionTo('manage-users');

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function actingAsManager(User $user): self
    {
        return $this->actingAs($user)->withSession(['2fa_verified' => true]);
    }

    public function test_manage_users_permission_grants_user_management_access(): void
    {
        $manager = $this->userManager();
        $target = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        Role::findOrCreate('user');

        $this->actingAsManager($manager)
            ->get(route('admin.users.index'))
            ->assertOk();

        $this->actingAsManager($manager)
            ->patch(route('admin.users.update', $target), [
                'name' => 'Atualizado',
                'email' => 'atualizado@example.com',
                'roles' => ['user'],
                'is_admin' => '0',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Atualizado',
            'email' => 'atualizado@example.com',
            'is_admin' => 0,
        ]);
    }
}
