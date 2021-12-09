<?php

namespace Tests\Livewire\Cms\UserWebinars;

use App\Models\Admin;
use App\Models\UserWebinar;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class EditUserWebinarTest extends TestCase
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

        $this->userWebinar = UserWebinar::factory()->create();
    }

    /** @test */
    public function edit_component_is_accessible()
    {
        Livewire::test('cms.user-webinars.edit-user-webinar', ['userWebinar' => $this->userWebinar])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_the_existing_user_webinar_record()
    {
        $data = $this->fakeRawData(UserWebinar::class);

        Livewire::test('cms.user-webinars.edit-user-webinar', ['userWebinar' => $this->userWebinar])
            ->set('userWebinar.user_id', $data['user_id'])
            ->set('userWebinar.webinar_id', $data['webinar_id'])
            ->set('userWebinar.payment_status', $data['payment_status'])
            ->set('userWebinar.payment_method', $data['payment_method'])
            ->set('userWebinar.payment_token', $data['payment_token'])
            ->set('userWebinar.feedback', $data['feedback'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/user_webinars');

        $this->assertDatabaseHas('user_webinars', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The user webinar has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_user_webinar_and_go_back_to_index_page()
    {
        $data = $this->fakeRawData(UserWebinar::class);

        Livewire::test('cms.user-webinars.edit-user-webinar', ['userWebinar' => $this->userWebinar])
            ->set('userWebinar.user_id', $data['user_id'])
            ->set('userWebinar.webinar_id', $data['webinar_id'])
            ->set('userWebinar.payment_status', $data['payment_status'])
            ->set('userWebinar.payment_method', $data['payment_method'])
            ->set('userWebinar.payment_token', $data['payment_token'])
            ->set('userWebinar.feedback', $data['feedback'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/user_webinars');

        $this->assertDatabaseMissing('user_webinars', $data);
    }
}
