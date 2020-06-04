<?php

namespace Luissobrinho\Builder;

use Luissobrinho\Builder\Console\Activity;
use Luissobrinho\Builder\Console\Auditing;
use Luissobrinho\Builder\Console\Api;
use Luissobrinho\Builder\Console\Bootstrap;
use Luissobrinho\Builder\Console\DebugBar;
use Luissobrinho\Builder\Console\Features;
use Luissobrinho\Builder\Console\Forge;
use Luissobrinho\Builder\Console\Logs;
use Luissobrinho\Builder\Console\Notifications;
use Luissobrinho\Builder\Console\Queue;
use Luissobrinho\Builder\Console\Socialite;
use Luissobrinho\Builder\Console\Starter;
use Illuminate\Support\ServiceProvider;
use Luissobrinho\LCrud\LCrudProvider;

class LuissobrinhoBuilderProvider extends ServiceProvider
{
    /**
     * Boot method.
     */
    public function boot()
    {
        // do nothing
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Providers
        |--------------------------------------------------------------------------
        */

        $this->app->register(LCrudProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            Activity::class,
            Auditing::class,
            Api::class,
            Bootstrap::class,
            DebugBar::class,
            Features::class,
            Forge::class,
            Logs::class,
            Queue::class,
            Notifications::class,
            Socialite::class,
            Starter::class,
        ]);
    }
}
