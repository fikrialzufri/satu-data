<?php

use App\Http\Controllers\ElementController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JenisDataController;
use App\Http\Controllers\JenisUnitController;
use App\Http\Controllers\LegendaController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SubElementController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    //ACL -- Access Control List
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('task', TaskController::class);

    // Master Data
    Route::resource('satuan', SatuanController::class);

    // Operator
    Route::resource('operator', OperatorController::class);

    // Element
    Route::resource('element', ElementController::class);
    Route::get('/elementimport', [ElementController::class, 'import'])->name('element.import');
    Route::post('/elementimport', [ElementController::class, 'importpost'])->name('element.import.post');
    Route::get('/elementdownload', [ElementController::class, 'download'])->name('element.download');
    // update nilai
    Route::post('/elementnilai', [SubElementController::class, 'nilai'])->name('elemen.update.nilai');

    // import Element
    Route::get('/elementinport', [SubElementController::class, 'import'])->name('sub-element.import');

    Route::resource('sub_element', SubElementController::class);
    Route::get('/sub_elementimport', [SubElementController::class, 'import'])->name('sub-element.import');
    Route::post('/sub_elementimport', [SubElementController::class, 'importpost'])->name('sub-element.import.post');

    Route::get('/sub_elementdownload', [SubElementController::class, 'download'])->name('sub-element.download');

    Route::resource('jenis_data', JenisDataController::class);
    Route::resource('jenis_unit', JenisUnitController::class);
    Route::resource('group', GroupController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('legenda', LegendaController::class);

    // jenis data getDetail
    Route::get('/jenisdetail', [JenisDataController::class, 'detail'])->name('jenisdata.detail');

    // ubah profile
    Route::get('/ubahuser', [UserController::class, 'ubah'])->name('user.ubah');
    Route::put('/simpanuser', [UserController::class, 'simpan'])->name('user.simpan');
    Route::put('/save-token', [UserController::class, 'token'])->name('user.token');
    Route::get('/user-notification', [UserController::class, 'notification'])->name('user.notification');
});
