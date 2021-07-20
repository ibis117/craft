<?php

namespace Ibis117\Craft;

use Ibis117\Craft\Commands\ControllerCommand;
use Ibis117\Craft\Commands\CraftCommand;
use Ibis117\Craft\Commands\ModelCommand;
use Ibis117\Craft\Commands\ViewCommand;
use Illuminate\Support\ServiceProvider;

class CraftServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ibis117');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'ibis117');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/craft.php', 'craft');

        // Register the service the package provides.
        $this->app->singleton('craft', function ($app) {
            return new Craft;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['craft'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/craft.php' => config_path('craft.php'),
        ], 'craft.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/ibis117'),
        ], 'craft.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/ibis117'),
        ], 'craft.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/ibis117'),
        ], 'craft.views');*/

        // Registering package commands.
        $this->commands([
            ControllerCommand::class,
            ModelCommand::class,
            ViewCommand::class,
            CraftCommand::class
        ]);
    }
}
