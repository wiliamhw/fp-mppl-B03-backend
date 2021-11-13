<?php

namespace Tests\Livewire\Cms;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CmsTests;
use Tests\TestCase;

class DashboardTest extends TestCase
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
    }

    /** @test */
    public function cms_dashboard_page_is_accessible()
    {
        $this->actingAs($this->admin, config('cms.guard'));

        $this->get($this->getCmsUrl('/'))
            ->assertStatus(200)
            ->assertSeeLivewire('cms.nav.header-mobile')
            ->assertSeeLivewire('cms.nav.side-bar')
            ->assertSeeLivewire('cms.nav.header')
            ->assertSeeLivewire('cms.nav.footer')
            ->assertSeeLivewire('cms.home.index');
    }
}
