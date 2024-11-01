<?php
namespace Sisukma\V2;
use Carbon\Carbon;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class Sisukmav2ServiceProvider extends ServiceProvider
{
    protected function registerRoutes()
    {
        Route::middleware(['web'])
        ->group(function () {
            $this->loadRoutesFrom(__DIR__.'/routes/admin.php');
        });
        Route::middleware(['web'])
        ->group(function () {
            $this->loadRoutesFrom(__DIR__.'/routes/auth.php');
        });
        Route::middleware(['web'])
        ->group(function () {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        });

    }
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'sisukma');
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
    }


    public function boot(Router $router)
    {
        $this->registerResources();
        $this->registerMigrations();
        Carbon::setLocale('ID');
        Config::set('auth.providers.users.model', 'Sisukma\V2\Models\User');

        $this->registerRoutes();
    }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/config/config.php", "sisukma");
        $this->registerFunctions();

    }
    protected function registerFunctions()
    {
        require_once(__DIR__ . "/Inc/Helpers.php");
    }
}
