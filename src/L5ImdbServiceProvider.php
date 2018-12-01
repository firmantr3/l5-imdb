<?php

namespace firmantr3\L5Imdb;

use Illuminate\Support\ServiceProvider;

class L5ImdbServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'firmantr3');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'firmantr3');
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
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/imdb.php', 'imdb');

        // Register the service the package provides.
        $this->app->singleton('l5imdb', function ($app) {
            return new L5Imdb;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['l5imdb'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/imdb.php' => config_path('imdb.php'),
        ], 'imdb.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/firmantr3'),
        ], 'l5imdb.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/firmantr3'),
        ], 'l5imdb.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/firmantr3'),
        ], 'l5imdb.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
