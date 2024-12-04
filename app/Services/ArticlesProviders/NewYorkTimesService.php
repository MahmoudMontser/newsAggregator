<?php


namespace App\Services\ArticlesProviders;

use App\Enums\NewsProvidersEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewYorkTimesService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.new_york_times.base_uri');
        $this->apiKey = config('services.new_york_times.api_key');
    }

    public function fetchArticles($queryParams = [], $pagination = [])
    {
        try {
            // Include pagination parameters (adapted for NYT's offset system)
            $queryParams = [
                'api-key' => $this->apiKey,
                'page' => $pagination['page'],
            ];

            $response = Http::get($this->baseUrl . 'search/v2/articlesearch.json', $queryParams);

            if ($response->ok()) {
                $data = json_decode($response->body())->response;

                return [
                    'articles' => $this->mapResults($response->json('response')['docs']),
                    'total' => $data->meta->hits,
                    'currentPage' => $pagination['page'] ?? 1,
                    'perPage' => $pagination['pageSize'] ?? 10,
                ];

            }

            Log::warning('NYT response not OK', ['response' => $response->json()]);
            return [];
        } catch (\Exception $e) {
            Log::error('NYT Error', ['message' => $e->getMessage()]);
            return [];
        }
    }


    public function mapResults(array $articles)
    {
        return collect($articles)->map(function ($article) {
            return [
                'provider' => NewsProvidersEnum::NEW_YORK_TIMES->value,
                'source' => $article['source'] ?? 'New York Times',
                'author' => $article['byline']['original'] ?? 'Anonymous',
                'title' => $article['headline']['main'] ?? 'No Title',
                'description' => $article['abstract'] ?? 'No Description',
                'url' => $article['web_url'] ?? '',
                'image_url' => $this->extractImageUrl($article),
                'published_at' => $article['pub_date'] ?? null,
                'content' => $article['lead_paragraph'] ?? '',
                'category' => $article['section_name'] ?? null,
            ];
        })->toArray();
    }

    private function extractImageUrl(array $article): ?string
    {
        if (!empty($article['multimedia'])) {
            $image = collect($article['multimedia'])->firstWhere('subtype', 'thumbnail');
            return $image ? 'https://www.nytimes.com/' . $image['url'] : null;
        }
        return null;
    }
}
