<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MangaDexService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.mangadex.org',
        ]);
    }

    public function getUpdatedManga($limit)
    {
        try {
            $tagsResponse = $this->client->request('GET', '/manga/tag');
            $tags = json_decode($tagsResponse->getBody()->getContents(), true);
            $excluded_tag_names = ["Boys' Love"];
            $excluded_tag_ids = []; // Initialize the array before using it

            foreach ($tags['data'] as $tag) {
                if (in_array($tag['attributes']['name']['en'], $excluded_tag_names)) {
                    $excluded_tag_ids[] = $tag['id'];
                }
            }

            $response = $this->client->request('GET', '/manga', [
                'query' => [
                    'limit' => $limit,
                    'order' => [
                        'updatedAt' => 'desc'
                    ],
                    'includes' => ['chapter']
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'params' => [
                    "contentRating[]" => ['safe'],
                    "excludedTags[]" => $excluded_tag_ids,
                ],
            ]);

            $mangaData = json_decode($response->getBody()->getContents(), true);

            foreach ($mangaData['data'] as &$manga) {
                $latestChapter = $this->getLatestChapter($manga['id']);
                $manga['latestChapter'] = $latestChapter;
            }

            return $mangaData;
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getLatestChapter($mangaId)
    {
        try {
            $response = $this->client->request('GET', '/chapter', [
                'query' => [
                    'manga' => $mangaId,
                    'limit' => 1,
                    'order' => [
                        'publishAt' => 'desc'
                    ]
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            $chapterData = json_decode($response->getBody()->getContents(), true);
            return $chapterData['data'][0] ?? null;
        } catch (RequestException $e) {
            return null;
        }
    }
}
