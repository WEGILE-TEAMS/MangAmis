<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log; // Import the Log facade
use Exception;

class MangaController extends Controller
{
    public function index()
    {
        try {
            $temp = $this->fetchMangaList();
            return view('home', compact('temp'));
        } catch (Exception $e) {
            Log::error('Error fetching manga data:', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchMangaList()
    {
        $client = new Client(['verify' => false]);
        $apiUrl = "https://api.mangadex.org/manga";
        $baseImageUrl = "https://uploads.mangadex.org/covers/";

        $response = $client->get($apiUrl, [
            'headers' => [
                'User-Agent' => 'YourAppName/1.0',
                'Accept' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to fetch manga list, status code: " . $response->getStatusCode());
        }

        $responseData = json_decode($response->getBody(), true);
        Log::info('Manga list response:', $responseData);

        $temp = [];

        for ($x = 0; $x < 4; $x++) {
            if (!isset($responseData['data'][$x])) {
                continue;
            }

            $relationships = $responseData['data'][$x]['relationships'];
            $authorId = null;

            foreach ($relationships as $relationship) {
                if ($relationship['type'] === 'author') {
                    $authorId = $relationship['id'];
                }
            }

            $tags = $responseData['data'][$x]['attributes']['tags'];
            $genres = [];

            foreach ($tags as $tag) {
                $attributes = $tag['attributes'];
                if ($attributes['group'] === 'genre' || $attributes['group'] === 'theme') {
                    if (isset($attributes['name']['en'])) {
                        $genres[] = $attributes['name']['en'];
                    }
                }
            }

            $coverId = null;
            foreach ($responseData['data'][$x]['relationships'] as $relationship) {
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

            $mangaId = $responseData['data'][$x]['id'];

            $coverData = json_decode($coverResponse->getBody(), true);
            Log::info('Cover image response:', $coverData);

            $coverFileName = $coverData['data']['attributes']['fileName'];
            $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;

            if ($authorId) {
                $getAuthor = 'https://api.mangadex.org/author/' . $authorId;
                $author = $client->get($getAuthor);
                $author = json_decode($author->getBody(), true);

                if (isset($author['data']['attributes']['name'])) {
                    $temp[] = [
                        "id" => $mangaId,
                        "title" => $responseData['data'][$x]['attributes']['title']['en'] ?? 'N/A',
                        "desc" => $responseData['data'][$x]['attributes']['description']['en'] ?? 'No description available',
                        "cover_id" => $coverId,
                        "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
                        "author_id" => $authorId,
                        "author_name" => $author['data']['attributes']['name'],
                        "genre" => $genres,
                    ];
                }
            }
        }
        return $temp;
    }

    public function topManga()
    {
        $client = new Client(['verify' => false]);
        $apiUrl = "https://api.mangadex.org/manga?title=Gachiakuta";
        $baseImageUrl = "https://uploads.mangadex.org/covers/";

        $response = $client->get($apiUrl, [
            'headers' => [
                'User-Agent' => 'YourAppName/1.0',
                'Accept' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Failed to fetch manga list, status code: " . $response->getStatusCode());
        }

        $responseData = json_decode($response->getBody(), true);
        Log::info('Manga list response:', $responseData);

        $relationships = $responseData['data'][0]['relationships'];
        $authorId = null;

        foreach ($relationships as $relationship) {
            if ($relationship['type'] === 'author') {
                $authorId = $relationship['id'];
            }
        }

        $tags = $responseData['data'][0]['attributes']['tags'];
        $genres = [];

        foreach ($tags as $tag) {
            $attributes = $tag['attributes'];
            if ($attributes['group'] === 'genre') {
                if (isset($attributes['name']['en'])) {
                    $genres[] = $attributes['name']['en'];
                }
            }
        }

        $coverId = null;
        foreach ($responseData['data'][0]['relationships'] as $relationship) {
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

        $mangaId = $responseData['data'][0]['id'];
        $coverData = json_decode($coverResponse->getBody(), true);
        Log::info('Cover image response:', $coverData);

        $coverFileName = $coverData['data']['attributes']['fileName'];
        $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;

        $topManga = [
            "id" => $mangaId,
            "title" => $responseData['data'][0]['attributes']['title']['en'] ?? 'N/A',
            "desc" => $responseData['data'][0]['attributes']['description']['en'] ?? 'No description available',
            "cover_id" => $coverId,
            "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
        ];

        return $topManga;
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
                ->header('Content-Type', $response->getHeader('Content-Type')[0]);

        } catch (Exception $e) {
            Log::error('Error fetching image:', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
