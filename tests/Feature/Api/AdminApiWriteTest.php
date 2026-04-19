<?php

namespace Tests\Feature\Api;

use App\Enums\ProjectStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminApiWriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_manage_posts_user_can_create_update_and_delete_post_via_v1_api(): void
    {
        Permission::findOrCreate('manage-posts');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->givePermissionTo('manage-posts');

        Sanctum::actingAs($user, $user->apiAbilities());

        $create = $this->postJson(route('api.v1.admin.posts.store'), [
            'title' => 'Post API V1',
            'body' => 'Corpo do post API',
            'is_published' => true,
        ]);

        $create->assertCreated()
            ->assertJsonPath('data.title', 'Post API V1');

        $postId = $create->json('data.id');

        $this->patchJson(route('api.v1.admin.posts.update', ['post' => $postId]), [
            'title' => 'Post API V1 Atualizado',
            'slug' => 'post-api-v1',
            'body' => 'Corpo atualizado',
            'is_published' => true,
        ])->assertOk()
            ->assertJsonPath('data.title', 'Post API V1 Atualizado');

        $this->deleteJson(route('api.v1.admin.posts.destroy', ['post' => $postId]))
            ->assertNoContent();

        $this->assertDatabaseMissing('posts', ['id' => $postId]);
    }

    public function test_manage_projects_user_can_create_update_and_delete_project_via_v1_api(): void
    {
        Permission::findOrCreate('manage-projects');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->givePermissionTo('manage-projects');

        Sanctum::actingAs($user, $user->apiAbilities());

        $create = $this->postJson(route('api.v1.admin.projects.store'), [
            'title' => 'Projeto API V1',
            'summary' => 'Resumo do projeto',
            'description' => 'Descricao completa',
            'status' => ProjectStatus::Draft->value,
            'is_featured' => true,
        ]);

        $create->assertCreated()
            ->assertJsonPath('data.title', 'Projeto API V1');

        $projectId = $create->json('data.id');

        $this->patchJson(route('api.v1.admin.projects.update', ['project' => $projectId]), [
            'title' => 'Projeto API V1 Atualizado',
            'summary' => 'Resumo atualizado',
            'description' => 'Descricao atualizada',
            'status' => ProjectStatus::Published->value,
            'is_featured' => false,
        ])->assertOk()
            ->assertJsonPath('data.status', ProjectStatus::Published->value);

        $this->deleteJson(route('api.v1.admin.projects.destroy', ['project' => $projectId]))
            ->assertNoContent();

        $this->assertDatabaseMissing('projects', ['id' => $projectId]);
    }

    public function test_manage_services_user_can_create_update_and_delete_service_via_v1_api(): void
    {
        Permission::findOrCreate('manage-services');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->givePermissionTo('manage-services');

        Sanctum::actingAs($user, $user->apiAbilities());

        $create = $this->postJson(route('api.v1.admin.services.store'), [
            'name' => 'Servico API V1',
            'short_description' => 'Resumo',
            'full_description' => 'Descricao longa',
            'is_active' => '1',
        ]);

        $create->assertCreated()
            ->assertJsonPath('data.name', 'Servico API V1');

        $serviceId = $create->json('data.id');

        $this->patchJson(route('api.v1.admin.services.update', ['service' => $serviceId]), [
            'name' => 'Servico API V1 Atualizado',
            'slug' => 'servico-api-v1',
            'short_description' => 'Resumo atualizado',
            'full_description' => 'Descricao longa atualizada',
            'is_active' => '0',
        ])->assertOk()
            ->assertJsonPath('data.is_active', false);

        $this->deleteJson(route('api.v1.admin.services.destroy', ['service' => $serviceId]))
            ->assertNoContent();

        $this->assertDatabaseMissing('services', ['id' => $serviceId]);
    }

    public function test_manage_leads_user_can_update_and_delete_lead_via_v1_api(): void
    {
        Permission::findOrCreate('manage-leads');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->givePermissionTo('manage-leads');

        $lead = Lead::query()->create([
            'name' => 'Lead API',
            'email' => 'lead-api@example.com',
            'message' => 'Mensagem lead',
            'status' => 'new',
        ]);

        Sanctum::actingAs($user, $user->apiAbilities());

        $this->patchJson(route('api.v1.admin.leads.update', ['lead' => $lead->id]), [
            'status' => 'replied',
            'internal_notes' => 'Contato realizado',
            'payment_link' => 'https://example.com/pay',
            'quoted_value' => 3500,
        ])->assertOk()
            ->assertJsonPath('data.status', 'replied');

        $this->deleteJson(route('api.v1.admin.leads.destroy', ['lead' => $lead->id]))
            ->assertNoContent();

        $this->assertDatabaseMissing('leads', ['id' => $lead->id]);
    }
}
