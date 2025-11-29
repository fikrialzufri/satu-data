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
use App\Http\Controllers\BannerController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {


    //ACL -- Access Control List
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('task', TaskController::class);

    // Master Data
    Route::resource('satuan', SatuanController::class);

    // Operator
    Route::resource('operator', OperatorController::class);

    // Element

    Route::get('/elementimport', [ElementController::class, 'import'])->name('element.import');
    Route::post('/elementimport', [ElementController::class, 'importpost'])->name('element.import.post');
    Route::get('/elementdownload', [ElementController::class, 'download'])->name('element.download');
    // update nilai
    Route::post('/elementnilai', [SubElementController::class, 'nilai'])->name('elemen.update.nilai');

    Route::get(
        '/sendelmentckan/{id}',
        [ElementController::class, 'sendckan']
    )->name('element.ckan');

    // import Element
    Route::get('/elementinport', [SubElementController::class, 'import'])->name('sub-element.import');

    Route::get('/sub_elementimport', [SubElementController::class, 'import'])->name('sub-element.import');
    Route::post('/sub_elementimport', [SubElementController::class, 'importpost'])->name('sub-element.import.post');

    Route::get('/sub_elementdownload', [SubElementController::class, 'download'])->name('sub-element.download');

    // kirim sub element ke ckan
    Route::get(
        '/sendsubelmentckan/{id}',
        [SubElementController::class, 'sendckan']
    )->name('sub-element.ckan');

    Route::resource('jenis_data', JenisDataController::class);
    Route::resource('jenis_unit', JenisUnitController::class);
    Route::resource('group', GroupController::class);

    Route::resource('unit', UnitController::class);
    Route::get('/sendckan/{id}', [UnitController::class, 'sendckan'])->name('unit.ckan');

    Route::resource('legenda', LegendaController::class);
    Route::resource('banner', BannerController::class);


    // ubah profile
    Route::get('/ubahuser', [UserController::class, 'ubah'])->name('user.ubah');
    Route::put('/simpanuser', [UserController::class, 'simpan'])->name('user.simpan');
    Route::put('/save-token', [UserController::class, 'token'])->name('user.token');
    Route::get('/user-notification', [UserController::class, 'notification'])->name('user.notification');
});

// jenis data getDetail
Route::get('/jenisdetail', [JenisDataController::class, 'detail'])->name('jenisdata.detail');

Route::get('/kategorielement', [SubElementController::class, 'kategorielement'])->name('kategorielement');
Route::get('/kelompokelement', [SubElementController::class, 'kelompokelement'])->name('kelompokelement');

Route::resource('element', ElementController::class);

Route::resource('sub_element', SubElementController::class);
