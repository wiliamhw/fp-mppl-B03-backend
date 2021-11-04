<?php

namespace Tests\Unit\Cms\Blade;

use Cms\Blade\FormGroup;
use Collective\Html\FormBuilder;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class FormGroupTest extends TestCase
{
    use InteractsWithSession;

    /**
     * Form group object.
     *
     * @var FormGroup
     */
    protected $formGroup;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->formGroup = new FormGroup('data.description.en', [
            'required'  => true,
            'class'     => 'form-control',
            'title'     => 'Custom Title',
            'guideline' => 'Title should be more valuable.',
        ]);
    }

    /** @test */
    public function options_should_contain_only_html_attributes()
    {
        $expected = [
            'required' => true,
            'class'    => 'form-control',
        ];

        $actual = $this->invokeMethod($this->formGroup, 'processOptions', [[
            'required'   => true,
            'class'      => 'form-control',
            'errorNames' => 'test_error',
            'title'      => 'Custom Title',
            'guideline'  => 'Title should be more valuable.',
        ]]);

        $this->assertEquals($expected, $actual->toArray());
    }

    /** @test */
    public function it_can_set_error_names_attribute()
    {
        $expected = ['data.description.en'];
        $this->formGroup->setErrorNames([]);
        $actual = $this->getPropertyValue($this->formGroup, 'errorNames');
        $this->assertEquals($expected, $actual);

        $expected = ['test_errors'];
        $this->formGroup->setErrorNames(['test_errors']);
        $actual = $this->getPropertyValue($this->formGroup, 'errorNames');
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_set_guideline_attribute()
    {
        $expected = 'Title should be more valuable.';
        $this->formGroup->setGuideline($expected);
        $actual = $this->getPropertyValue($this->formGroup, 'guideline');
        $this->assertEquals($expected, $actual);

        $expected = '';
        $this->formGroup->setGuideline($expected);
        $actual = $this->getPropertyValue($this->formGroup, 'guideline');
        $this->assertEquals($expected, $actual);

        $this->expectException(\TypeError::class);
        $this->formGroup->setGuideline(null);
    }

    /** @test */
    public function it_can_set_title_attribute()
    {
        $expected = 'Custom Title';
        $this->formGroup->setTitle($expected);
        $actual = $this->getPropertyValue($this->formGroup, 'title');
        $this->assertEquals($expected, $actual);

        $expected = 'Description';
        $this->formGroup->setTitle('');
        $actual = $this->getPropertyValue($this->formGroup, 'title');
        $this->assertEquals($expected, $actual);

        $this->expectException(\TypeError::class);
        $this->formGroup->setTitle(null);
    }

    /** @test */
    public function it_can_set_input_creator_attribute()
    {
        $this->formGroup->setInputCreator(function () {
            return true;
        });
        $actual = $this->getPropertyValue($this->formGroup, 'inputCreator');
        $this->assertInstanceOf(\Closure::class, $actual);
    }

    /** @test */
    public function it_can_set_default_input_class_attribute()
    {
        $expected = 'form-control mb-2';
        $this->formGroup->setDefaultInputClass($expected);
        $actual = $this->getPropertyValue($this->formGroup, 'defaultInputClass');
        $this->assertEquals($expected, $actual);

        $expected = 'form-control';
        $this->formGroup->setDefaultInputClass('');
        $actual = $this->getPropertyValue($this->formGroup, 'defaultInputClass');
        $this->assertEquals($expected, $actual);

        $this->expectException(\TypeError::class);
        $this->formGroup->setDefaultInputClass(null);
    }

    /** @test */
    public function it_can_set_thumbnail_attribute()
    {
        $expected = 'http://lorempixel.com/400/200/sports/';
        $this->formGroup->setThumbnail($expected);
        $actual = $this->getPropertyValue($this->formGroup, 'thumbnail');
        $this->assertEquals($expected, $actual);

        $expected = '';
        $this->formGroup->setThumbnail($expected);
        $actual = $this->getPropertyValue($this->formGroup, 'thumbnail');
        $this->assertEquals($expected, $actual);

        $this->formGroup->setThumbnail(null);
        $actual = $this->getPropertyValue($this->formGroup, 'thumbnail');
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_retrieve_and_access_form_builder_object()
    {
        $actual = $this->invokeMethod($this->formGroup, 'builder');

        $this->assertNotNull($actual);
        $this->assertIsObject($actual);
        $this->assertInstanceOf(FormBuilder::class, $actual);
    }

    /** @test */
    public function it_can_resolve_default_session_error_names_for_an_input()
    {
        $expected = [
            ['test_input'],
            ['title.en', 'title.*'],
            ['items.*.name', 'items.*.*'],
        ];
        $names = ['test_input', 'title[en]', 'items[][name]'];

        $count = count($names);
        for ($i = 0; $i < $count; $i++) {
            $actual = $this->invokeMethod($this->formGroup, 'resolveErrorNames', [$names[$i]]);
            $this->assertEquals($expected[$i], $actual);
        }
    }

    /** @test */
    public function it_can_resolve_default_label_title_for_an_input()
    {
        $expected = [
            'Test Input',
            'Title',
            'Name',
        ];
        $names = ['data.test_input', 'data.title[en]', 'data.items[][name]'];

        $count = count($names);
        for ($i = 0; $i < $count; $i++) {
            $actual = $this->invokeMethod($this->formGroup, 'resolveTitle', [$names[$i]]);
            $this->assertEquals($expected[$i], $actual);
        }
    }

    /** @test */
    public function it_can_retrieve_error_message_for_a_specific_input()
    {
        $expected = ['Invalid Title'];
        $bag = new MessageBag([
            'title.en' => $expected,
        ]);
        $this->formGroup->setErrorBag((new ViewErrorBag())->put('default', $bag));

        $actual = $this->invokeMethod($this->formGroup, 'getErrorMessages', [['title.en', 'title.*']]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_empty_array_when_retrieving_unavailable_error_message()
    {
        $bag = new MessageBag([
            'title.en' => ['Invalid Title'],
        ]);
        $this->withSession(['errors' => $bag]);

        $actual = $this->invokeMethod($this->formGroup, 'getErrorMessages', [['description.id', 'description.*']]);

        $this->assertEquals([], $actual);
    }

    /** @test */
    public function it_can_render_error_messages_for_any_input()
    {
        $errors = [
            'Invalid field value.',
            'Field value has to be more valuable.',
        ];
        $expected = '<div class="invalid-feedback"><div>Invalid field value.</div><div>Field value has to be more valuable.</div></div>';

        $actual = $this->invokeMethod($this->formGroup, 'renderErrors', [$errors]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_render_guideline_element_for_any_input()
    {
        $guides = ['', 'This field is required.'];
        $expected = ['', '<p>This field is required.</p>'];

        $count = count($guides);
        for ($i = 0; $i < $count; $i++) {
            $this->formGroup->setGuideline($guides[$i]);
            $actual = $this->invokeMethod($this->formGroup, 'renderGuideline');
            $this->assertEquals($expected[$i], $actual);
        }
    }

    /** @test */
    public function it_can_render_thumbnail_element_for_any_input()
    {
        $thumbnails = ['', 'http://lorempixel.com/400/200/sports/'];
        $expected = ['', '<div><img src="http://lorempixel.com/400/200/sports/" alt="Custom Title" class="form-group-thumbnail" /></div>'];

        $count = count($thumbnails);
        for ($i = 0; $i < $count; $i++) {
            $this->formGroup->setThumbnail($thumbnails[$i]);
            $actual = $this->invokeMethod($this->formGroup, 'renderThumbnail');
            $this->assertEquals($expected[$i], $actual);
        }
    }

    /** @test */
    public function it_can_render_label_element_for_any_input()
    {
        $titles = ['', 'My Own Title'];
        $expected = [
            '<label for="data.description.en">Description</label>',
            '<label for="data.description.en">My Own Title</label>',
        ];

        $count = count($titles);
        for ($i = 0; $i < $count; $i++) {
            $this->formGroup->setTitle($titles[$i]);
            $actual = $this->invokeMethod($this->formGroup, 'renderLabel');
            $this->assertEquals($expected[$i], $actual);
        }
    }

    /** @test */
    public function it_can_generate_normal_text_input()
    {
        $value = 'old value';

        $this->formGroup->setInputCreator(
            function (FormBuilder $builder, string $name, Collection $options) use ($value) {
                return $builder->text($name, old($name, $value), $options->toArray());
            }
        );
        $expected = '<div class="form-group"><label for="data.description.en">Custom Title</label><input class="form-control" required wire:model.defer="data.description.en" name="data.description.en" type="text" value="old value" id="data.description.en"><p>Title should be more valuable.</p></div>';
        $actual = $this->formGroup->toHtml();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_text_input_with_errors()
    {
        $errors = ['Invalid description format.'];
        $bag = new MessageBag([
            'data.description.en' => $errors,
        ]);
        $this->formGroup->setErrorBag((new ViewErrorBag())->put('default', $bag));

        $this->formGroup->setInputCreator(
            function (FormBuilder $builder, string $name, Collection $options) {
                return $builder->text($name, null, $options->toArray());
            }
        );
        $expected = '<div class="form-group"><label for="data.description.en">Custom Title</label><input class="form-control is-invalid" required wire:model.defer="data.description.en" name="data.description.en" type="text" id="data.description.en"><div class="invalid-feedback"><div>Invalid description format.</div></div><p>Title should be more valuable.</p></div>';
        $actual = $this->formGroup->toHtml();

        $this->assertEquals($expected, $actual);
    }
}
