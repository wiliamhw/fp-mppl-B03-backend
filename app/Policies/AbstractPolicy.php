<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractPolicy
{
    /**
     * Determine if the given User has permission to do the given ability
     * toward the given model object.
     *
     * @param User $user
     * @param Model    $model
     * @param string   $ability
     *
     * @return bool
     */
    protected function can(User $user, Model $model, string $ability): bool
    {
        $ability = $this->getPermissionKey($ability, $model);

        return $this->hasPermissions($user) && $user->can($ability);
    }

    /**
     * Generate the permission key.
     *
     * @param string $ability
     * @param Model  $model
     *
     * @return string
     */
    protected function getPermissionKey(string $ability, Model $model): string
    {
        return 'cms.'.$model->getTable().'.'.$ability;
    }

    /**
     * Determine if the current admin object has permissions.
     *
     * @param User $user
     *
     * @return bool
     */
    protected function hasPermissions(User $user): bool
    {
        return method_exists($user, 'can');
    }
}
