<?php

namespace App\Http\Livewire\Cms\Admins;

use App\Models\Admin;
use App\Models\Role;
use App\Rules\DigitExist;
use App\Rules\LowercaseExist;
use App\Rules\UppercaseExist;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

abstract class AdminForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * The related admin instance.
     *
     * @var Admin
     */
    public Admin $admin;

    /**
     * Store the Admin's temporary data.
     *
     * @var Collection
     */
    public Collection $data;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * Stores a list of role options.
     *
     * @var string[]
     */
    public array $roleOptions;

    /**
     * The validation rules for admin model.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'data.name'                  => 'required|string|min:2|max:255',
            'data.email'                 => 'required|string|email|min:11|max:255',
            'data.password'              => ['required', 'string', 'min:8', new UppercaseExist(), new LowercaseExist(), new DigitExist()],
            'data.password_confirmation' => ['required', 'string'],
            'data.roles'                 => 'nullable',
        ];
    }

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.admins.index')
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
        $permission = 'cms.'.$this->admin->getTable().'.'.$this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit admin page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.admins.edit', ['admin' => $this->admin])
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
                'title' => 'Admins',
                'url'   => route('cms.admins.index'),
            ],
        ];
    }

    /**
     * Get the success message after `save` action called successfully.
     *
     * @return string
     */
    protected function getSuccessMessage(): string
    {
        return ($this->operation === 'create') ?
            'The new admin has been saved.' :
            'The admin has been updated.';
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

        $this->data = collect([
            'name'                  => $this->admin->getAttribute('name'),
            'email'                 => $this->admin->getAttribute('email'),
            'password'              => null,
            'password_confirmation' => null,
            'roles'                 => $this->admin->getRoleNames()->toArray(),
        ]);

        $this->roleOptions = Role::pluck('name', 'name')->toArray();
    }

    /**
     * Save the admin model.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.admins.index'));
        }

        $this->confirmAuthorization();
        $this->dispatchBrowserEvent('LiveWireComponentRefreshed');
        $this->validate();

        $this->data->forget('password_confirmation');

        if ($this->data->get('password') === null || ($this->data->get('password') === '')) {
            $this->data->forget('password');
        }

        $this->admin->fill($this->data->only(['name', 'email', 'password'])->all());

        if ($this->admin->isDirty(['password'])) {
            $this->admin->password = Hash::make($this->admin->password);
        }

        $this->admin->save();
        $this->admin->syncRoles($this->data->get('roles'));

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.admins.index'));
    }

    /**
     * Handle the `updated` lifecycle hook.
     *
     * @param string $name
     */
    public function updated(string $name): void
    {
        if ($name === 'data.roles') {
            $this->dispatchBrowserEvent('LiveWireComponentRefreshed');
        }
    }
}
