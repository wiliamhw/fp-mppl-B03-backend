<?php

namespace App\Http\Livewire\Cms\Settings;

use App\Models\Setting;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class SettingForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * The related setting instance.
     *
     * @var Setting
     */
    public Setting $setting;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * The validation rules for setting model.
     *
     * @var string[]
     */
    protected array $rules = [
        'setting.key'   => 'required|string|min:2|max:255',
        'setting.value' => 'required|string|min:2|max:65535',
        'setting.type'  => 'required|in:text,textarea,number,email',
    ];

    /**
     * Defines the setting type options.
     *
     * @var string[]
     */
    public array $settingTypes = [
        'text'     => 'text',
        'textarea' => 'textarea',
        'number'   => 'number',
        'email'    => 'email',
    ];

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.settings.index')
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
        $permission = 'cms.'.$this->setting->getTable().'.'.$this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit setting page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.settings.edit', ['setting' => $this->setting])
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
                'title' => 'Settings',
                'url'   => route('cms.settings.index'),
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
            'The new setting has been saved.' :
            'The setting has been updated.';
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
    }

    /**
     * Save the setting model.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.settings.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->setting->save();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.settings.index'));
    }
}
