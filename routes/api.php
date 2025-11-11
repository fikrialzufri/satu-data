<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubElementController;
use App\Http\Controllers\Api\CkanVisitorController;


Route::get('/kelompokelement', [SubElementController::class, 'apikategorielement'])->name('kelompokelement.api');
Route::post('/ckan/visitors', [CkanVisitorController::class, 'store'])->name('ckan.visitors.store');
