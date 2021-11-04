<?php

namespace Tests\Unit\Cms\Livewire\Concerns;

use App\Http\Livewire\Cms\Settings\SettingsIndex;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProcessingPaginatedDataTest extends TestCase
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
        $this->datatable->perPage = 1;
    }

    /** @test */
    public function it_can_sort_the_data_by_the_given_column_name()
    {
        $this->datatable->sortBy('key');

        self::assertEquals('key', $this->datatable->sortColumn);
        self::assertEquals('asc', $this->datatable->sortDirection);

        $this->datatable->sortBy('key');

        self::assertEquals('key', $this->datatable->sortColumn);
        self::assertEquals('desc', $this->datatable->sortDirection);
    }

    /** @test */
    public function it_can_jump_to_a_specific_page()
    {
        $this->datatable->refresh();
        $this->datatable->goTo(3);

        self::assertEquals(3, $this->datatable->currentPage);
    }

    /** @test */
    public function it_can_generate_pagination_dom_elements()
    {
        $this->datatable->refresh();

        $actual = $this->datatable->getPaginationProperty();

        self::assertTrue(strlen($actual) > 20);
    }

    /** @test */
    public function it_can_perform_full_text_search()
    {
        $setting = Setting::factory()->create([
            'key' => 'youtube-url',
        ]);

        $this->datatable->refresh();
        $this->datatable->search = 'youtube';
        $this->datatable->refresh();

        $data = $this->datatable->getDataProperty()->items()[0];

        self::assertEquals($setting->getKey(), $data->getKey());
        self::assertEquals($setting->key, $data->key);
        self::assertEquals($setting->value, $data->value);
    }
}
