<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the admin user: admin@technoprostir.ua / admin.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@technoprostir.ua'],
            [
                'name' => 'Адміністратор',
                'password' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
