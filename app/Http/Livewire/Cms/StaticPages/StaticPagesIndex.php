<?php

namespace App\Http\Livewire\Cms\StaticPages;

use App\Models\StaticPage;
use Cms\Livewire\DatatableColumn;
use Cms\Livewire\DatatableComponent;
use Illuminate\Database\Eloquent\Builder;

class StaticPagesIndex extends DatatableComponent
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
            DatatableColumn::make('name'),
            DatatableColumn::make('slug')
                ->setInvisible(true),
            DatatableColumn::make('content')
                ->setInvisible(true),
            DatatableColumn::make('youtube_video')
                ->setInvisible(true),
            DatatableColumn::make('layout')
                ->setInvisible(true),
            DatatableColumn::make('published'),
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
        return 'cms.static_pages.';
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
                'title' => 'Static Pages',
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
        return (new StaticPage())
            ->newQuery()
            ->joinSeoMeta();
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.static_pages.static_pages_index')
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
            'name',
            'slug',
        ];
    }
}
