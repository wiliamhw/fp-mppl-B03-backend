<?php

namespace Tests\Unit\Cms\Console\Commands;

use App\Models\Admin;
use Cms\Console\Commands\CreateNewAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CreateNewAdminTest extends TestCase
{
    use DatabaseMigrations;

    protected $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->command = app(CreateNewAdmin::class);
    }

    /** @test */
    public function it_has_the_desired_command_name()
    {
        $actual = $this->getPropertyValue($this->command, 'signature');
        $this->assertEquals('cms:create-admin {name=Administrator} {email=admin@admin.com} {password=password}', $actual);
    }

    /** @test */
    public function it_can_create_a_new_admin_based_on_the_given_arguments()
    {
        $newAdmin = [
            'name'     => 'Richan Fongdasen',
            'email'    => 'richan.fongdasen@gmail.com',
            'password' => 'verystrongpassword',
        ];

        \Artisan::call('cms:create-admin', $newAdmin);
        unset($newAdmin['password']);

        $this->assertDatabaseHas('admins', $newAdmin);

        $admin = Admin::where('email', $newAdmin['email'])->firstOrFail();

        $this->assertTrue(\Hash::check('verystrongpassword', $admin->password));
        $this->assertTrue($admin->hasRole('super-administrator'));

        $permissions = Permission::where('guard_name', config('cms.guard'))->get();
        foreach ($permissions as $permission) {
            $this->assertTrue($admin->can($permission->name));
        }
    }
}
