<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admini\LoginController;
use App\Http\Controllers\Admini\Base\ManagerController;
use App\Http\Controllers\Admini\Base\RoleController;


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

# 后台用户登录
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('refresh', [LoginController::class, 'refresh']);
    Route::post('me', [LoginController::class, 'me'])->middleware(['jwt.role:admin', 'jwt.auth'])->name('me');
});

Route::group(['middleware' => ['jwt.role:admin', 'jwt.auth'] ], function () {

    Route::group(['prefix' => 'base'], function () {
        Route::group(['prefix' => 'manager'], function () {
            Route::get('lists', [ManagerController::class, 'lists']);
        });
        Route::group(['prefix' => 'role' ], function () {
            Route::get('lists', [RoleController::class, 'lists']);
        });
    });


});
