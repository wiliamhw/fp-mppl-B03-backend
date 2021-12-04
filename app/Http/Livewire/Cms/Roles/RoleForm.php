<?php

namespace App\Http\Livewire\Cms\Roles;

use App\Models\Admin;
use App\Models\Role;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

abstract class RoleForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * Stores an array of permission groups.
     *
     * @var string[]
     */
    public array $permissionGroups;

    /**
     * Stores an array of permissions which the current role is capable to.
     *
     * @var string[]
     */
    public array $permissions;

    /**
     * The related role instance.
     *
     * @var Role
     */
    public Role $role;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * The validation rules for role model.
     *
     * @var string[]
     */
    protected array $rules = [
        'role.name'       => 'required|string|min:2|max:255',
        'role.guard_name' => 'required|string|min:2|max:255',
    ];

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.roles.index')
        );
    }

    /**
     * Find out if the currently logged in Admin can manage at least one of the child nodes
     * of the given main menu node.
     *
     * @param array $menuNode
     *
     * @return bool
     */
    public function canManageAtLeastOneNode(array $menuNode): bool
    {
        if (!isset($menuNode['children']) || !is_array($menuNode['children']) || (count($menuNode['children']) === 0)) {
            return false;
        }

        foreach ($menuNode['children'] as $child) {
            if ($this->canManageNode($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the currently logged in Admin has any permission to manage the given CMS menu node.
     *
     * @param array $childNode
     *
     * @return bool
     */
    public function canManageNode(array $childNode): bool
    {
        if (!isset($childNode['permission'])) {
            return false;
        }

        $segments = explode('.', $childNode['permission']);

        return !(
            (!isset($segments[0]) ||
            ($segments[0] !== 'cms') ||
            !in_array($segments[1], $this->permissionGroups, true))
        );
    }

    /**
     * Confirm Admin authorization to access the datatable resources.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    protected function confirmAuthorization(): void
    {
        $permission = 'cms.'.$this->role->getTable().'.'.$this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit role page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.roles.edit', ['role' => $this->role])
        );
    }

    /**
     * Provide the breadcrumb items for the current livewire component.
     *
     * @return array[]
     */
    public function getBreadcrumbItemsProperty(): array
    {
        return [
            [
                'title' => 'Roles',
                'url'   => route('cms.roles.index'),
            ],
        ];
    }

    /**
     * Get the permission groups based on the given permission collection.
     *
     * @param Collection $permissions
     *
     * @return Collection
     */
    protected function getPermissionGroups(Collection $permissions): Collection
    {
        $permissions->sortBy('name');
        $groups = collect();

        foreach ($permissions as $permission) {
            $segments = explode('.', $permission->getAttribute('name'));

            if (!isset($segments[0]) || ($segments[0] !== 'cms')) {
                continue;
            }

            if (isset($segments[1]) && !$groups->contains($segments[1])) {
                $groups->push($segments[1]);
            }
        }

        return $groups;
    }

    /**
     * Generate permission group name.
     *
     * @param string $permission
     * @param string $style
     *
     * @return string
     */
    public function getPermissionGroupName(string $permission, string $style = ''): string
    {
        $segments = explode('.', $permission);

        if ($style === 'raw') {
            return $segments[1];
        }

        return Str::title(
            str_replace('_', ' ', Str::snake(Str::plural($segments[1])))
        );
    }

    /**
     * Get the success message after `save` action called successfully.
     *
     * @return string
     */
    protected function getSuccessMessage(): string
    {
        return ($this->operation === 'create') ?
            'The new role has been saved.' :
            'The role has been updated.';
    }

    /**
     * Handle the `mount` lifecycle event.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function mount(): void
    {
        $this->confirmAuthorization();

        $collection = $this->role->getAllPermissions();

        $this->permissions = $collection->map(static function (Permission $permission) {
            return $permission->getAttribute('name');
        })->all();

        $this->permissionGroups = $this->getPermissionGroups(cms_admin()->getAllPermissions())->toArray();
    }

    /**
     * Save the role model.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.roles.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->role->save();

        in_array('access-cms', $this->permissions) ?: array_push($this->permissions, 'access-cms');
        $this->role->syncPermissions($this->permissions);

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.roles.index'));
    }
}
