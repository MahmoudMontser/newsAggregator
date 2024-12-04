<?php

namespace App\Listeners;

use App\Events\FetchArticlesByCategory;
use App\Services\ArticleAggregator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class FetchArticlesListener implements ShouldQueue
{
    protected $aggregator;

    public function __construct(ArticleAggregator $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    public function handle(FetchArticlesByCategory $event)
    {
        $category = $event->category;
        Log::info("Fetching articles for category: {$category}");

        $articles = $this->aggregator->fetchAndStore(['category' => $category], ['pageSize' => 50]);

        Log::info("Fetched and processed " . $articles . " articles for category: {$category}");
    }
}
