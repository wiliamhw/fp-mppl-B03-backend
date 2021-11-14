<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Webinar;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebinarPolicy extends AbstractPolicy
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
        return $this->can($user, new Webinar(), 'viewAny');
    }

    /**
     * Determine whether the User can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Webinar $webinar
     *
     * @return bool
     */
    public function view(User $user, Webinar $webinar): bool
    {
        return $this->can($user, $webinar, 'view');
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
        return $this->can($user, new Webinar(), 'create');
    }

    /**
     * Determine whether the User can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Webinar $webinar
     *
     * @return bool
     */
    public function update(User $user, Webinar $webinar): bool
    {
        return $this->can($user, $webinar, 'update');
    }

    /**
     * Determine whether the User can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Webinar $webinar
     *
     * @return bool
     */
    public function delete(User $user, Webinar $webinar): bool
    {
        return $this->can($user, $webinar, 'delete');
    }

    /**
     * Determine whether the User can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Webinar $webinar
     *
     * @return bool
     */
    public function restore(User $user, Webinar $webinar): bool
    {
        return $this->can($user, $webinar, 'restore');
    }

    /**
     * Determine whether the User can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Webinar $webinar
     *
     * @return bool
     */
    public function forceDelete(User $user, Webinar $webinar): bool
    {
        return $this->can($user, $webinar, 'forceDelete');
    }
}
