<?php

use App\Http\Controllers\Api\ProvincesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|

*/
Route::get('/datas', [ProvincesController::class, 'index'])->name('postapidatas');

Route::post('/save-data', [ProvincesController::class, 'hasilcluster'])->name('saveData');
