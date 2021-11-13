<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy extends AbstractPolicy
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
        return $this->can($user, new Category(), 'viewAny');
    }

    /**
     * Determine whether the User can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Category $category
     *
     * @return bool
     */
    public function view(User $user, Category $category): bool
    {
        return $this->can($user, $category, 'view');
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
        return $this->can($user, new Category(), 'create');
    }

    /**
     * Determine whether the User can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Category $category
     *
     * @return bool
     */
    public function update(User $user, Category $category): bool
    {
        return $this->can($user, $category, 'update');
    }

    /**
     * Determine whether the User can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Category $category
     *
     * @return bool
     */
    public function delete(User $user, Category $category): bool
    {
        return $this->can($user, $category, 'delete');
    }

    /**
     * Determine whether the User can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Category $category
     *
     * @return bool
     */
    public function restore(User $user, Category $category): bool
    {
        return $this->can($user, $category, 'restore');
    }

    /**
     * Determine whether the User can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Category $category
     *
     * @return bool
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $this->can($user, $category, 'forceDelete');
    }
}
