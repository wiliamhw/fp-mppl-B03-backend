<?php

namespace Cms\Facades;

use Cms\Services\SeoService;
use Illuminate\Support\Facades\Facade;

class SEO extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SeoService::class;
    }
}
