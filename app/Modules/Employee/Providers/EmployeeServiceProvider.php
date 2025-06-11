<?php

namespace App\Modules\Employee\Providers;

use App\Modules\Employee\Console\JobContractProbationEndEmail;
use App\Modules\Employee\Console\NotifyThreeMonthPeriods;
use App\Modules\Employee\Repositories\AssetDetailInterface;
use App\Modules\Employee\Repositories\AssetDetailRepository;
use App\Modules\Employee\Repositories\BankDetailInterface;
use App\Modules\Employee\Repositories\BankDetailRepository;
use App\Modules\Employee\Repositories\EmergencyDetailInterface;
use App\Modules\Employee\Repositories\EmergencyDetailRepository;
use App\Modules\Employee\Repositories\BenefitDetailInterface;
use App\Modules\Employee\Repositories\BenefitDetailRepository;
use App\Modules\Employee\Repositories\ContractDetailInterface;
use App\Modules\Employee\Repositories\ContractDetailRepository;
use App\Modules\Employee\Repositories\DocumentDetailInterface;
use App\Modules\Employee\Repositories\DocumentDetailRepository;
use App\Modules\Employee\Repositories\EducationDetailInterface;
use App\Modules\Employee\Repositories\EducationDetailRepository;
use App\Modules\Employee\Repositories\PreviousJobDetailInterface;
use App\Modules\Employee\Repositories\PreviousJobDetailRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveInterface;
use App\Modules\Employee\Repositories\EmployeeSubstituteLeaveRepository;
use App\Modules\Employee\Repositories\EmployeeTransferInterface;
use App\Modules\Employee\Repositories\EmployeeTransferRepository;
use App\Modules\Employee\Repositories\FamilyDetailInterface;
use App\Modules\Employee\Repositories\FamilyDetailRepository;
use App\Modules\Employee\Repositories\InsuranceDetailInterface;
use App\Modules\Employee\Repositories\InsuranceDetailRepository;
use App\Modules\Employee\Repositories\MedicalDetailInterface;
use App\Modules\Employee\Repositories\MedicalDetailRepository;
use App\Modules\Employee\Repositories\ResearchAndPublicationDetailInterface;
use App\Modules\Employee\Repositories\ResearchAndPublicationDetailRepository;
use App\Modules\Employee\Repositories\TrainingDetailInterface;
use App\Modules\Employee\Repositories\TrainingDetailRepository;
use App\Modules\Employee\Repositories\VisaAndImmigrationDetailInterface;
use App\Modules\Employee\Repositories\VisaAndImmigrationDetailRepository;


class EmployeeServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Employee';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'employee';

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
        $this->builtEmployee();
        $this->builtFamilyDetail();
        $this->builtAssetDetail();
        $this->builtEmergencyDetail();
        $this->builtBenefitDetail();
        $this->builtEducationDetail();
        $this->builtPreviousJobDetail();
        $this->builtBankDetail();
        $this->builtContractDetail();
        $this->builtDocumentDetail();
        $this->builtResearchAndPublicationDetail();
        $this->builtVisaAndImmigrationDetail();
        $this->builtMedicalDetail();
        $this->builtInsuranceDetail();
        $this->appbindEmployeeSubstituteLeave();
        $this->appbindEmployeeTransfer();
        $this->appbindTrainingDetail();
        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->commands([
            JobContractProbationEndEmail::class,
            NotifyThreeMonthPeriods::class,
        ]);
    }
    public function builtEmployee()
    {
        $this->app->bind(
            EmployeeInterface::class,
            EmployeeRepository::class
        );
    }

    public function builtFamilyDetail()
    {
        $this->app->bind(
            FamilyDetailInterface::class,
            FamilyDetailRepository::class
        );
    }

    public function builtAssetDetail()
    {
        $this->app->bind(
            AssetDetailInterface::class,
            AssetDetailRepository::class
        );
    }

    public function builtEmergencyDetail()
    {
        $this->app->bind(
            EmergencyDetailInterface::class,
            EmergencyDetailRepository::class
        );
    }

    public function builtBenefitDetail()
    {
        $this->app->bind(
            BenefitDetailInterface::class,
            BenefitDetailRepository::class
        );
    }

    public function builtEducationDetail()
    {
        $this->app->bind(
            EducationDetailInterface::class,
            EducationDetailRepository::class
        );
    }

    public function builtPreviousJobDetail()
    {
        $this->app->bind(
            PreviousJobDetailInterface::class,
            PreviousJobDetailRepository::class
        );
    }

    public function builtBankDetail()
    {
        $this->app->bind(
            BankDetailInterface::class,
            BankDetailRepository::class
        );
    }

    public function builtContractDetail()
    {
        $this->app->bind(
            ContractDetailInterface::class,
            ContractDetailRepository::class
        );
    }

    public function builtDocumentDetail()
    {
        $this->app->bind(
            DocumentDetailInterface::class,
            DocumentDetailRepository::class
        );
    }

    public function builtResearchAndPublicationDetail()
    {
        $this->app->bind(
            ResearchAndPublicationDetailInterface::class,
            ResearchAndPublicationDetailRepository::class
        );
    }

    public function builtVisaAndImmigrationDetail()
    {
        $this->app->bind(
            VisaAndImmigrationDetailInterface::class,
            VisaAndImmigrationDetailRepository::class
        );
    }

    public function builtMedicalDetail()
    {
        $this->app->bind(
            MedicalDetailInterface::class,
            MedicalDetailRepository::class
        );
    }

    public function appbindEmployeeSubstituteLeave()
    {
        $this->app->bind(
            EmployeeSubstituteLeaveInterface::class,
            EmployeeSubstituteLeaveRepository::class
        );
    }

    public function appbindEmployeeTransfer()
    {
        $this->app->bind(
            EmployeeTransferInterface::class,
            EmployeeTransferRepository::class
        );
    }

    public function appbindTrainingDetail()
    {
        $this->app->bind(
            TrainingDetailInterface::class,
            TrainingDetailRepository::class
        );
    }

    public function builtInsuranceDetail()
    {
        $this->app->bind(
            InsuranceDetailInterface::class,
            InsuranceDetailRepository::class
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