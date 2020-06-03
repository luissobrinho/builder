<?php

use Illuminate\Foundation\Application;
use Luissobrinho\Builder\LuissobrinhoBuilderProvider;

class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @var Application
     */
    protected $app;

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app->make('Illuminate\Contracts\Http\Kernel');
    }

    protected function getPackageProviders($app)
    {
        return [
            LuissobrinhoBuilderProvider::class,
        ];
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/../src/Models/Factories');
        $this->artisan('migrate', [
            '--database' => 'testbench',
        ]);
        $this->withoutMiddleware();
        $this->withoutEvents();
    }
}
