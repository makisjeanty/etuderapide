<?php

namespace Tests\Feature\Api;

use App\Enums\ProjectStatus;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_posts_api_lists_only_published_posts_and_supports_tag_filter(): void
    {
        $tag = Tag::query()->create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $post = Post::factory()->published()->create([
            'title' => 'Publicado API',
            'slug' => 'publicado-api',
        ]);
        $post->tags()->attach($tag);

        Post::factory()->create([
            'title' => 'Rascunho API',
            'slug' => 'rascunho-api',
            'is_published' => false,
        ]);

        $this->getJson(route('api.public.posts.index', ['tag' => 'laravel']))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.slug', 'publicado-api');

        $this->getJson(route('api.public.posts.show', ['slug' => 'publicado-api']))
            ->assertOk()
            ->assertJsonPath('data.slug', 'publicado-api');

        $this->getJson(route('api.public.posts.show', ['slug' => 'rascunho-api']))
            ->assertNotFound();
    }

    public function test_public_projects_api_lists_only_published_projects(): void
    {
        Project::factory()->published()->create([
            'title' => 'Projeto Publico API',
            'slug' => 'projeto-publico-api',
            'is_featured' => true,
        ]);

        Project::factory()->create([
            'title' => 'Projeto Privado API',
            'slug' => 'projeto-privado-api',
            'status' => ProjectStatus::Draft,
        ]);

        $this->getJson(route('api.public.projects.index', ['is_featured' => 1]))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.slug', 'projeto-publico-api');

        $this->getJson(route('api.public.projects.show', ['slug' => 'projeto-publico-api']))
            ->assertOk()
            ->assertJsonPath('data.slug', 'projeto-publico-api');
    }

    public function test_public_services_api_lists_only_active_services(): void
    {
        Service::factory()->create([
            'name' => 'Servico Publico API',
            'slug' => 'servico-publico-api',
            'is_active' => true,
        ]);

        Service::factory()->create([
            'name' => 'Servico Inativo API',
            'slug' => 'servico-inativo-api',
            'is_active' => false,
        ]);

        $this->getJson(route('api.public.services.index', ['search' => 'Publico']))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.slug', 'servico-publico-api');

        $this->getJson(route('api.public.services.show', ['slug' => 'servico-publico-api']))
            ->assertOk()
            ->assertJsonPath('data.slug', 'servico-publico-api');

        $this->getJson(route('api.public.services.show', ['slug' => 'servico-inativo-api']))
            ->assertNotFound();
    }

    public function test_v1_public_posts_route_works(): void
    {
        Post::factory()->published()->create([
            'title' => 'Post V1 Publico',
            'slug' => 'post-v1-publico',
        ]);

        $this->getJson(route('api.v1.public.posts.index'))
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'post-v1-publico');
    }
}
