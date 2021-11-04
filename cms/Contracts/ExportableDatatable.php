<?php

namespace Cms\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ExportableDatatable
{
    /**
     * Get eloquent query builder for data export processing.
     *
     * @return Builder
     */
    public function getExportQuery(): Builder;

    /**
     * Get the export heading row.
     *
     * @return array
     */
    public function getExportHeadings(): array;
}
