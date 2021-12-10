<?php

namespace App\Http\Livewire\Cms\UserWebinars;

use App\Models\UserWebinar;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

abstract class UserWebinarForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * The related user webinar instance.
     *
     * @var UserWebinar
     */
    public UserWebinar $userWebinar;

    /**
     * Variable to store payment status options.
     *
     * @var array
     */
    public array $paymentStatusOptions;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    protected function rules(): array
    {
        return [
            'userWebinar.user_id' => 'required|integer|between:0,4294967295',
            'userWebinar.webinar_id' => 'required|integer|between:0,4294967295',
            'userWebinar.payment_status' => [
                'required', 'string', 'min:2', 'max:45',
                Rule::in(UserWebinar::PAYMENT_STATUS)
            ],
            'userWebinar.payment_method' => 'required|string|min:2|max:45',
            'userWebinar.payment_token' => 'required|string|min:2|max:45',
            'userWebinar.feedback' => 'required|string|min:2|max:65535',
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
            route('cms.user_webinars.index')
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
        $permission = 'cms.' . $this->userWebinar->getTable() . '.' . $this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit user webinar page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.user_webinars.edit', ['userWebinar' => $this->userWebinar])
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
                'title' => 'User Webinars',
                'url' => route('cms.user_webinars.index'),
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
            'The new user webinar has been saved.' :
            'The user webinar has been updated.';
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
        $this->paymentStatusOptions = UserWebinar::PAYMENT_STATUS_NAME;
    }

    /**
     * Save the user webinar model.
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.user_webinars.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->userWebinar->save();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.user_webinars.index'));
    }
}
