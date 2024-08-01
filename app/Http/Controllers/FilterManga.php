<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilterManga extends Controller
{
    public function filterManga($genre = 'all')
{
    $client = new Client(['verify' => false]);
    $baseApiUrl = "https://api.mangadex.org/manga";
    $baseImageUrl = "https://uploads.mangadex.org/covers/";

    // Build the API URL with optional genre filtering
    $apiUrl = $baseApiUrl;

    // Fetch manga data
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

    $mangaList = [];

    foreach ($responseData['data'] as $manga) {
        $relationships = $manga['relationships'];
        $coverId = null;

        foreach ($relationships as $relationship) {
            if ($relationship['type'] === 'cover_art') {
                $coverId = $relationship['id'];
                break;
            }
        }

        if (!$coverId) {
            continue; // Skip if no cover ID is found
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

        $mangaId = $manga['id'];
        $coverData = json_decode($coverResponse->getBody(), true);
        $coverFileName = $coverData['data']['attributes']['fileName'];
        $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;

        $tags = $manga['attributes']['tags'];
        $genres = [];

        foreach ($tags as $tag) {
            $attributes = $tag['attributes'];
            if ($attributes['group'] === 'genre') {
                if (isset($attributes['name']['en'])) {
                    $genres[] = $attributes['name']['en'];
                }
            }
        }

        // Only add manga to the list if it matches the selected genre
        if ($genre === 'all' || in_array($genre, $genres)) {
            $mangaList[] = [
                "id" => $mangaId,
                "title" => $manga['attributes']['title']['en'] ?? 'N/A',
                "desc" => $manga['attributes']['description']['en'] ?? 'No description available',
                "cover_id" => $coverId,
                "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
                "genres" => $genres,
            ];
        }
    }

    return $mangaList;
}

}
