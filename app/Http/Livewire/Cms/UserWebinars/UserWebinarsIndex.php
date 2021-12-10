<?php

namespace App\Http\Livewire\Cms\UserWebinars;

use App\Models\UserWebinar;
use Cms\Livewire\DatatableColumn;
use Cms\Livewire\DatatableComponent;
use Illuminate\Database\Eloquent\Builder;

class UserWebinarsIndex extends DatatableComponent
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
            DatatableColumn::make('user_id'),
            DatatableColumn::make('user.name')->setInvisible(true),
            DatatableColumn::make('webinar_id'),
            DatatableColumn::make('webinar.title')->setInvisible(true),
            DatatableColumn::make('payment_status')->renderWith(function ($q) {
                return UserWebinar::PAYMENT_STATUS_NAME[$q->payment_status];
            }),
            DatatableColumn::make('payment_method'),
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
        return 'cms.user_webinars.';
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
                'title' => 'User Webinars',
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
        return (new UserWebinar())
            ->newQuery()
            ->with(['user', 'webinar']);
    }

    /**
     * Render the LiveWire component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function render()
    {
        return view('livewire.cms.user_webinars.user_webinars_index')
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
            'payment_status',
            'payment_method',
            'payment_token',
            'feedback',
            'user.email',
            'user.password',
            'user.name',
            'user.phone_number',
            'webinar.title',
            'webinar.description',
            'webinar.type',
            'webinar.zoom_id',
        ];
    }
}
