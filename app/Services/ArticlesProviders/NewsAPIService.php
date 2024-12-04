<?php
namespace App\Services\ArticlesProviders;

use App\Enums\NewsProvidersEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPIService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.newsapi.base_uri');
        $this->apiKey = config('services.newsapi.api_key');
    }

    public function fetchArticles($queryParams = [], $pagination = [])
    {
        try {
            $category=strtolower($queryParams['category']) ?? '';
            // Include pagination parameters
            $queryParams =[
                'apiKey' => $this->apiKey,
                'q' => $category,
                'sortBy'=>'publishedAt',
                'page' => $pagination['page'] ?? 1,
                'pageSize' => $pagination['pageSize'] ?? 10,
            ];

            $response = Http::get($this->baseUrl . 'everything', $queryParams);

            if ($response->ok()) {
                $data = json_decode($response->body());
                return [
                    'articles' => $this->mapResults($response->json('articles')),
                    'total' => $data->totalResults ?? 0,
                    'currentPage' => $pagination['page'] ?? 1,
                    'perPage' => $pagination['pageSize'] ?? 10,
                ];
            }

            Log::warning('NewsAPI response not OK', ['response' => $response->json()]);
            return [];
        } catch (\Exception $e) {
            Log::error('NewsAPI Error', ['message' => $e->getMessage()]);
            return [];
        }
    }


    public function mapResults(array $articles)
    {
        return collect($articles)->map(function ($article) {
            $article=(array)$article;
            if ($article['source']['name'] !='[Removed]')
            {
                return [
                    'provider' => NewsProvidersEnum::NEWS_API->value,
                    'source' => $article['source']['name'] ?? 'Unknown',
                    'author' => $article['author'] ?? 'Anonymous',
                    'title' => $article['title'] ?? 'No Title',
                    'description' => $article['description'] ?? 'No Description',
                    'url' => $article['url'] ?? '',
                    'image_url' => $article['urlToImage'] ?? '',
                    'published_at' => $article['publishedAt'] ?? null,
                    'content' => $article['content'] ?? 'No Content',
                    'category' => null, // NewsAPI doesn't have a category field
                ];
            }

        })->toArray();
    }

}
