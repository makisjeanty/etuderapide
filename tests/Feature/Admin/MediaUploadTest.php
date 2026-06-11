<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
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

    public function test_admin_can_upload_valid_image(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);

        $response = $this->actingAsAdmin($admin)
            ->postJson(route('admin.media.upload'), [
                'file' => $file,
            ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['status', 'url', 'path']);

        $path = $response->json('path');
        Storage::disk('public')->assertExists($path);
    }

    public function test_media_upload_sanitizes_extension_and_ignores_client_provided_unsafe_extension(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        // Generate a valid PNG image
        $tempImage = UploadedFile::fake()->image('temp.png', 800, 600);

        // Wrap it with client name ending in .jpg (allowed by validation) but with image/png mime type
        $file = new UploadedFile(
            $tempImage->getPathname(),
            'exploit.jpg',
            'image/png',
            null,
            true
        );

        $response = $this->actingAsAdmin($admin)
            ->postJson(route('admin.media.upload'), [
                'file' => $file,
            ]);

        $response->assertOk()
            ->assertJsonPath('status', 'success');

        $path = $response->json('path');

        // Assert that the file is stored with .png extension (detected from MIME type) rather than .jpg
        $this->assertStringEndsWith('.png', $path);
        $this->assertStringEndsNotWith('.jpg', $path);

        Storage::disk('public')->assertExists($path);

        // Make sure no php files were created
        $files = Storage::disk('public')->allFiles();
        foreach ($files as $f) {
            $this->assertStringEndsNotWith('.php', $f);
        }
    }

    public function test_unauthenticated_user_cannot_upload_media(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('photo.jpg');

        $this->postJson(route('admin.media.upload'), [
            'file' => $file,
        ])->assertStatus(401); // Redirected or unauthorized
    }

    public function test_non_admin_user_cannot_upload_media(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        $file = UploadedFile::fake()->image('photo.jpg');

        $this->actingAs($user)
            ->postJson(route('admin.media.upload'), [
                'file' => $file,
            ])
            ->assertStatus(403);
    }
}
