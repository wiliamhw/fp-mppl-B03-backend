<?php

namespace Tests\Livewire\Cms\Admins;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class EditAdminTest extends TestCase
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
    public function edit_component_is_accessible()
    {
        Livewire::test('cms.admins.edit-admin', ['admin' => $this->testAdmin])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_update_the_existing_admin_record()
    {
        $data = Admin::factory()->raw([
            'password' => 'P@ssw0rd!',
        ]);

        Livewire::test('cms.admins.edit-admin', ['admin' => $this->testAdmin])
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.password', $data['password'])
            ->set('data.password_confirmation', $data['password'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/admins');

        unset($data['password'], $data['password_confirmation'], $data['remember_token']);
        $this->assertDatabaseHas('admins', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The admin has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_update_the_existing_admin_record_without_updating_password()
    {
        $data = Admin::factory()->raw();

        Livewire::test('cms.admins.edit-admin', ['admin' => $this->testAdmin])
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/admins');

        unset($data['password_confirmation'], $data['remember_token']);
        $this->assertDatabaseHas('admins', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The admin has been updated.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_updating_existing_admin_and_go_back_to_index_page()
    {
        $data = Admin::factory()->raw();

        Livewire::test('cms.admins.edit-admin', ['admin' => $this->testAdmin])
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.password', $data['password'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/admins');

        $this->assertDatabaseMissing('admins', $data);
    }
}
