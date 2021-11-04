<?php

namespace Cms\Facades;

use Cms\Blade\FormBuilder;
use Illuminate\Support\Facades\Facade;

class CmsForm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FormBuilder::class;
    }
}
