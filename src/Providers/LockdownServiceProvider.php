<?php

namespace DeadPixelStudio\Lockdown\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LockdownServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerResources();
    }

    public function register()
    {
    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(dirname(__FILE__, 3).'/database/migrations');
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(dirname(__FILE__, 3).'/routes/api.php');
        });
    }

    private function routeConfiguration ()
    {
        return [
            'prefix' => 'api/lockdown',
            'middleware' => 'api',
            'namespace' => 'DeadPixelStudio\Lockdown\Http\Controllers'
        ];
    }
}
