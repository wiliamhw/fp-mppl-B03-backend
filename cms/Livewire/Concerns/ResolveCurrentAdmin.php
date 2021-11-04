<?php

namespace Cms\Livewire\Concerns;

use App\Models\Admin;

trait ResolveCurrentAdmin
{
    /**
     * Get the currently logged in admin instance.
     *
     * @throws \ErrorException
     *
     * @return Admin
     */
    public function getCurrentAdminProperty(): Admin
    {
        return cms_admin();
    }
}
