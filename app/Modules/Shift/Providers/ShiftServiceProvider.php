<?php

namespace App\Modules\Shift\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

use App\Modules\Shift\Repositories\ShiftInterface;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\Shift\Repositories\ShiftGroupRepository;
use App\Modules\Shift\Repositories\EmployeeShiftInterface;
use App\Modules\Shift\Repositories\EmployeeShiftRepository;

class ShiftServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerShift();
        $this->registerEmployeeShift();
        $this->registerShiftGroup();
    }

    public function registerShiftGroup()
    {
        $this->app->bind(
            ShiftGroupInterface::class,
            ShiftGroupRepository::class
        );
    }

    public function registerEmployeeShift()
    {
        $this->app->bind(
            EmployeeShiftInterface::class,
            EmployeeShiftRepository::class
        );
    }

    public function registerShift()
    {
        $this->app->bind(
            ShiftInterface::class,
            ShiftRepository::class
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('shift.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'shift'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/shift');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/shift';
        }, \Config::get('view.paths')), [$sourcePath]), 'shift');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/shift');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'shift');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'shift');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
