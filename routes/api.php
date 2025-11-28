<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubElementController;
use App\Http\Controllers\Api\CkanVisitorController;
use App\Http\Controllers\Api\AuthController;


Route::get('/kelompokelement', [SubElementController::class, 'apikategorielement'])->name('kelompokelement.api');




Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        // logout
        Route::post('/logout', [AuthController::class, 'logout']);
        // user
        Route::get('/user', [AuthController::class, 'user']);
    });
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/ckan/visitors', [CkanVisitorController::class, 'store'])->name('ckan.visitors.store');
    });
});
