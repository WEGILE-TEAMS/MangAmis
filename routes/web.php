<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\RegisterController;
use GuzzleHttp\Client as HttpClient;
use App\Http\Controllers\DetailMangaController;
use App\Http\Controllers\MangaHistoryController;
use Illuminate\Routing\Route as RoutingRoute;
use App\Http\Controllers\UpdatedMangaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" mid    dleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/', [RegisterController::class, 'index']);

Route::get('/register', [RegisterController::class, 'index']);
Route::get('/proxy-image', [MangaController::class, 'proxyImage'])->name('proxy-image');

Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', function () {
    return view('login');
});
