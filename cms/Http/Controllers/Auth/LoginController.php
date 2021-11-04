<?php

namespace Cms\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = '/'.config('cms.path_prefix');
    }

    /**
     * Show the application's login form.
     *
     * @return mixed
     */
    public function showLoginForm()
    {
        return view('cms::auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard(): Guard
    {
        return \Auth::guard(config('cms.guard'));
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return void
     */
    protected function validateLogin(Request $request): void
    {
        $rules = [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ];

        if (config('cms.captcha_enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);
    }
}
