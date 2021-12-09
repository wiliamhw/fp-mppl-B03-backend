<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserWebinar;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserWebinarPolicy extends AbstractPolicy
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
        return $this->can($user, new UserWebinar(), 'viewAny');
    }

    /**
     * Determine whether the User can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return bool
     */
    public function view(User $user, UserWebinar $userWebinar): bool
    {
        return $this->can($user, $userWebinar, 'view');
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
        return $this->can($user, new UserWebinar(), 'create');
    }

    /**
     * Determine whether the User can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return bool
     */
    public function update(User $user, UserWebinar $userWebinar): bool
    {
        return $this->can($user, $userWebinar, 'update');
    }

    /**
     * Determine whether the User can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return bool
     */
    public function delete(User $user, UserWebinar $userWebinar): bool
    {
        return $this->can($user, $userWebinar, 'delete');
    }

    /**
     * Determine whether the User can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return bool
     */
    public function restore(User $user, UserWebinar $userWebinar): bool
    {
        return $this->can($user, $userWebinar, 'restore');
    }

    /**
     * Determine whether the User can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\UserWebinar $userWebinar
     *
     * @return bool
     */
    public function forceDelete(User $user, UserWebinar $userWebinar): bool
    {
        return $this->can($user, $userWebinar, 'forceDelete');
    }
}
