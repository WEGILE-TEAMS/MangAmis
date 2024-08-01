<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $bookmarks = $this->showBookmark();
        return view('profile', ['user' => $user, 'bookmarks' => $bookmarks]);
    }

    public function showBookmark(){
        $userId = Auth::id();
        $bookmarks = Bookmark::where('user_id', $userId)->get();
        // dd($histories);
        foreach ($bookmarks as $bookmark) {
            $manga_id = $bookmark->manga_id;
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

            $image = $this->getImageCover($manga_id, $coverId);
            // dd($image);
            $temp[] = [
                'id' => $manga_id,
                'title' => $title,
                "desc" => $mangaData['data']['attributes']['description']['en'] ?? 'No description available',
                "cover_id" => $coverId,
                "image" => $image,
                "author_id" => $authorId,
                "author_name" => $author['data']['attributes']['name'],
                "genre" => $genres,
            ];

            // dd($temp);

        }
        if(count($bookmarks) == 0) $temp = [];
        return $temp;
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

    public function update(Request $request)
    {
        $user=Auth::user();
        $request->validate([
            'username' => 'nullable|string|max:255',
            'user_email' => 'unique:users|nullable|email|max:255',
            'user_password' => 'nullable|string|min:5',
        ]);

        if ($request->filled('username') && $request->username != $user->username) {
            $user->username = $request->username;
        }else{
            $user->username=$user->username;
        }

        if ($request->filled('user_email') && $request->user_email != $user->user_email) {
            $user->user_email = $request->user_email;
        }else{
            $user->user_email=$user->user_email;
        }
        if (!empty($request->user_password)) {
            $user->user_password = Hash::make($request->user_password);
        }

        $user->save();
        
        return redirect()->back()->with('success','User Profile Berhasil di Update!');

    }
}
