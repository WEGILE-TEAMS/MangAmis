<?php
namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MangaController extends Controller
{
    public function index()
    {
        $client = new Client(['verify' => false]); // Ensure SSL/TLS verification
        $apiUrl = "https://api.mangadex.org/manga?title=Gachiakuta";
        $baseImageUrl = "https://uploads.mangadex.org/covers/";

    try {
        // Make the API request to fetch manga list
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
        // dd($responseData);
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

            // Find the cover relationship in the relationships array
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

            // Make a second API request to fetch the cover image details
            $coverResponse = $client->get("https://api.mangadex.org/cover/{$coverId}", [
                'headers' => [
                    'User-Agent' => 'YourAppName/1.0',
                    'Accept' => 'application/json',
                ]
            ]);

            if ($coverResponse->getStatusCode() !== 200) {
                throw new Exception("Failed to fetch cover details, status code: " . $coverResponse->getStatusCode());
            }
            // dd($coverResponse);
            $mangaId = $responseData['data'][0]['id'];

            $coverData = json_decode($coverResponse->getBody(), true);
            Log::info('Cover image response:', $coverData);
            // dd($coverData);
            // Extract the cover file name
            $coverFileName = $coverData['data']['attributes']['fileName'];

            // Construct the cover image URL
            $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;
            $temp[] = [
                "id" => $mangaId,
                "title" => $responseData['data'][0]['attributes']['title']['en'] ?? 'N/A',
                "desc" => $responseData['data'][0]['attributes']['description']['en'] ?? 'No description available',
                "cover_id" => $coverId,
                "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
            ];
        // dd($temp);

            return view('home', compact('temp'));
        } catch (Exception $e) {
            Log::error('Error fetching manga data:', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
