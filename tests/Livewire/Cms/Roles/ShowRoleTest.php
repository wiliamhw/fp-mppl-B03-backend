<?php

namespace App\Http\Livewire\Cms\Roles;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowRoleTest extends TestCase
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
    public function show_component_is_accessible()
    {
        Livewire::test('cms.roles.show-role', ['role' => $this->role])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_role_page()
    {
        Livewire::test('cms.roles.show-role', ['role' => $this->role])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/roles/'.$this->role->getKey().'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.roles.show-role', ['role' => $this->role])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/roles');
    }
}
