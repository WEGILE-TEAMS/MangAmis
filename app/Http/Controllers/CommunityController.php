<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    public function community(Request $request)
    {
        $client = new Client(['verify' => false]);
        $query = $request->input('query');
        $apiUrl = 'https://api.mangadex.org/manga';
        $baseImageUrl = "https://uploads.mangadex.org/covers/";
        $combinedList=[];
        if (!empty($query)) {
        $response = $client->request('GET', $apiUrl, [
            'query' => [
                'title' => $query,
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
                // $exists = DB::table('communities')->where('manga_id', $mangaId)->exists();
                $count = DB::table('communities')->where('manga_id', $mangaId)->count();
                $coverUrl = $baseImageUrl . $mangaId . '/' . $coverFileName;
                $combinedList[] = [
                    'id' => $mangaId,
                    'title' => $mangaList[$i]['attributes']['title']['en'] ?? 'No English title',
                    'description' => $mangaList[$i]['attributes']['description']['en'] ?? 'No English description',
                    'image' => route('proxy-image', ['url' => urlencode($coverUrl)]),
                    // 'exists' => $exists,
                    'count' => $count
                ];
            }
            $communities = "";
        }else {
            $combinedList = [];
        }


    }

    if(empty($query)) {
        $communities = $this->viewCommunity();
        // dd($communities);
    }

    return view('community', ['combinedList' => $combinedList, 'query' => $query, 'communities' => $communities]);

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

    public function addCommunity(Request $request) {
        $credentials = $request->validate([
            'manga_id' => 'required',
            'content' => 'required',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mp3,mov,avi|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $credentials['image'] = $filePath;
            $credentials['image_name'] = $fileName;
        }

        $credentials['user_id'] = Auth::user()->id;

        $community = Community::create($credentials);

        return redirect()->route('detailCommunity', ['manga_id' => $credentials['manga_id']])
                         ->with('success', 'Berhasil nambah post');
    }



    public function viewCommunity(){
        $communities=Community::all();
        $count = $communities->count();
        // dd($count);
        $uniqueMangaIds = $communities->unique('manga_id');
        if($count > 0) {

            foreach($uniqueMangaIds as $community) {
                $manga_id = $community->manga_id;
                $count = DB::table('communities')->where('manga_id', $manga_id)->count();
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
                    "count" => $count
                ];
            }
        } else {
            $temp = [];
        }
        return $temp;
    }
}
