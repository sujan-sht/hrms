<?php

namespace App\Modules\Advance\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Advance\Repositories\AdvanceInterface;
use App\Modules\Advance\Repositories\AdvanceRepository;
use App\Modules\Advance\Repositories\AdvancePaymentLedgerInterface;
use App\Modules\Advance\Repositories\AdvancePaymentLedgerRepository;
use App\Modules\Advance\Repositories\AdvanceSettlementPaymentInterface;
use App\Modules\Advance\Repositories\AdvanceSettlementPaymentRepository;

class AdvanceServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Advance';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'advance';

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
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->appBindAdvance();
        $this->appBindAdvancePaymentLedger();
        $this->appBindAdvanceSettlementPayment();
    }

    /**
     * Binding advance
     */
    public function appBindAdvance()
    {
        $this->app->bind(AdvanceInterface::class, AdvanceRepository::class);
    }

    /**
     * Binding advance payment ledger
     */
    public function appBindAdvancePaymentLedger()
    {
        $this->app->bind(AdvancePaymentLedgerInterface::class, AdvancePaymentLedgerRepository::class);
    }

    /**
     * Binding advance settlement payment
     */
    public function appBindAdvanceSettlementPayment()
    {
        $this->app->bind(AdvanceSettlementPaymentInterface::class, AdvanceSettlementPaymentRepository::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
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

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
