<?php

namespace Tests\Unit\Cms\Blade;

use App\Models\Admin;
use Cms\Blade\MenuBuilder;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Facade;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MenuBuilderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Cms Admin Object.
     *
     * @var \App\Models\Admin
     */
    protected $admin;

    /**
     * Menu Builders Object.
     *
     * @var \LuminousCMS\Supports\MenuBuilder
     */
    protected $builder;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);
        Permission::findOrCreate('high-level-permission', config('cms.guard'));

        $this->admin = Admin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->admin, config('cms.guard'));

        $this->builder = new MenuBuilder();
    }

    /** @test */
    public function it_can_build_child_menu_for_authorized_admin_with_icon()
    {
        $data = [
            'title'      => 'Users',
            'url'        => '/cms/users',
            'icon'       => 'la la-home',
            'permission' => 'access-cms',
        ];

        $expected = '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="'.url($data['url']).'"><i class="'.$data['icon'].'"><span></span></i> <span class="menu-text">'.$data['title'].'</span></a></li>';
        $actual = $this->invokeMethod($this->builder, 'buildChildMenuItem', [$data]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_build_child_menu_for_authorized_admin_without_icon()
    {
        $data = [
            'title'      => 'Users',
            'url'        => '/cms/users',
            'icon'       => '',
            'permission' => 'access-cms',
        ];

        $expected = '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="'.url($data['url']).'"> <span class="menu-text">'.$data['title'].'</span></a></li>';
        $actual = $this->invokeMethod($this->builder, 'buildChildMenuItem', [$data]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_not_build_child_menu_for_unauthorized_admin()
    {
        $data = [
            'title'      => 'Users',
            'url'        => '/cms/users',
            'icon'       => '',
            'permission' => 'high-level-permission',
        ];
        $actual = $this->invokeMethod($this->builder, 'buildChildMenuItem', [$data]);

        $this->assertNull($actual);
    }

    /** @test */
    public function it_can_build_parent_menu_for_authorized_admin_with_child_menus()
    {
        $data = [
            'title'      => 'General',
            'url'        => '/',
            'icon'       => 'la la-home',
            'permission' => 'access-cms',
            'children'   => [
                [
                    'title'      => 'One',
                    'url'        => '/one',
                    'icon'       => '',
                    'permission' => 'access-cms',
                ],
                [
                    'title'      => 'Two',
                    'url'        => '/two',
                    'icon'       => '',
                    'permission' => 'high-level-permission',
                ],
                [
                    'title'      => 'Three',
                    'url'        => '/three',
                    'icon'       => 'la la-home',
                    'permission' => 'access-cms',
                ],
            ],
        ];

        $expected = '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a href="'.url($data['url']).'" class="menu-link menu-toggle"><i class="'.$data['icon'].'"></i><span class="menu-text">'.$data['title'].'</span><i class="menu-arrow"></i></a><div class="menu-submenu"><i class="menu-arrow"></i><ul class="menu-subnav"><li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="'.url($data['children'][0]['url']).'"> <span class="menu-text">'.$data['children'][0]['title'].'</span></a></li><li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="'.url($data['children'][2]['url']).'"><i class="'.$data['children'][2]['icon'].'"><span></span></i> <span class="menu-text">'.$data['children'][2]['title'].'</span></a></li></ul></div></li>';
        $actual = $this->invokeMethod($this->builder, 'buildMenuItem', [$data]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_will_not_build_main_menu_for_unauthorized_admin()
    {
        $data = [
            'title'      => 'General',
            'url'        => '/',
            'icon'       => 'la la-home',
            'permission' => 'high-level-permission',
            'children'   => [
                [
                    'title'      => 'One',
                    'url'        => '/one',
                    'icon'       => '',
                    'permission' => 'access-cms',
                ],
                [
                    'title'      => 'Two',
                    'url'        => '/two',
                    'icon'       => '',
                    'permission' => 'high-level-permission',
                ],
                [
                    'title'      => 'Three',
                    'url'        => '/three',
                    'icon'       => 'la la-home',
                    'permission' => 'access-cms',
                ],
            ],
        ];

        $actual = $this->invokeMethod($this->builder, 'buildMenuItem', [$data]);

        $this->assertNull($actual);
    }

    /** @test */
    public function it_will_not_build_child_menus_for_unauthorized_admin()
    {
        $data = [
            'title'      => 'General',
            'url'        => '/',
            'icon'       => 'la la-home',
            'permission' => 'access-cms',
            'children'   => [
                [
                    'title'      => 'One',
                    'url'        => '/one',
                    'icon'       => '',
                    'permission' => 'high-level-permission',
                ],
                [
                    'title'      => 'Two',
                    'url'        => '/two',
                    'icon'       => '',
                    'permission' => 'high-level-permission',
                ],
                [
                    'title'      => 'Three',
                    'url'        => '/three',
                    'icon'       => 'la la-home',
                    'permission' => 'high-level-permission',
                ],
            ],
        ];

        $expected = '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a href="'.url($data['url']).'" class="menu-link menu-toggle"><i class="'.$data['icon'].'"></i><span class="menu-text">'.$data['title'].'</span><i class="menu-arrow"></i></a></li>';
        $actual = $this->invokeMethod($this->builder, 'buildMenuItem', [$data]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_build_the_whole_main_navigation_component()
    {
        $data = [
            [
                'title'      => 'General',
                'url'        => '/',
                'icon'       => 'la la-home',
                'permission' => 'access-cms',
                'children'   => [
                    [
                        'title'      => 'One',
                        'url'        => '/one',
                        'icon'       => '',
                        'permission' => 'access-cms',
                    ],
                    [
                        'title'      => 'Two',
                        'url'        => '/two',
                        'icon'       => '',
                        'permission' => 'high-level-permission',
                    ],
                    [
                        'title'      => 'Three',
                        'url'        => '/three',
                        'icon'       => 'la la-home',
                        'permission' => 'access-cms',
                    ],
                ],
            ],
            [
                'title'      => 'Specific',
                'url'        => '/specific',
                'icon'       => 'la la-specific',
                'permission' => 'access-cms',
                'children'   => [
                    [
                        'title'      => 'One',
                        'url'        => '/one',
                        'icon'       => '',
                        'permission' => 'high-level-permission',
                    ],
                ],
            ],
        ];
        config(['cms_menu.items' => $data]);

        $expected = '<ul class="menu-nav"><li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a href="'.url($data[0]['url']).'" class="menu-link menu-toggle"><i class="'.$data[0]['icon'].'"></i><span class="menu-text">'.$data[0]['title'].'</span><i class="menu-arrow"></i></a><div class="menu-submenu"><i class="menu-arrow"></i><ul class="menu-subnav"><li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="'.url($data[0]['children'][0]['url']).'"> <span class="menu-text">'.$data[0]['children'][0]['title'].'</span></a></li><li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="'.url($data[0]['children'][2]['url']).'"><i class="'.$data[0]['children'][2]['icon'].'"><span></span></i> <span class="menu-text">'.$data[0]['children'][2]['title'].'</span></a></li></ul></div></li><li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a href="'.url($data[1]['url']).'" class="menu-link menu-toggle"><i class="'.$data[1]['icon'].'"></i><span class="menu-text">'.$data[1]['title'].'</span><i class="menu-arrow"></i></a></li></ul>';

        $this->assertEquals($expected, $this->builder->toHtml());
    }

    /** @test */
    public function access_restricted_for_unauthorized_admin()
    {
        $admin = Admin::factory()->create();

        $guard = \Mockery::mock(SessionGuard::class);
        $guard->shouldReceive('user')
            ->andReturns($admin);

        $manager = \Mockery::mock(AuthManager::class, ['guard']);
        $manager->shouldReceive('guard')
            ->with(config('cms.guard'))
            ->andReturns($guard);

        $this->app->bind('auth', function () use ($manager) {
            return $manager;
        }, true);
        $this->app->forgetInstance('auth');

        Facade::clearResolvedInstance('auth');

        $this->assertFalse($this->invokeMethod($this->builder, 'accessPermitted', ['access-cms']));
    }

    /** @test */
    public function it_raises_error_exception_when_the_authenticated_admin_has_no_permission()
    {
        $this->expectException(\ErrorException::class);

        $guard = \Mockery::mock(SessionGuard::class);
        $guard->shouldReceive('user')
            ->andReturns(new \App\Models\User());

        $manager = \Mockery::mock(AuthManager::class, ['guard']);
        $manager->shouldReceive('guard')
            ->with(config('cms.guard'))
            ->andReturns($guard);

        $this->app->bind('auth', function () use ($manager) {
            return $manager;
        }, true);
        $this->app->forgetInstance('auth');

        Facade::clearResolvedInstance('auth');

        $this->invokeMethod($this->builder, 'accessPermitted', ['access-cms']);
    }
}
