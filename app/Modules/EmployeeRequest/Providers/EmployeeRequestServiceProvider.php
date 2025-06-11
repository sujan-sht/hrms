<?php

namespace App\Modules\EmployeeRequest\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

// Repositories
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestInterface;
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestRepository;

use App\Modules\EmployeeRequest\Repositories\EmployeeRequestTypeInterface;
use App\Modules\EmployeeRequest\Repositories\EmployeeRequestTypeRepository;

use App\Modules\EmployeeRequest\Repositories\BenefitRequestInterface;
use App\Modules\EmployeeRequest\Repositories\BenefitRequestRepository;


class EmployeeRequestServiceProvider extends ServiceProvider
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
        $this->registerFactories();
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
        $this->registerEmployeeRequest();
        $this->registerEmployeeRequestType();
    }

    public function registerEmployeeRequest()
    {
        $this->app->bind(
            EmployeeRequestInterface::class,
            EmployeeRequestRepository::class
        );
    }

    public function registerEmployeeRequestType()
    {
        $this->app->bind(
            EmployeeRequestTypeInterface::class,
            EmployeeRequestTypeRepository::class
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
            __DIR__.'/../Config/config.php' => config_path('employeerequest.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'employeerequest'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/employeerequest');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/employeerequest';
        }, \Config::get('view.paths')), [$sourcePath]), 'employeerequest');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/employeerequest');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'employeerequest');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'employeerequest');
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
