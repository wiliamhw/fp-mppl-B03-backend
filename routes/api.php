<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\UsersController;
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
Route::post('/users/login',  [UsersController::class, 'login'])->name('users.login');
Route::post('/users', [UsersController::class, 'store'])->name('users.store');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test/login', [TestController::class, 'login'])->name('test.login');

    Route::get('/users',  [UsersController::class, 'show'])->name('users.show');
    Route::put('/users',  [UsersController::class, 'update'])->name('users.update');
    Route::get('/users/logout',  [UsersController::class, 'logout'])->name('users.logout');
});

Route::apiResource('/settings', 'SettingsController')->only(['index', 'show']);
