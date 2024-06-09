<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class MangaController extends Controller
{
    public function index()
    {
        $client = new Client();
        $temp = [];

        $apiUrl = "https://api.mangadex.org/manga";
        $response = $client->get($apiUrl);
        $response = json_decode($response->getBody(), true);

        try {
            for ($x = 0; $x < 4; $x++) {
                if (!isset($response['data'][$x])) {
                    continue;
                }

                $relationships = $response['data'][$x]['relationships'];
                $coverId = null;
                $authorId = null;

                foreach ($relationships as $relationship) {
                    if ($relationship['type'] === 'cover_art') {
                        $coverId = $relationship['id'];
                    }
                    if ($relationship['type'] === 'author') {
                        $authorId = $relationship['id'];
                    }
                }

                if ($coverId && $authorId) {
                    $getCover = 'https://api.mangadex.org/cover/' . $coverId;
                    $cover = $client->get($getCover);
                    $cover = json_decode($cover->getBody(), true);

                    $getAuthor = 'https://api.mangadex.org/author/' . $authorId;
                    $author = $client->get($getAuthor);
                    $author = json_decode($author->getBody(), true);

                    if (isset($cover['data']['attributes']['fileName']) && isset($author['data']['attributes']['name'])) {
                        $temp[] = [
                            "id" => $response['data'][$x]['id'],
                            "title" => $response['data'][$x]['attributes']['title']['en'] ?? 'N/A',
                            "desc" => $response['data'][$x]['attributes']['description']['en'] ?? 'No description available',
                            "cover_id" => $coverId,
                            "cover_filename" => $cover['data']['attributes']['fileName'],
                            "cover_src" => "https://uploads.mangadex.org/covers/"."{$response['data'][$x]['id']}/"."{$cover['data']['attributes']['fileName']}",
                            "author_id" => $authorId,
                            "author_name" => $author['data']['attributes']['name'],
                        ];
                    }
                }
            }
            // dd($temp);
            return view('home', compact('temp'));
        } catch (Exception $e) {
            Log::error('Error fetching manga data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
