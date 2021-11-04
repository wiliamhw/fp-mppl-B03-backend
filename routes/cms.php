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
 * Begin route definition for `Seo Metas` resources.
 */
Route::get('/seo_metas', 'SeoMetas\SeoMetasIndex')->name('seo_metas.index');
Route::get('/seo_metas/create', 'SeoMetas\CreateSeoMeta')->name('seo_metas.create');
Route::get('/seo_metas/{seoMeta}', 'SeoMetas\ShowSeoMeta')->name('seo_metas.show');
Route::get('/seo_metas/{seoMeta}/edit', 'SeoMetas\EditSeoMeta')->name('seo_metas.edit');

/**
 * Begin route definition for `Static Pages` resources.
 */
Route::get('/static_pages', 'StaticPages\StaticPagesIndex')->name('static_pages.index');
Route::get('/static_pages/create', 'StaticPages\CreateStaticPage')->name('static_pages.create');
Route::get('/static_pages/{staticPage}', 'StaticPages\ShowStaticPage')->name('static_pages.show');
Route::get('/static_pages/{staticPage}/edit', 'StaticPages\EditStaticPage')->name('static_pages.edit');
