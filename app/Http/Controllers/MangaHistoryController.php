<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MangaHistory;
use Exception;
use GuzzleHttp\Client;
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

    public function show(){
        $userId = Auth::id();
        $histories = MangaHistory::where('user_id', $userId)->get();
        // dd($histories);
        foreach ($histories as $history) {
            $manga_id = $history->manga_id;
            $client = new Client();

            $mangaResponse = $client->get("https://api.mangadex.org/manga/{$manga_id}");
            $mangaData = json_decode($mangaResponse->getBody(), true);

            $coverId = null;
            foreach ($mangaData['data']['relationships'] as $relationship) {
                if ($relationship['type'] === 'cover_art') {
                    $coverId = $relationship['id'];
                    break;
                }
            }

            $authorId = null;
            foreach ($mangaData['data']['relationships'] as $relationship) {
                if ($relationship['type'] === 'author') {
                    $authorId = $relationship['id'];
                    break;
                }
            }

            if ($coverId) {
                $coverResponse = $client->get("https://api.mangadex.org/cover/{$coverId}");
                $coverData = json_decode($coverResponse->getBody(), true);
                $coverFileName = $coverData['data']['attributes']['fileName'];

                // URL dasar untuk gambar sampul
                $coverUrl = "https://uploads.mangadex.org/covers/{$manga_id}/{$coverFileName}";
            }

            if ($authorId) {
                $getAuthor = 'https://api.mangadex.org/author/' . $authorId;
                $author = $client->get($getAuthor);
                $author = json_decode($author->getBody(), true);
            }

            $title = $mangaData['data']['attributes']['title']['en'] ?? 'N/A';

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

            $temp[] = [
                'id' => $manga_id,
                'title' => $title,
                "desc" => $mangaData['data']['attributes']['description']['en'] ?? 'No description available',
                "cover_id" => $coverId,
                "image" => route('proxy-image', ['url' => urlencode($coverUrl)]),
                "author_id" => $authorId,
                "author_name" => $author['data']['attributes']['name'],
                "genre" => $genres,
            ];

            // dd($temp);

        }
        return view('history', ['histories' => $temp]);
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
