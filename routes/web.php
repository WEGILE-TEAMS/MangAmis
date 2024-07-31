<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DetailCommunityController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController2;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\RegisterController;
use GuzzleHttp\Client as HttpClient;
use App\Http\Controllers\DetailMangaController;
use App\Http\Controllers\MangaHistoryController;
use Illuminate\Routing\Route as RoutingRoute;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UpdatedMangaController;
use App\Http\Controllers\RandomMangaController;
use App\Http\Controllers\ViewCommunityController;

Route::get('/', function () {
    return view('login');
});

Route::get('/home', [MangaController::class, 'index'])->name('home')->middleware('Login');
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/testing', function () {
    return view('home_front');
});
// Route::get('/home', [MangaController::class, 'index']);

Route::get('/randomManga', [MangaController::class, 'randomManga'])->name('randomManga')->middleware('Login');
Route::get('/detailManga', [MangaController::class, 'detailManga'])->name('detailManga')->middleware('Login');
Route::get('/read-manga/{mangaTitle}/{chapterId}', [MangaController::class, 'readManga'])->name('read.manga')->middleware('Login');

Route::get('/proxy-image', [MangaController2::class, 'proxyImage'])->name('proxy-image')->middleware('Login');

Route::post('/save-manga-history', [MangaHistoryController::class, 'saveMangaHistory'])->middleware('Login');
Route::post('/save-bookmark', [BookmarkController::class, 'saveBookmark'])->middleware('Login');

Route::get('/history', [MangaHistoryController::class, 'show'])->middleware('Login');

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);


Route::get('/updated-manga', [
    UpdatedMangaController::class, 'showUpdatedManga'
])->middleware('Login');

Route::get('/proxy-image', [MangaController::class, 'proxyImage'])->name('proxy-image');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('Login');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('Login');
Route::get('/community', [CommunityController::class, 'community'])->name('community')->middleware('Login');
Route::get('/detailCommunity', [CommunityController::class, 'community'])->name('community')->middleware('Login');
Route::post('/addCommunity', [CommunityController::class, 'addCommunity'])->name('addCommunity')->middleware('Login');

Route::get('/detailCommunity/{manga_id}', [DetailCommunityController::class, 'detailCommunity'])->name('detailCommunity')->middleware('Login');
Route::get('/chat/{community_id}', [ChatController::class, 'viewChat'])->name('viewChat')->middleware('Login');
Route::post('/', [ChatController::class, 'addChat'])->name('addChat')->middleware('Login');
Route::delete('/chat/{chat_id}/{community_id}', [ChatController::class,'destroy'])
->name('chat.destroy')->middleware('Login');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('Login');

