<?php

return [

    /**
     * Specify the CMS name or title.
     */
    'name' => 'Tampil',

    /**
     * Specify the CMS tagline.
     */
    'tagline' => 'Easy access to <br> webinars and online events.',

    /**
     * Specify which authentication guard should be used to authenticate the CMS admins.
     */
    'guard' => 'cms',

    /**
     * Specify the CMS authentication path's prefix. For example, if you specified
     * the path prefix as "/secret/auth" then the full authentication routes will be :
     *   "/secret/auth/login"
     *   "/secret/auth/password/reset"
     *   "/secret/auth/password/email".
     */
    'auth_path_prefix' => '/secret/auth',

    /**
     * Specify the CMS authentication middleware. These middleware will be assigned to every
     * authentication route.
     */
    'auth_middleware' => [
        'web',
        \Cms\Http\Middleware\RedirectIfAuthenticated::class,
        \Cms\Http\Middleware\BlockRobots::class,
    ],

    /**
     * Specify the CMS path prefix that will be accessed by an admin, after the admin
     * has successfully logged into the CMS.
     */
    'path_prefix' => 'cms',

    /**
     * Specify the CMS route name prefix. This string will be prepended to every
     * CMS route name.
     */
    'route_prefix' => 'cms',

    /**
     * Specify the CMS application's middleware. These middleware will be assigned to every
     * CMS route.
     */
    'middleware' => [
        'web',
        \Cms\Http\Middleware\Authenticate::class,
        \Cms\Http\Middleware\BlockRobots::class,
    ],

    /**
     * Specify the controller's namespace that will be used in every CMS route.
     */
    'controller_namespace' => 'App\Http\Livewire\Cms',

    /**
     * The TinyMCE key.
     */
    'tinymce_key' => env('TINYMCE_KEY'),

    /**
     * Specify the CMS author.
     */
    'author' => 'Tampil',

    /**
     * Specify the CMS author's url.
     */
    'author_url' => 'https://tampil.id/',

    /**
     * Specify the Copyright year.
     */
    'copyright_year' => '2021',

    /**
     * Determine whether the application should enforce
     * using recaptcha validation in authentication routes.
     * Set this value into `false` to bypass recaptcha validation.
     */
    'captcha_enabled' => (bool) env('CAPTCHA_ENABLED'),

    /**
     * Determine whether the application should enforce
     * using HTTPS URLs.
     */
    'force_https_url' => env('FORCE_HTTPS_URL', false),

    /**
     * Define which storage disk should be used
     * in datatable export operation.
     */
    'datatable_export_disk' => env('DATATABLE_EXPORT_DISK', 'public'),

    /**
     * Define the standard quality for every image manipulation processing.
     */
    'media_quality' => env('MEDIA_QUALITY', 95),
];
