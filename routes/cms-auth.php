<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::post('/login', 'LoginController@login')->name('login.submit');

Route::prefix('password')->group(function () {
    Route::post('email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('reset', 'ResetPasswordController@reset')->name('password.update');
    Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
});
