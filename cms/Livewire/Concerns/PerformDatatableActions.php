<?php

namespace Cms\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait PerformDatatableActions
{
    /**
     * Delete a specific record identified by the given key.
     *
     * @throws \Exception
     */
    public function delete(string $key): void
    {
        $row = $this->newQuery()->find($key);

        if ($row instanceof Model) {
            $row->delete();
        }

        $this->selectAllRows = false;
        $this->selectedRows = [];

        $this->refresh();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', 'The record ('.$key.') have been deleted.');
    }

    /**
     * Delete the selected rows in datatable.
     */
    public function deleteSelected(): void
    {
        $keys = $this->getSelectedRows();
        $rows = $this->newQuery()->findMany($keys);

        $rows->each(function ($item) {
            $item->delete();
        });

        $this->selectAllRows = false;
        $this->selectedRows = [];

        $this->refresh();

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', 'The selected records ('.implode(', ', $keys).') have been deleted.');
    }

    /**
     * Defines the base route name for current datatable component.
     *
     * @return string
     */
    abstract public function getBaseRouteName(): string;

    /**
     * Get the route key name for the current datatable component.
     *
     * @return string
     */
    protected function getRouteKeyName(): string
    {
        return Str::camel(class_basename(get_class($this->newQuery()->getModel())));
    }

    /**
     * Get the keys of the selected rows.
     *
     * @return array
     */
    abstract public function getSelectedRows(): array;

    /**
     * Get a new query builder instance for the current datatable component.
     * You may include the model's relationships if it's necessary.
     *
     * @return Builder
     */
    abstract protected function newQuery(): Builder;

    /**
     * Perform a specific action for the given record key.
     *
     * @param string      $action
     * @param string|null $key
     *
     * @return mixed
     */
    public function performAction(string $action, string $key = null)
    {
        return redirect()->to(
            route($this->getBaseRouteName().$action, [$this->getRouteKeyName() => $key])
        );
    }
}
