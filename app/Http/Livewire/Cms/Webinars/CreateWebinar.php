<?php

namespace App\Http\Livewire\Cms\Webinars;

use App\Models\Webinar;

class CreateWebinar extends WebinarForm
{
    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation = 'create';

    /**
     * Handle the `mount` lifecycle event.
     */
    public function mount(): void
    {
        $this->webinar = new Webinar();

        parent::mount();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.webinars.create_webinar')
            ->extends('cms::_layouts.app')
            ->section('content');
    }

    /**
     * Save the promo model.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function save()
    {
        if (!isset($this->webinar['category_id'])) {
            $this->webinar['category_id'] = array_key_first($this->categoryOptions);
        }
        if (!isset($this->webinar['price']) || $this->webinar['price'] === "") {
            $this->webinar['price'] = 0;
        }
        parent::save();
    }
}
