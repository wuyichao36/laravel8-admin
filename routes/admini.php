<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admini\LoginController;
use App\Http\Controllers\Admini\Base\AccountController;
use App\Http\Controllers\Admini\Base\RoleController;
use App\Http\Controllers\Admini\Base\MenuController;


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
        Route::group(['prefix' => 'account'], function () {
            Route::get('index', [AccountController::class, 'index']);
            Route::get('show', [AccountController::class, 'show']);
            Route::post('store_update', [AccountController::class, 'store_update']);
            Route::post('destroy', [AccountController::class, 'destroy']);
            Route::post('password', [AccountController::class, 'password']);
        });
        Route::group(['prefix' => 'role' ], function () {
            Route::get('index', [RoleController::class, 'index']);
            Route::get('show', [RoleController::class, 'show']);
            Route::post('store_update', [RoleController::class, 'store_update']);
            Route::get('lists', [RoleController::class, 'lists']);
            Route::post('destroy', [RoleController::class, 'destroy']);
        });

        Route::group(['prefix' => 'menu' ], function () {
            Route::get('load_tree', [MenuController::class, 'load_tree']);
        });

    });


});
