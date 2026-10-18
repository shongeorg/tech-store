<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * @return array<string, int> slug => id
     */
    public function run(): array
    {
        $catalog = json_decode(
            (string) file_get_contents(__DIR__.'/data/catalog.json'),
            true
        );
        $ids = [];

        foreach ($catalog['categories'] as $index => $row) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'nav_name' => $row['nav'] ?? $row['name'],
                    'icon' => $row['icon'] ?? null,
                    'emoji' => $row['emoji'] ?? null,
                    'sort' => $index,
                    'is_active' => true,
                ]
            );
            $ids[$row['slug']] = $category->id;
        }

        return $ids;
    }
}
