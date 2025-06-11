<?php

namespace App\Modules\Tada\Providers;

use App\Modules\Tada\Repositories\AllowanceTypeInterface;
use App\Modules\Tada\Repositories\AllowanceTypeRepository;

// Repositories
use App\Modules\Tada\Repositories\BillInterface;
use App\Modules\Tada\Repositories\BillRepository;
use App\Modules\Tada\Repositories\BillTypeInterface;
use App\Modules\Tada\Repositories\BillTypeRepository;
use App\Modules\Tada\Repositories\TadaRequestInterface;
use App\Modules\Tada\Repositories\TadaRequestRepository;
use App\Modules\Tada\Repositories\TadaInterface;
use App\Modules\Tada\Repositories\TadaRepository;
use App\Modules\Tada\Repositories\TadaTypeInterface;
use App\Modules\Tada\Repositories\TadaTypeRepository;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;

class TadaServiceProvider extends ServiceProvider
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
        $this->registerBillType();
        $this->registerAllowanceType();
        $this->registerTadaBill();
        $this->registerTada();
        $this->registerTadaType();
        $this->registerTadaRequest();
    }

    public function registerTada()
    {
        $this->app->bind(
            TadaInterface::class,
            TadaRepository::class
        );
    }

    public function registerTadaRequest()
    {
        $this->app->bind(
            TadaRequestInterface::class,
            TadaRequestRepository::class
        );
    }

    public function registerTadaBill()
    {
        $this->app->bind(
            BillInterface::class,
            BillRepository::class
        );
    }

    public function registerBillType()
    {
        $this->app->bind(
            BillTypeInterface::class,
            BillTypeRepository::class
        );
    }

    public function registerAllowanceType()
    {
        $this->app->bind(
            AllowanceTypeInterface::class,
            AllowanceTypeRepository::class
        );
    }

    public function registerTadaType()
    {
        $this->app->bind(TadaTypeInterface::class, TadaTypeRepository::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('tada.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'tada'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/tada');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/tada';
        }, \Config::get('view.paths')), [$sourcePath]), 'tada');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/tada');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'tada');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'tada');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
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