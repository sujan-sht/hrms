<?php

namespace App\Modules\Appraisal\Providers;

use App\Modules\Appraisal\Repositories\AppraisalInterface;
use App\Modules\Appraisal\Repositories\AppraisalRepository;
use App\Modules\Appraisal\Repositories\CompetencyRepository;
use App\Modules\Appraisal\Repositories\CompetencyInterface;
use App\Modules\Appraisal\Repositories\CompetencyLibraryInterface;
use App\Modules\Appraisal\Repositories\CompetencyLibraryRepository;
use App\Modules\Appraisal\Repositories\CompetencyQuestionInterface;
use App\Modules\Appraisal\Repositories\CompetencyQuestionRepository;
use App\Modules\Appraisal\Repositories\QuestionnaireInterface;
use App\Modules\Appraisal\Repositories\QuestionnaireRepository;
use App\Modules\Appraisal\Repositories\RatingScaleInterface;
use App\Modules\Appraisal\Repositories\RatingScaleRepository;
use App\Modules\Appraisal\Repositories\ScoreInterface;
use App\Modules\Appraisal\Repositories\ScoreRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class AppraisalServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Appraisal';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'appraisal';

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
        $this->registerScore();
        $this->registerRatingscale();
        $this->registerCompetencyLibrary();
        $this->registerCompetency();
        $this->registerCompetencyQuestion();
        $this->registerCompetencyQuestion();
        $this->registerQuestionnaire();
        $this->registerAppraisal();
    }


    public function registerScore()
    {
        $this->app->bind(
           ScoreInterface::class,
           ScoreRepository::class
        );
    }
    public function registerRatingscale()
    {
        $this->app->bind(
           RatingScaleInterface::class,
           RatingScaleRepository::class
        );
    }

    public function registerCompetencyLibrary()
    {
        $this->app->bind(
           CompetencyLibraryInterface::class,
           CompetencyLibraryRepository::class
        );
    }

    public function registerCompetency()
    {
        $this->app->bind(
           CompetencyInterface::class,
           CompetencyRepository::class
        );
    }

    public function registerCompetencyQuestion()
    {
        $this->app->bind(
           CompetencyQuestionInterface::class,
           CompetencyQuestionRepository::class
        );
    }

    public function registerQuestionnaire()
    {
        $this->app->bind(
           QuestionnaireInterface::class,
           QuestionnaireRepository::class
        );
    }

    public function registerAppraisal()
    {
        $this->app->bind(
           AppraisalInterface::class,
           AppraisalRepository::class
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
