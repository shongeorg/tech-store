<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = \Str::ucfirst(\Str::headline(fake()->unique()->words(3, true)));

        return [
            'category_id' => Category::factory(),
            'slug' => \Str::slug($title, '-', 'uk'),
            'title' => $title,
            'short' => fake()->sentence(4),
            'brand' => fake()->company(),
            'price' => fake()->numberBetween(999, 59_999),
            'old_price' => null,
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
            'reviews_count' => fake()->numberBetween(0, 120),
            'badge' => null,
            'badge_kind' => null,
            'installment' => null,
            'sku' => fake()->unique()->numberBetween(100000, 999999),
            'stock' => 'in',
            'stock_text' => 'В наявності',
            'description' => fake()->paragraph(),
            'specs' => [['Характеристика' => 'Значення']],
            'memory' => null,
            'colors' => null,
            'full_specs' => null,
            'images' => null,
            'emoji' => '📱',
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
