<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::query()->updateOrCreate(
            ['code' => 'TECHNO2026'],
            ['amount' => 500, 'is_active' => true]
        );
    }
}
