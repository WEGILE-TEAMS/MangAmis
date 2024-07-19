<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $client = new Client(['verify' => false]);
        $query = $request->input('query');
        $limit = 10;
        $apiUrl = 'https://api.mangadex.org/manga';
        $baseImageUrl = "https://uploads.mangadex.org/covers/";


        $response = $client->request('GET', $apiUrl, [
            'query' => [
                'title' => $query,
                'limit' => $limit,
            ],
        ]);
        // dd($response);

        if ($response->getStatusCode() == 200) {
            $mangaList = json_decode($response->getBody()->getContents(), true)['data'];
            // dd($mangaList);
            $mangaCount = count($mangaList);
            $coverId = null;
            // dd($mangaCount);

            for($i=0;$i<$mangaCount;$i++){
                foreach ($mangaList[$i]['relationships'] as $relationship) {
                    if ($relationship['type'] === 'cover_art') {
                        $coverId = $relationship['id'];
                        break;
                    }
                }

                if (!$coverId) {
                    throw new Exception("Cover ID not found.");
                }
                $coverResponse = $client->get("https://api.mangadex.org/cover/{$coverId}", [
                    'headers' => [
                        'User-Agent' => 'YourAppName/1.0',
                        'Accept' => 'application/json',
                    ]
                ]);

                if ($coverResponse->getStatusCode() !== 200) {
                    throw new Exception("Failed to fetch cover details, status code: " . $coverResponse->getStatusCode());
                }

                $coverData = json_decode($coverResponse->getBody(), true);
                // dd($coverData);
                Log::info('Cover image response:', $coverData);
                // dd($coverData);
                $coverFileName = $coverData['data']['attributes']['fileName'];
                $mangaId = $mangaList[$i]['id'];

                $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;
                $combinedList[] = [
                    'id' => $mangaId,
                    'title' => $mangaList[$i]['attributes']['title']['en'] ?? 'No English title',
                    'description' => $mangaList[$i]['attributes']['description']['en'] ?? 'No English description',
                    'image' => route('proxy-image', ['url' => urlencode($coverUrl)]),
                ];
                // dd($combinedList);
            }
        }else {
            $combinedList = [];
        }

        return view('search', ['combinedList' => $combinedList, 'query' => $query]);
    }

    public function proxyImage(Request $request)
    {
        $client = new Client(['verify' => false]);
        $imageUrl = urldecode($request->input('url'));

        try {
            $response = $client->get($imageUrl, [
                'headers' => [
                    'User-Agent' => 'YourAppName/1.0',
                    'Accept' => 'image/jpeg,image/png',
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new Exception("Failed to fetch image, status code: " . $response->getStatusCode());
            }

            return response($response->getBody(), 200)
                ->header('Content-Type', $response->getHeader('Content-Type'));

        } catch (Exception $e) {
            Log::error('Error fetching image:', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
