<?php

namespace App\Modules\Payroll\Providers;

use App\Modules\Payroll\Console\MassIncrement;
use App\Modules\Payroll\Console\PayrollEmail;
use App\Modules\Payroll\Repositories\ArrearAdjustmentInterface;
use App\Modules\Payroll\Repositories\ArrearAdjustmentRepository;
use App\Modules\Payroll\Repositories\BonusInterface;
use App\Modules\Payroll\Repositories\BonusRepository;
use App\Modules\Payroll\Repositories\BonusSetupInterface;
use App\Modules\Payroll\Repositories\BonusSetupRepository;
use Illuminate\Support\ServiceProvider;
use App\Modules\Payroll\Repositories\PayrollInterface;
use App\Modules\Payroll\Repositories\PayrollRepository;
use App\Modules\Payroll\Repositories\IncomeSetupInterface;
use App\Modules\Payroll\Repositories\IncomeSetupRepository;
use App\Modules\Payroll\Repositories\TaxSlabSetupInterface;
use App\Modules\Payroll\Repositories\TaxSlabSetupRepository;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use App\Modules\Payroll\Repositories\DeductionSetupInterface;
use App\Modules\Payroll\Repositories\EmployeeSetupRepository;
use App\Modules\Payroll\Repositories\DeductionSetupRepository;
use App\Modules\Payroll\Repositories\HoldPaymentInterface;
use App\Modules\Payroll\Repositories\HoldPaymentRepository;
use App\Modules\Payroll\Repositories\LeaveAmountSetupInterface;
use App\Modules\Payroll\Repositories\LeaveAmountSetupRepository;
use App\Modules\Payroll\Repositories\MassIncrementInterface;
use App\Modules\Payroll\Repositories\MassIncrementRepository;
use App\Modules\Payroll\Repositories\StopPaymentInterface;
use App\Modules\Payroll\Repositories\StopPaymentRepository;
use App\Modules\Payroll\Repositories\TaxExcludeSetupInterface;
use App\Modules\Payroll\Repositories\TaxExcludeSetupRepository;
use App\Modules\Payroll\Repositories\ThresholdBenefitSetupInterface;
use App\Modules\Payroll\Repositories\ThresholdBenefitSetupRepository;

class PayrollServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Payroll';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'payroll';

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
        $this->appBindIncome();
        $this->appBindDeduction();
        $this->appBindLeaveDeduction();
        $this->appTaxExcludeSetup();
        $this->appBindBonusSetup();
        $this->appBindEmployee();
        $this->appBindTaxSlab();
        $this->appBindBonus();
        $this->appBindPayroll();
        $this->appBindThreshold();
        $this->appBindMassIncrement();
        $this->appBindArrearAdjustment();
        $this->appBindHoldPayment();
        $this->appBindStopPayment();
        $this->registerCommands();
    }
    protected function registerCommands()
    {
        $this->commands([
            MassIncrement::class,
            PayrollEmail::class,
        ]);
    }

    /**
     * Income binding
     */
    public function appBindThreshold()
    {
        $this->app->bind(
            ThresholdBenefitSetupInterface::class,
            ThresholdBenefitSetupRepository::class
        );
    }
    public function appBindIncome()
    {
        $this->app->bind(
            IncomeSetupInterface::class,
            IncomeSetupRepository::class
        );
    }

    /**
     * Deduction binding
     */
    public function appBindDeduction()
    {
        $this->app->bind(
            DeductionSetupInterface::class,
            DeductionSetupRepository::class
        );
    }
    public function appBindLeaveDeduction()
    {
        $this->app->bind(
            LeaveAmountSetupInterface::class,
            LeaveAmountSetupRepository::class
        );
    }
    
    public function appBindBonusSetup()
    {
        $this->app->bind(
            BonusSetupInterface::class,
            BonusSetupRepository::class
        );
    }
    public function appTaxExcludeSetup()
    {
        $this->app->bind(
            TaxExcludeSetupInterface::class,
            TaxExcludeSetupRepository::class
        );
    }
    public function appBindTaxSlab()
    {
        $this->app->bind(
            TaxSlabSetupInterface::class,
            TaxSlabSetupRepository::class
        );
    }

    /**
     * Employee Setup binding
     */
    public function appBindEmployee()
    {
        $this->app->bind(
            EmployeeSetupInterface::class,
            EmployeeSetupRepository::class
        );
    }
    public function appBindBonus()
    {
        $this->app->bind(BonusInterface::class, BonusRepository::class);
    }

    /**
     * Payroll binding
     */
    public function appBindPayroll()
    {
        $this->app->bind(PayrollInterface::class, PayrollRepository::class);
    }

    public function appBindMassIncrement()
    {
        $this->app->bind(MassIncrementInterface::class, MassIncrementRepository::class);
    }


    public function appBindArrearAdjustment()
    {
        $this->app->bind(ArrearAdjustmentInterface::class, ArrearAdjustmentRepository::class);
    }

    public function appBindHoldPayment()
    {
        $this->app->bind(HoldPaymentInterface::class, HoldPaymentRepository::class);
    }
    public function appBindStopPayment()
    {
        $this->app->bind(StopPaymentInterface::class, StopPaymentRepository::class);
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
