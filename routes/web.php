<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DetailCommunityController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController2;
use App\Http\Controllers\RegisterController;
use GuzzleHttp\Client as HttpClient;
use App\Http\Controllers\DetailMangaController;
use App\Http\Controllers\MangaHistoryController;
use Illuminate\Routing\Route as RoutingRoute;
use App\Http\Controllers\UpdatedMangaController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ViewCommunityController;

Route::get('/home', [MangaController::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/testing', function () {
    return view('home_front');
});
// Route::get('/home', [MangaController::class, 'index']);

Route::get('/detailManga', [MangaController::class, 'detailManga'])->name('detailManga');
Route::get('/read-manga/{mangaTitle}/{chapterId}', [MangaController::class, 'readManga'])->name('read.manga');

Route::get('/proxy-image', [MangaController2::class, 'proxyImage'])->name('proxy-image');

Route::post('/save-manga-history', [MangaHistoryController::class, 'saveMangaHistory'])->middleware('auth');
Route::get('/history', [MangaHistoryController::class, 'show']);

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);


Route::get('/updated-manga', [
    UpdatedMangaController::class, 'showUpdatedManga'
]);

Route::get('/proxy-image', [MangaController::class, 'proxyImage'])->name('proxy-image');

Route::get('/community', [CommunityController::class, 'community'])->name('community');
Route::get('/detailCommunity', [CommunityController::class, 'community'])->name('community');
Route::post('/addCommunity', [CommunityController::class, 'addCommunity'])->name('addCommunity');

Route::get('/detailCommunity/{manga_id}', [DetailCommunityController::class, 'detailCommunity'])->name('detailCommunity');
Route::get('/chat/{community_id}', [ChatController::class, 'viewChat'])->name('viewChat');
Route::post('/', [ChatController::class, 'addChat'])->name('addChat');
