<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\SeoMeta;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Policies\PublicReadOnlyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Admin::class      => PublicReadOnlyPolicy::class,
        Role::class       => PublicReadOnlyPolicy::class,
        SeoMeta::class    => PublicReadOnlyPolicy::class,
        Setting::class    => PublicReadOnlyPolicy::class,
        StaticPage::class => PublicReadOnlyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
