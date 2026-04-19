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
        $title = $this->faker->sentence(3);

        return [
            'user_id' => User::factory()->admin(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numerify('####'),
            'summary' => $this->faker->paragraph(),
            'description' => $this->faker->paragraphs(4, true),
            'status' => ProjectStatus::Draft,
            'featured_image' => null,
            'tech_stack_lines' => "Laravel\nTailwind CSS",
            'repository_url' => $this->faker->optional()->url(),
            'demo_url' => $this->faker->optional()->url(),
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
