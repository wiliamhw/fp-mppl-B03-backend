<?php

namespace Cms\Providers\Concerns;

use Cms\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

trait HasRoutes
{
    /**
     * Fixes URL scheme to enforce https url.
     */
    protected function fixesUrlScheme(): void
    {
        if ((config('cms.force_https_url') === true) || app()->environment(['production', 'staging'])) {
            URL::forceScheme('https');
        }
    }

    /**
     * Define the "auth" routes for the CMS application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function registerAuthRoutes(): void
    {
        $routeFile = realpath(base_path('routes/cms-auth.php'));

        if ($routeFile !== false) {
            Route::prefix(config('cms.auth_path_prefix'))
                ->name('cms.auth.')
                ->namespace('Cms\Http\Controllers\Auth')
                ->middleware(config('cms.auth_middleware'))
                ->group($routeFile);
        }
    }

    /**
     * Define the "cms" routes for the CMS application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function registerCmsRoutes(): void
    {
        $routeFile = realpath(base_path('routes/cms.php'));

        if ($routeFile !== false) {
            Route::prefix(config('cms.path_prefix'))
                ->name('cms.')
                ->namespace(config('cms.controller_namespace'))
                ->middleware(config('cms.middleware'))
                ->group($routeFile);
        }

        Route::prefix(config('cms.path_prefix'))
            ->middleware(config('cms.middleware'))
            ->get('current-admin/logout', LoginController::class.'@logout')
            ->name('cms.current-admin.logout');
    }
}
