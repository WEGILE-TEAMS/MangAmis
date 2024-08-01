<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class RandomMangaController extends Controller
{
    public function show() {
        $client = new Client();
        $mangaList = [];
        $limit = 100;

        for ($i = 0; $i < 5; $i++) {
            $randomOffset = rand(0, 10000 - $limit);
            $response = $client->get('https://api.mangadex.org/manga', [
                'query' => [
                    'limit' => $limit,
                    'offset' => $randomOffset
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            $mangaList = array_merge($mangaList, $data['data']);
        }

        $randomManga = $mangaList[array_rand($mangaList)];

        dd($randomManga);
    }
}
