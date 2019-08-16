<?php

namespace ArcherZdip\LaravelApiAuth\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use ArcherZdip\LaravelApiAuth\Console\Commands\PutAppAuth;
use ArcherZdip\LaravelApiAuth\Console\Commands\ListAppAuth;
use ArcherZdip\LaravelApiAuth\Console\Commands\GenerateAppAuth;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        if( function_exists('config_path') ) {
            $this->publishes([
                __DIR__ . '/../config/apikey.php' => config_path('apikey.php'),
            ], 'config');
        }
        $this->registerMiddleware($router);
        if (function_exists('database_path')) {
            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            GenerateAppAuth::class,
            ListAppAuth::class,
            PutAppAuth::class,
        ]);
    }

    /**
     * Register middleware
     *
     * Support added for different Laravel versions
     *
     * @param Router $router
     */
    protected function registerMiddleware(Router $router)
    {
        $versionComparison = version_compare(app()->version(), '5.4.0');

        if ($versionComparison >= 0) {
            $router->aliasMiddleware('auth.apikey', AuthorizeApiKey::class);
        } else {
            $router->middleware('auth.apikey', AuthorizeApiKey::class);
        }
    }
}
