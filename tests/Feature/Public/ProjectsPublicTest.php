<?php

namespace Tests\Feature\Public;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectsPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_index_is_ok(): void
    {
        $this->get(route('projects.index'))->assertOk();
    }

    public function test_only_published_projects_are_listed(): void
    {
        $author = User::factory()->admin()->create();

        $live = Project::factory()->published()->create([
            'user_id' => $author->id,
            'title' => 'Visible Case Study',
            'slug' => 'visible-case-study',
        ]);

        Project::factory()->create([
            'user_id' => $author->id,
            'title' => 'Secret Draft',
            'slug' => 'secret-draft',
            'status' => ProjectStatus::Draft,
        ]);

        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertSee('Visible Case Study');
        $response->assertDontSee('Secret Draft');
        $response->assertSee($live->slug);
    }

    public function test_published_project_show_is_ok(): void
    {
        $author = User::factory()->admin()->create();

        Project::factory()->published()->create([
            'user_id' => $author->id,
            'title' => 'Public Title',
            'slug' => 'public-title',
            'summary' => 'A short summary.',
        ]);

        $this->get(route('projects.show', 'public-title'))
            ->assertOk()
            ->assertSee('Public Title')
            ->assertSee('A short summary.');
    }

    public function test_draft_project_show_returns_404(): void
    {
        $author = User::factory()->admin()->create();

        Project::factory()->create([
            'user_id' => $author->id,
            'title' => 'Draft Only',
            'slug' => 'draft-only',
            'status' => ProjectStatus::Draft,
        ]);

        $this->get(route('projects.show', 'draft-only'))->assertNotFound();
    }
}
