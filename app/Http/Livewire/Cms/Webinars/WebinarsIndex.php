<?php

namespace App\Http\Livewire\Cms\Webinars;

use App\Models\Webinar;
use Cms\Livewire\DatatableColumn;
use Cms\Livewire\DatatableComponent;
use Illuminate\Database\Eloquent\Builder;

class WebinarsIndex extends DatatableComponent
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
            DatatableColumn::make('category.id')->setInvisible(true),
            DatatableColumn::make('category.name')->setInvisible(true),
            DatatableColumn::make('title'),
            DatatableColumn::make('price')->setInvisible(true),
            DatatableColumn::make('start_at'),
            DatatableColumn::make('end_at'),
            DatatableColumn::make('end_at')->setInvisible(true)
                ->setTitle('Status')
                ->renderWith(function ($q) {
                    return ($q->status === Webinar::STATUS_HAS_START) ? 'Has start' : 'Hasn\'t start';
                }),
            DatatableColumn::make('type')->setInvisible(true)->renderWith(function ($q) {
                return ($q->type === Webinar::TYPE_FREE) ? 'Free' : 'Paid';
            }),
            DatatableColumn::make('max_participants')->setInvisible(true),
            DatatableColumn::make('published_at')->setInvisible(true),
            DatatableColumn::make('created_at')->setInvisible(true),
            DatatableColumn::make('updated_at')->setInvisible(true),
        ]);
    }

    /**
     * Defines the base route name for current datatable component.
     *
     * @return string
     */
    public function getBaseRouteName(): string
    {
        return 'cms.webinars.';
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
                'title' => 'Webinars',
                'url' => route($this->getBaseRouteName() . 'index'),
            ]
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
        return (new Webinar())
            ->newQuery()
            ->with(['category']);
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.webinars.webinars_index')
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
            'title',
            'description',
            'type',
            'zoom_id',
            'category.name',
        ];
    }
}
