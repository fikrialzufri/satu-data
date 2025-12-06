<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubElementController;
use App\Http\Controllers\Api\CkanVisitorController;
use App\Http\Controllers\Api\AuthController;


Route::get('/kelompokelement', [SubElementController::class, 'apikategorielement'])->name('kelompokelement.api');





Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        // Public routes (no authentication required)
        Route::post('/login', [AuthController::class, 'login']);
        
        // Protected routes (authentication required)
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
        });
    });
    
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/ckan/visitors', [CkanVisitorController::class, 'store'])->name('ckan.visitors.store');
    });
});
