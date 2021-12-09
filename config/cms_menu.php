<?php

return [

    'items' => [
        [
            'title'      => 'Dashboard',
            'url'        => '/cms',
            'icon'       => 'menu-icon fa fa-home',
            'permission' => 'access-cms',
            'children'   => [],
        ],
        [
            'title'      => 'Core',
            'url'        => 'javascript:void(0);',
            'icon'       => 'menu-icon fa fa-cogs',
            'permission' => 'access-cms',
            'children'   => [
                [
                    'title'      => 'Admins',
                    'url'        => '/cms/admins',
                    'icon'       => 'menu-icon fa fa-user-cog',
                    'permission' => 'cms.admins.view',
                ],
                [
                    'title'      => 'Roles',
                    'url'        => '/cms/roles',
                    'icon'       => 'menu-icon fa fa-users',
                    'permission' => 'cms.roles.view',
                ],
                [
                    'title'      => 'Settings',
                    'url'        => '/cms/settings',
                    'icon'       => 'menu-icon fa fa-cog',
                    'permission' => 'cms.settings.view',
                ],
            ],
        ],
        [
            'title'      => 'Website',
            'url'        => 'javascript:void(0);',
            'icon'       => 'menu-icon fa fa-globe',
            'permission' => 'access-cms',
            'children'   => [
                [
                    'title'      => 'Users',
                    'url'        => '/cms/users',
                    'icon'       => 'menu-icon fa fa-user',
                    'permission' => 'cms.users.view',
                ],
                [
                    'title'      => 'Categories',
                    'url'        => '/cms/categories',
                    'icon'       => 'menu-icon fa fa-list-alt',
                    'permission' => 'cms.categories.view',
                ],
                [
                    'title'      => 'Webinars',
                    'url'        => '/cms/webinars',
                    'icon'       => 'menu-icon fa fa-desktop',
                    'permission' => 'cms.webinars.view',
                ],
                [
                    'title'      => 'User\'s Webinars',
                    'url'        => '/cms/user_webinars',
                    'icon'       => 'menu-icon fas fa-address-book',
                    'permission' => 'cms.user_webinar.view',
                ],
            ],
        ],
    ],

];
