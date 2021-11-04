<?php

namespace Tests\Livewire\Cms\Roles;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class EditRoleTest extends TestCase
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
     * The Role instance to support any test cases.
     *
     * @var Role
     */
    protected Role $role;

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

        $this->role = Role::factory()->create();
    }

    /** @test */
    public function edit_component_is_accessible()
    {
        Livewire::test('cms.roles.edit-role', ['role' => $this->role])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_the_existing_role_record()
    {
        $data = Role::factory()->raw();

        Livewire::test('cms.roles.edit-role', ['role' => $this->role])
            ->set('role.name', $data['name'])
            ->set('role.guard_name', $data['guard_name'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/roles');

        $this->assertDatabaseHas('roles', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The role has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_role_and_go_back_to_index_page()
    {
        $data = Role::factory()->raw();

        Livewire::test('cms.roles.edit-role', ['role' => $this->role])
            ->set('role.name', $data['name'])
            ->set('role.guard_name', $data['guard_name'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/roles');

        $this->assertDatabaseMissing('roles', $data);
    }
}
