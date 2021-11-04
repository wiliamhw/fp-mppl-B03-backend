<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicReadOnlyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User|null $user
     *
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        unset($user);

        return true;
    }

    /**
     * Determine whether the user can view a single model.
     *
     * @param User|null $user
     *
     * @return bool
     */
    public function view(?User $user): bool
    {
        unset($user);

        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool
     */
    public function update(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }
}
