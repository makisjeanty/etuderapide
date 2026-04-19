<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-leads',
            'manage-projects',
            'manage-posts',
            'manage-services',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign existing permissions
        $admin = Role::findOrCreate('admin');
        $admin->givePermissionTo(Permission::all());

        $editor = Role::findOrCreate('editor');
        $editor->givePermissionTo([
            'view-dashboard',
            'manage-leads',
            'manage-projects',
            'manage-posts',
            'manage-services',
        ]);

        Role::findOrCreate('user')->givePermissionTo(['view-dashboard']);
    }
}
