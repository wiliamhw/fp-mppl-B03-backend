<?php

namespace Tests\Livewire\Cms\Roles;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreateRoleTest extends TestCase
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
        Livewire::test('cms.roles.create-role')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_save_the_new_role_record()
    {
        $data = Role::factory()->raw();

        Livewire::test('cms.roles.create-role')
            ->set('role.name', $data['name'])
            ->set('role.guard_name', $data['guard_name'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/roles');

        $this->assertDatabaseHas('roles', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new role has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_role_and_go_back_to_index_page()
    {
        $data = Role::factory()->raw();

        Livewire::test('cms.roles.create-role')
            ->set('role.name', $data['name'])
            ->set('role.guard_name', $data['guard_name'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/roles');

        $this->assertDatabaseMissing('roles', $data);
    }
}
