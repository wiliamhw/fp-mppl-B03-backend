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
                    'icon'       => 'menu-icon fa fa-user',
                    'permission' => 'cms.admins.view',
                ],
                [
                    'title'      => 'Roles',
                    'url'        => '/cms/roles',
                    'icon'       => 'menu-icon fa fa-users',
                    'permission' => 'cms.roles.view',
                ],
                [
                    'title'      => 'SEO Metas',
                    'url'        => '/cms/seo_metas',
                    'icon'       => 'menu-icon fa fa-robot',
                    'permission' => 'cms.seo_metas.view',
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
                    'title'      => 'Static Pages',
                    'url'        => '/cms/static_pages',
                    'icon'       => 'menu-icon fa fa-file-code',
                    'permission' => 'cms.static_pages.view',
                ],
            ],
        ],
    ],

];
