<?php

namespace Tests\Feature\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostCrudTest extends TestCase
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

    public function test_non_admin_cannot_list_posts(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get(route('admin.posts.index'))->assertForbidden();
    }

    public function test_editor_with_manage_posts_permission_can_list_posts(): void
    {
        Permission::findOrCreate('manage-posts');
        $editorRole = Role::findOrCreate('editor');
        $editorRole->givePermissionTo('manage-posts');

        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($editorRole);

        $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->get(route('admin.posts.index'))
            ->assertOk();
    }

    public function test_admin_can_create_update_and_delete_post_with_audit(): void
    {
        $admin = $this->admin();

        $this->actingAsAdmin($admin)
            ->get(route('admin.posts.create'))
            ->assertOk();

        $payload = [
            'title' => 'Hello world',
            'slug' => 'hello-world',
            'body' => 'Plain text body for the post.',
            'is_published' => '1',
        ];

        $this->actingAsAdmin($admin)
            ->post(route('admin.posts.store'), $payload)
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'slug' => 'hello-world',
            'title' => 'Hello world',
            'user_id' => $admin->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'posts.created',
        ]);

        $post = Post::query()->where('slug', 'hello-world')->firstOrFail();

        $this->actingAsAdmin($admin)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Hello world updated',
                'slug' => 'hello-world',
                'body' => 'Updated body.',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Hello world updated',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'posts.updated',
        ]);

        $this->actingAsAdmin($admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'posts.deleted',
        ]);
    }
}
