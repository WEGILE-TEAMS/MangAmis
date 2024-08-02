<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DetailCommunityController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\MangaHistoryController;
use App\Http\Controllers\ProfileController;

// Auth
Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('Login');
// End of Auth

Route::get('/home', [MangaController::class, 'index'])->name('home')->middleware('Login');

// Manga Service
Route::get('/manga', function () {
    return view('manga');
})->name('manga');
Route::get('/randomManga', [MangaController::class, 'randomManga'])->name('randomManga')->middleware('Login');
Route::get('/detailManga', [MangaController::class, 'detailManga'])->name('detailManga')->middleware('Login');
Route::get('/read-manga/{mangaTitle}/{chapterId}', [MangaController::class, 'readManga'])->name('read.manga')->middleware('Login');
Route::get('/proxy-image', [MangaController::class, 'proxyImage'])->name('proxy-image');
Route::post('/save-manga-history', [MangaHistoryController::class, 'saveMangaHistory'])->middleware('Login');
Route::post('/save-bookmark', [BookmarkController::class, 'saveBookmark'])->middleware('Login');
// ENd of Manga Service

// Profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('Login');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('Login');
// End of Profile

// Community
Route::get('/community', [CommunityController::class, 'community'])->name('community')->middleware('Login');
// Route::get('/detailCommunity', [CommunityController::class, 'community'])->name('community')->middleware('Login');
Route::get('/detailCommunity/{manga_id}', [DetailCommunityController::class, 'detailCommunity'])->name('detailCommunity')->middleware('Login');
Route::get('/chat/{community_id}', [ChatController::class, 'viewChat'])->name('viewChat')->middleware('Login');

Route::post('/addCommunity', [CommunityController::class, 'addCommunity'])->name('addCommunity')->middleware('Login');
Route::post('/', [ChatController::class, 'addChat'])->name('addChat')->middleware('Login');

Route::delete('/chat/{chat_id}/{community_id}', [ChatController::class,'destroy'])
->name('chat.destroy')->middleware('Login');
// End of Community

