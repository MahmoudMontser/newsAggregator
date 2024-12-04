<?php
namespace App\Services;

use App\Services\ArticlesProviders\GuardianAPIService;
use App\Services\ArticlesProviders\NewsAPIService;
use App\Services\ArticlesProviders\NewYorkTimesService;

class ArticleAggregator
{
    protected $services;

    public function __construct()
    {
        $this->services = [
            new NewsAPIService(),
            new NewYorkTimesService(),
            new GuardianAPIService(),

        ];
    }

    public function fetchAndStore($queryParams = [], $paginationParams = [])
    {
        $total=0;
        foreach ($this->services as $service) {
            dump(class_basename($service));

            $page = 1;
            $perPage = $paginationParams['pageSize'] ?? 50;

            do {
                dump($page);

                $articles = $service->fetchArticles($queryParams, [
                    'page' => $page,
                    'pageSize' => $perPage,
                ]);
                // Save all fetched articles
                if (isset($articles['articles']))
                {
                    $this->storeArticles($articles['articles']);
                    $total+=count($articles['articles']);
                }
                $page++;
                $hasMorePages = (!isset($articles['articles'])) ? false : $articles['total']-($perPage*$page) > 0;
                dump(isset($articles['articles']),$hasMorePages);

            } while ($hasMorePages);
        }



        return $total;
    }


    protected function storeArticles(array $articles)
    {
        foreach ($articles as $article) {
            if ($article)
            {
                \App\Models\Article::updateOrCreate(
                    $this->getArticleIdentifiers($article),
                    $this->getArticleAttributes($article)
                );
            }

        }
    }

    protected function getArticleIdentifiers(array $article): array
    {
        return [
            'provider' => $article['provider'] ?? '',
            'source' => $article['source'] ?? '',
            'title' => $article['title'] ?? 'No Title',
        ];
    }

    protected function getArticleAttributes(array $article): array
    {
        return [
            'content' => $article['content'] ?? 'No Content',
            'published_at' => $article['published_at'] ?? null,
            'source_url' => $article['url'] ?? '',
            'author' => $article['author'] ?? 'Unknown',
            'category' => $article['category'] ?? null,
        ];
    }

}
