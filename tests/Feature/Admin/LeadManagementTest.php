<?php

namespace Tests\Feature\Admin;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LeadManagementTest extends TestCase
{
    use RefreshDatabase;

    private function leadManager(): User
    {
        Permission::findOrCreate('manage-leads');

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $user->givePermissionTo('manage-leads');

        return $user;
    }

    private function actingAsManager(User $user): self
    {
        return $this->actingAs($user)->withSession(['2fa_verified' => true]);
    }

    public function test_user_without_lead_permission_cannot_access_leads(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get(route('admin.leads.index'))->assertForbidden();
    }

    public function test_lead_manager_can_view_update_and_delete_lead(): void
    {
        $manager = $this->leadManager();
        $lead = Lead::query()->create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@example.com',
            'phone' => '+5511999999999',
            'service_interest' => 'Landing page',
            'message' => 'Quero uma proposta.',
            'status' => 'new',
        ]);

        $this->actingAsManager($manager)
            ->get(route('admin.leads.index'))
            ->assertOk()
            ->assertSee('Cliente Teste');

        $this->actingAsManager($manager)
            ->get(route('admin.leads.show', $lead))
            ->assertOk()
            ->assertSee('cliente@example.com');

        $lead->refresh();
        $this->assertSame('read', $lead->status);

        $this->actingAsManager($manager)
            ->patch(route('admin.leads.status', $lead), [
                'status' => 'replied',
                'internal_notes' => 'Contato realizado via WhatsApp.',
                'payment_link' => 'https://example.com/pay',
                'quoted_value' => '2499.90',
            ])
            ->assertRedirect(route('admin.leads.show', $lead));

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'replied',
            'payment_link' => 'https://example.com/pay',
        ]);

        $this->actingAsManager($manager)
            ->delete(route('admin.leads.destroy', $lead))
            ->assertRedirect(route('admin.leads.index'));

        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
        ]);
    }
}
