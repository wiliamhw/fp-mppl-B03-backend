<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Permission::findOrCreate('access-cms', config('cms.guard'));

        $this->createResourcePermissionsFor('admins');
        $this->createResourcePermissionsFor('roles');
        $this->createResourcePermissionsFor('settings');
        $this->createResourcePermissionsFor('users');
    }

    /**
     * Create a set of resource permissions for the given resource string.
     *
     * @param string $resource
     *
     * @return void
     */
    public function createResourcePermissionsFor(string $resource): void
    {
        Permission::findOrCreate('cms.'.$resource.'.viewAny', config('cms.guard'));
        Permission::findOrCreate('cms.'.$resource.'.view', config('cms.guard'));
        Permission::findOrCreate('cms.'.$resource.'.create', config('cms.guard'));
        Permission::findOrCreate('cms.'.$resource.'.update', config('cms.guard'));
        Permission::findOrCreate('cms.'.$resource.'.delete', config('cms.guard'));
        Permission::findOrCreate('cms.'.$resource.'.restore', config('cms.guard'));
        Permission::findOrCreate('cms.'.$resource.'.forceDelete', config('cms.guard'));
    }
}
