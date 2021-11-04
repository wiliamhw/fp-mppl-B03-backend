<?php

namespace Tests\Unit\Cms\Livewire\Concerns;

use App\Http\Livewire\Cms\Settings\SettingsIndex;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ManageColumnVisibilityTest extends TestCase
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

        $this->datatable = app(SettingsIndex::class);
    }

    /** @test */
    public function it_can_apply_column_visibility_to_the_given_columns_set()
    {
        $this->datatable->columnVisibility = [
            0 => true,
        ];

        $columns = $this->datatable->columns();

        self::assertTrue($columns[0]->isInvisible());
        self::assertFalse($columns[1]->isInvisible());
        self::assertTrue($columns[2]->isInvisible());
        self::assertTrue($columns[3]->isInvisible());
        self::assertFalse($columns[4]->isInvisible());
    }

    /** @test */
    public function it_can_toggle_visible_column_to_be_invisible()
    {
        $this->datatable->columnVisibility = [
            0 => false,
        ];

        $this->datatable->toggleVisibility(0);

        $columns = $this->datatable->columns();

        self::assertTrue($columns[0]->isInvisible());
    }

    /** @test */
    public function it_can_toggle_invisible_column_to_be_visible()
    {
        $this->datatable->columnVisibility = [
            0 => true,
        ];

        $this->datatable->toggleVisibility(0);

        $columns = $this->datatable->columns();

        self::assertFalse($columns[0]->isInvisible());
    }
}
