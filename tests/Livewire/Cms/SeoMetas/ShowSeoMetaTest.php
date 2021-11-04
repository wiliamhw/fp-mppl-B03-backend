<?php

namespace App\Http\Livewire\Cms\SeoMetas;

use App\Models\Admin;
use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowSeoMetaTest extends TestCase
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
     * The Seo Meta instance to support any test cases.
     *
     * @var SeoMeta
     */
    protected SeoMeta $seoMeta;

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

        $this->seoMeta = SeoMeta::factory()->create();
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.seo-metas.show-seo-meta', ['seoMeta' => $this->seoMeta])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_seo_meta_page()
    {
        Livewire::test('cms.seo-metas.show-seo-meta', ['seoMeta' => $this->seoMeta])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/seo_metas/'.$this->seoMeta->getKey().'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.seo-metas.show-seo-meta', ['seoMeta' => $this->seoMeta])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/seo_metas');
    }
}
