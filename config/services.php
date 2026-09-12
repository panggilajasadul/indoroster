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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'seo' => [
        'api_token' => env('SEO_API_TOKEN', 'dev-seo-token-secret-123'),
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'owner' => env('GITHUB_OWNER'),
        'repo' => env('GITHUB_REPO'),
    ],

    'meta' => [
        'pixel_id' => env('META_PIXEL_ID', '947593387751313'),
        'access_token' => env('META_CAPI_ACCESS_TOKEN', 'EABEbPpACKgwBSWp3ggEAgihnWDu3ZC6Oa0eS6J0HZCWANEduZBEL0rvb1Mq8RxlHrvsCfTZBKyZBBNREZBLGUGs6MZC66uXNEDLa0z8RYSJVFLKqKPdbvQPB1KFaTPd7yvd9Nngel9sZAdhyv7WBrWcZCymsLSDpmqPLihC4BvQqxsXbfOTYQp1Ctl46ZAxtKIz7KkZAAZDZD'),
        'test_event_code' => env('META_TEST_EVENT_CODE'),
    ],

];
