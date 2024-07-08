<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class DetailMangaController extends Controller
{
    public function index($id, $title, $author, $desc, $genres, $coverId)
    {
        // Decode the parameters
        $title = urldecode($title);
        $author = urldecode($author);
        $desc = urldecode($desc);
        $genres = explode(',', urldecode($genres)); // Convert back to an array
        // dd(compact('id', 'title', 'author', 'desc', 'genres', 'image'));

        $chapters = $this->getChaptersFromMangaDex($id);
        // dd($chapters);

        $image = $this->getImageCover($id, $coverId);
        $similarManga = $this->getSimilarManga();

        return view('detailManga', [
            'title' => 'DetailManga',
            'manga_id' => $id,
            'manga_title' => $title,
            'manga_author' => $author,
            'manga_desc' => $desc,
            'genres' => $genres,
            'chapters' => $chapters,
            'image' => $image,
            'similar' => $similarManga
        ]);
    }

    private function getChaptersFromMangaDex($mangaId)
    {
        $client = new Client();
        $response = $client->get("https://api.mangadex.org/manga/$mangaId/feed?limit=500&translatedLanguage[]=en");

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            $chapters = $data['data'];
            // dd($chapters);
            // Sort chapters by volume and chapter number
            usort($chapters, function($a, $b) {
                $volumeA = isset($a['attributes']['volume']) ? (float)$a['attributes']['volume'] : 0;
                $volumeB = isset($b['attributes']['volume']) ? (float)$b['attributes']['volume'] : 0;

                if ($volumeA == $volumeB) {
                    $chapterA = isset($a['attributes']['chapter']) ? (float)$a['attributes']['chapter'] : 0;
                    $chapterB = isset($b['attributes']['chapter']) ? (float)$b['attributes']['chapter'] : 0;
                    return $chapterA <=> $chapterB;
                }

                return $volumeA <=> $volumeB;
            });

            return $chapters;
        }

        return [];
    }

    private function getImageCover($id, $cover_id) {
        $client = new Client(['verify' => false]); // Ensure SSL/TLS verification
        $apiUrl = "https://api.mangadex.org/manga";
        $baseImageUrl = "https://uploads.mangadex.org/covers/";
        $response = $client->get($apiUrl, [
            'headers' => [
                'User-Agent' => 'YourAppName/1.0',
                'Accept' => 'application/json',
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Make a second API request to fetch the cover image details
        $coverResponse = $client->get("https://api.mangadex.org/cover/{$cover_id}", [
            'headers' => [
                'User-Agent' => 'YourAppName/1.0',
                'Accept' => 'application/json',
            ]
        ]);

        $coverData = json_decode($coverResponse->getBody(), true);

        // Extract the cover file name
        $coverFileName = $coverData['data']['attributes']['fileName'];

        // Construct the cover image URL
        $coverUrl = $baseImageUrl . $id . '/' . $coverFileName;

        $image = route('proxy-image', ['url' => urlencode($coverUrl)]);

        return $image;
    }

    public function getSimilarManga()
{
    $client = new Client(['verify' => false]);
    $apiUrl = "https://api.mangadex.org/manga";
    $baseImageUrl = "https://uploads.mangadex.org/covers/";
    $similar = [];

    try {
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
            $randomNumber = rand(0, 9);
            if (!isset($responseData['data'][$randomNumber])) {
                continue;
            }

            if (in_array($randomNumber, $temp)) {
                continue;
            }
            $temp[] = $randomNumber;

            $mangaData = $responseData['data'][$randomNumber];
            $relationships = $mangaData['relationships'];
            $authorId = null;

            foreach ($relationships as $relationship) {
                if ($relationship['type'] === 'author') {
                    $authorId = $relationship['id'];
                }
            }

            $tags = $mangaData['attributes']['tags'];
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
            foreach ($relationships as $relationship) {
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
            Log::info('Cover image response:', $coverData);

            $coverFileName = $coverData['data']['attributes']['fileName'];
            $coverUrl = $baseImageUrl . $mangaData['id'] . '/' . $coverFileName;

            if ($authorId) {
                $getAuthor = 'https://api.mangadex.org/author/' . $authorId;
                $authorResponse = $client->get($getAuthor);
                $author = json_decode($authorResponse->getBody(), true);

                if (isset($author['data']['attributes']['name'])) {
                    $similar[] = [
                        "id" => $mangaData['id'],
                        "title" => $mangaData['attributes']['title']['en'] ?? 'N/A',
                        "desc" => $mangaData['attributes']['description']['en'] ?? 'No description available',
                        "cover_id" => $coverId,
                        "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
                        "author_id" => $authorId,
                        "author_name" => $author['data']['attributes']['name'],
                        "genre" => $genres,
                    ];
                }
            }
        }

        return $similar;
    } catch (Exception $e) {
        Log::error('Error fetching manga data:', ['exception' => $e]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function proxyImage(Request $request)
    {
        $client = new Client(['verify' => false]);
        $imageUrl = urldecode($request->input('url'));

        $response = $client->get($imageUrl, [
            'headers' => [
                'User-Agent' => 'YourAppName/1.0',
                'Accept' => 'image/jpeg,image/png',
            ]
        ]);

        return response($response->getBody(), 200)
        ->header('Content-Type', $response->getHeader('Content-Type')[0]);
    }
}
