<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


Route::get(
    '/csrf-cookie',
    CsrfCookieController::class . '@show'
)->middleware('web')->name('auth.cookies');


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('me', AuthController::class . '@me')->name('auth.me');
    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');
});
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
