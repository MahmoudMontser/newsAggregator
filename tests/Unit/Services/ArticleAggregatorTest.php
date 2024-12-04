<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Services\ArticleAggregator;
use App\Services\ArticlesProviders\GuardianAPIService;
use App\Services\ArticlesProviders\NewsAPIService;
use App\Services\ArticlesProviders\NewYorkTimesService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleAggregatorTest extends TestCase
{
    use RefreshDatabase;

    protected $mockedServices;
    protected $aggregator;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock article services
        $this->mockedServices = [
            'guardian' => Mockery::mock(GuardianAPIService::class),
            'newsAPI' => Mockery::mock(NewsAPIService::class),
            'nyt' => Mockery::mock(NewYorkTimesService::class),
        ];

        // Replace real services with mocked ones in ArticleAggregator
        $this->aggregator = new class($this->mockedServices) extends ArticleAggregator {
            public function __construct($mockedServices)
            {
                $this->services = $mockedServices;
            }
        };
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testFetchAndStoreHandlesPaginationAndStoresArticles()
    {
        // Mock responses for each service
        $mockArticles = [
            'articles' => [
                [
                    'provider' => 'MockProvider',
                    'source' => 'MockSource',
                    'title' => 'Mock Title',
                    'content' => 'Mock Content',
                    'published_at' => now()->toDateTimeString(),
                    'url' => 'http://example.com',
                    'author' => 'Author',
                ],
            ],
            'total' => 1,
        ];

        foreach ($this->mockedServices as $mock) {
            $mock->shouldReceive('fetchArticles')
                ->withAnyArgs()
                ->andReturn($mockArticles);
        }

        $total = $this->aggregator->fetchAndStore();

        $this->assertEquals(3, $total); // 1 article per service
        $this->assertDatabaseHas('articles', [
            'title' => 'Mock Title',
        ]);
    }

    public function testFetchAndStoreHandlesEmptyResponsesGracefully()
    {
        foreach ($this->mockedServices as $mock) {
            $mock->shouldReceive('fetchArticles')
                ->withAnyArgs()
                ->andReturn(['articles' => [], 'total' => 0]);
        }

        $total = $this->aggregator->fetchAndStore();

        $this->assertEquals(0, $total);
    }

}
