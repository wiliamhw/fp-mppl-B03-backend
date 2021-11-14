<?php

namespace App\QueryBuilders;

use App\Http\Requests\CategoryGetRequest;
use App\Models\Category;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CategoryBuilder extends Builder
{
    /**
     * Current HTTP Request object.
     *
     * @var CategoryGetRequest
     */
    protected $request;

    /**
     * CategoryBuilder constructor.
     *
     * @param CategoryGetRequest $request
     */
    public function __construct(CategoryGetRequest $request)
    {
        $this->request = $request;
        $this->builder = QueryBuilder::for(Category::class, $request);
    }

    /**
     * Get a list of allowed columns that can be selected.
     *
     * @return string[]
     */
    protected function getAllowedFields(): array
    {
        return [
            'categories.id',
            'categories.name',
            'categories.created_at',
            'categories.updated_at',
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
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
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
            'webinars',
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
        return 'name';
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
            ->has('webinars');
    }
}
