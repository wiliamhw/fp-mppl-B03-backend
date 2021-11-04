<?php

namespace Cms\Providers\Concerns;

use Cms\Blade\MultilingualForm;
use Illuminate\Support\Facades\Blade;

trait HasViews
{
    /**
     * Register custom blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('multilingual', static function ($expression) {
            return (new MultilingualForm($expression))->getOpeningElements();
        });

        Blade::directive('endmultilingual', static function () {
            return (new MultilingualForm())->getEndingElements();
        });
    }

    /**
     * Register the package's views.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $viewPath = realpath(base_path('resources/views/vendor/cms'));

        if ($viewPath !== false) {
            $this->loadViewsFrom($viewPath, 'cms');
        }
    }
}
