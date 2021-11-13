<?php

namespace App\Http\Livewire\Cms\Users;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowUserTest extends TestCase
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
     * The User instance to support any test cases.
     *
     * @var User
     */
    protected User $user;

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

        $this->user = User::factory()->create();
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.users.show-user', ['user' => $this->user])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_user_page()
    {
        Livewire::test('cms.users.show-user', ['user' => $this->user])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/users/'. $this->user->getKey() .'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.users.show-user', ['user' => $this->user])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/users');
    }
}
