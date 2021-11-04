<?php

namespace Cms\Livewire\Concerns;

use Cms\Livewire\ComponentDataRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

trait ProcessingPaginatedData
{
    /**
     * Store the base SQL command which populate the current datatable component's data.
     *
     * @var string
     */
    public string $baseSql = '';

    /**
     * Specify the datatable's current page index.
     *
     * @var int
     */
    public int $currentPage = 1;

    /**
     * The paginated data which will be displayed in the datatable component's user interface.
     *
     * @var LengthAwarePaginator
     */
    protected LengthAwarePaginator $data;

    /**
     * The number of items to be shown per page.
     *
     * @var int
     */
    public int $perPage = 10;

    /**
     * Specify the options of per page values available for the current datatable component.
     *
     * @var int[]
     */
    public array $perPageOptions = [
        10, 20, 50, 100,
    ];

    /**
     * Store the value of search keywords.
     *
     * @var string
     */
    public string $search = '';

    /**
     * Defines which column that the datatable should perform sort operation for.
     *
     * @var string
     */
    public string $sortColumn = 'id';

    /**
     * Defines the sort direction that should be performed.
     *
     * @var string
     */
    public string $sortDirection = 'desc';

    /**
     * Apply data sorting to the given query builder instance.
     *
     * @param Builder $query
     */
    protected function applyDataSorting(Builder $query): void
    {
        $query->orderBy($this->sortColumn, $this->sortDirection);
    }

    /**
     * Apply required query scopes to the given query builder instance.
     *
     * @param Builder $query
     */
    protected function applyQueryScopes(Builder $query): void
    {
        if ($this->search !== '') {
            $query->whereLike($this->searchableColumns(), $this->search);
        }
    }

    /**
     * Specify the datatable's columns and their behaviors.
     *
     * @return array
     */
    abstract public function columns(): array;

    /**
     * Provide the paginated data and make it accessible in views.
     *
     * @return LengthAwarePaginator
     */
    public function getDataProperty(): LengthAwarePaginator
    {
        return $this->data;
    }

    /**
     * Get the pagination visualization in view.
     *
     * @return string
     */
    public function getPaginationProperty(): string
    {
        return $this->data->render('livewire.cms.nav.datatable-paginator');
    }

    /**
     * Go to a specific page in datatable component.
     *
     * @param int $page
     */
    public function goTo(int $page): void
    {
        $this->currentPage = $page;
        $this->refresh();
    }

    /**
     * Get a new query builder instance for the current datatable component.
     * You may include the model's relationships if it's necessary.
     *
     * @return Builder
     */
    abstract protected function newQuery(): Builder;

    /**
     * Refresh the datatable component's data.
     */
    public function refresh(): void
    {
        $query = $this->newQuery();

        $this->applyQueryScopes($query);
        $this->applyDataSorting($query);
        $this->updatePagePosition($query);

        $this->data = $query->paginate($this->perPage, ['*'], 'page', $this->currentPage);

        $this->populateSelectedRowsData();

        app(ComponentDataRepository::class)->save($this);
    }

    /**
     * Specify the searchable column names in the current datatable component.
     *
     * @return array
     */
    abstract protected function searchableColumns(): array;

    /**
     * Update the datatable sorting behavior.
     *
     * @param string $column
     */
    public function sortBy(string $column): void
    {
        if ($this->sortColumn !== $column) {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';

            $this->refresh();

            return;
        }

        $this->sortDirection = ($this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->refresh();
    }

    /**
     * Detect if there are any changes to the base sql command,
     * and update the page position accordingly.
     *
     * @param Builder $query
     */
    protected function updatePagePosition(Builder $query): void
    {
        $sql = $query->toSql();

        if ($sql !== $this->baseSql) {
            $this->currentPage = 1;
            $this->selectAllRows = false;
            $this->selectedRows = [];
        }

        $this->baseSql = $sql;
    }
}
