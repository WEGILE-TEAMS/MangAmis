<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MangaController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'index']);

Route::post('/register', [RegisterController::class, 'store']);
Route::get('/home', [MangaController::class, 'index']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/test', function(){
    return view('try_register');
});
Route::get('/proxy-image', [MangaController::class, 'proxyImage'])->name('proxy-image');
