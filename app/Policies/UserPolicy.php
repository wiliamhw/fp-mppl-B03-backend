<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the User can view any models.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->can($user, new User(), 'viewAny');
    }

    /**
     * Determine whether the User can view the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function view(User $user): bool
    {
        return $this->can($user, $user, 'view');
    }

    /**
     * Determine whether the User can create models.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->can($user, new User(), 'create');
    }

    /**
     * Determine whether the User can update the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function update(User $user): bool
    {
        return $this->can($user, $user, 'update');
    }

    /**
     * Determine whether the User can delete the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $this->can($user, $user, 'delete');
    }

    /**
     * Determine whether the User can restore the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $this->can($user, $user, 'restore');
    }

    /**
     * Determine whether the User can permanently delete the model.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $this->can($user, $user, 'forceDelete');
    }
}
