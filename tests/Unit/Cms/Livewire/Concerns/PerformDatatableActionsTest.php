<?php

namespace Tests\Unit\Cms\Livewire\Concerns;

use App\Http\Livewire\Cms\Settings\SettingsIndex;
use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\TestCase;

class PerformDatatableActionsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Cms Admin Object.
     *
     * @var \App\Models\Admin
     */
    protected Admin $admin;

    /**
     * The setting datatable livewire component instance.
     *
     * @var SettingsIndex
     */
    protected SettingsIndex $datatable;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->session([]);

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->admin, config('cms.guard'));

        Setting::factory(5)->create();

        $this->datatable = app(SettingsIndex::class);
    }

    /** @test */
    public function it_can_delete_the_selected_rows()
    {
        $settings = Setting::factory(3)->create();

        $this->datatable->refresh();
        $this->datatable->selectedRows[6] = true;
        $this->datatable->selectedRows[7] = true;
        $this->datatable->selectedRows[8] = true;

        $this->datatable->deleteSelected();

        foreach ($settings as $setting) {
            $values = $setting->toArray();
            $this->assertDatabaseMissing('settings', $values);
        }

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The selected records (8, 7, 6) have been deleted.', session('alertMessage'));
    }

    /** @test */
    public function it_can_delete_a_specific_row()
    {
        $setting = Setting::factory()->create();

        $this->datatable->delete($setting->getKey());

        $this->assertDatabaseMissing('settings', $setting->toArray());

        self::assertEquals('success', session('alertType'));
        self::assertEquals('The record ('.$setting->getKey().') have been deleted.', session('alertMessage'));
    }

    /** @test */
    public function it_can_perform_show_action()
    {
        Livewire::test('cms.settings.settings-index')
            ->call('performAction', 'show', 5)
            ->assertRedirect('/cms/settings/5');
    }

    /** @test */
    public function it_can_perform_edit_action()
    {
        Livewire::test('cms.settings.settings-index')
            ->call('performAction', 'edit', 5)
            ->assertRedirect('/cms/settings/5/edit');
    }

    /** @test */
    public function it_can_perform_create_action()
    {
        Livewire::test('cms.settings.settings-index')
            ->call('performAction', 'create')
            ->assertRedirect('/cms/settings/create');
    }
}
