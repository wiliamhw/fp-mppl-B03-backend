<?php

namespace Tests\Feature\Cms\Auth;

use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;
use Tests\CmsTests;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
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

    /**
     * Mock recaptcha service and let it provide the expected response for
     * the test cases.
     *
     * @param bool $expectedResponse
     * @param int  $times
     */
    protected function mockRecaptcha(bool $expectedResponse = true, int $times = 1): void
    {
        $captcha = \Mockery::mock(NoCaptcha::class);
        app()->bind('captcha', function () use ($captcha) {
            return $captcha;
        });

        $captcha->shouldReceive('verifyResponse')
            ->times($times)
            ->andReturn($expectedResponse);
    }

    /** @test */
    public function forgot_password_page_is_accessible()
    {
        $this->get($this->getAuthUrl('/password/reset'))
            ->assertStatus(200);
    }

    /** @test */
    public function redirect_authenticated_admin_to_home_page()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, config('cms.guard'));

        $this->get($this->getAuthUrl('/password/reset'))
            ->assertStatus(302)
            ->assertRedirect(url($this->getCmsUrl('/')));
    }

    /** @test */
    public function admin_who_logged_in_using_different_auth_guard_can_access_cms_reset_password_page()
    {
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($admin, 'web');

        $this->get($this->getAuthUrl('/password/reset'))
            ->assertStatus(200);
    }

    /** @test */
    public function email_validation_works_as_expected()
    {
        $this->mockRecaptcha();

        $this->post($this->getAuthUrl('/password/email'), [
            'email'                => '',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');

        $this->post($this->getAuthUrl('/password/email'), [
            'email' => 'not.a.valid.email.address',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function recaptcha_validation_works_as_expected()
    {
        $this->mockRecaptcha(false);

        $this->post($this->getAuthUrl('/password/email'), [
            'email'                => '',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function reset_password_failed_using_unregistered_email_address()
    {
        $this->mockRecaptcha();
        $this->post($this->getAuthUrl('/password/email'), [
            'email'                => 'admin@admin.net',
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function reset_password_failed_using_registered_email_with_no_access_to_cms()
    {
        $this->mockRecaptcha();
        $admin = Admin::factory()->create();

        $this->post($this->getAuthUrl('/password/email'), [
            'email'                => $admin->email,
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function reset_password_success_using_registered_email_address()
    {
        $this->mockRecaptcha();
        $admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->post($this->getAuthUrl('/password/email'), [
            'email'                => $admin->email,
            'g-recaptcha-response' => 'bypassed',
        ])
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->get($this->getAuthUrl('/password/reset'))
            ->assertSee('We have emailed your password reset link!');
    }
}
