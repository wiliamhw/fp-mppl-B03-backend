<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Home\Index')->name('home');
Route::get('/current-admin/profile', 'CurrentAdmin\Profile')->name('current-admin.profile');

/**
 * Begin route definition for `Settings` resources.
 */
Route::get('/settings', 'Settings\SettingsIndex')->name('settings.index');
Route::get('/settings/create', 'Settings\CreateSetting')->name('settings.create');
Route::get('/settings/{setting}', 'Settings\ShowSetting')->name('settings.show');
Route::get('/settings/{setting}/edit', 'Settings\EditSetting')->name('settings.edit');

/**
 * Begin route definition for `Roles` resources.
 */
Route::get('/roles', 'Roles\RolesIndex')->name('roles.index');
Route::get('/roles/create', 'Roles\CreateRole')->name('roles.create');
Route::get('/roles/{role}', 'Roles\ShowRole')->name('roles.show');
Route::get('/roles/{role}/edit', 'Roles\EditRole')->name('roles.edit');

/**
 * Begin route definition for `Admins` resources.
 */
Route::get('/admins', 'Admins\AdminsIndex')->name('admins.index');
Route::get('/admins/create', 'Admins\CreateAdmin')->name('admins.create');
Route::get('/admins/{admin}', 'Admins\ShowAdmin')->name('admins.show');
Route::get('/admins/{admin}/edit', 'Admins\EditAdmin')->name('admins.edit');


/**
 * Begin route definition for `Users` resources.
 */
Route::get('/users', 'Users\UsersIndex')->name('users.index');
Route::get('/users/create', 'Users\CreateUser')->name('users.create');
Route::get('/users/{user}', 'Users\ShowUser')->name('users.show');
Route::get('/users/{user}/edit', 'Users\EditUser')->name('users.edit');


/**
 * Begin route definition for `Categories` resources.
 */
Route::get('/categories', 'Categories\CategoriesIndex')->name('categories.index');
Route::get('/categories/create', 'Categories\CreateCategory')->name('categories.create');
Route::get('/categories/{category}', 'Categories\ShowCategory')->name('categories.show');
Route::get('/categories/{category}/edit', 'Categories\EditCategory')->name('categories.edit');


/**
 * Begin route definition for `Webinars` resources.
 */
Route::get('/webinars', 'Webinars\WebinarsIndex')->name('webinars.index');
Route::get('/webinars/create', 'Webinars\CreateWebinar')->name('webinars.create');
Route::get('/webinars/{webinar}', 'Webinars\ShowWebinar')->name('webinars.show');
Route::get('/webinars/{webinar}/edit', 'Webinars\EditWebinar')->name('webinars.edit');
