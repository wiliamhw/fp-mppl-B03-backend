<?php

namespace Cms\Livewire\Concerns;

use App\Models\Admin;
use Cms\Jobs\ExportDatatable;
use Cms\Jobs\NotifyAdminOfCompletedExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait ExportDataToFile
{
    /**
     * Defines the allowed export file types.
     *
     * @var string[]
     */
    protected static array $allowedExportTypes = [
        'Xlsx',
        'Csv',
    ];

    /**
     * Apply data sorting to the given query builder instance.
     *
     * @param Builder $query
     */
    abstract protected function applyDataSorting(Builder $query): void;

    /**
     * Apply required query scopes to the given query builder instance.
     *
     * @param Builder $query
     */
    abstract protected function applyQueryScopes(Builder $query): void;

    /**
     * Export the data into a new file with the given file format.
     *
     * @param string $type
     *
     * @throws \ErrorException
     */
    public function export(string $type): void
    {
        $this->refresh();

        if (!in_array($type, self::$allowedExportTypes, true)) {
            session()->flash('alertType', 'danger');
            session()->flash('alertMessage', 'Invalid export file type.');

            return;
        }

        $filename = $this->getExportFilename($type);
        $admin = $this->getCurrentAdminProperty();

        (new ExportDatatable($this))
            ->queue($filename, config('cms.datatable_export_disk'), $type, ['visibility' => 'public'])
            ->chain([
                new NotifyAdminOfCompletedExport($admin, $filename),
            ]);

        session()->flash('alertType', 'success');
        session()->flash('alertMessage', 'Data export is in progress, we will send you an email once the data export has been completed.');
    }

    /**
     * Retrieve the current livewire component's unique id.
     *
     * @return string
     */
    abstract public function getComponentId(): string;

    /**
     * Get the export file name to store the exported data.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getExportFilename(string $type): string
    {
        $modelName = class_basename(get_class($this->newQuery()->getModel()));
        $hash = $this->getComponentId();

        return 'exported_datatable/'.Str::slug($modelName).'_'.$hash.'.'.strtolower($type);
    }

    /**
     * Get the export heading row.
     *
     * @return array
     */
    public function getExportHeadings(): array
    {
        $data = $this->getExportQuery()->first();

        if ($data === null) {
            return [];
        }

        return $this->resolveHeading($data->toArray());
    }

    /**
     * Get the currently logged in admin instance.
     *
     * @throws \ErrorException
     *
     * @return Admin
     */
    abstract public function getCurrentAdminProperty(): Admin;

    /**
     * Get eloquent query builder for data export processing.
     *
     * @return Builder
     */
    public function getExportQuery(): Builder
    {
        $query = $this->newQuery();

        $this->applyQueryScopes($query);
        $this->applyDataSorting($query);

        return $query;
    }

    /**
     * Get a new query builder instance for the current datatable component.
     * You may include the model's relationships if it's necessary.
     *
     * @return Builder
     */
    abstract protected function newQuery(): Builder;

    /**
     * Resolve the heading row values.
     *
     * @param array      $data
     * @param array|null $headings
     * @param string     $prefix
     *
     * @return array
     */
    protected function resolveHeading(array $data, array $headings = null, string $prefix = ''): array
    {
        if ($headings === null) {
            $headings = [];
        }

        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                continue;
            }

            if (is_array($value)) {
                $prefix = Str::title(str_replace('_', ' ', Str::singular($key))).'\'s ';
                $headings = $this->resolveHeading($value, $headings, $prefix);

                continue;
            }

            $headings[] = $prefix.Str::title(str_replace('_', ' ', $key));
        }

        return $headings;
    }
}
