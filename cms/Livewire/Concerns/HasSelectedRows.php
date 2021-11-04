<?php

namespace Cms\Livewire\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

trait HasSelectedRows
{
    /**
     * Determine if the datatable component has all of it's rows get selected.
     *
     * @var bool
     */
    public bool $selectAllRows = false;

    /**
     * Store a list of primary keys for every selected record in the datatable component.
     *
     * @var array
     */
    public array $selectedRows = [];

    /**
     * Provide the paginated data and make it accessible in views.
     *
     * @return LengthAwarePaginator
     */
    abstract public function getDataProperty(): LengthAwarePaginator;

    /**
     * Get the keys of the selected rows.
     *
     * @return array
     */
    public function getSelectedRows(): array
    {
        $selected = [];

        foreach ($this->selectedRows as $key => $value) {
            if ($value === true) {
                $selected[] = $key;
            }
        }

        return $selected;
    }

    /**
     * Populate the initial selected rows data.
     */
    protected function populateSelectedRowsData(): void
    {
        foreach ($this->getDataProperty()->items() as $item) {
            if ($item instanceof Model) {
                $this->initiateSelectedKeyData($item->getKey());
            }
        }
    }

    /**
     * Initiate selected rows data for the given model's key.
     *
     * @param int|string $key
     */
    protected function initiateSelectedKeyData($key): void
    {
        if (data_get($this->selectedRows, $key, null) === null) {
            $this->selectedRows[$key] = false;
        }
    }

    /**
     * Toggle the select all rows checkbox.
     */
    public function toggleSelectAllRows(): void
    {
        foreach ($this->selectedRows as $key => $value) {
            $this->selectedRows[$key] = $this->selectAllRows;

            unset($value);
        }

        $this->refresh();
    }
}
