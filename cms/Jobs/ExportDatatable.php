<?php

namespace Cms\Jobs;

use Cms\Contracts\ExportableDatatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportDatatable implements FromQuery, ShouldQueue, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * Base eloquent query builder which will be used to export the data.
     *
     * @var ExportableDatatable
     */
    protected ExportableDatatable $datatable;

    /**
     * Create a new job instance.
     *
     * @param ExportableDatatable $datatable
     */
    public function __construct(ExportableDatatable $datatable)
    {
        $this->datatable = $datatable;
    }

    /**
     * Provide the exported data heading row's value.
     *
     * @return array
     */
    public function headings(): array
    {
        return $this->datatable->getExportHeadings();
    }

    /**
     * Provide data mapping for export file.
     *
     * @var mixed
     */
    public function map($row): array
    {
        return ($row instanceof Model) ? Arr::dot($row->toArray()) : [];
    }

    /**
     * Provide the query builder for export process.
     *
     * @return Builder
     */
    public function query()
    {
        return $this->datatable->getExportQuery();
    }
}
