<?php

namespace App\Services\ArticlesProviders;

use App\Enums\NewsProvidersEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuardianAPIService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.the_guardian.base_uri');
        $this->apiKey = config('services.the_guardian.api_key');
    }

    public function fetchArticles($queryParams = [], $pagination = [])
    {
        try {
            $category=strtolower($queryParams['category']) ?? '';

            // Include pagination parameters
            $queryParams = array_merge($queryParams, [
                'api-key' => $this->apiKey,
                'q' => $category,
                'page' => $pagination['page'] ?? 1,
                'page-size' => $pagination['pageSize'] ?? 10,
            ]);

            $response = Http::get($this->baseUrl . 'search', $queryParams);

            if ($response->ok()) {
                $data = json_decode($response->body())->response;
                return [
                    'articles' => $this->mapResults($response->json('response')['results']),
                    'total' => $data->total ?? 0,
                    'currentPage' => $pagination['page'] ?? 1,
                    'perPage' => $pagination['pageSize'] ?? 10,
                ];
            }

            Log::warning('GuardianAPI response not OK', ['response' => $response->json()]);
            return [];
        } catch (\Exception $e) {
            Log::error('GuardianAPI Error', ['message' => $e->getMessage()]);
            return [];
        }
    }


    public function mapResults(array $articles): array
    {
        return collect($articles)->map(function ($article) {
            return [
                'provider' => NewsProvidersEnum::GUARDIAN_API->value,
                'source' => 'The Guardian',
                'author' => $article['fields']['byline'] ?? 'Anonymous',
                'title' => $article['webTitle'] ?? 'No Title',
                'description' => $article['fields']['trailText'] ?? 'No Description',
                'url' => $article['webUrl'] ?? '',
                'image_url' => $article['fields']['thumbnail'] ?? '',
                'published_at' => $article['webPublicationDate'] ?? null,
                'content' => null, // The Guardian API doesn't provide full content
                'category' => $article['sectionName'] ?? null,
            ];
        })->toArray();
    }



}
