<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdatedMangaController;
use App\Http\Controllers\SearchController;
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

Route::post('/register', [RegisterController::class, 'store']);
Route::get('/home', [MangaController::class, 'index']);

Route::get('/login', [LoginController::class, 'index']);

Route::post('/login', [LoginController::class, 'authenticate']);


Route::get('/updated-manga', [
    UpdatedMangaController::class, 'showUpdatedManga'
]);

Route::get('/proxy-image', [MangaController::class, 'proxyImage'])->name('proxy-image');
Route::get('/search',[SearchController::class,'search'])->name('manga.search');
Route::get('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Route::get('/navbar', function(){
//     return view('template.navbar');
// });
// Route::get('/footer', function(){
//     return view('template.footer');
// });
