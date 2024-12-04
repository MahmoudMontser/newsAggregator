<?php

namespace App\Enums;

enum NewsProvidersEnum: int
{
    case NEWS_API = 1;
    case GUARDIAN_API = 2;
    case NEW_YORK_TIMES = 3;
    /**
     * Get the corresponding service class for each provider.
     */
    public function getServiceClass(): string
    {
        return match ($this) {
            self::NEWS_API => \App\Services\ArticlesProviders\NewsAPIService::class,
            self::GUARDIAN_API => \App\Services\ArticlesProviders\GuardianAPIService::class,
            self::NEW_YORK_TIMES => \App\Services\ArticlesProviders\NewYorkTimesService::class,
        };
    }

    /**
     * Get the configuration key for the provider.
     */
    public function getConfigKey(): string
    {
        return match ($this) {
            self::NEWS_API => 'services.newsapi',
            self::GUARDIAN_API => 'services.guardianapi',
            self::NEW_YORK_TIMES => 'services.new_york_times',
        };
    }
}
