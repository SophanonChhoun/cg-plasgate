<?php


namespace Cg\Plasgate;


use Carbon\Laravel\ServiceProvider;

class CgPlasgateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('cg-plasgate.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cg-plasgate');

        // Register the main class to use with the facade
        $this->app->singleton('cg-plasgate', function () {
            return new CgPlasgate();
        });
    }
}
