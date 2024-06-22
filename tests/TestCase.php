<?php

namespace Tests;

use LacErnest\LaravelCart\Providers\LaravelCartServiceProvider;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Tests\SetUp\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Registers the service providers required by the package.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelCartServiceProvider::class];
    }

    /**
     * Configures the environment for testing.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $this->configureDatabase($app);
        $this->setApplicationKey($app);
        $this->configureAuth($app);
    }

    /**
     * Configures the in-memory database for testing.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    private function configureDatabase($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Sets the application key.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    private function setApplicationKey($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(
            Encrypter::generateKey($app['config']['app.cipher'])
        ));
    }

    /**
     * Configures the authentication provider model.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    private function configureAuth($app): void
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Sets up the testing environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/SetUp/Migrations');
        Artisan::call('migrate');
    }
}
