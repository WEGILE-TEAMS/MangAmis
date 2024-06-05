<?php

namespace App\Http\Controllers;

use App\Services\MangaDexService;

class UpdatedMangaController extends Controller
{
    protected $mangaDexService;

    public function __construct(MangaDexService $mangaDexService)
    {
        $this->mangaDexService = $mangaDexService;
    }

    public function showUpdatedManga()
    {
        $manga = $this->mangaDexService->getUpdatedManga();

        if (isset($manga['error']) && $manga['error']) {
            return response()->json(['error' => $manga['message']], 500);
        }
        // dd($manga);
        return view('updatedManga', ['manga' => $manga['data']]);
    }
}
