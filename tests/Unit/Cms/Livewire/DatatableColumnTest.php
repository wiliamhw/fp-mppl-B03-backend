<?php

namespace Tests\Unit\Cms\Livewire;

use App\Models\User;
use Cms\Livewire\DatatableColumn;
use Illuminate\Support\Str;
use Tests\TestCase;

class DatatableColumnTest extends TestCase
{
    /**
     * The datatable column instance.
     *
     * @var DatatableColumn
     */
    protected DatatableColumn $column;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        $this->column = new DatatableColumn('purchase_invoices.customer_information');
    }

    /** @test */
    public function it_can_generate_column_title_automatically()
    {
        $expected = 'Purchase Invoice\'s Customer Information';

        self::assertEquals($expected, $this->column->getTitle());
    }

    /** @test */
    public function the_column_title_is_customizable()
    {
        $expected = 'Custom Column Name';
        $this->column->setTitle($expected);

        self::assertEquals($expected, $this->column->getTitle());
    }

    /** @test */
    public function the_column_can_be_marked_as_not_sortable()
    {
        $this->column->notSortable();

        $actual = $this->getPropertyValue($this->column, 'sortable');

        self::assertFalse($actual);
    }

    /** @test */
    public function the_column_text_alignment_is_adjustable()
    {
        $this->column->align('center');

        $actual = $this->getPropertyValue($this->column, 'align');

        self::assertEquals('center', $actual);
    }

    /** @test */
    public function it_can_render_the_column_header_markups_with_no_active_sorting_indicator()
    {
        $expected = '<th wire:click.prevent="sortBy(\'purchase_invoices.customer_information\')" class="text-left sortable">Purchase Invoice\'s Customer Information</th>';
        $actual = $this->column->renderHeader('another_column', 'asc');

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_the_column_header_markups_with_active_asc_sorting_indicator()
    {
        $expected = '<th wire:click.prevent="sortBy(\'purchase_invoices.customer_information\')" class="text-left sortable sort_asc">Purchase Invoice\'s Customer Information</th>';
        $actual = $this->column->renderHeader('purchase_invoices.customer_information', 'asc');

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_the_column_header_markups_with_active_desc_sorting_indicator()
    {
        $expected = '<th wire:click.prevent="sortBy(\'purchase_invoices.customer_information\')" class="text-left sortable sort_desc">Purchase Invoice\'s Customer Information</th>';
        $actual = $this->column->renderHeader('purchase_invoices.customer_information', 'desc');

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_the_column_header_markups_without_sorting_feature()
    {
        $this->column->notSortable();

        $expected = '<th  class="text-left">Purchase Invoice\'s Customer Information</th>';
        $actual = $this->column->renderHeader('id', 'desc');

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_the_column_header_with_a_fixed_column_width()
    {
        $this->column->notSortable()
            ->setWidth('240px');

        $expected = '<th  class="text-left" style="width: 240px;">Purchase Invoice\'s Customer Information</th>';
        $actual = $this->column->renderHeader('id', 'desc');

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_will_not_render_an_invisible_column_header()
    {
        $this->column->setInvisible(true);

        $expected = '';
        $actual = $this->column->renderHeader('purchase_invoices.customer_information', 'desc');

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_raises_exception_while_passing_non_model_instance_when_rendering_the_cell()
    {
        $this->expectException(\TypeError::class);
        $this->column = DatatableColumn::make('name');

        $model = collect(['name' => 'Richan Fongdasen']);

        $this->column->renderCell($model);
    }

    /** @test */
    public function it_can_render_the_column_markups_with_default_render_method()
    {
        $this->column = DatatableColumn::make('name');

        $model = new User(['name' => 'Richan Fongdasen']);

        $expected = '<td class="text-left">Richan Fongdasen</td>';
        $actual = $this->column->renderCell($model);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_the_column_markups_with_custom_render_method()
    {
        $this->column = DatatableColumn::make('name')
            ->renderWith(static function (User $model) {
                return Str::slug($model->name);
            });

        $model = new User(['name' => 'Richan Fongdasen']);

        $expected = '<td class="text-left">richan-fongdasen</td>';
        $actual = $this->column->renderCell($model);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_the_column_markups_with_fixed_width()
    {
        $this->column = DatatableColumn::make('name')->setWidth('240px');

        $model = new User(['name' => 'Richan Fongdasen']);

        $expected = '<td class="text-left" style="width: 240px;">Richan Fongdasen</td>';
        $actual = $this->column->renderCell($model);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_will_not_render_an_invisible_cell()
    {
        $this->column = DatatableColumn::make('name')
            ->setInvisible(true);

        $model = new User(['name' => 'Richan Fongdasen']);

        $expected = '';
        $actual = $this->column->renderCell($model);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_set_the_column_invisibility_status()
    {
        self::assertFalse($this->column->isInvisible());

        $this->column->setInvisible(true);

        self::assertTrue($this->column->isInvisible());
    }

    /** @test */
    public function it_can_render_visibility_option_markup_for_visible_column()
    {
        $expected = '<div wire:click="toggleVisibility(1)" class="dt-column-visibility-options col-md-6 col-sm-12 pb-2 pt-2"><i class="icon-nm fa fa-check-circle"></i> Purchase Invoice\'s Customer Information</div>';
        $actual = $this->column->renderVisibilityOption(1);

        self::assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_visibility_option_markup_for_invisible_column()
    {
        $this->column->setInvisible(true);

        $expected = '<div wire:click="toggleVisibility(1)" class="dt-column-visibility-options col-md-6 col-sm-12 pb-2 pt-2"><i class="icon-nm fa fa-times-circle"></i> Purchase Invoice\'s Customer Information</div>';
        $actual = $this->column->renderVisibilityOption(1);

        self::assertEquals($expected, $actual);
    }
}
