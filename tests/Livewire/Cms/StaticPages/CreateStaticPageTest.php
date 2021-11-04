<?php

namespace Tests\Livewire\Cms\StaticPages;

use App\Models\Admin;
use App\Models\StaticPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreateStaticPageTest extends TestCase
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
    }

    /** @test */
    public function create_component_is_accessible()
    {
        Livewire::test('cms.static-pages.create-static-page')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_save_the_new_static_page_record()
    {
        $data = StaticPage::factory()->raw();
        $seoMeta = $this->fakeAttachedSeoMetaData(StaticPage::class);
        $seoMeta['en']['seo_url'] = '/'.Str::slug($data['name']);
        $seoMeta['en']['seo_content'] = strip_tags($data['content']);

        Livewire::test('cms.static-pages.create-static-page')
            ->set('staticPage.name', $data['name'])
            ->set('staticPage.content', $data['content'])
            ->set('staticPage.youtube_video', $data['youtube_video'])
            ->set('staticPage.layout', $data['layout'])
            ->set('staticPage.published', $data['published'])
            ->set('seoMeta.seo_title.en', $seoMeta['en']['seo_title'])
            ->set('seoMeta.seo_description.en', $seoMeta['en']['seo_description'])
            ->set('seoMeta.open_graph_type.en', $seoMeta['en']['open_graph_type'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/static_pages');

        $this->assertDatabaseHas('static_pages', $data);
        $this->assertDatabaseHas('seo_metas', $seoMeta['en']);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new static page has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_static_page_and_go_back_to_index_page()
    {
        $data = StaticPage::factory()->raw();
        $seoMeta = $this->fakeAttachedSeoMetaData(StaticPage::class);
        $seoMeta['en']['seo_url'] = '/'.Str::slug($data['name']);
        $seoMeta['en']['seo_content'] = strip_tags($data['content']);

        Livewire::test('cms.static-pages.create-static-page')
            ->set('staticPage.name', $data['name'])
            ->set('staticPage.content', $data['content'])
            ->set('staticPage.youtube_video', $data['youtube_video'])
            ->set('staticPage.layout', $data['layout'])
            ->set('staticPage.published', $data['published'])
            ->set('seoMeta.seo_title.en', $seoMeta['en']['seo_title'])
            ->set('seoMeta.seo_description.en', $seoMeta['en']['seo_description'])
            ->set('seoMeta.open_graph_type.en', $seoMeta['en']['open_graph_type'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/static_pages');

        $this->assertDatabaseMissing('static_pages', $data);
        $this->assertDatabaseMissing('seo_metas', $seoMeta['en']);
    }
}
