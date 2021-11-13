<?php

namespace App\Http\Livewire\Cms\Users;

use App\Models\User;
use App\Rules\DigitExist;
use App\Rules\LowercaseExist;
use App\Rules\UppercaseExist;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

abstract class UserForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * The related user instance.
     *
     * @var User
     */
    public User $user;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * Store the Admin's temporary data.
     *
     * @var Collection
     */
    public Collection $data;

    /**
     * The validation rules for user model.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'user.name'                     => 'required|string|min:2|max:255',
            'user.email'                    => 'required|string|email|min:11|max:255',
            'data.password'                 => ['required', 'string', 'min:8', new UppercaseExist(), new LowercaseExist(), new DigitExist()],
            'data.password_confirmation'    => 'required|string',
            'user.phone_number'             => 'required|string|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/|max:16',
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
            route('cms.users.index')
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
        $permission = 'cms.' . $this->user->getTable() . '.' . $this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit user page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.users.edit', ['user' => $this->user])
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
                'title' => 'Users',
                'url' => route('cms.users.index'),
            ]
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
            'The new user has been saved.' :
            'The user has been updated.';
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
            'password'              => null,
            'password_confirmation' => null,
        ]);
    }

    /**
     * Save the user model.
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.users.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->data->forget('password_confirmation');

        if ($this->data->get('password') === null || ($this->data->get('password') === '')) {
            $this->data->forget('password');
        }

        $this->user->fill($this->data->only($this->user->offsetGet('fillable'))->toArray());

        if ($this->user->isDirty(['password'])) {
            $this->user->password = Hash::make($this->user->password);
        }

        $this->user->save();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.users.index'));
    }
}
