<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookmarkController extends Controller
{
    public function saveBookmark(Request $request)
    {
        $request->validate([
            'manga_id' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        $bookmark = new Bookmark;
        $bookmark->manga_id = $request->manga_id;
        $bookmark->user_id = $request->user_id;
        $bookmark->save();

        return response()->json(['success' => 'Manga bookmarked successfully!']);
    }
}
