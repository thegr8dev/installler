<?php

namespace Mediacity\Installer;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Mediacity\Installer\Http\Middleware\IsInstalled;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('is_install', IsInstalled::class);

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views/', 'installer');

        $this->mergeConfigFrom(
            __DIR__.'/config/installer.php', 'installer'
        );

        $this->publishes([
            __DIR__.'/resources/assets' => public_path('installer'),
            __DIR__.'/config/installer.php' => config_path('installer.php'),
            __DIR__.'/resources/views' => resource_path('views/install')
        ],'installer');
    }
}
