<?php

namespace App\Http\Livewire\Cms\Home;

use Livewire\Component;

class Index extends Component
{
    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.home.index')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
