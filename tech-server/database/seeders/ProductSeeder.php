<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * @param  array<string, string>  $imagesBySlug  product slug => Unsplash image URL
     */
    public function run(array $imagesBySlug = []): void
    {
        $catalog = json_decode(
            (string) file_get_contents(__DIR__.'/data/catalog.json'),
            true
        );

        foreach ($catalog['products'] as $row) {
            $category = Category::query()->where('slug', $row['category'])->firstOrFail();

            Product::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'category_id' => $category->id,
                    'title' => $row['title'],
                    'short' => $row['short'] ?? null,
                    'brand' => $row['brand'],
                    'price' => $row['price'],
                    'old_price' => $row['oldPrice'] ?? null,
                    'rating' => $row['rating'] ?? 0,
                    'reviews_count' => $row['reviews'] ?? 0,
                    'badge' => $row['badge'] ?? null,
                    'badge_kind' => $row['badgeKind'] ?? null,
                    'installment' => $row['installment'] ?? null,
                    'sku' => $row['sku'] ?? null,
                    'stock' => $row['stock'] ?? 'in',
                    'stock_text' => $row['stockLine'] ?? null,
                    'description' => $row['desc'] ?? null,
                    'specs' => $row['specs'] ?? null,
                    'memory' => $row['memory'] ?? null,
                    'colors' => $row['colors'] ?? null,
                    'full_specs' => $row['fullSpecs'] ?? null,
                    'images' => $imagesBySlug[$row['slug']] ?? null,
                    'emoji' => $row['emoji'] ?? null,
                    'is_active' => true,
                    'is_featured' => in_array($row['badgeKind'] ?? '', ['hit', 'new'], true),
                ]
            );
        }
    }
}
