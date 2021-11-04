<?php

namespace Tests\Livewire\Cms\CurrentAdmin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ProfileTest extends TestCase
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
    public function admin_profile_page_is_accessible()
    {
        $this->get($this->getCmsUrl('/current-admin/profile'))
            ->assertStatus(200)
            ->assertSee('noindex, nofollow, noimageindex, noarchive, nosnippet')
            ->assertSeeLivewire('cms.nav.header-mobile')
            ->assertSeeLivewire('cms.nav.side-bar')
            ->assertSeeLivewire('cms.nav.header')
            ->assertSeeLivewire('cms.nav.footer')
            ->assertSeeLivewire('cms.current-admin.profile');
    }

    /** @test */
    public function profile_component_displays_the_admin_name()
    {
        Livewire::test('cms.current-admin.profile')
            ->assertSet('data.name', $this->admin->name);
    }

    /** @test */
    public function update_profile_failed_with_wrong_current_password()
    {
        Livewire::test('cms.current-admin.profile')
            ->set('data.current_password', 'wrong-password')
            ->call('save')
            ->assertHasErrors('data.current_password')
            ->assertSee('Oops, there are some errors');
    }

    /** @test */
    public function update_profile_failed_with_password_confirmation_doesnt_match_to_the_new_password()
    {
        Livewire::test('cms.current-admin.profile')
            ->set('data.password', 'new-password')
            ->set('data.password_confirmation', 'another-password')
            ->call('save')
            ->assertHasErrors('data.password')
            ->assertSee('Oops, there are some errors');
    }

    /** @test */
    public function admin_profile_being_updated_successfully()
    {
        Livewire::test('cms.current-admin.profile')
            ->set('data.name', 'Super Administrator')
            ->set('data.current_password', 'password')
            ->set('data.password', 'new-password')
            ->set('data.password_confirmation', 'new-password')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Your profile has been updated successfully.');

        $this->admin->refresh();

        self::assertEquals('Super Administrator', $this->admin->name);
        self::assertTrue(Hash::check('new-password', $this->admin->password));
    }

    /** @test */
    public function admin_profile_being_updated_successfully_without_updating_password()
    {
        Livewire::test('cms.current-admin.profile')
            ->set('data.name', 'Super Administrator')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Your profile has been updated successfully.');

        $this->admin->refresh();

        self::assertEquals('Super Administrator', $this->admin->name);
        self::assertTrue(Hash::check('password', $this->admin->password));
    }
}
