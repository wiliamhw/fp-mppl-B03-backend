<?php

namespace Tests\Livewire\Cms\Users;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreateUserTest extends TestCase
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
        Livewire::test('cms.users.create-user')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_save_the_new_user_record()
    {
        $data = $this->fakeRawData(User::class);

        Livewire::test('cms.users.create-user')
            ->set('user.email', $data['email'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password'])
            ->set('user.name', $data['name'])
            ->set('user.phone_number', $data['phone_number'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/users');

        unset($data['password'], $data['password_confirmation'], $data['remember_token']);
        $this->assertDatabaseHas('users', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new user has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_user_and_go_back_to_index_page()
    {
        $data = $this->fakeRawData(User::class);

        Livewire::test('cms.users.create-user')
            ->set('user.email', $data['email'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password'])
            ->set('user.name', $data['name'])
            ->set('user.phone_number', $data['phone_number'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/users');

        unset($data['password'], $data['password_confirmation'], $data['remember_token']);
        $this->assertDatabaseMissing('users', $data);
    }
}
