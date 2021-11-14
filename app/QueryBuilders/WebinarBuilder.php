<?php

namespace App\QueryBuilders;

use App\Http\Requests\WebinarGetRequest;
use App\Models\Webinar;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class WebinarBuilder extends Builder
{
    /**
     * Current HTTP Request object.
     *
     * @var WebinarGetRequest
     */
    protected $request;

    /**
     * WebinarBuilder constructor.
     *
     * @param WebinarGetRequest $request
     */
    public function __construct(WebinarGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Webinar::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'webinars.id',
            'webinars.category_id',
            'webinars.title',
            'webinars.start_at',
            'webinars.end_at',
            'webinars.price',
            'webinars.type',
            'webinars.zoom_id',
            'webinars.max_participants',
            'webinars.published_at',
            'webinars.created_at',
            'webinars.updated_at',
            'category.id',
            'category.name',
            'category.created_at',
            'category.updated_at',
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
            AllowedFilter::exact('category_id'),
            'title',
            AllowedFilter::exact('start_at'),
            AllowedFilter::exact('end_at'),
            AllowedFilter::exact('price'),
            'type',
            'zoom_id',
            AllowedFilter::exact('max_participants'),
            AllowedFilter::exact('published_at'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),

            AllowedFilter::scope('expired'),
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
            'category',
            'userWebinars',
            'users',
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
            'title',
            'zoom_id',
            'type',
            'category.name',
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
            'category_id',
            'title',
            'start_at',
            'end_at',
            'price',
            'type',
            'zoom_id',
            'max_participants',
            'published_at',
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
        return 'end_at';
    }

    /**
     * Get default query builder.
     *
     * @return QueryBuilder
     */
    public function query(): QueryBuilder
    {
        // @phpstan-ignore-next-line
        return parent::query()
            ->published()
            ->allowedAppends(['status']);
    }
}
