<?php

namespace Tests\Unit\Cms\Livewire\Concerns;

use App\Http\Livewire\Cms\Settings\SettingsIndex;
use App\Models\Admin;
use App\Models\Setting;
use Cms\Jobs\NotifyAdminOfCompletedExport;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportDataToFileTest extends TestCase
{
    use DatabaseMigrations;
    use InteractsWithSession;

    /**
     * The currently logged in CMS Admin instance.
     *
     * @var Admin
     */
    protected Admin $admin;

    /**
     * The setting datatable livewire component instance.
     *
     * @var SettingsIndex
     */
    protected SettingsIndex $datatable;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin, config('cms.guard'));

        Setting::factory(5)->create();

        $this->datatable = app(SettingsIndex::class);
    }

    /** @test */
    public function it_can_provide_the_query_builder_to_perform_data_export_operation()
    {
        $query = $this->datatable->getExportQuery();

        $expected = 'select * from "settings" where "settings"."deleted_at" is null order by "id" desc';
        $actual = $query->toSql();

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_resolve_export_headings_row_values_automatically()
    {
        $data = [
            'id'             => 1,
            'name'           => 'Taylor Otwell',
            'email'          => 'taylor@laravel.com',
            'phone_number'   => '0218888888',
            'favorite_books' => [
                [
                    'title'  => 'Book 1',
                    'author' => 'Author 1',
                ],
                [
                    'title'  => 'Book 2',
                    'author' => 'Author 2',
                ],
            ],
            'group' => [
                'id'          => 1,
                'name'        => 'Administrator Group',
                'description' => 'Lorem ipsum',
            ],
        ];

        $expected = [
            'Id',
            'Name',
            'Email',
            'Phone Number',
            'Group\'s Id',
            'Group\'s Name',
            'Group\'s Description',
        ];

        $actual = $this->invokeMethod($this->datatable, 'resolveHeading', [$data]);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_provide_and_resolve_the_export_headings_row_values()
    {
        $this->datatable->refresh();

        $expected = [
            'Id',
            'Key',
            'Value',
            'Type',
            'Deleted At',
            'Created At',
            'Updated At',
        ];
        $actual = $this->datatable->getExportHeadings();

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_will_returns_empty_array_when_trying_to_resolve_the_export_headings_row_values_of_zero_datasets()
    {
        $this->datatable->refresh();
        $this->datatable->search = 'qwertyuiop';
        $this->datatable->refresh();

        self::assertEquals([], $this->datatable->getExportHeadings());
    }

    /** @test */
    public function it_can_generate_the_exported_filename_automatically()
    {
        $expected = 'exported_datatable/setting_'.$this->datatable->getComponentId().'.xlsx';
        $actual = $this->invokeMethod($this->datatable, 'getExportFilename', ['Xlsx']);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_export_the_data_into_a_file()
    {
        Excel::fake();

        $filename = 'exported_datatable/setting_'.$this->datatable->getComponentId().'.xlsx';

        $this->datatable->export('Xlsx');

        Excel::assertQueued($filename, config('cms.datatable_export_disk'));
        Excel::assertQueuedWithChain([new NotifyAdminOfCompletedExport($this->admin, $filename)]);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('Data export is in progress, we will send you an email once the data export has been completed.', session('alertMessage'));
    }

    /** @test */
    public function it_will_display_error_messages_on_invalid_export_file_type()
    {
        Excel::fake();

        $this->datatable->export('Pdf');

        self::assertEquals('danger', session('alertType'));
        self::assertEquals('Invalid export file type.', session('alertMessage'));
    }
}
