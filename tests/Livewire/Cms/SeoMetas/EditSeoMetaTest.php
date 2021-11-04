<?php

namespace Tests\Livewire\Cms\SeoMetas;

use App\Models\Admin;
use App\Models\SeoMeta;
use Cms\Testing\FakeTemporaryUpload;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\CmsTests;
use Tests\TestCase;

class EditSeoMetaTest extends TestCase
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
    public function edit_component_is_accessible()
    {
        Livewire::test('cms.seo-metas.edit-seo-meta', ['seoMeta' => $this->seoMeta])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_the_existing_seo_meta_record()
    {
        $data = SeoMeta::factory()->raw();

        Livewire::test('cms.seo-metas.edit-seo-meta', ['seoMeta' => $this->seoMeta])
            ->set('seoMeta.seo_url', $data['seo_url'])
            ->set('seoMeta.seo_title', $data['seo_title'])
            ->set('seoMeta.seo_description', $data['seo_description'])
            ->set('seoMeta.open_graph_type', $data['open_graph_type'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/seo_metas');

        $this->assertDatabaseHas('seo_metas', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The seo meta has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_update_the_existing_seo_meta_record_with_uploaded_media()
    {
        $data = SeoMeta::factory()->raw();
        $uploads = FakeTemporaryUpload::create('image')->getCollection();

        Livewire::test('cms.seo-metas.edit-seo-meta', ['seoMeta' => $this->seoMeta])
            ->set('seoMeta.seo_url', $data['seo_url'])
            ->set('seoMeta.seo_title', $data['seo_title'])
            ->set('seoMeta.seo_description', $data['seo_description'])
            ->set('seoMeta.open_graph_type', $data['open_graph_type'])
            ->set('seoImage', $uploads)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/seo_metas');

        $this->assertDatabaseHas('seo_metas', $data);

        $this->seoMeta->refresh();

        $media = $this->seoMeta->getFirstMedia('seo_image');
        self::assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        Storage::disk(config('media-library.disk_name'))->assertExists($path);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The seo meta has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_seo_meta_and_go_back_to_index_page()
    {
        $data = SeoMeta::factory()->raw();

        Livewire::test('cms.seo-metas.edit-seo-meta', ['seoMeta' => $this->seoMeta])
            ->set('seoMeta.seo_url', $data['seo_url'])
            ->set('seoMeta.seo_title', $data['seo_title'])
            ->set('seoMeta.seo_description', $data['seo_description'])
            ->set('seoMeta.open_graph_type', $data['open_graph_type'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/seo_metas');

        $this->assertDatabaseMissing('seo_metas', $data);
    }
}
