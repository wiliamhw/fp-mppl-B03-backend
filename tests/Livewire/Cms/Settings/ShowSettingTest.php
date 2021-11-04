<?php

namespace App\Http\Livewire\Cms\Settings;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\CmsTests;
use Tests\TestCase;

class ShowSettingTest extends TestCase
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
     * The Setting instance to support any test cases.
     *
     * @var Setting
     */
    protected Setting $setting;

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

        $this->setting = Setting::factory()->create();
    }

    /** @test */
    public function show_component_is_accessible()
    {
        Livewire::test('cms.settings.show-setting', ['setting' => $this->setting])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_can_guide_admin_to_the_edit_setting_page()
    {
        Livewire::test('cms.settings.show-setting', ['setting' => $this->setting])
            ->call('edit')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/settings/'.$this->setting->getKey().'/edit');
    }

    /** @test */
    public function it_can_go_back_to_index_page()
    {
        Livewire::test('cms.settings.show-setting', ['setting' => $this->setting])
            ->call('backToIndex')
            ->assertHasNoErrors()
            ->assertRedirect('/cms/settings');
    }
}
