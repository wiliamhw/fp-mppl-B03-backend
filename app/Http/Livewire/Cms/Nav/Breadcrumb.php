<?php

namespace App\Http\Livewire\Cms\Nav;

use Livewire\Component;

class Breadcrumb extends Component
{
    /**
     * Breadcrumb items in array format.
     *
     * @var array|null
     */
    public $items;

    /**
     * Generate default breadcrumb items.
     *
     * @return array[]
     */
    protected function getDefaultItems(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'url'   => url('/'.config('cms.path_prefix')),
            ],
        ];
    }

    /**
     * Handle the mount event.
     *
     * @throws \ErrorException
     */
    public function mount(): void
    {
        $this->items = ($this->items === null) ?
            $this->getDefaultItems() :
            array_merge($this->getDefaultItems(), $this->items);
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.nav.breadcrumb');
    }
}
