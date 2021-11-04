<?php

namespace App\QueryBuilders;

use App\Http\Requests\StaticPageGetRequest;
use App\Models\StaticPage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class StaticPageBuilder extends Builder
{
    /**
     * Current HTTP Request object.
     *
     * @var StaticPageGetRequest
     */
    protected $request;

    /**
     * StaticPageBuilder constructor.
     *
     * @param StaticPageGetRequest $request
     */
    public function __construct(StaticPageGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(StaticPage::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'static_pages.id',
            'static_pages.name',
            'static_pages.slug',
            'static_pages.content',
            'static_pages.youtube_video',
            'static_pages.layout',
            'static_pages.published',
            'static_pages.deleted_at',
            'static_pages.created_at',
            'static_pages.updated_at',
        ];
    }

    /**
     * Get a list of allowed columns that can be used in any filter operations.
     *
     * @return array
     */
    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::exact('id'),
            'name',
            'slug',
            'content',
            'youtube_video',
            'layout',
            'published',
            AllowedFilter::exact('deleted_at'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('static_pages.id'),
            'static_pages.name',
            'static_pages.slug',
            'static_pages.content',
            'static_pages.youtube_video',
            'static_pages.layout',
            'static_pages.published',
            AllowedFilter::exact('static_pages.deleted_at'),
            AllowedFilter::exact('static_pages.created_at'),
            AllowedFilter::exact('static_pages.updated_at'),
        ];
    }

    /**
     * Get a list of allowed relationships that can be used in any include operations.
     *
     * @return string[]
     */
    protected function getAllowedIncludes(): array
    {
        return [
            'seoMetas',
            'seo_metas',
        ];
    }

    /**
     * Get a list of allowed searchable columns which can be used in any search operations.
     *
     * @return string[]
     */
    protected function getAllowedSearch(): array
    {
        return [
            'name',
            'slug',
            'content',
            'youtube_video',
            'layout',
            'published',
        ];
    }

    /**
     * Get a list of allowed columns that can be used in any sort operations.
     *
     * @return string[]
     */
    protected function getAllowedSorts(): array
    {
        return [
            'id',
            'name',
            'slug',
            'content',
            'youtube_video',
            'layout',
            'published',
            'deleted_at',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Get the default sort column that will be used in any sort operation.
     *
     * @return string
     */
    protected function getDefaultSort(): string
    {
        return 'id';
    }
}
