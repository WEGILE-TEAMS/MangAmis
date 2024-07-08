<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MangaHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MangaHistoryController extends Controller
{
    public function saveMangaHistory(Request $request)
    {
        $validatedData = $request->validate([
            'manga_id' => 'required|string',
            'chapter_id' => 'required|string',
        ]);

        Log::info('Saving manga history', $validatedData);

        $history = MangaHistory::create([
            'user_id' => Auth::id(),
            'manga_id' => $validatedData['manga_id'],
            'chapter_id' => $validatedData['chapter_id'],
            'read_at' => now(),
        ]);

        Log::info('Manga history saved', ['history' => $history]);

        return response()->json(['success' => true]);
    }
}
