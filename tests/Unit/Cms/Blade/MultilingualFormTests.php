<?php

namespace Tests\Unit\Cms\Blade;

use Cms\Blade\MultilingualForm;
use Illuminate\Support\Str;
use Tests\TestCase;

class MultilingualFormTests extends TestCase
{
    /**
     * The multilingual form name.
     *
     * @var string
     */
    protected string $formName;

    /**
     * The MultilingualForm Object.
     *
     * @var MultilingualForm
     */
    protected MultilingualForm $multilingualForm;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $randomName = Str::random(8);
        $this->formName = Str::slug($randomName);
        $this->multilingualForm = new MultilingualForm($randomName);
    }

    /** @test */
    public function it_can_return_the_form_name_correctly()
    {
        $this->assertEquals($this->formName, $this->multilingualForm->getName());
    }

    /** @test */
    public function it_can_generate_tab_navigation_item_content()
    {
        $expected = '<a class="nav-link <?php echo ($_first) ? \'active\' : \'\'; ?>" data-toggle="tab" href="#'.$this->multilingualForm->getName().'-<?php echo $_locale->language; ?>"><?php echo $_locale->name; ?></a>';
        $actual = $this->invokeMethod($this->multilingualForm, 'generateNavigationItemContent', []);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_tab_navigation_loop_element()
    {
        $expected = '<?php $_first = true; foreach (\\I18n::getLocale() as $_locale) : ?><li class="nav-item <?php echo ($_first) ? \'active\' : \'\'; ?>"><a class="nav-link <?php echo ($_first) ? \'active\' : \'\'; ?>" data-toggle="tab" href="#'.$this->multilingualForm->getName().'-<?php echo $_locale->language; ?>"><?php echo $_locale->name; ?></a></li><?php $_first = false; endforeach; ?>';
        $actual = $this->invokeMethod($this->multilingualForm, 'generateNavigationLoop', []);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_tab_navigation_elements()
    {
        $expected = '<div class="example-preview mb-7"><ul class="nav nav-tabs" role="tablist"><?php $_first = true; foreach (\\I18n::getLocale() as $_locale) : ?><li class="nav-item <?php echo ($_first) ? \'active\' : \'\'; ?>"><a class="nav-link <?php echo ($_first) ? \'active\' : \'\'; ?>" data-toggle="tab" href="#'.$this->multilingualForm->getName().'-<?php echo $_locale->language; ?>"><?php echo $_locale->name; ?></a></li><?php $_first = false; endforeach; ?></ul><div class="tab-content pt-5">';
        $actual = $this->invokeMethod($this->multilingualForm, 'generateTabNavigation', []);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_generate_multilanguage_tab_opening_html_elements_for_multi_languages()
    {
        $expected = '<div class="example-preview mb-7"><ul class="nav nav-tabs" role="tablist"><?php $_first = true; foreach (\\I18n::getLocale() as $_locale) : ?><li class="nav-item <?php echo ($_first) ? \'active\' : \'\'; ?>"><a class="nav-link <?php echo ($_first) ? \'active\' : \'\'; ?>" data-toggle="tab" href="#'.$this->multilingualForm->getName().'-<?php echo $_locale->language; ?>"><?php echo $_locale->name; ?></a></li><?php $_first = false; endforeach; ?></ul><div class="tab-content pt-5">';
        $expected .= '<?php $_first = true; ?><?php foreach (\\I18n::getLocale() as $_locale) : ?><?php CmsForm::createMultilingualGroup($_locale); ?><div class="tab-pane fade p-2 <?php echo ($_first) ? \'active show\' : \'\'; ?>" id="'.$this->multilingualForm->getName().'-<?php echo $_locale->language; ?>" role="tabpanel">';

        $this->assertEquals($expected, (string) $this->multilingualForm);
    }

    /** @test */
    public function it_can_generate_multilanguage_tab_ending_html_elements()
    {
        $expected = '</div><?php $_first = false; CmsForm::closeMultilingualGroup(); ?><?php endforeach; ?></div></div>';

        $this->assertEquals($expected, $this->multilingualForm->getEndingElements());
    }
}
