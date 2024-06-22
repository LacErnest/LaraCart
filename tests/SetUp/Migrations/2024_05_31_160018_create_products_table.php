<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executes the migration to create the 'products' table.
     * This method defines the schema of the 'products' table, including the necessary fields
     * and constraints. It is automatically called when the migration is run.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Title field
            $table->string('price')->default(0); // Price field with a default value
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverts the migration by dropping the 'products' table.
     * This method is automatically called when the migration is rolled back.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
