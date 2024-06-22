<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the cart_items table.
     */
    public function up(): void
    {
        $cartItemsTable = config('laravel-cart.cart_items.table', 'cart_items');
        $cartForeignKey = config('laravel-cart.carts.foreign_key', 'cart_id');
        $cartsTable = config('laravel-cart.carts.table', 'carts');

        Schema::create($cartItemsTable, function (Blueprint $table) use ($cartForeignKey, $cartsTable) {
            $table->id();
            $table->foreignId($cartForeignKey)->constrained($cartsTable)->cascadeOnDelete();
            $table->morphs('itemable');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the cart_items table.
     */
    public function down(): void
    {
        $cartItemsTable = config('laravel-cart.cart_items.table', 'cart_items');
        Schema::dropIfExists($cartItemsTable);
    }
};
