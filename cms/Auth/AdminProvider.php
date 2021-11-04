<?php

namespace Cms\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class AdminProvider extends EloquentUserProvider
{
    /**
     * Return value when there was no Admin found.
     *
     * @var Authenticatable|null
     */
    public ?Authenticatable $noAdmin = null;

    /**
     * Retrieve an admin by the given credentials.
     *
     * @param array $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $admin = parent::retrieveByCredentials($credentials);

        if ($admin === null) {
            return $this->noAdmin;
        }

        if (!method_exists($admin, 'hasPermissionTo') || !$admin->hasPermissionTo('access-cms', config('cms.guard'))) {
            return $this->noAdmin;
        }

        return $admin;
    }
}
