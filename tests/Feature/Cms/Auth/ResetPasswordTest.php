<?php

namespace Tests\Feature\Cms\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\CmsTests;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use CmsTests;
    use DatabaseMigrations;

    /** @test */
    public function reset_password_page_is_accessible()
    {
        $this->get($this->getAuthUrl('password/reset/'.Str::random(16)))
            ->assertStatus(200);
    }

    /** @test */
    public function email_validation_works_as_expected()
    {
        $token = Str::random(16);
        $password = Str::random(8);

        $this->post($this->getAuthUrl('/password/reset'), [
            'token'                 => $token,
            'email'                 => '',
            'password'              => $password,
            'password_confirmation' => $password,
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');

        $this->post($this->getAuthUrl('/password/reset'), [
            'token'                 => $token,
            'email'                 => 'not.a.valid.email.address',
            'password'              => $password,
            'password_confirmation' => $password,
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_validation_works_as_expected()
    {
        $token = Str::random(16);
        $password = Str::random(4);

        $this->post($this->getAuthUrl('/password/reset'), [
            'token'                 => $token,
            'email'                 => '',
            'password'              => $password,
            'password_confirmation' => $password,
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_confirmation_validation_works_as_expected()
    {
        $token = Str::random(16);
        $password = Str::random(8);

        $this->post($this->getAuthUrl('/password/reset'), [
            'token'                 => $token,
            'email'                 => '',
            'password'              => $password,
            'password_confirmation' => Str::random(8),
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('password');
    }
}
