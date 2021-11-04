<?php

namespace App\Http\Livewire\Cms\Admins;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowAdminTest extends TestCase
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
     * The Admin instance to support any test cases.
     *
     * @var Admin
     */
    protected Admin $testAdmin;

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

        $this->testAdmin = Admin::factory()->create();
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.admins.show-admin', ['admin' => $this->testAdmin])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_admin_page()
    {
        Livewire::test('cms.admins.show-admin', ['admin' => $this->testAdmin])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/admins/'.$this->testAdmin->getKey().'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.admins.show-admin', ['admin' => $this->testAdmin])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/admins');
    }
}
