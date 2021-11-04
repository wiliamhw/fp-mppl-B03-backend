<?php

namespace Tests\Unit\Cms\Blade;

use Cms\Blade\FormBuilder;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use RichanFongdasen\I18n\Locale;
use Tests\TestCase;

class FormBuilderTest extends TestCase
{
    use InteractsWithSession;

    /**
     * CMS Form Builders Object.
     *
     * @var FormBuilder
     */
    protected $builder;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->builder = app(FormBuilder::class);
    }

    /** @test */
    public function it_can_call_form_builder_method_dynamically()
    {
        $expected = '<form>';
        $formBuilder = \Mockery::mock(\Collective\Html\FormBuilder::class);
        $formBuilder->shouldReceive('open')
            ->times(1)
            ->andReturns($expected);

        $builder = new FormBuilder($formBuilder);

        $this->assertEquals($expected, $builder->open());
    }

    /** @test */
    public function it_raises_exception_when_dynamically_calling_an_unknown_method()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->builder->unknownMethod();
    }

    /** @test */
    public function it_can_generate_text_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="text" id="test_input"></div>';
        $actual = $this->builder->text('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_password_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="password" value="" id="test_input"></div>';
        $actual = $this->builder->password('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_range_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="range" id="test_input"></div>';
        $actual = $this->builder->range('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_email_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="email" id="test_input"></div>';
        $actual = $this->builder->email('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_tel_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="tel" id="test_input"></div>';
        $actual = $this->builder->tel('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_number_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="number" id="test_input"></div>';
        $actual = $this->builder->number('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_date_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="date" id="test_input"></div>';
        $actual = $this->builder->date('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_time_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="time" id="test_input"></div>';
        $actual = $this->builder->time('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_datetime_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="datetime" id="test_input"></div>';
        $actual = $this->builder->datetime('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_url_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="url" id="test_input"></div>';
        $actual = $this->builder->url('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_color_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="color" id="test_input"></div>';
        $actual = $this->builder->color('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_textarea_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><textarea class="form-control" required wire:model.defer="test_input" name="test_input" cols="50" rows="10" id="test_input"></textarea></div>';
        $actual = $this->builder->textarea('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_file_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><input class="form-control" required wire:model.defer="test_input" name="test_input" type="file" id="test_input"></div>';
        $actual = $this->builder->file('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_select_input()
    {
        $list = [
            '1' => 'One',
            '2' => 'Two',
        ];
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><select class="form-control" required wire:model.defer="test_input" id="test_input" name="test_input"><option value="1">One</option><option value="2">Two</option></select></div>';
        $actual = $this->builder->select('test_input', $list);

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_select_range_input()
    {
        $expected = '<div class="form-group"><label for="test_input">Test Input</label><select class="form-control" required wire:model.defer="test_input" id="test_input" name="test_input"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></div>';
        $actual = $this->builder->selectRange('test_input', 1, 3);

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_create_multilingual_form_group()
    {
        $locale = new Locale('English', 'en', 'US');

        $this->builder->createMultilingualGroup($locale);

        $actual = $this->getPropertyValue($this->builder, 'currentLocale');

        $this->assertEquals('English', $actual->name);
    }

    /** @test */
    public function it_can_close_the_currently_opened_multilingual_form_group()
    {
        $locale = new Locale('English', 'en', 'US');

        $this->builder->createMultilingualGroup($locale);
        $this->builder->closeMultilingualGroup();

        $actual = $this->getPropertyValue($this->builder, 'currentLocale');

        $this->assertNull($actual);
    }

    /** @test */
    public function it_can_generate_multilingual_text_input()
    {
        $locale = new Locale('English', 'en', 'US');
        $this->builder->createMultilingualGroup($locale);

        $expected = '<div class="form-group"><label for="translations.test_input.en">Test Input</label><input class="form-control" required id="translations_test_input_en" wire:model.defer="translations.test_input.en" name="translations.test_input.en" type="text"></div>';
        $actual = $this->builder->text('test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }

    /** @test */
    public function it_can_generate_multilingual_text_input_with_non_standard_array_store()
    {
        $locale = new Locale('English', 'en', 'US');
        $this->builder->createMultilingualGroup($locale);

        $expected = '<div class="form-group"><label for="store.test_input.en">Test Input</label><input class="form-control" required id="store_test_input_en" wire:model.defer="store.test_input.en" name="store.test_input.en" type="text"></div>';
        $actual = $this->builder->text('store.test_input');

        $this->assertEquals($expected, $actual->toHtml());
    }
}
