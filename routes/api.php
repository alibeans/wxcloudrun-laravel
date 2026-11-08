<?php

use App\Http\Controllers\CounterController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 云托管连通性测试（DB 计数器）
Route::get('/count', [CounterController::class, 'getCount']);
Route::post('/count', [CounterController::class, 'updateCount']);

// 业主认证（福多多小程序）
Route::get('/owner/profile', [OwnerController::class, 'profile']);
Route::post('/owner/apply', [OwnerController::class, 'apply']);
Route::post('/owner/cancel', [OwnerController::class, 'cancel']);
