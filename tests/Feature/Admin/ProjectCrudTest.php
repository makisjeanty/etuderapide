<?php

namespace Tests\Feature\Admin;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
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

    public function test_non_admin_cannot_list_projects(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get(route('admin.projects.index'))->assertForbidden();
    }

    public function test_admin_crud_publish_and_audit(): void
    {
        $admin = $this->admin();

        $this->actingAsAdmin($admin)
            ->get(route('admin.projects.create'))
            ->assertOk();

        $this->actingAsAdmin($admin)
            ->post(route('admin.projects.store'), [
                'title' => 'Case Study App',
                'slug' => 'case-study-app',
                'summary' => 'Short summary.',
                'description' => 'Long description here.',
                'status' => ProjectStatus::Draft->value,
                'tech_stack_lines' => "PHP\nLaravel",
                'is_featured' => '1',
            ])
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', [
            'slug' => 'case-study-app',
            'title' => 'Case Study App',
            'user_id' => $admin->id,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'projects.created',
        ]);

        $project = Project::query()->where('slug', 'case-study-app')->firstOrFail();

        $this->actingAsAdmin($admin)
            ->put(route('admin.projects.update', $project), [
                'title' => 'Case Study App',
                'slug' => 'case-study-app',
                'summary' => 'Short summary.',
                'description' => 'Long description here.',
                'status' => ProjectStatus::Published->value,
                'tech_stack_lines' => "PHP\nLaravel",
                'is_featured' => '1',
            ])
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'projects.updated',
        ]);

        $this->actingAsAdmin($admin)
            ->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'projects.deleted',
        ]);
    }
}
