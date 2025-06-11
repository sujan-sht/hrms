<?php

namespace App\Modules\Onboarding\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Onboarding\Repositories\MrfInterface;
use App\Modules\Onboarding\Repositories\MrfRepository;
use App\Modules\Onboarding\Repositories\ApplicantInterface;
use App\Modules\Onboarding\Repositories\InterviewInterface;
use App\Modules\Onboarding\Repositories\ApplicantRepository;
use App\Modules\Onboarding\Repositories\EvaluationInterface;
use App\Modules\Onboarding\Repositories\InterviewRepository;
use App\Modules\Onboarding\Repositories\EvaluationRepository;
use App\Modules\Onboarding\Repositories\OfferLetterInterface;
use App\Modules\Onboarding\Repositories\OfferLetterRepository;
use App\Modules\Onboarding\Repositories\InterviewLevelInterface;
use App\Modules\Onboarding\Repositories\InterviewLevelRepository;
use App\Modules\Onboarding\Repositories\OnboardInterface;
use App\Modules\Onboarding\Repositories\OnboardRepository;

class OnboardingServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Onboarding';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'onboarding';

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
        $this->appBindMrf();
        $this->appBindApplicant();
        $this->appBindInterviewLevel();
        $this->appBindInterview();
        $this->appBindEvaluation();
        $this->appBindOfferLetter();
        $this->appBindOnboard();
    }

    /**
     * Bind Onboard
     */
    public function appBindOnboard()
    {
        $this->app->bind(OnboardInterface::class, OnboardRepository::class);
    }

    /**
     * App bind with mrf
     */
    public function appBindMrf()
    {
        $this->app->bind(
            MrfInterface::class,
            MrfRepository::class
        );
    }

    /**
     * App bind with applicant
     */
    public function appBindApplicant()
    {
        $this->app->bind(
            ApplicantInterface::class,
            ApplicantRepository::class
        );
    }

    /**
     * App bind with interview level
     */
    public function appBindInterviewLevel()
    {
        $this->app->bind(
            InterviewLevelInterface::class,
            InterviewLevelRepository::class
        );
    }

    /**
     * App bind with interview 
     */
    public function appBindInterview()
    {
        $this->app->bind(
            InterviewInterface::class,
            InterviewRepository::class
        );
    }

    /**
     * App bind with evaluation 
     */
    public function appBindEvaluation()
    {
        $this->app->bind(
            EvaluationInterface::class,
            EvaluationRepository::class
        );
    }

    /**
     * App bind with offer letter 
     */
    public function appBindOfferLetter()
    {
        $this->app->bind(
            OfferLetterInterface::class,
            OfferLetterRepository::class
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
