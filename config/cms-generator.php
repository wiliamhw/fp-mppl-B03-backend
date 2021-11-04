<?php

return [
    'authorization' => [
        'user_class'     => 'Admin',
        'user_namespace' => 'App\\Models',
        'user_var_name'  => 'admin',
        'user_title'     => 'CMS Admin',
    ],

    'blade' => [
        'path'      => 'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'livewire'.DIRECTORY_SEPARATOR.'cms',
    ],

    'livewire' => [
        'namespace' => 'App\\Http\\Livewire\\Cms',
        'path'      => 'app'.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Livewire'.DIRECTORY_SEPARATOR.'Cms',
    ],

    'model' => [
        'namespace' => 'App\\Models',
        'path'      => 'app'.DIRECTORY_SEPARATOR.'Models',
    ],

    'test' => [
        'namespace'       => 'Tests\\Livewire\\Cms',
        'path'            => 'tests'.DIRECTORY_SEPARATOR.'Livewire'.DIRECTORY_SEPARATOR.'Cms',
    ],

    'route_path'           => 'routes'.DIRECTORY_SEPARATOR.'cms.php',
];
