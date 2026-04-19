<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogAccessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);
    }

    private function actingAsAdmin(User $admin): self
    {
        return $this->actingAs($admin)->withSession(['2fa_verified' => true]);
    }

    public function test_non_admin_cannot_access_audit_logs(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get(route('admin.audit-logs.index'))->assertForbidden();
    }

    public function test_admin_can_view_audit_logs(): void
    {
        $admin = $this->admin();
        $log = AuditLog::query()->create([
            'user_id' => $admin->id,
            'action' => 'user.updated',
            'subject_type' => User::class,
            'subject_id' => $admin->id,
            'properties' => ['field' => 'email'],
            'ip_address' => '127.0.0.1',
        ]);

        $this->actingAsAdmin($admin)
            ->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertSee('user.updated');

        $this->actingAsAdmin($admin)
            ->get(route('admin.audit-logs.show', $log))
            ->assertOk()
            ->assertSee('127.0.0.1');
    }
}
