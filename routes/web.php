<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClusteringController;

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

Route::resource('/inputdata', ProvinceController::class);
Route::get('/inputdata', [\App\Http\Controllers\ProvinceController::class, 'index'])->name('inputdata');
Route::post('/inputdata', [\App\Http\Controllers\ProvinceController::class, 'importdatas'])->name('importdatas');
Route::delete('/inputdata/{province}', [App\Http\Controllers\ProvinceController::class, 'destroy'])->name('delete.province');

Route::get('/province/{id}/edit', [App\Http\Controllers\ProvinceController::class, 'edit'])->name('edit.province');

Route::put('/province/{id}/edit', [App\Http\Controllers\ProvinceController::class, 'update'])->name('update.province');



Route::get('/hasilklaster', [\App\Http\Controllers\ClusteringController::class, 'show'])->name('hasilklaster');
Route::get('/klasteringdata', [\App\Http\Controllers\ClusteringController::class, 'index'])->name('klasteringdata');


Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
