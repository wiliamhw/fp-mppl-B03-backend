<?php

namespace App\Http\Livewire\Cms\StaticPages;

use App\Models\Admin;
use App\Models\SeoMeta;
use App\Models\StaticPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowStaticPageTest extends TestCase
{
    use CmsTests;
    use DatabaseMigrations;

    /**
     * Cms Admin Object.
     *
     * @var \App\Models\Admin
     */
    protected Admin $admin;

    /**
     * The fake SEO Meta data, which being used in these test cases.
     *
     * @var array
     */
    protected array $seoMeta = [];

    /**
     * The Static Page instance to support any test cases.
     *
     * @var StaticPage
     */
    protected StaticPage $staticPage;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->admin, config('cms.guard'));

        $this->staticPage = StaticPage::factory()->create();

        // Create fake seo meta for the post
        $this->seoMeta = $this->fakeAttachedSeoMetaData(StaticPage::class);
        SeoMeta::factory()->create($this->seoMeta['en']);
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.static-pages.show-static-page', ['staticPage' => $this->staticPage])
            ->assertSet('seoMeta.seo_title.en', $this->seoMeta['en']['seo_title'])
            ->assertSet('seoMeta.seo_description.en', $this->seoMeta['en']['seo_description'])
            ->assertSet('seoMeta.open_graph_type.en', $this->seoMeta['en']['open_graph_type'])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_static_page_page()
    {
        Livewire::test('cms.static-pages.show-static-page', ['staticPage' => $this->staticPage])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/static_pages/'.$this->staticPage->getKey().'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.static-pages.show-static-page', ['staticPage' => $this->staticPage])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/static_pages');
    }
}
