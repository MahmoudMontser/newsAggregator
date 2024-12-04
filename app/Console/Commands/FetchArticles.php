<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\FetchArticlesByCategory;

class FetchArticles extends Command
{
    protected $signature = 'articles:fetch {categories?* : List of categories to fetch (optional)}';

    protected $description = 'Fetch articles for predefined or specified categories';

    public function handle()
    {
        $categories = $this->argument('categories') ?: $this->getDefaultCategories();

        $this->info('Dispatching events to fetch articles for the following categories:');
        foreach ($categories as $category) {
            $this->info("- {$category}");
            FetchArticlesByCategory::dispatch($category);
        }

        $this->info('All fetch events dispatched successfully.');

        return 0;
    }

    protected function getDefaultCategories()
    {
        return ['Technology', 'Health', 'Sports', 'Business', 'Entertainment'];
    }
}
