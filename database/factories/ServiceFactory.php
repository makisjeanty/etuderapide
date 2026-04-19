<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = fake()->words(3, true).' service';

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'short_description' => fake()->sentence(12),
            'full_description' => fake()->paragraphs(3, true),
            'price_from' => fake()->optional()->randomFloat(2, 50, 5000),
            'delivery_time' => fake()->randomElement(['1 week', '2 weeks', '1 month']),
            'category_id' => Category::factory(),
            'is_active' => true,
            'call_to_action' => 'Book a call',
            'seo_title' => null,
            'seo_description' => null,
        ];
    }
}
