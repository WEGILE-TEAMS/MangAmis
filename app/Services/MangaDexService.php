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

    public function getUpdatedManga($limit = 10)
    {
        try {
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

    protected function getLatestChapter($mangaId)
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
