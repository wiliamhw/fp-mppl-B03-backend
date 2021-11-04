<?php

namespace App\Http\Livewire\Cms\SeoMetas;

use App\Models\SeoMeta;
use Cms\Livewire\DatatableColumn;
use Cms\Livewire\DatatableComponent;
use Illuminate\Database\Eloquent\Builder;

class SeoMetasIndex extends DatatableComponent
{
    /**
     * Specify the datatable's columns and their behaviors.
     *
     * @return array
     */
    public function columns(): array
    {
        return $this->applyColumnVisibility([
            DatatableColumn::make('id'),
            DatatableColumn::make('seo_url')
                ->setTitle('URL'),
            DatatableColumn::make('seo_title')
                ->setTitle('Title')
                ->setInvisible(true),
            DatatableColumn::make('seo_description')
                ->setTitle('Description')
                ->setInvisible(true),
            DatatableColumn::make('open_graph_type')
                ->setInvisible(true),
            DatatableColumn::make('created_at'),
            DatatableColumn::make('updated_at')
                ->setInvisible(true),
        ]);
    }

    /**
     * Defines the base route name for current datatable component.
     *
     * @return string
     */
    public function getBaseRouteName(): string
    {
        return 'cms.seo_metas.';
    }

    /**
     * Provide the breadcrumb items for the current livewire component.
     *
     * @return array[]
     */
    public function getBreadcrumbItemsProperty(): array
    {
        return [
            [
                'title' => 'Seo Metas',
                'url'   => route($this->getBaseRouteName().'index'),
            ],
        ];
    }

    /**
     * Get a new query builder instance for the current datatable component.
     * You may include the model's relationships if it's necessary.
     *
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        return (new SeoMeta())
            ->newQuery()
            ->whereNull(['attachable_type', 'attachable_id', 'locale']);
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.seo_metas.seo_metas_index')
            ->extends('cms::_layouts.app')
            ->section('content');
    }

    /**
     * Specify the searchable column names in the current datatable component.
     *
     * @return array
     */
    protected function searchableColumns(): array
    {
        return [
            'seo_url',
            'seo_title',
            'seo_description',
        ];
    }
}
