<?php

namespace App\Http\Livewire\Cms\Webinars;

use App\Models\Webinar;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class WebinarForm extends Component
{
    use AuthorizesRequests;
    use ResolveCurrentAdmin;

    /**
     * The related webinar instance.
     *
     * @var Webinar
     */
    public Webinar $webinar;

    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation;

    /**
     * The validation rules for webinar model.
     *
     * @var string[]
     */
    protected array $rules = [
        'webinar.category_id' => 'required|integer|between:0,4294967295',
        'webinar.title' => 'required|string|min:2|max:255',
        'webinar.description' => 'required|string|min:2|max:511',
        'webinar.start_at' => 'required|date',
        'webinar.end_at' => 'required|date',
        'webinar.price' => 'required|integer|between:0,4294967295',
        'webinar.type' => 'required|string|min:2|max:32',
        'webinar.zoom_id' => 'required|string|min:2|max:11',
        'webinar.max_participants' => 'required|integer|between:0,4294967295',
        'webinar.published_at' => 'required|date',
    ];

    /**
     * Redirect and go back to index page.
     *
     * @return mixed
     */
    public function backToIndex()
    {
        return redirect()->to(
            route('cms.webinars.index')
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
        $permission = 'cms.' . $this->webinar->getTable() . '.' . $this->operation;

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Redirect to the edit webinar page.
     *
     * @return mixed
     */
    public function edit()
    {
        return redirect()->to(
            route('cms.webinars.edit', ['webinar' => $this->webinar])
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
                'title' => 'Webinars',
                'url' => route('cms.webinars.index'),
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
            'The new webinar has been saved.' :
            'The webinar has been updated.';
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
     * Save the webinar model.
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function save()
    {
        if (($this->operation !== 'create') && ($this->operation !== 'update')) {
            return redirect()->to(route('cms.webinars.index'));
        }

        $this->confirmAuthorization();
        $this->validate();

        $this->webinar->save();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', $this->getSuccessMessage());

        return redirect()->to(route('cms.webinars.index'));
    }
}
