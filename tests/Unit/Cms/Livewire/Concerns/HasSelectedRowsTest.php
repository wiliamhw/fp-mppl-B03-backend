<?php

namespace Tests\Unit\Cms\Livewire\Concerns;

use App\Http\Livewire\Cms\Settings\SettingsIndex;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HasSelectedRowsTest extends TestCase
{
    use DatabaseMigrations;

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

        Setting::factory(5)->create();

        $this->datatable = app(SettingsIndex::class);
    }

    /** @test */
    public function it_can_initiate_the_initial_selected_key_data()
    {
        $this->invokeMethod($this->datatable, 'initiateSelectedKeyData', [60]);

        self::assertFalse(data_get($this->datatable->selectedRows, 60, null));
    }

    /** @test */
    public function it_can_retrieve_the_keys_of_all_selected_rows()
    {
        $this->datatable->selectedRows = [
            60          => true,
            59          => false,
            39          => true,
            'some-uuid' => true,
        ];

        $expected = [60, 39, 'some-uuid'];
        $actual = $this->datatable->getSelectedRows();

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_populate_initial_selected_rows_data_for_all_of_visible_rows()
    {
        $this->datatable->refresh();

        $this->datatable->selectedRows = [
            1 => true,
            3 => true,
        ];

        $expected = [
            1 => true,
            3 => true,
            5 => false,
            4 => false,
            2 => false,
        ];

        $this->invokeMethod($this->datatable, 'populateSelectedRowsData', []);

        self::assertEquals($expected, $this->datatable->selectedRows);
    }

    /** @test */
    public function it_can_select_all_available_rows()
    {
        $this->datatable->refresh();

        $expected = [
            5 => true,
            4 => true,
            3 => true,
            2 => true,
            1 => true,
        ];

        $this->datatable->selectAllRows = true;
        $this->datatable->toggleSelectAllRows();

        self::assertEquals($expected, $this->datatable->selectedRows);
    }

    /** @test */
    public function it_can_deselect_all_available_rows()
    {
        $this->datatable->refresh();

        $this->datatable->selectedRows = [
            5 => true,
            4 => true,
            3 => true,
            2 => true,
            1 => true,
        ];

        $expected = [
            5 => false,
            4 => false,
            3 => false,
            2 => false,
            1 => false,
        ];

        $this->datatable->selectAllRows = false;
        $this->datatable->toggleSelectAllRows();

        self::assertEquals($expected, $this->datatable->selectedRows);
    }
}
