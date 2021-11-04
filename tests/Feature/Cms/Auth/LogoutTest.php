<?php

namespace Tests\Feature\Cms\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CmsTests;
use Tests\TestCase;

abstract class LogoutTest extends TestCase
{
    use CmsTests;
    use DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);
    }

    /** @test */
    public function home_dashboard_is_not_accessible_by_unauthenticated_admin()
    {
        $this->get($this->getCmsUrl('/'))
            ->assertStatus(302)
            ->assertRedirect($this->getAuthUrl('/login'));
    }

    /** @test */
    public function home_dashboard_page_is_accessible_by_authenticated_admin()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, config('cms.guard'));

        $this->get($this->getCmsUrl('/'))
            ->assertStatus(200);
    }

    /** @test */
    public function authenticated_admin_can_logout_from_cms()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, config('cms.guard'));

        $this->get($this->getCmsUrl('/current-admin/logout'))
            ->assertStatus(302)
            ->assertRedirect($this->getAuthUrl('/login'));
    }
}
