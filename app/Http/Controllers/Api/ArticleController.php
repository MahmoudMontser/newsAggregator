<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Retrieve articles based on search queries, filters, and preferences.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        // Extract filters from the request
        $filters = $request->only(['q', 'from_date', 'to_date', 'category', 'source', 'author']);

        // Fetch articles using the service
        $articles = $this->articleService->getArticles($filters);

        // Return paginated article resources
        return ArticleResource::collection($articles);
    }



    /**
     * Retrieve a list of unique article sources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSources()
    {
        $sources = Article::select('source')->distinct()->pluck('source');
        return response()->json($sources);
    }

    /**
     * Retrieve a list of unique article categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = Article::select('category')->distinct()->pluck('category');
        return response()->json($categories);
    }
}
