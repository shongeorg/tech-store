<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'slug' => \Str::slug($name, '-', 'uk'),
            'name' => \Str::ucfirst($name),
            'nav_name' => \Str::ucfirst($name),
            'emoji' => '🧩',
            'sort' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
