<?php

namespace App\Http\Livewire\Cms\Categories;

use App\Models\Category;

class CreateCategory extends CategoryForm
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
        $this->category = new Category();

        parent::mount();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.categories.create_category')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
