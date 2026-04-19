<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
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

    public function test_non_admin_cannot_manage_categories(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->get(route('admin.categories.index'))->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_category(): void
    {
        $admin = $this->admin();

        $this->actingAsAdmin($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Blog',
                'slug' => '',
                'description' => 'Categorias do blog.',
                'type' => 'post',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::query()->where('name', 'Blog')->firstOrFail();

        $this->assertSame('blog', $category->slug);
        $this->assertSame('post', $category->type);

        $this->actingAsAdmin($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Blog Institucional',
                'slug' => '',
                'description' => 'Categorias editoriais.',
                'type' => 'general',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category->refresh();

        $this->assertSame('blog-institucional', $category->slug);
        $this->assertSame('general', $category->type);

        $this->actingAsAdmin($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
