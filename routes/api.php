<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\UserLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:web')->group(function () {
    Route::apiResource('/admins', 'AdminsController')->only(['index', 'show']);
    Route::apiResource('/permissions', 'PermissionsController')->only(['index', 'show']);
    Route::apiResource('/roles', 'RolesController')->only(['index', 'show']);
});

Route::get('/test', [TestController::class, 'connection'])->name('test');

Route::post('/users/login',  [UserLoginController::class, 'login'])->name('login');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test/login', [TestController::class, 'login'])->name('test.login');
    Route::get('/users/logout',  [UserLoginController::class, 'logout'])->name('logout');
});

Route::apiResource('/users', 'UsersController');
Route::apiResource('/settings', 'SettingsController')->only(['index', 'show']);
