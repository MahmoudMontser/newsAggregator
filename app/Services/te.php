<?php
namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function getArticles(array $filters)
    {
        $query = Article::query();

        // Apply search filters
        $query->when(!empty($filters['q']), function ($q) use ($filters) {
            $q->where(function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['q'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['q'] . '%');
            });
        });

        // Apply filtering by date range
        $query->when(!empty($filters['from_date']) && !empty($filters['to_date']), function ($q) use ($filters) {
            $q->whereBetween('published_at', [$filters['from_date'], $filters['to_date']]);
        });

        // Filter by category
        $query->when(!empty($filters['category']), function ($q) use ($filters) {
            $q->where('category', $filters['category']);
        });

        // Filter by source
        $query->when(!empty($filters['source']), function ($q) use ($filters) {
            $q->where('source', $filters['source']);
        });

        // Filter by author
        $query->when(!empty($filters['author']), function ($q) use ($filters) {
            $q->where('author', $filters['author']);
        });

        // Paginate results
        return $query->paginate(10);
    }
}
