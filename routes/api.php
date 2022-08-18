<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubElementController;


Route::get('/kelompokelement', [SubElementController::class, 'apikategorielement'])->name('kelompokelement.api');
