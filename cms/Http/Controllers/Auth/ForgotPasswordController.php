<?php

namespace Cms\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * ForgotPasswordController constructor.
     */
    public function __construct()
    {
        ResetPassword::toMailUsing(static function ($notifiable, $token) {
            $name = data_get($notifiable, 'name');

            return (new MailMessage())
                ->subject('Reset Password Notification')
                ->line('Hi '.$name.',')
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', url(config('app.url').route('cms.auth.password.reset', $token, false)))
                ->line('This password reset link will expire in '.config('auth.passwords.users.expire').' minutes.')
                ->line('If you did not request a password reset, no further action is required.');
        });
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return \Password::broker('admins');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return mixed
     */
    public function showLinkRequestForm()
    {
        return view('cms::auth.passwords.email');
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email|min:11',
        ];

        if (config('cms.captcha_enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);
    }
}
