<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiAssistantControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ai_generation_uses_pipeline_service_response(): void
    {
        Http::fake([
            'http://localhost:3001/analyze' => Http::response([
                'status' => 'success',
                'analysis' => 'Texto gerado pela pipeline',
            ]),
        ]);

        $admin = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->withSession(['2fa_verified' => true])
            ->postJson(route('admin.ai.generate'), [
                'command' => 'seo_title',
                'context' => 'Landing page premium',
            ])
            ->assertOk()
            ->assertJson([
                'status' => 'success',
                'result' => 'Texto gerado pela pipeline',
            ]);
    }
}
