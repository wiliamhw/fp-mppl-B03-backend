<?php

namespace App\Http\Livewire\Cms\Nav;

use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Livewire\Component;

class Header extends Component
{
    use ResolveCurrentAdmin;

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.nav.header');
    }
}
