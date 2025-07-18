<?php

namespace App\Modules\Notice\Providers;

use App\Modules\Notice\Console\SendScheduledNoticesCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

use App\Modules\Notice\Repositories\NoticeInterface;
use App\Modules\Notice\Repositories\NoticeRepository;

class NoticeServiceProvider extends ServiceProvider
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
        $this->noticeRegister();
        $this->registerCommands();
    }

    public function noticeRegister()
    {
        $this->app->bind(
            NoticeInterface::class,
            NoticeRepository::class
        );
    }

    protected function registerCommands()
    {
        $this->commands([
            SendScheduledNoticesCommand::class,
        ]);
    }
    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('notice.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'notice'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/notice');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/notice';
        }, \Config::get('view.paths')), [$sourcePath]), 'notice');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/notice');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'notice');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'notice');
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
