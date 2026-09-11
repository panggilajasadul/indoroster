<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    public const API_KEY = 'e7d4f9b821a043cbb845fa92e1069df1';

    /**
     * Submit single or multiple URLs to IndexNow API (Bing, Copilot, Yandex, Seznam).
     *
     * @param  array<string>|string  $urls
     */
    public static function submit(array|string $urls): bool
    {
        $urlList = is_array($urls) ? $urls : [$urls];
        if (empty($urlList)) {
            return false;
        }

        $host = parse_url(config('app.url', 'https://indoroster.com'), PHP_URL_HOST) ?: 'indoroster.com';
        $keyLocation = "https://{$host}/".self::API_KEY.'.txt';

        $payload = [
            'host' => $host,
            'key' => self::API_KEY,
            'keyLocation' => $keyLocation,
            'urlList' => array_values(array_unique($urlList)),
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json; charset=utf-8',
                ])
                ->post('https://api.indexnow.org/indexnow', $payload);

            if ($response->successful() || $response->status() === 202) {
                Log::info('IndexNow submission successful', [
                    'count' => count($urlList),
                    'status' => $response->status(),
                ]);

                return true;
            }

            Log::warning('IndexNow submission returned non-200/202 status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('IndexNow submission failed with exception', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
