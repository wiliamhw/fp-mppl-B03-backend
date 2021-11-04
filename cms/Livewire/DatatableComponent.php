<?php

namespace Cms\Livewire;

use Cms\Contracts\ExportableDatatable;
use Cms\Livewire\Concerns\ExportDataToFile;
use Cms\Livewire\Concerns\HasSelectedRows;
use Cms\Livewire\Concerns\ManageColumnVisibility;
use Cms\Livewire\Concerns\PerformDatatableActions;
use Cms\Livewire\Concerns\ProcessingPaginatedData;
use Cms\Livewire\Concerns\ResolveCurrentAdmin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

abstract class DatatableComponent extends Component implements ExportableDatatable
{
    use AuthorizesRequests;
    use ExportDataToFile;
    use HasSelectedRows;
    use ManageColumnVisibility;
    use PerformDatatableActions;
    use ProcessingPaginatedData;
    use ResolveCurrentAdmin;

    /**
     * Handle the `boot` lifecycle event hook.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException|\ErrorException
     */
    public function boot(): void
    {
        $this->confirmAuthorization();

        app(ComponentDataRepository::class)->load($this);

        // Initialize column visibilities
        $this->columns();

        // Initialize datatable's data
        $this->refresh();
    }

    /**
     * Confirm Admin authorization to access the datatable resources.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     */
    protected function confirmAuthorization(): void
    {
        $table = $this->newQuery()->getModel()->getTable();
        $permission = 'cms.'.$table.'.viewAny';

        if (!$this->getCurrentAdminProperty()->can($permission)) {
            throw new AuthorizationException();
        }
    }

    /**
     * Defines the base route name for current datatable component.
     *
     * @return string
     */
    abstract public function getBaseRouteName(): string;

    /**
     * Retrieve the current livewire component's unique id.
     *
     * @return string
     */
    public function getComponentId(): string
    {
        return (string) $this->id;
    }

    /**
     * Handle the `updated` lifecycle event hook.
     */
    public function updated(): void
    {
        $this->currentPage = 1;
        $this->refresh();
    }
}
