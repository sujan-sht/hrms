<?php

namespace App\Modules\Attendance\Providers;

use App\Modules\Attendance\Console\NotifyAtdRequestCommand;
use App\Modules\Attendance\Console\NotifyLateArrivalEarlyDepartureCommand;
use App\Modules\Attendance\Console\NotifyMonthlyPendingRequestCommand;
use App\Modules\Attendance\Console\NotifyPendingRequestCommand;
use App\Modules\Attendance\Console\RunAtdTestCommand;
use Illuminate\Support\ServiceProvider;
use App\Modules\Attendance\Console\RunAttendanceCommand;
use App\Modules\Attendance\Repositories\AttendanceInterface;
use App\Modules\Attendance\Repositories\AttendanceRepository;
use App\Modules\Attendance\Repositories\AttendanceLogInterface;
use App\Modules\Attendance\Repositories\AttendanceLogRepository;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;
use App\Modules\Attendance\Repositories\AttendanceRequestInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestRepository;
use App\Modules\Attendance\Repositories\AttendanceRequestLinkInterface;
use App\Modules\Attendance\Repositories\AttendanceRequestLinkRepository;

class AttendanceServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Attendance';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'attendance';

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
        $this->appBindAttendanceReport();
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
        $this->appBindAttendance();
        $this->appBindAttendanceLog();
        $this->appBindAttendanceRequest();
        $this->appBindAttendanceRequestLink();
        $this->registerCommands();
    }

    /**
     * Register Package Command.
     *
     *@return void
     */
    protected function registerCommands()
    {
        $this->commands([
            RunAttendanceCommand::class,
            NotifyAtdRequestCommand::class,
            RunAtdTestCommand::class,
            NotifyLateArrivalEarlyDepartureCommand::class,
            NotifyPendingRequestCommand::class,
            NotifyMonthlyPendingRequestCommand::class

        ]);
    }

    /**
     *
     */
    public function appBindAttendance()
    {
        $this->app->bind(
            AttendanceInterface::class,
            AttendanceRepository::class
        );
    }

    public function appBindAttendanceReport()
    {
        $this->app->bind(
            AttendanceReportInterface::class,
            AttendanceReportRepository::class
        );
    }

    /**
     *
     */
    public function appBindAttendanceLog()
    {
        $this->app->bind(
            AttendanceLogInterface::class,
            AttendanceLogRepository::class
        );
    }

    /**
     *
     */
    public function appBindAttendanceRequest()
    {
        $this->app->bind(
            AttendanceRequestInterface::class,
            AttendanceRequestRepository::class
        );
    }

    /**
     *
     */
    public function appBindAttendanceRequestLink()
    {
        $this->app->bind(
            AttendanceRequestLinkInterface::class,
            AttendanceRequestLinkRepository::class
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
