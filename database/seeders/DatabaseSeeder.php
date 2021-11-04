<?php

namespace Database\Seeders;

use Artisan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SeoMetaSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(StaticPageSeeder::class);

        Artisan::call('cms:create-admin');
    }
}
