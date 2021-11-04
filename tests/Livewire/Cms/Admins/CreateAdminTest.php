<?php

namespace Tests\Livewire\Cms\Admins;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreateAdminTest extends TestCase
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
        Livewire::test('cms.admins.create-admin')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_save_the_new_admin_record()
    {
        $password = 'P@ssw0rd!';
        $data = Admin::factory()->raw([
            'password' => $password,
        ]);

        Livewire::test('cms.admins.create-admin')
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
        self::assertEquals('The new admin has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_admin_and_go_back_to_index_page()
    {
        $data = Admin::factory()->raw();

        Livewire::test('cms.admins.create-admin')
            ->set('data.name', $data['name'])
            ->set('data.email', $data['email'])
            ->set('data.password', $data['password'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/admins');

        $this->assertDatabaseMissing('admins', $data);
    }
}
