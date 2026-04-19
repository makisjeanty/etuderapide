<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $user = User::query()->where('is_admin', true)->first() ?? User::factory()->admin()->create();
        $category = Category::query()->whereIn('type', ['post', 'general'])->first() ?? Category::factory()->state(['type' => 'post'])->create();

        return [
            'author_id' => $user->id,
            'category_id' => $category->id,
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numerify('####'),
            'body' => $this->faker->paragraphs(3, true),
            'is_published' => false,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
