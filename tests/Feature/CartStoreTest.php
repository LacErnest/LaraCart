<?php

use LacErnest\LaravelCart\Models\Cart;
use LacErnest\LaravelCart\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertInstanceOf;

/*
 * Utilize `RefreshDatabase` to ensure a clean state for each test by rolling back database migrations.
 */
uses(RefreshDatabase::class);

test('can store product in cart', function () {
    $user = User::factory()->create(['name' => 'Ernest', 'email' => 'ernest@example.com']);
    $product = Product::factory()->create(['title' => 'Product 1']);

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cartItem = new CartItem([
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    $cart->items()->save($cartItem);

    assertInstanceOf($product::class, $cartItem->itemable()->first());
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

test('can store product in cart with custom table name from config', function () {
    config()->set([
        'laravel-cart.carts.table' => 'custom_carts',
        'laravel-cart.cart_items.table' => 'custom_cart_items',
    ]);

    Artisan::call('migrate:refresh');

    $user = User::factory()->create(['name' => 'Ernest', 'email' => 'ernest@example.com']);
    $product = Product::factory()->create(['title' => 'Product 1']);

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    $cartItem = new CartItem([
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    $cart->items()->save($cartItem);

    assertInstanceOf($product::class, $cartItem->itemable()->first());
    assertDatabaseCount('custom_carts', 1);
    assertDatabaseCount('custom_cart_items', 1);
    assertDatabaseHas('custom_cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

test('can store product in cart with firstOrCreateWithItems scope', function () {
    $user = User::factory()->create(['name' => 'Ernest', 'email' => 'ernest@example.com']);
    $product = Product::factory()->create(['title' => 'Product 1']);

    $cart = Cart::query()->firstOrCreateWithStoreItems($product, 1, $user->id);

    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

test('can store product in cart with firstOrCreateWithItems scope when user sign-in', function () {
    $user = User::factory()->create(['name' => 'Ernest', 'email' => 'ernest@example.com']);
    $product = Product::factory()->create(['title' => 'Product 1']);

    auth()->login($user);

    $cart = Cart::query()->firstOrCreateWithStoreItems($product, 1);

    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

test('can store multiple products in cart', function () {
    $user = User::factory()->create(['name' => 'Ernest', 'email' => 'ernest@example.com']);
    $products = Product::factory()->count(3)->create(['title' => 'Product 1']);

    $items = $products->map(function ($product) {
        return [
            'itemable' => $product,
            'quantity' => random_int(1, 5),
        ];
    })->all();

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItems($items);

    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 3);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $products->first()->id,
        'itemable_type' => $products->first()::class,
        'quantity' => $items[0]['quantity'],
    ]);
});

test('get correct price with calculated quantity', function () {
    $user = User::factory()->create(['name' => 'Ernest', 'email' => 'ernest@example.com']);
    $products = Product::factory()->count(3)->create([
        ['title' => 'Product 1', 'price' => 15000],
        ['title' => 'Product 2', 'price' => 25000],
        ['title' => 'Product 3', 'price' => 35000],
    ]);

    $items = $products->map(function ($product, $index) {
        return [
            'itemable' => $product,
            'quantity' => $index + 1,
        ];
    })->all();

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItems($items);

    \PHPUnit\Framework\assertEquals(230000, $cart->calculatedPriceByQuantity());

    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 3);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $products->first()->id,
        'itemable_type' => $products->first()::class,
        'quantity' => $items[0]['quantity'],
    ]);
});
