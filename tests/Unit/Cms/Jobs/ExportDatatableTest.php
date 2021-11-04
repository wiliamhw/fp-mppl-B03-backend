<?php

namespace Tests\Unit\Cms\Jobs;

use App\Http\Livewire\Cms\Settings\SettingsIndex;
use Cms\Jobs\ExportDatatable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExportDatatableTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * The setting datatable livewire component instance.
     *
     * @var SettingsIndex
     */
    protected SettingsIndex $datatable;

    /**
     * The job instance, which being tested.
     *
     * @var ExportDatatable
     */
    protected ExportDatatable $job;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->datatable = app(SettingsIndex::class);
        $this->job = new ExportDatatable($this->datatable);
    }

    /** @test */
    public function it_can_resolve_export_headings_row_values()
    {
        self::assertEquals($this->datatable->getExportHeadings(), $this->job->headings());
    }

    /** @test */
    public function it_provides_query_builder_instance_to_perform_data_export_operation()
    {
        self::assertEquals($this->datatable->getExportQuery()->toSql(), $this->job->query()->toSql());
    }
}
