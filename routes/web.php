<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\RegisterController;
use GuzzleHttp\Client as HttpClient;
use App\Http\Controllers\DetailMangaController;
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

Route::get('/home', [MangaController::class, 'index']);

Route::get('/detailManga/{id}/{title}/{author}/{desc}/{genres}/{cover_id}', [DetailMangaController::class, 'index'])
    ->where('id', '[a-zA-Z0-9\-]+')
    ->where('title', '.*')
    ->where('author', '.*')
    ->where('desc', '.*')
    ->where('genres', '.*')
    ->where('cover_id', '[a-zA-Z0-9\-]+');

Route::get('/login', [LoginController::class, 'index']);

Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/navbar', function(){
    return view('template.navbar');
});
Route::get('/footer', function(){
    return view('template.footer');
});

Route::get('/updated-manga', [
    UpdatedMangaController::class, 'showUpdatedManga'
]);
