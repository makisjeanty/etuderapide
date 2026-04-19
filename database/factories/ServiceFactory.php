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
        $name = $this->faker->words(3, true).' service';

        return [
            'user_id' => User::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numerify('###'),
            'short_description' => $this->faker->sentence(12),
            'full_description' => $this->faker->paragraphs(3, true),
            'price_from' => $this->faker->optional()->randomFloat(2, 50, 5000),
            'delivery_time' => $this->faker->randomElement(['1 week', '2 weeks', '1 month']),
            'category_id' => Category::factory(),
            'is_active' => true,
            'call_to_action' => 'Book a call',
            'seo_title' => null,
            'seo_description' => null,
        ];
    }
}
