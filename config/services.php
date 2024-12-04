<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'newsapi' => [
        'base_uri' => env('NEWSAPI_BASE_URI', 'https://newsapi.org/v2/'),
        'api_key'  => env('NEWSAPI_API_KEY'),
    ],

    'the_guardian' => [
        'base_uri' => env('THE_GUARDIAN_BASE_URI', 'https://content.guardianapis.com/'),
        'api_key'  => env('THE_GUARDIAN_API_KEY'),
    ],

    'new_york_times' => [
        'base_uri' => env('NEW_YORK_TIMES_BASE_URI', 'https://api.nytimes.com/svc/'),
        'api_key'  => env('NEW_YORK_TIMES_API_KEY'),
    ],


];
