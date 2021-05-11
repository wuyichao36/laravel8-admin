<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

# 普通用户登录
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);//登录 mobile password
    Route::post('logout', [AuthController::class, 'logout']); // 注销 token
    Route::post('refresh', [AuthController::class, 'refresh']); // 刷新令牌 token
    Route::post('me', [AuthController::class, 'me'])->name('me')->middleware(['jwt.role:user', 'jwt.auth']);
    //请求经过中间件 token或者 Header头Authorization 内容：Bearer token参数值
});

