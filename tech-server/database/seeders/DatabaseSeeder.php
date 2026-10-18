<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $images = (new ProductImageSeeder)->run();

        $this->call(AdminUserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PromoCodeSeeder::class);

        $this->callWith(ProductSeeder::class, ['imagesBySlug' => $images]);
    }
}
