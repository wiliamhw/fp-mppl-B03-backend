<?php

namespace Tests\Livewire\Cms\Webinars;

use App\Models\Admin;
use App\Models\Webinar;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class EditWebinarTest extends TestCase
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
     * The Webinar instance to support any test cases.
     *
     * @var Webinar
     */
    protected Webinar $webinar;

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

        $this->webinar = Webinar::factory()->create();
    }

    /** @test */
    public function edit_component_is_accessible()
    {
        Livewire::test('cms.webinars.edit-webinar', ['webinar' => $this->webinar])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_the_existing_webinar_record()
    {
        $data = $this->fakeRawData(Webinar::class, [
            'start_at'      => date('Y-m-d H:i:s'),
            'end_at'        => date('Y-m-d H:i:s'),
        ]);

        Livewire::test('cms.webinars.edit-webinar', ['webinar' => $this->webinar])
            ->set('webinar.category_id', $data['category_id'])
            ->set('webinar.title', $data['title'])
            ->set('webinar.brief_description', $data['brief_description'])
            ->set('webinar.description', $data['description'])
            ->set('webinar.start_at', $data['start_at'])
            ->set('webinar.end_at', $data['end_at'])
            ->set('webinar.price', $data['price'])
            ->set('webinar.zoom_id', $data['zoom_id'])
            ->set('webinar.max_participants', $data['max_participants'])
            ->set('isPublished', 'true')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/webinars');

        unset($data['published_at'], $data['start_at'], $data['end_at']);
        $this->assertDatabaseHas('webinars', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The webinar has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_webinar_and_go_back_to_index_page()
    {
        $data = $this->fakeRawData(Webinar::class, [
            'start_at'      => date('Y-m-d H:i:s'),
            'end_at'        => date('Y-m-d H:i:s'),
        ]);

        Livewire::test('cms.webinars.edit-webinar', ['webinar' => $this->webinar])
            ->set('webinar.category_id', $data['category_id'])
            ->set('webinar.title', $data['title'])
            ->set('webinar.brief_description', $data['brief_description'])
            ->set('webinar.description', $data['description'])
            ->set('webinar.start_at', $data['start_at'])
            ->set('webinar.end_at', $data['end_at'])
            ->set('webinar.price', $data['price'])
            ->set('webinar.zoom_id', $data['zoom_id'])
            ->set('webinar.max_participants', $data['max_participants'])
            ->set('isPublished', 'true')
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/webinars');

        unset($data['published_at'], $data['start_at'], $data['end_at']);
        $this->assertDatabaseMissing('webinars', $data);
    }
}
