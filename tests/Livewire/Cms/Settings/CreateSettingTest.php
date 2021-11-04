<?php

namespace Tests\Livewire\Cms\Settings;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class CreateSettingTest extends TestCase
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
        Livewire::test('cms.settings.create-setting')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_save_the_new_setting_record()
    {
        $data = Setting::factory()->raw();

        Livewire::test('cms.settings.create-setting')
            ->set('setting.key', $data['key'])
            ->set('setting.value', $data['value'])
            ->set('setting.type', $data['type'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/settings');

        $this->assertDatabaseHas('settings', $data);

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The new setting has been saved.', session('alertMessage'));
    }

    /** @test */
    public function it_can_cancel_creating_new_setting_and_go_back_to_index_page()
    {
        $data = Setting::factory()->raw();

        Livewire::test('cms.settings.create-setting')
            ->set('setting.key', $data['key'])
            ->set('setting.value', $data['value'])
            ->set('setting.type', $data['type'])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/settings');

        $this->assertDatabaseMissing('settings', $data);
    }
}
