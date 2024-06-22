<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executes the migration to create the 'users' table.
     * 
     * This method defines the schema of the 'users' table, including the necessary fields
     * and constraints. It is automatically called when the migration is run.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name field
            $table->string('email')->unique(); // Email field with a uniqueness constraint
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverts the migration by dropping the 'users' table.
     * 
     * This method is automatically called when the migration is rolled back.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
