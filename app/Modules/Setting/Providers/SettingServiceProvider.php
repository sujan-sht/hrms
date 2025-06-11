<?php

namespace App\Modules\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\Setting\Entities\LeaveDeductionSetup;
use App\Modules\Setting\Repositories\DarbandiInterface;
use App\Modules\Setting\Repositories\DarbandiRepository;
use App\Modules\Setting\Repositories\DashainAllowanceSetupInterface;
use App\Modules\Setting\Repositories\DashainAllowanceSetupRepository;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Setting\Repositories\DepartmentRepository;
use App\Modules\Setting\Repositories\DesignationInterface;
use App\Modules\Setting\Repositories\DesignationRepository;
use App\Modules\Setting\Repositories\SettingInterface;
use App\Modules\Setting\Repositories\SettingRepository;

use App\Modules\Setting\Repositories\MrfApprovalFlowInterface;
use App\Modules\Setting\Repositories\DeviceManagementInterface;
use App\Modules\Setting\Repositories\DeviceManagementRepository;
use App\Modules\Setting\Repositories\FestivalAllowanceSetupInterface;
use App\Modules\Setting\Repositories\FestivalAllowanceSetupRepository;
use App\Modules\Setting\Repositories\ForceLeaveSetupInterface;
use App\Modules\Setting\Repositories\ForceLeaveSetupRepository;
use App\Modules\Setting\Repositories\HierarchySetupInterface;
use App\Modules\Setting\Repositories\HierarchySetupRepository;
use App\Modules\Setting\Repositories\LeaveDeductionSetupInterface;
use App\Modules\Setting\Repositories\LeaveDeductionSetupRepository;
use App\Modules\Setting\Repositories\LeaveEncashmentSetupInterface;
use App\Modules\Setting\Repositories\LeaveEncashmentSetupRepository;
use App\Modules\Setting\Repositories\LevelInterface;
use App\Modules\Setting\Repositories\LevelRepository;
use App\Modules\Setting\Repositories\MrfApprovalFlowRepository;
use App\Modules\Setting\Repositories\OTRateSetupInterface;
use App\Modules\Setting\Repositories\OTRateSetupRepository;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Setting';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'setting';

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
        $this->settingRegister();
        $this->deviceManagementRegister();
        $this->appBindLeaveDeductionSetup();
        $this->hierarchySetupRegister();
        $this->appBindMrfApprovalFlow();
        $this->appBindFestivalAllowanceSetup();
        $this->appOTRateSetup();
        $this->darbandi();
        $this->designation();
        $this->department();
        $this->level();
        $this->leaveEncashmentSetup();
        $this->forceLeaveSetup();
    }

    public function settingRegister()
    {
        $this->app->bind(
            SettingInterface::class,
            SettingRepository::class
        );
    }

    /**
     *
     */
    public function appBindLeaveDeductionSetup()
    {
        $this->app->bind(
            LeaveDeductionSetupInterface::class,
            LeaveDeductionSetupRepository::class,
        );
    }


    public function deviceManagementRegister()
    {
        $this->app->bind(
            DeviceManagementInterface::class,
            DeviceManagementRepository::class
        );
    }

    public function hierarchySetupRegister()
    {
        $this->app->bind(
            HierarchySetupInterface::class,
            HierarchySetupRepository::class
        );
    }

    public function appBindMrfApprovalFlow()
    {
        $this->app->bind(
            MrfApprovalFlowInterface::class,
            MrfApprovalFlowRepository::class
        );
    }
    public function appBindFestivalAllowanceSetup()
    {
        $this->app->bind(
            FestivalAllowanceSetupInterface::class,
            FestivalAllowanceSetupRepository::class
        );
    }
    public function appOTRateSetup()
    {
        $this->app->bind(
            OTRateSetupInterface::class,
            OTRateSetupRepository::class
        );
    }
    public function darbandi()
    {
        $this->app->bind(
            DarbandiInterface::class,
            DarbandiRepository::class
        );
    }

    public function designation()
    {
        $this->app->bind(
            DesignationInterface::class,
            DesignationRepository::class
        );
    }

    public function department()
    {
        $this->app->bind(
            DepartmentInterface::class,
            DepartmentRepository::class
        );
    }

    public function level()
    {
        $this->app->bind(
            LevelInterface::class,
            LevelRepository::class
        );
    }

    public function leaveEncashmentSetup()
    {
        $this->app->bind(
            LeaveEncashmentSetupInterface::class,
            LeaveEncashmentSetupRepository::class
        );
    }

    public function forceLeaveSetup()
    {
        $this->app->bind(
            ForceLeaveSetupInterface::class,
            ForceLeaveSetupRepository::class
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
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
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
