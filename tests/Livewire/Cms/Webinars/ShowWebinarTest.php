<?php

namespace App\Http\Livewire\Cms\Webinars;

use App\Models\Admin;
use App\Models\Webinar;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowWebinarTest extends TestCase
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
    public function show_component_is_accessible()
    {
        Livewire::test('cms.webinars.show-webinar', ['webinar' => $this->webinar])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_webinar_page()
    {
        Livewire::test('cms.webinars.show-webinar', ['webinar' => $this->webinar])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/webinars/'. $this->webinar->getKey() .'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.webinars.show-webinar', ['webinar' => $this->webinar])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/webinars');
    }
}
