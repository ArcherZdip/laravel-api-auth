<?php

namespace ArcherZdip\LaravelApiAuth\Providers;

use ArcherZdip\LaravelApiAuth\Console\Commands\GenerateAppAuth;
use ArcherZdip\LaravelApiAuth\Console\Commands\ListAppAuth;
use ArcherZdip\LaravelApiAuth\Console\Commands\PutAppAuth;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/apikey.php' => config_path('apikey.php'),
            ], 'config');
        }
        $this->registerMiddleware($router);
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
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
     * Register middleware.
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
