<?php

namespace Cms\Providers;

use Cms\Auth\AdminProvider;
use Cms\Blade\FormBuilder;
use Cms\Console\Commands\CreateNewAdmin;
use Cms\Providers\Concerns\HasMacros;
use Cms\Providers\Concerns\HasRoutes;
use Cms\Providers\Concerns\HasViews;
use Cms\Services\SeoService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    use HasMacros;
    use HasRoutes;
    use HasViews;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerAdminProvider();
        $this->registerAuthRoutes();
        $this->registerBladeDirectives();
        $this->registerCmsRoutes();
        $this->registerViews();
        $this->registerWhereLikeMacroToBuilder();

        $this->fixesUrlScheme();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->registerSingletons();
    }

    /**
     * Register Auth Admin provider.
     *
     * @return void
     */
    protected function registerAdminProvider(): void
    {
        \Auth::provider('admins', static function (Application $app, array $config) {
            return new AdminProvider($app->make(HasherContract::class), $config['model']);
        });
    }

    /**
     * Register the package's console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            CreateNewAdmin::class,
        ]);
    }

    /**
     * Register application singletons.
     *
     * @return void
     */
    protected function registerSingletons(): void
    {
        $this->app->singleton(FormBuilder::class, static function () {
            return new FormBuilder(app('form'));
        });

        $this->app->singleton(SeoService::class, static function () {
            return new SeoService();
        });
    }
}
