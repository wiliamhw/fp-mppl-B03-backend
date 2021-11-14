<?php

namespace Tests\Livewire\Cms\Webinars;

use App\Models\Admin;
use App\Models\Webinar;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreateWebinarTest extends TestCase
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
        Livewire::test('cms.webinars.create-webinar')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_save_the_new_webinar_record()
    {
        $data = $this->fakeRawData(Webinar::class);

        Livewire::test('cms.webinars.create-webinar')
            ->set('webinar.category_id', $data['category_id'])
            ->set('webinar.title', $data['title'])
            ->set('webinar.description', $data['description'])
            ->set('webinar.start_at', $data['start_at'])
            ->set('webinar.end_at', $data['end_at'])
            ->set('webinar.price', $data['price'])
            ->set('webinar.type', $data['type'])
            ->set('webinar.zoom_id', $data['zoom_id'])
            ->set('webinar.max_participants', $data['max_participants'])
            ->set('webinar.published_at', $data['published_at'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/webinars');

        $this->assertDatabaseHas('webinars', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new webinar has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_webinar_and_go_back_to_index_page()
    {
        $data = $this->fakeRawData(Webinar::class);

        Livewire::test('cms.webinars.create-webinar')
            ->set('webinar.category_id', $data['category_id'])
            ->set('webinar.title', $data['title'])
            ->set('webinar.description', $data['description'])
            ->set('webinar.start_at', $data['start_at'])
            ->set('webinar.end_at', $data['end_at'])
            ->set('webinar.price', $data['price'])
            ->set('webinar.type', $data['type'])
            ->set('webinar.zoom_id', $data['zoom_id'])
            ->set('webinar.max_participants', $data['max_participants'])
            ->set('webinar.published_at', $data['published_at'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/webinars');

        $this->assertDatabaseMissing('webinars', $data);
    }
}
