<?php

namespace App\Http\Livewire\Cms\SeoMetas;

use App\Models\SeoMeta;

class CreateSeoMeta extends SeoMetaForm
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
        $this->seoMeta = new SeoMeta();

        parent::mount();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.seo_metas.create_seo_meta')
            ->extends('cms::_layouts.app')
            ->section('content');
    }
}
