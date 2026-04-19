<?php

namespace Tests\Feature\Api;

use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_admin_can_fetch_admin_summary(): void
    {
        $admin = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        Lead::query()->create([
            'name' => 'Lead API',
            'email' => 'lead@example.com',
            'message' => 'Mensagem de teste',
            'status' => 'new',
        ]);

        Sanctum::actingAs($admin, $admin->apiAbilities());

        $this->getJson(route('api.admin.summary'))
            ->assertOk()
            ->assertJsonPath('data.total_leads_count', 1)
            ->assertJsonPath('data.new_leads_count', 1);
    }

    public function test_user_with_manage_leads_permission_can_fetch_admin_leads_api(): void
    {
        Permission::findOrCreate('manage-leads');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->givePermissionTo('manage-leads');

        Lead::query()->create([
            'name' => 'Cliente API',
            'email' => 'cliente@example.com',
            'phone' => '+5511988887777',
            'message' => 'Preciso de ajuda',
            'status' => 'read',
        ]);
        Lead::query()->create([
            'name' => 'Outro Cliente',
            'email' => 'other@example.com',
            'service_interest' => 'SEO',
            'message' => 'Outro lead',
            'status' => 'new',
        ]);

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->getJson(route('api.admin.leads.index', [
            'per_page' => 5,
            'status' => 'read',
            'search' => 'cliente',
        ]))
            ->assertOk()
            ->assertJsonPath('meta.count', 1)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.email', 'cliente@example.com');
    }

    public function test_non_admin_user_cannot_fetch_admin_summary(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->getJson(route('api.admin.summary'))
            ->assertForbidden();
    }

    public function test_unverified_admin_cannot_fetch_admin_api(): void
    {
        $admin = User::factory()->admin()->unverified()->create();

        Sanctum::actingAs($admin, $admin->apiAbilities());

        $this->getJson(route('api.admin.summary'))
            ->assertForbidden();
    }

    public function test_user_with_manage_posts_permission_can_fetch_admin_posts_api(): void
    {
        Permission::findOrCreate('manage-posts');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->givePermissionTo('manage-posts');

        $post = Post::factory()->create([
            'title' => 'API Post',
            'body' => 'Conteudo detalhado do post',
            'is_published' => true,
        ]);
        Post::factory()->create([
            'title' => 'Draft Post',
            'is_published' => false,
        ]);

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->getJson(route('api.admin.posts.index', [
            'search' => 'API',
            'is_published' => 1,
            'per_page' => 5,
        ]))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'API Post');

        $this->getJson(route('api.admin.posts.show', $post))
            ->assertOk()
            ->assertJsonPath('data.body', 'Conteudo detalhado do post');
    }

    public function test_user_with_manage_projects_permission_can_fetch_admin_projects_api(): void
    {
        Permission::findOrCreate('manage-projects');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->givePermissionTo('manage-projects');

        $project = Project::factory()->create([
            'title' => 'Projeto API',
            'description' => 'Descricao completa do projeto',
            'is_featured' => true,
        ]);
        Project::factory()->create([
            'title' => 'Projeto Rascunho',
            'is_featured' => false,
        ]);

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->getJson(route('api.admin.projects.index', [
            'search' => 'Projeto API',
            'is_featured' => 1,
            'per_page' => 5,
        ]))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Projeto API');

        $this->getJson(route('api.admin.projects.show', $project))
            ->assertOk()
            ->assertJsonPath('data.description', 'Descricao completa do projeto');
    }

    public function test_user_with_manage_services_permission_can_fetch_admin_services_api(): void
    {
        Permission::findOrCreate('manage-services');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->givePermissionTo('manage-services');

        $service = Service::factory()->create([
            'name' => 'Servico API',
            'full_description' => 'Descricao detalhada do servico',
            'is_active' => true,
        ]);
        Service::factory()->create([
            'name' => 'Servico Inativo',
            'is_active' => false,
        ]);

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->getJson(route('api.admin.services.index', [
            'search' => 'Servico API',
            'is_active' => 1,
            'per_page' => 5,
        ]))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Servico API');

        $this->getJson(route('api.admin.services.show', $service))
            ->assertOk()
            ->assertJsonPath('data.full_description', 'Descricao detalhada do servico');
    }

    public function test_admin_leads_api_supports_date_filters_and_sorting(): void
    {
        Permission::findOrCreate('manage-leads');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->givePermissionTo('manage-leads');

        $oldLead = Lead::query()->create([
            'name' => 'Lead Antigo',
            'email' => 'old@example.com',
            'message' => 'Lead antigo',
            'quoted_value' => 100,
            'status' => 'read',
        ]);
        $oldLead->forceFill([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ])->save();

        $newLead = Lead::query()->create([
            'name' => 'Lead Novo',
            'email' => 'new@example.com',
            'message' => 'Lead novo',
            'quoted_value' => 900,
            'status' => 'read',
        ]);
        $newLead->forceFill([
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ])->save();

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->getJson(route('api.admin.leads.index', [
            'status' => 'read',
            'created_from' => now()->subDays(2)->toDateString(),
            'sort_by' => 'quoted_value',
            'sort_direction' => 'desc',
        ]))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.sort_by', 'quoted_value')
            ->assertJsonPath('meta.sort_direction', 'desc')
            ->assertJsonPath('data.0.email', 'new@example.com');
    }
}
