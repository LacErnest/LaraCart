<?php

namespace LacErnest\LaravelCart\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelCartServiceProvider extends ServiceProvider
{
    /**
     * Registers the package's services and configurations.
     */
    public function register(): void
    {
        $this->registerMigrations();
        $this->registerConfigurations();
    }

    /**
     * Boots the package services, including publishing configurations and migrations.
     */
    public function boot(): void
    {
        $this->publishConfigurations();
        $this->publishMigrations();
    }

    /**
     * Registers the package's migrations.
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Registers the package's configurations.
     */
    protected function registerConfigurations(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-cart.php', 'laravel-cart');
    }

    /**
     * Publishes the package's configurations.
     */
    protected function publishConfigurations(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/laravel-cart.php' => config_path('laravel-cart.php'),
        ], 'laravel-cart-config');
    }

    /**
     * Publishes the package's migrations.
     */
    protected function publishMigrations(): void
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'laravel-cart-migrations');
    }
}
