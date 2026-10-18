<?php

use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    $this->category = Category::factory()->create(['slug' => 'test-cat', 'name' => 'Тест-категорія']);
});

test('health endpoint responds', function () {
    $this->getJson('/api/v1/health')
        ->assertOk()
        ->assertJsonPath('service', 'technoprostir-api');
});

test('products endpoint paginates and wraps resources', function () {
    Product::factory()->count(15)->create(['category_id' => $this->category->id]);

    $response = $this->getJson('/api/v1/products?per_page=12');

    $response->assertOk();
    $response->assertJsonCount(12, 'data');
    $response->assertJsonPath('meta.current_page', 1);
    $response->assertJsonPath('meta.last_page', 2);
    $response->assertJsonStructure(['data' => [['slug', 'title', 'price', 'image', 'category' => ['slug']]]]);
});

test('products endpoint filters by category, search and brand', function () {
    Product::factory()->create(['category_id' => $this->category->id, 'brand' => 'Apple', 'title' => 'iPhone 16 Pro']);
    Product::factory()->create(['category_id' => $this->category->id, 'brand' => 'Samsung', 'title' => 'Galaxy S25']);

    $this->getJson('/api/v1/products?category=test-cat&brands[]=Apple')->assertJsonCount(1, 'data');
    $this->getJson('/api/v1/products?q=galaxy')->assertJsonCount(1, 'data');
});

test('products endpoint filters by stock, price range and memory', function () {
    Product::factory()->create([
        'category_id' => $this->category->id,
        'brand' => 'Apple',
        'price' => 10_000,
        'stock' => 'in',
        'memory' => ['128 ГБ', '256 ГБ'],
    ]);
    Product::factory()->create([
        'category_id' => $this->category->id,
        'brand' => 'Samsung',
        'price' => 50_000,
        'stock' => 'order',
        'memory' => ['512 ГБ'],
    ]);

    $this->getJson('/api/v1/products?stock=in')->assertJsonCount(1, 'data');
    $this->getJson('/api/v1/products?price_min=20000')->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.brand', 'Samsung');
    $this->getJson('/api/v1/products?price_max=20000')->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.brand', 'Apple');
    $this->getJson('/api/v1/products?memory[]=256 ГБ')->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.brand', 'Apple');
    $this->getJson('/api/v1/products?stock=nope')->assertJsonCount(2, 'data');
});

test('inactive products and categories are hidden', function () {
    Product::factory()->create(['category_id' => $this->category->id, 'is_active' => false]);

    $this->getJson('/api/v1/products')->assertJsonCount(0, 'data');
});

test('product show returns details with specs pairs', function () {
    $product = Product::factory()->create([
        'category_id' => $this->category->id,
        'price' => 5_000,
        'old_price' => 6_000,
        'images' => ['https://images.unsplash.com/photo-test'],
        'specs' => [['Діагональ', '6.7"']],
        'colors' => [['name' => 'Чорний', 'hex' => '#000000']],
    ]);

    $response = $this->getJson("/api/v1/products/{$product->slug}")
        ->assertOk()
        ->assertJsonPath('data.price', 5000)
        ->assertJsonPath('data.old_price', 6000)
        ->assertJsonPath('data.discount', 17)
        ->assertJsonPath('data.image', 'https://images.unsplash.com/photo-test')
        ->assertJsonPath('data.specs.0.0', 'Діагональ')
        ->assertJsonPath('data.colors.0.hex', '#000000');
});

test('product show handles images stored as a plain string', function () {
    $product = Product::factory()->create([
        'category_id' => $this->category->id,
        'images' => 'https://images.unsplash.com/photo-string',
        'memory' => '256 ГБ',
    ]);

    $response = $this->getJson("/api/v1/products/{$product->slug}")
        ->assertOk()
        ->assertJsonPath('data.image', 'https://images.unsplash.com/photo-string');
    $response->assertJsonPath('data.images.0', 'https://images.unsplash.com/photo-string');
    $response->assertJsonPath('data.memory.0', '256 ГБ');
});

test('product show is 404 for unknown slugs', function () {
    $this->getJson('/api/v1/products/nope')->assertNotFound();
});

test('stats endpoint aggregates catalog', function () {
    Product::factory()->create(['category_id' => $this->category->id, 'price' => 10_000, 'stock' => 'in']);
    Product::factory()->create(['category_id' => $this->category->id, 'price' => 20_000, 'stock' => 'order']);

    $this->getJson('/api/v1/stats')
        ->assertOk()
        ->assertJsonPath('products', 2)
        ->assertJsonPath('in_stock', 1)
        ->assertJsonPath('min_price', 10000)
        ->assertJsonPath('max_price', 20000);
});

test('categories endpoint includes product counts', function () {
    Product::factory()->count(3)->create(['category_id' => $this->category->id]);

    $this->getJson('/api/v1/categories')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'test-cat')
        ->assertJsonPath('data.0.products_count', 3);
});
