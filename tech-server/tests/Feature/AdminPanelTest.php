<?php

use App\Models\User;

test('admin panel requires authentication', function () {
    $this->get('/admin')->assertRedirect('/login');
});

test('admin panel is forbidden for regular users', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get('/admin')->assertNotFound();
});

test('admin panel is accessible for admins', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->get('/admin')->assertOk();
});

test('admin user seeder creates the admin account', function () {
    $this->seed(AdminUserSeeder::class);

    expect(User::query()->where('email', 'admin@technoprostir.ua')->where('is_admin', true)->exists())->toBeTrue();
});
