<?php

namespace App\Http\Livewire\Cms\Nav;

use Livewire\Component;

class SideBar extends Component
{
    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.nav.side-bar');
    }
}
