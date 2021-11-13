<?php

namespace Tests\Feature\Cms\Auth;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Tests\CmsTests;
use Tests\TestCase;

class LoginTest extends TestCase
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

        Permission::findOrCreate('access-cms', config('cms.guard'));

        $this->seed(['RoleSeeder']);

        Config::set('cms.captcha_enabled', true);
    }

    /** @test */
    public function redirect_unauthorized_admins_to_login_page()
    {
        $this->get($this->getCmsUrl('/'))
            ->assertStatus(302)
            ->assertRedirect($this->getAuthUrl('/login'));
    }

    /** @test */
    public function login_page_is_accessible()
    {
        $this->get($this->getAuthUrl('/login'))
            ->assertStatus(200);
    }

    /** @test */
    public function email_validation_works_as_expected()
    {
        $this->post($this->getAuthUrl('/login'), [
            'email'    => '',
            'password' => Str::random(8),
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_validation_works_as_expected()
    {
        $this->post($this->getAuthUrl('/login'), [
            'email'    => 'admin@admin.net',
            'password' => '',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function login_failed_using_invalid_credential()
    {
        $captcha = \Mockery::mock(NoCaptcha::class);
        app()->bind('captcha', function () use ($captcha) {
            return $captcha;
        });

        $captcha->shouldReceive('verifyResponse')
            ->times(1)
            ->andReturn(true);

        $this->post($this->getAuthUrl('/login'), [
            'email'                => 'admin@admin.net',
            'password'             => 'password',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_failed_using_a_valid_credential_but_has_no_permission_to_access_cms()
    {
        $captcha = \Mockery::mock(NoCaptcha::class);
        app()->bind('captcha', function () use ($captcha) {
            return $captcha;
        });

        $captcha->shouldReceive('verifyResponse')
            ->times(1)
            ->andReturn(true);

        $admin = Admin::factory()->create();

        $this->post($this->getAuthUrl('/login'), [
            'email'                => $admin->email,
            'password'             => 'password',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');

        $this->assertNull(\Auth::guard(config('cms.guard'))->user());
    }

    /** @test */
    public function login_success_using_a_valid_credential()
    {
        $captcha = \Mockery::mock(NoCaptcha::class);
        app()->bind('captcha', function () use ($captcha) {
            return $captcha;
        });

        $captcha->shouldReceive('verifyResponse')
            ->times(1)
            ->andReturn(true);

        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->post($this->getAuthUrl('/login'), [
            'email'                => $admin->email,
            'password'             => 'password',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertRedirect(url($this->getCmsUrl('/')));

        $actualAdmin = \Auth::guard(config('cms.guard'))->user();
        $this->assertInstanceOf(Admin::class, $actualAdmin);
        $this->assertEquals($admin->email, $actualAdmin->email);
    }

    /** @test */
    public function redirect_authenticated_admin_to_home_page()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, config('cms.guard'));

        $this->get($this->getAuthUrl('/login'))
            ->assertStatus(302)
            ->assertRedirect(url($this->getCmsUrl('/')));
    }

    /** @test */
    public function redirect_authenticated_admin_who_logged_in_using_a_different_auth_guard()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, 'web');

        $this->get($this->getCmsUrl('/'))
            ->assertStatus(302)
            ->assertRedirect(url($this->getAuthUrl('/login')));
    }

    /** @test */
    public function authenticated_admin_who_logged_in_using_different_auth_guard_can_access_cms_login_page()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, 'web');

        $this->get($this->getAuthUrl('/login'))
            ->assertStatus(200);
    }
}
