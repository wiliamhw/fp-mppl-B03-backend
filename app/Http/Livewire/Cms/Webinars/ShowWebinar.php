<?php

namespace App\Http\Livewire\Cms\Webinars;

use App\Models\Webinar;

class ShowWebinar extends WebinarForm
{
    /**
     * Define the current operation of the livewire component.
     * The valid options for operation values are: create, view, update.
     *
     * @var string
     */
    protected string $operation = 'view';

    public string $webinarType;
    public string $priceInRp;

    /**
     * Handle the `mount` lifecycle event.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    public function mount(): void
    {
        parent::mount();
        $this->priceInRp = formatRupiah($this->webinar->price);
        $this->webinarType = ($this->webinar->type === Webinar::TYPE_FREE) ? 'Free' : 'Paid';
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.webinars.show_webinar')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
