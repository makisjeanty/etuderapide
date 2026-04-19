<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_metrics_without_errors(): void
    {
        $admin = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        $category = Category::factory()->create([
            'type' => 'general',
        ]);

        Post::factory()->published()->create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ]);

        Project::factory()->published()->create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ]);

        Service::factory()->create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        Lead::create([
            'name' => 'Cliente Novo',
            'email' => 'novo@example.com',
            'phone' => '+5511999999999',
            'service_interest' => 'Pacote Prata',
            'message' => 'Quero saber mais.',
            'status' => 'new',
        ]);

        Lead::create([
            'name' => 'Cliente Qualificado',
            'email' => 'qualificado@example.com',
            'service_interest' => 'Pacote Ouro',
            'message' => 'Preciso de proposta.',
            'quoted_value' => 3500,
            'status' => 'replied',
        ]);

        $this->actingAs($admin)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Cliente Novo')
            ->assertSee('Cliente Qualificado')
            ->assertSee('2')
            ->assertSee('50');
    }
}
