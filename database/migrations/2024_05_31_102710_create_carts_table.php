<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the carts table with a foreign key reference to the users table.
     */
    public function up(): void
    {
        $userTableName = config('laravel-cart.users.table', 'users');
        $userForeignKey = config('laravel-cart.users.foreign_key', 'user_id'); // Updated to 'foreign_key'
        $cartTableName = config('laravel-cart.carts.table', 'carts');

        Schema::create($cartTableName, function (Blueprint $table) use ($userTableName, $userForeignKey) {
            $table->id();

            // Ensures foreign key references are correctly set up and cascade on delete.
            $table->foreignId($userForeignKey)->constrained($userTableName)->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Drops the carts table if it exists.
     */
    public function down(): void
    {
        $cartTableName = config('laravel-cart.carts.table', 'carts');

        Schema::dropIfExists($cartTableName);
    }
};