<?php

namespace Cms\Livewire\Concerns;

use Cms\Livewire\DatatableColumn;

trait ManageColumnVisibility
{
    /**
     * Determine which columns are visible / invisible.
     *
     * @var array
     */
    public array $columnVisibility = [];

    /**
     * Set the column visibilities.
     *
     * @param DatatableColumn[] $columns
     *
     * @return DatatableColumn[]
     */
    protected function applyColumnVisibility(array $columns): array
    {
        foreach ($columns as $key => $column) {
            $value = data_get($this->columnVisibility, $key);

            if ($value === null) {
                $this->columnVisibility[$key] = $column->isInvisible();

                continue;
            }

            $column->setInvisible((bool) $value);
        }

        return $columns;
    }

    /**
     * Refresh the datatable component's data.
     */
    abstract public function refresh(): void;

    /**
     * Toggle the column visibility.
     *
     * @param int $columnIndex
     */
    public function toggleVisibility(int $columnIndex): void
    {
        $currentValue = data_get($this->columnVisibility, $columnIndex, false);
        $this->columnVisibility[$columnIndex] = ($currentValue === false);

        $this->refresh();
    }
}
