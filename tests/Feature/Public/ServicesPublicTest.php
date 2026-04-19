<?php

namespace Tests\Feature\Public;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_index_is_ok(): void
    {
        $this->get(route('services.index'))->assertOk();
    }

    public function test_only_active_services_are_listed(): void
    {
        $author = User::factory()->admin()->create();

        Service::factory()->create([
            'user_id' => $author->id,
            'name' => 'Active Offering',
            'slug' => 'active-offering',
            'is_active' => true,
        ]);

        Service::factory()->create([
            'user_id' => $author->id,
            'name' => 'Hidden Service',
            'slug' => 'hidden-service',
            'is_active' => false,
        ]);

        $response = $this->get(route('services.index'));

        $response->assertOk();
        $response->assertSee('Active Offering');
        $response->assertDontSee('Hidden Service');
    }

    public function test_active_service_show_is_ok(): void
    {
        $author = User::factory()->admin()->create();

        Service::factory()->create([
            'user_id' => $author->id,
            'name' => 'Consulting',
            'slug' => 'consulting',
            'short_description' => 'One line pitch.',
            'is_active' => true,
        ]);

        $this->get(route('services.show', 'consulting'))
            ->assertOk()
            ->assertSee('Consulting')
            ->assertSee('One line pitch.');
    }

    public function test_inactive_service_show_returns_404(): void
    {
        $author = User::factory()->admin()->create();

        Service::factory()->create([
            'user_id' => $author->id,
            'name' => 'Off',
            'slug' => 'off-service',
            'is_active' => false,
        ]);

        $this->get(route('services.show', 'off-service'))->assertNotFound();
    }
}
