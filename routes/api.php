<?php

use App\Http\Controllers\AuthApiController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth', [AuthApiController::class, 'login']);
Route::post('/absensi', [AuthApiController::class, 'simpan_absensi']);
Route::post('/data',[AuthApiController::class,'index']);
Route::post('/profile',[AuthApiController::class,'profil_user']);
Route::get('/test', [AuthApiController::class, 'coba']);
Route::post('/logout',[AuthApiController::class,'keluar'])->middleware('auth:sanctum');
