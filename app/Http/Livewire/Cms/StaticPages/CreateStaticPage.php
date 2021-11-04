<?php

namespace App\Http\Livewire\Cms\StaticPages;

use App\Models\StaticPage;

class CreateStaticPage extends StaticPageForm
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
        $this->staticPage = new StaticPage();

        parent::mount();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.static_pages.create_static_page')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
