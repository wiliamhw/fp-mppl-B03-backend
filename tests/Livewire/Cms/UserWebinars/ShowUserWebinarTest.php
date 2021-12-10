<?php

namespace App\Http\Livewire\Cms\UserWebinars;

use App\Models\Admin;
use App\Models\UserWebinar;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowUserWebinarTest extends TestCase
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
     * The User Webinar instance to support any test cases.
     *
     * @var UserWebinar
     */
    protected UserWebinar $userWebinar;

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

        UserWebinar::factory()->create();
        $this->userWebinar = UserWebinar::first();
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.user-webinars.show-user-webinar', ['userWebinar' => $this->userWebinar])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_user_webinar_page()
    {
        Livewire::test('cms.user-webinars.show-user-webinar', ['userWebinar' => $this->userWebinar])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/user_webinars/'. $this->userWebinar->id .'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.user-webinars.show-user-webinar', ['userWebinar' => $this->userWebinar])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/user_webinars');
    }
}
