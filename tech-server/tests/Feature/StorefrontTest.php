<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromoCode;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

test('catalog page renders products and pagination', function () {
    Product::factory()->count(15)->create(['category_id' => $this->category->id]);

    $response = $this->get('/catalog');

    $response->assertOk();
    $response->assertSee('?page=2', false);
});

test('catalog filters by brand and sorts by price', function () {
    Product::factory()->create(['category_id' => $this->category->id, 'brand' => 'Apple', 'price' => 20_000]);
    Product::factory()->create(['category_id' => $this->category->id, 'brand' => 'Samsung', 'price' => 30_000]);

    $this->get('/catalog?brands[]=Apple')
        ->assertOk()
        ->assertSee('Apple', false)
        ->assertSee('20 000', false)
        ->assertDontSee('30 000', false);
});

test('product page renders price and add-to-cart form', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id, 'price' => 5_499]);

    $response = $this->get("/product/{$product->slug}");

    $response->assertOk();
    $response->assertSee('5 499', false);
    $response->assertSee('cart/add', false);
});

test('guest can add product to cart', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);

    $response = $this->post('/cart/add', ['product' => $product->slug]);

    $response->assertRedirect();
    $response->assertSessionHas('toast');
    expect(session('cart'))->toBe([$product->id => 1]);
});

test('cart shows item and totals after adding', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id, 'price' => 9_999]);

    $this->post('/cart/add', ['product' => $product->slug]);

    $this->get('/cart')
        ->assertOk()
        ->assertSee($product->title, false)
        ->assertSee('9 999', false);
});

test('promo codes apply fixed discount', function () {
    PromoCode::factory()->create(['code' => 'TECHNO2026', 'amount' => 500, 'is_active' => true]);
    $product = Product::factory()->create(['category_id' => $this->category->id, 'price' => 5_000]);

    $this->post('/cart/add', ['product' => $product->slug]);
    $response = $this->post('/cart/promo', ['code' => 'TECHNO2026']);

    $response->assertRedirect();
    $response->assertSessionHas('toast', 'Промокод застосовано: −500 ₴');
    $this->get('/cart')->assertSee('4 500', false);
});

test('invalid promo code is rejected', function () {
    PromoCode::factory()->create(['code' => 'TECHNO2026', 'amount' => 500, 'is_active' => true]);
    $product = Product::factory()->create(['category_id' => $this->category->id, 'price' => 5_000]);

    $this->post('/cart/add', ['product' => $product->slug]);
    $response = $this->post('/cart/promo', ['code' => 'WRONGCODE']);

    $response->assertSessionHas('toast', 'Такого промокоду не існує');
});

test('checkout saves order with items and clears cart', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id, 'price' => 6_000]);

    $this->post('/cart/add', ['product' => $product->slug, 'qty' => 2]);
    $response = $this->post('/checkout', [
        'name' => 'Олексій Мельник',
        'phone' => '+380671234567',
        'email' => 'oleksii@example.ua',
        'city' => 'Київ',
        'branch' => 'Відділення №45',
        'delivery' => 'novaposhta',
        'payment' => 'card',
    ]);
    $response->assertRedirect('/success');

    $order = Order::query()->where('name', 'Олексій Мельник')->sole();

    $response->assertRedirect('/success');
    expect($order->total)->toBe(12_000)
        ->and($order->delivery)->toBe('novaposhta')
        ->and($order->items()->count())->toBe(1)
        ->and($order->items()->first()->qty)->toBe(2)
        ->and(session('last_order_id'))->toBe($order->id);

    $this->get('/cart')->assertSee('Кошик порожній', false);
});

test('success page shows latest order', function () {
    $product = Product::factory()->create(['category_id' => $this->category->id]);
    $order = Order::factory()->create(['name' => 'Марія Ковальчук']);
    $order->items()->create(['product_id' => $product->id, 'title' => $product->title, 'slug' => $product->slug, 'price' => 1_000, 'qty' => 1, 'total' => 1_000]);

    session(['last_order_id' => $order->id]);

    $this->get('/success')
        ->assertOk()
        ->assertSee('Марія Ковальчук', false);
});
