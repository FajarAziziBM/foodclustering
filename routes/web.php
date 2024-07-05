<?php

use App\Http\Controllers\Api\ProvincesController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClusteringController;
use App\Models\Clustering;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get('/inputdata', [\App\Http\Controllers\ProvinceController::class, 'index'])->name('inputdata');
Route::post('/inputdata', [\App\Http\Controllers\ProvinceController::class, 'importdatas'])->name('importdatas');

Route::get('/province/{id}/edit', [App\Http\Controllers\ProvinceController::class, 'edit'])->name('edit.province');
Route::get('/province/{id}/formedit', [App\Http\Controllers\ProvinceController::class, 'formedit'])->name('editdataprovinsi');
Route::put('/province/{id}/edit', [App\Http\Controllers\ProvinceController::class, 'update'])->name('update.province');
Route::post('/deleteprov/{tahun}', [\App\Http\Controllers\ProvinceController::class, 'destroy'])->name('delete.province');

Route::get('/klasteringdata', [ClusteringController::class, 'index'])->name('klasteringdata');
Route::get('/sendDatas', [ProvincesController::class, 'index'])->name('sendDatas');
Route::get('/klasteringdata/{id}/dbscan', [\App\Http\Controllers\ClusteringController::class, 'hasilcluster'])->name('hasilklaster');
Route::delete('/klasteringdata/{id}', [App\Http\Controllers\ClusteringController::class, 'destroy'])->name('delete.cluster');
Route::get('/hasilklasterdbscan', [\App\Http\Controllers\ClusteringController::class, 'show'])->name('hasilklasterdbscan');
// tampilan grafik
Route::get('/api/grafik-clustering-data', [\App\Http\Controllers\ClusteringController::class, 'getGrafikClusteringData'])->name('data.grafik');



Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);

// Route::put('/klasteringdata/{id}/dbscan', [\App\Http\Controllers\ClusteringController::class, 'index'])->name('klasteringdata2');






