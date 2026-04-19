<?php

namespace Tests\Feature\Admin;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCrudTest extends TestCase
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

    public function test_non_admin_cannot_list_services(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get(route('admin.services.index'))->assertForbidden();
    }

    public function test_admin_crud_activation_and_audit(): void
    {
        $admin = $this->admin();

        $this->actingAsAdmin($admin)
            ->post(route('admin.services.store'), [
                'name' => 'Landing page',
                'slug' => 'landing-page',
                'short_description' => 'Fast landing pages.',
                'full_description' => 'Details here.',
                'price_from' => '499.00',
                'delivery_time' => '2 weeks',
                'category' => 'web',
                'call_to_action' => 'Contact me',
            ])
            ->assertRedirect(route('admin.services.index'));

        $service = Service::query()->where('slug', 'landing-page')->firstOrFail();
        $this->assertTrue($service->is_active);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'services.created',
        ]);

        // Update with changed name to trigger dirty check
        $this->actingAsAdmin($admin)
            ->put(route('admin.services.update', $service), [
                'name' => 'Landing page updated',
                'slug' => 'landing-page',
                'short_description' => 'Fast landing pages.',
                'full_description' => 'Details here.',
                'price_from' => '499.00',
                'delivery_time' => '2 weeks',
                'category' => 'web',
                'call_to_action' => 'Contact me',
            ])
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'services.updated',
        ]);

        // Deactivate
        $this->actingAsAdmin($admin)
            ->put(route('admin.services.update', $service), [
                'name' => 'Landing page updated',
                'slug' => 'landing-page',
                'short_description' => 'Fast landing pages.',
                'full_description' => 'Details here.',
                'price_from' => '499.00',
                'delivery_time' => '2 weeks',
                'category' => 'web',
                'call_to_action' => 'Contact me',
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.services.index'));

        // Delete
        $this->actingAsAdmin($admin)
            ->delete(route('admin.services.destroy', $service))
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'services.deleted',
        ]);
    }
}
