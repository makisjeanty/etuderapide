<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'summary' => fake()->paragraph(),
            'description' => fake()->paragraphs(4, true),
            'status' => ProjectStatus::Draft,
            'featured_image' => null,
            'tech_stack' => ['PHP', 'Laravel'],
            'repository_url' => fake()->optional()->url(),
            'demo_url' => fake()->optional()->url(),
            'started_at' => now()->subMonths(2),
            'finished_at' => now()->subMonth(),
            'is_featured' => false,
            'seo_title' => null,
            'seo_description' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::Published,
        ]);
    }
}
