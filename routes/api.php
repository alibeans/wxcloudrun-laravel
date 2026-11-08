<?php

use App\Http\Controllers\OwnerController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 业主认证（福多多小程序）
Route::get('/owner/profile', [OwnerController::class, 'profile']);
Route::post('/owner/apply', [OwnerController::class, 'apply']);
Route::post('/owner/cancel', [OwnerController::class, 'cancel']);
