<?php
namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MangaController extends Controller
{
    // Get manga from api mangadex
    private function baseApiRequest($client) {
        try {
            $apiUrl = "https://api.mangadex.org/manga";
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

            return $responseData;
        
        } catch(Exception $errors) {
            Log::error('Error fetching manga data:', ['exception' => $errors]);
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    private function getAuthor($client, $data) {
        try {

            $relationships = $data['relationships'];
                
            // Get Author Id
            $authorId = null;
            foreach ($relationships as $relationship) {
                if ($relationship['type'] === 'author') {
                    $authorId = $relationship['id'];
                }
            }

            // Get Author name
            $author = 'https://api.mangadex.org/author/' . $authorId;
            $author = $client->get($author);
            $author = json_decode($author->getBody(), true);
            $author = $author['data']['attributes']['name'];

            return $author;
        
        } catch(Exception $errors) {
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    private function getCover($client, $data) {
        try {
            $baseImageUrl = "https://uploads.mangadex.org/covers/";

            
            $coverId = null;
            $relationships = $data['relationships'];
            foreach ($relationships as $relationship) {
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

            // getMangaId
            $mangaId = $data['id'];
            $coverData = json_decode($coverResponse->getBody(), true);
            Log::info('Cover image response:', $coverData);

            // Extract the cover file name
            $coverFileName = $coverData['data']['attributes']['fileName'];

            // Construct the cover image URL
            $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;

            return $coverUrl;
        } catch(Exception $errors) {
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    private function getGenre($data) {
        try {

            $tags = $data['attributes']['tags'];
            $genres = [];

            foreach ($tags as $tag) {
                $attributes = $tag['attributes'];
                if ($attributes['group'] === 'genre' || $attributes['group'] === 'theme') {
                    if (isset($attributes['name']['en'])) {
                        $genres[] = $attributes['name']['en'];
                    } else {
                        $genres[] = [];
                    }
                }
            }

            return $genres;

        } catch(Exception $errors) {
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    private function getChaptersFromMangaDex($mangaId)
    {
        $client = new Client();
        $response = $client->get("https://api.mangadex.org/manga/$mangaId/feed?limit=500&translatedLanguage[]=en");

        
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            $chapters = $data['data'];
            // dd($chapters);
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

    private function getSimilarManga()
    {
        try {
            $client = new Client(['verify' => false]); // Ensure SSL/TLS verification

            // Get manga
            $data = $this->baseApiRequest($client);
            $data = $data['data'];

            for ($x = 0; $x < 4; $x++) {
                $randomNumber = rand(0, 9);
                if (!isset($data[$randomNumber])) continue;
                
                // Get Author
                $author = $this->getAuthor($client, $data[$randomNumber]);

                // Get genres
                $genres = $this->getGenre($data[1]);
                
                // Get Cover
                $coverUrl = $this->getCover($client, $data[$randomNumber]);

                // Build final response
                $similar[] = [
                    "id" => $data[$randomNumber]['id'],
                    "title" => $data[$randomNumber]['attributes']['title']['en'] ?? 'N/A',
                    "desc" => $responseData['data'][$randomNumber]['attributes']['description']['en'] ?? 'No description available',
                    "cover_url" => $coverUrl,
                    "image" => route('proxy-image', ['url' => urldecode($coverUrl)]),
                    "author_name" => $author,
                    "genre" => $genres,
                ];
            }

            return $similar;
        } catch (Exception $e) {
            Log::error('Error fetching manga data:', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $client = new Client(['verify' => false]); // Ensure SSL/TLS verification

            // Get manga
            $data = $this->baseApiRequest($client);
            $data = $data['data'];

            for ($x = 0; $x < 4; $x++) {
                if (!isset($data[$x])) continue;
                
                // Get Author
                $author = $this->getAuthor($client, $data[$x]);

                // Get genres
                $genres = $this->getGenre($data[1]);
                
                // Get Cover
                $coverUrl = $this->getCover($client, $data[$x]);

                // Build final response
                $temp[] = [
                    "id" => $data[$x]['id'],
                    "title" => $data[$x]['attributes']['title']['en'] ?? 'N/A',
                    "desc" => $responseData['data'][$x]['attributes']['description']['en'] ?? 'No description available',
                    "cover_url" => $coverUrl,
                    "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
                    "author_name" => $author,
                    "genre" => $genres,
                ];
            }

            return view('home', compact('temp'));

        } catch(Exception $errors) {
            Log::error('Error fetching manga data:', ['exception' => $errors]);
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    public function detailManga(Request $request) {
        // Decode the parameters
        $id = $request->query('id');
        $title = $request->query('title');
        $author = $request->query('author');
        $desc = $request->query('desc');
        $genres = explode(',', $request->query('genres'));
        $coverUrl = $request->query('cover_url');
        
        $chapters = $this->getChaptersFromMangaDex($id);
        // dd($chapters);

        $similarManga = $this->getSimilarManga();

        $temp = [
            'title' => 'DetailManga',
            'manga_id' => $id,
            'manga_title' => $title,
            'manga_author' => $author,
            'manga_desc' => $desc,
            'genres' => $genres,
            'chapters' => $chapters,
            'image' => route('proxy-image', ['url' => urlencode($coverUrl)]),
            'similar' => $similarManga
        ];

        // dd($chapters);
        return view('detail_manga', compact('temp'));
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
