<?php
namespace App\Http\Controllers;

use App\Models\MangaHistory;
use App\Services\MangaDexService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MangaController extends Controller
{
    protected $mangaDexService;

    public function __construct(MangaDexService $mangaDexService)
    {
        $this->mangaDexService = $mangaDexService;
    }

    // Get manga from api mangadex
    private function baseApiRequest($client, $apiUrl) 
    {
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

            return $responseData;
        
        } catch(Exception $errors) {
            Log::error('Error fetching manga data:', ['exception' => $errors]);
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    private function getAuthor($client, $data) 
    {
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

    private function getCover($client, $data) 
    {
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

    private function getGenre($data) 
    {
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
                    return $chapterB <=> $chapterA;
                }

                return $volumeB <=> $volumeA;
            });

            return $chapters;
        }

        return [];
    }

    private function getSimilarManga()
    {
        try {
            $client = new Client(['verify' => false]); // Ensure SSL/TLS verification
            $apiUrl = "https://api.mangadex.org/manga";

            // Get manga
            $data = $this->baseApiRequest($client, $apiUrl);
            $data = $data['data'];
            // dd($data);

            $numbers = [];
            for ($x = 0; $x < 4; $x++) {
                do {
                    $randomNumber = rand(0, 9);
                } while (in_array($randomNumber, $numbers)); // Ulangi jika angka sudah ada dalam array
                
                $numbers[] = $randomNumber;
                if (!isset($data[$randomNumber])) continue;
                
                // Get Author
                $author = $this->getAuthor($client, $data[$randomNumber]);

                // Get genres
                $genres = $this->getGenre($data[1]);
                
                // Get Cover
                $coverUrl = $this->getCover($client, $data[$randomNumber]);

                $chapters = $this->mangaDexService->getLatestChapter($data[$randomNumber]['id']);
                // dd($chapters);

                // Build final response
                $similar[] = [
                    "id" => $data[$randomNumber]['id'],
                    "title" => $data[$randomNumber]['attributes']['title']['en'] ?? 'N/A',
                    "desc" => $data[$randomNumber]['attributes']['description']['en'] ?? 'No description available',
                    "cover_url" => $coverUrl,
                    "image" => route('proxy-image', ['url' => urldecode($coverUrl)]),
                    "author_name" => $author,
                    "genre" => $genres,
                    "chapter_number" => $chapters['attributes']['chapter'],
                    "chapter_title" => $chapters['attributes']['title'],
                ];
            }

            return $similar;
        } catch (Exception $e) {
            Log::error('Error fetching manga data:', ['exception' => $e]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getHistory() {
        $userId = Auth::id();
        $histories = MangaHistory::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        $historyData = [];

        foreach ($histories as $history) {
            $manga_id = $history->manga_id;
            $client = new Client();

            try {
                $mangaResponse = $client->get("https://api.mangadex.org/manga/{$manga_id}");
                $mangaData = json_decode($mangaResponse->getBody(), true);
            } catch (\Exception $e) {
                continue; // Skip this history if there's an error
            }

            $coverId = null;
            $authorId = null;
            
            foreach ($mangaData['data']['relationships'] as $relationship) {
                if ($relationship['type'] === 'cover_art') {
                    $coverId = $relationship['id'];
                }
                if ($relationship['type'] === 'author') {
                    $authorId = $relationship['id'];
                }
            }

            $coverUrl = null;
            if ($coverId) {
                try {
                    $coverResponse = $client->get("https://api.mangadex.org/cover/{$coverId}");
                    $coverData = json_decode($coverResponse->getBody(), true);
                    $coverFileName = $coverData['data']['attributes']['fileName'];
                    $coverUrl = "https://uploads.mangadex.org/covers/{$manga_id}/{$coverFileName}";
                } catch (\Exception $e) {
                    // Handle error if needed
                }
            }

            $authorName = null;
            if ($authorId) {
                try {
                    $authorResponse = $client->get("https://api.mangadex.org/author/{$authorId}");
                    $authorData = json_decode($authorResponse->getBody(), true);
                    $authorName = $authorData['data']['attributes']['name'];
                } catch (\Exception $e) {
                    // Handle error if needed
                }
            }

            $title = $mangaData['data']['attributes']['title']['en'] ?? 'N/A';
            $description = $mangaData['data']['attributes']['description']['en'] ?? 'No description available';

            $tags = $mangaData['data']['attributes']['tags'];
            $genres = [];

            foreach ($tags as $tag) {
                $attributes = $tag['attributes'];
                if ($attributes['group'] === 'genre' || $attributes['group'] === 'theme') {
                    if (isset($attributes['name']['en'])) {
                        $genres[] = $attributes['name']['en'];
                    }
                }
            }

            $historyData[] = [
                'id' => $manga_id,
                'title' => $title,
                'desc' => $description,
                'cover_id' => $coverId,
                'image' => $coverUrl ? route('proxy-image', ['url' => urlencode($coverUrl)]) : null,
                'author_id' => $authorId,
                'author_name' => $authorName,
                'genre' => $genres,
                'cover_url' => $this->getCover($client, $mangaData['data'])
            ];
        }

        $page = request()->get('page', 1); // Get the current page or default to 1
        $perPage = 4; // Number of items per page

        $paginatedData = new LengthAwarePaginator(
            array_slice($historyData, ($page - 1) * $perPage, $perPage),
            count($historyData),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginatedData;
    }

    public function index()
    {
        try {
            $client = new Client(['verify' => false]); // Ensure SSL/TLS verification
            $apiUrl = "https://api.mangadex.org/manga";
            $apiUrlGachiakuta = "https://api.mangadex.org/manga?title=Gachiakuta";

            // Get manga
            // $data = $this->baseApiRequest($client, $apiUrl);
            // $data = $data['data'];

            $data = $this->mangaDexService->getUpdatedManga(4);

            if (isset($data['error']) && $data['error']) {
                return response()->json(['error' => $data['message']], 500);
            }

            
            $data = $data['data'];
            // dd($data);
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
                    "desc" => $data[$x]['attributes']['description']['en'] ?? 'No description available',
                    "cover_url" => $coverUrl,
                    "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
                    "author_name" => $author,
                    "genre" => $genres,
                    "chapter_title" => $data[$x]["latestChapter"]['attributes']['title'] ?? 'N/A',
                    "chapter_number" => $data[$x]["latestChapter"]['attributes']['chapter'] ?? 'N/A'
                ];
            }

            $history = $this->getHistory();

            $dataTopManga = $this->baseApiRequest($client, $apiUrlGachiakuta);
            $dataTopManga = $dataTopManga['data'][0];


            $topManga = [
                "id" => $dataTopManga['id'],
                "title" => $dataTopManga['attributes']['title']['en'] ?? 'N/A',
                "cover_url" => $this->getCover($client, $dataTopManga),
                "desc" => $dataTopManga['attributes']['description']['en'] ?? 'No description available',
                "image" => route('proxy-image', ['url' => urlencode($this->getCover($client, $dataTopManga))]),
                "author_name" => $this->getAuthor($client, $dataTopManga),
                "genre" => $this->getGenre($dataTopManga)
            ];

            return view('home', compact('temp', 'topManga', 'history'));

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

        $similarManga = $this->getSimilarManga();

        $temp = [
            'title' => $title,
            'manga_id' => $id,
            'manga_title' => $title,
            'manga_author' => $author,
            'manga_desc' => $desc,
            'genres' => $genres,
            'chapters' => $chapters,
            'image' => route('proxy-image', ['url' => urlencode($coverUrl)]),
            'similar' => $similarManga
        ];

        // dd($temp);
        return view('detail_manga', compact('temp'));
    }
    
    public function readManga($mangaTitle, $chapterId) {
        try {
            $data = $this->getChapterImage($chapterId);
            $chapter = $data['chapter'];
            $next = $data['nextChapterId'];
            $prev = $data['previousChapterId'];
            $chapterDetails = $data['chapterDetails'];

            $images = [];
            foreach($chapter['chapter']['data'] as $page) {
                $urlTemp = $chapter['baseUrl'].'/data/'.$chapter['chapter']['hash'].'/'.$page;
                $images[] = route('proxy-image', ['url' => urlencode($urlTemp)]);
            }

            // Get detail manga
            $client = new Client(['verify' => false]); // Ensure SSL/TLS verification
            $apiUrl = "https://api.mangadex.org/manga?title={$mangaTitle}";
            
            $detailManga = $this->baseApiRequest($client, $apiUrl);
            $detailManga = $detailManga['data'][0];


            $detailManga = [
                "id" => $detailManga['id'],
                "title" => $detailManga['attributes']['title']['en'] ?? 'N/A',
                "cover_url" => $this->getCover($client, $detailManga),
                "desc" => $detailManga['attributes']['description']['en'] ?? 'No description available',
                "image" => route('proxy-image', ['url' => urlencode($this->getCover($client, $detailManga))]),
                "author_name" => $this->getAuthor($client, $detailManga),
                "genre" => $this->getGenre($detailManga)
            ];

            // get 4 latest chapter
            $chapters = $this->getChaptersFromMangaDex($detailManga['id']);

            return view('read_manga', compact('images', 'next', 'prev', 'chapterDetails', 'detailManga', 'chapters'));
        } catch (Exception $errors) {
            return response()->json(['error' => $errors->getMessage()], 500);
        }
    }

    private function getChapterImage($chapterId) {
        try {
            $client = new Client(['verify' => false]); // Ensure SSL/TLS verification
            $apiUrl = "https://api.mangadex.org/chapter/{$chapterId}"; 
            
            $chapterDetailsResponse = $this->baseApiRequest($client, $apiUrl);
            $chapterDetails = $chapterDetailsResponse['data'];

            $mangaId = null;
            foreach ($chapterDetails['relationships'] as $relationship) {
                if ($relationship['type'] === 'manga') {
                    $mangaId = $relationship['id'];
                    break;
                }
            }

            $chapters = $this->getChaptersFromMangaDex($mangaId);
            // dd($chapters);

            // Find next and previous chapters
            $nextChapterId = null;
            $previousChapterId = null;
            $chapterDetails = [];
            foreach ($chapters as $index => $ch) {
                if ($ch['id'] == $chapterId) {
                    $chapterDetails = [
                        "title" => $ch['attributes']['title'],
                        "number" => $ch['attributes']['chapter'],
                    ];
                    if (isset($chapters[$index + 1])) {
                        $previousChapterId = $chapters[$index + 1]['id'];
                    }
                    if (isset($chapters[$index - 1])) {
                        $nextChapterId = $chapters[$index - 1]['id'];
                    }
                    break;
                }
            }

            // Fetch the At-Home server details for the chapter
            $atHomeResponse = Http::get("https://api.mangadex.org/at-home/server/{$chapterId}");
            $chapter = $atHomeResponse->json();
            
            $data = [
                "chapter" => $chapter,
                "nextChapterId" => $nextChapterId,
                "previousChapterId" => $previousChapterId,
                "chapterDetails" => $chapterDetails,
            ];

            return $data;

        } catch (Exception $errors) {
            return response()->json(['error' => $errors->getMessage()], 500);
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
