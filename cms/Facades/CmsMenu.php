<?php

namespace Cms\Facades;

use Cms\Blade\MenuBuilder;
use Illuminate\Support\Facades\Facade;

class CmsMenu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return MenuBuilder::class;
    }
}
