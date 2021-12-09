<?php

namespace App\Http\Livewire\Cms\UserWebinars;

use App\Models\UserWebinar;

class CreateUserWebinar extends UserWebinarForm
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
        $this->userWebinar = new UserWebinar();

        parent::mount();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.user_webinars.create_user_webinar')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
