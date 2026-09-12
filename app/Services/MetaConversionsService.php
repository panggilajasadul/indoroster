<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MetaConversionsService
{
    protected string $pixelId;

    protected string $accessToken;

    protected ?string $testEventCode;

    protected string $apiVersion = 'v20.0';

    public function __construct()
    {
        $this->pixelId = (string) (config('services.meta.pixel_id') ?: SiteSetting::getValue('meta_pixel_id', '947593387751313'));
        $this->accessToken = (string) (config('services.meta.access_token') ?: SiteSetting::getValue('meta_capi_access_token', ''));
        $this->testEventCode = config('services.meta.test_event_code') ?: SiteSetting::getValue('meta_test_event_code');
    }

    /**
     * Check if Meta CAPI is configured with valid credentials.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->pixelId) && ! empty($this->accessToken);
    }

    /**
     * Send an event payload to Meta Conversions API.
     *
     * @param  string  $eventName  Standard Meta event (e.g. PageView, ViewContent, Contact, AddToCart, Purchase, Lead)
     * @param  array  $customData  Optional custom properties (e.g. content_name, value, currency)
     * @param  array  $userData  Optional user data overrides (e.g. ph, em, ct, st)
     * @param  string|null  $eventId  Deduplication ID shared between browser pixel and server event
     * @param  string|null  $eventSourceUrl  URL where the event occurred
     * @return array Result summary with status and response data
     */
    public function sendEvent(
        string $eventName,
        array $customData = [],
        array $userData = [],
        ?string $eventId = null,
        ?string $eventSourceUrl = null
    ): array {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Meta CAPI not configured (missing pixel_id or access_token).',
            ];
        }

        $request = request();
        $eventId = $eventId ?: (string) Str::uuid();
        $eventTime = time();
        $eventSourceUrl = $eventSourceUrl ?: ($request ? $request->fullUrl() : config('app.url'));

        // Prepare and hash user data
        $formattedUserData = $this->prepareUserData($userData);

        $eventPayload = [
            'event_name' => $eventName,
            'event_time' => $eventTime,
            'event_id' => $eventId,
            'event_source_url' => $eventSourceUrl,
            'action_source' => 'website',
            'user_data' => $formattedUserData,
        ];

        if (! empty($customData)) {
            $eventPayload['custom_data'] = $customData;
        }

        $body = [
            'data' => [$eventPayload],
        ];

        // Include test event code if provided for Meta Events Manager testing
        $testCode = $userData['test_event_code'] ?? $this->testEventCode;
        if (! empty($testCode)) {
            $body['test_event_code'] = $testCode;
        }

        try {
            $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";

            $response = Http::timeout(5)
                ->withToken($this->accessToken)
                ->acceptJson()
                ->post($url, $body);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'event_id' => $eventId,
                    'events_received' => $response->json('events_received', 1),
                    'fbtrace_id' => $response->json('fbtrace_id'),
                ];
            }

            Log::warning('Meta CAPI Request Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'event_name' => $eventName,
                'event_id' => $eventId,
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error('Meta CAPI Exception: '.$e->getMessage(), [
                'event_name' => $eventName,
                'event_id' => $eventId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format and hash user information according to Meta guidelines.
     */
    protected function prepareUserData(array $inputUserData = []): array
    {
        $request = request();
        $userData = [];

        // 1. Client IP Address (Do NOT hash)
        if (! empty($inputUserData['client_ip_address'])) {
            $userData['client_ip_address'] = trim($inputUserData['client_ip_address']);
        } elseif ($request) {
            $rawIp = $request->header('CF-Connecting-IP')
                ?: $request->header('X-Forwarded-For')
                ?: $request->header('X-Real-IP')
                ?: $request->ip();

            if (! empty($rawIp)) {
                $firstIp = trim(explode(',', $rawIp)[0]);
                if (! empty($firstIp) && $firstIp !== '127.0.0.1' && $firstIp !== '::1') {
                    $userData['client_ip_address'] = $firstIp;
                }
            }
        }

        // 2. Client User Agent (Do NOT hash)
        if (! empty($inputUserData['client_user_agent'])) {
            $userData['client_user_agent'] = trim($inputUserData['client_user_agent']);
        } elseif ($request && ! empty($request->userAgent())) {
            $userData['client_user_agent'] = $request->userAgent();
        }

        // 3. Meta Cookies (fbp & fbc) (Do NOT hash)
        $fbp = $inputUserData['fbp'] ?? ($request ? $request->cookie('_fbp') : null);
        if (! empty($fbp)) {
            $userData['fbp'] = trim($fbp);
        }

        $fbc = $inputUserData['fbc'] ?? ($request ? $request->cookie('_fbc') : null);
        // If fbclid query param is present on current request but no cookie yet, construct fbc
        if (empty($fbc) && $request && $request->has('fbclid')) {
            $fbc = 'fb.1.'.time().'.'.$request->query('fbclid');
        }
        if (! empty($fbc)) {
            $userData['fbc'] = trim($fbc);
        }

        // 4. Hashable Fields (SHA-256 after trim + lowercase)
        if (! empty($inputUserData['em'])) {
            $userData['em'] = [hash('sha256', strtolower(trim($inputUserData['em'])))];
        }

        if (! empty($inputUserData['ph'])) {
            // Clean phone: digits only, convert 08xx or 8xx to 628xx (E.164 international standard)
            $cleanedPhone = preg_replace('/[^0-9]/', '', (string) $inputUserData['ph']);
            if (str_starts_with($cleanedPhone, '0')) {
                $cleanedPhone = '62'.substr($cleanedPhone, 1);
            } elseif (str_starts_with($cleanedPhone, '8')) {
                $cleanedPhone = '62'.$cleanedPhone;
            }

            if (strlen($cleanedPhone) >= 9) {
                $userData['ph'] = [hash('sha256', $cleanedPhone)];
            }
        }

        if (! empty($inputUserData['ct'])) {
            $cleanedCity = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $inputUserData['ct'])));
            $userData['ct'] = [hash('sha256', $cleanedCity)];
        }

        if (! empty($inputUserData['st'])) {
            $cleanedState = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $inputUserData['st'])));
            $userData['st'] = [hash('sha256', $cleanedState)];
        }

        if (! empty($inputUserData['country'])) {
            $cleanedCountry = strtolower(trim($inputUserData['country']));
            $userData['country'] = [hash('sha256', $cleanedCountry)];
        } else {
            $userData['country'] = [hash('sha256', 'id')]; // Default Indonesia (ISO 2-letter)
        }

        // Split name into first and last name if provided as single string
        if (empty($inputUserData['fn']) && ! empty($inputUserData['name'])) {
            $nameParts = preg_split('/\s+/', trim($inputUserData['name']), 2);
            $inputUserData['fn'] = $nameParts[0] ?? '';
            if (! empty($nameParts[1])) {
                $inputUserData['ln'] = $nameParts[1];
            }
        }

        if (! empty($inputUserData['fn'])) {
            $userData['fn'] = [hash('sha256', strtolower(trim($inputUserData['fn'])))];
        }

        if (! empty($inputUserData['ln'])) {
            $userData['ln'] = [hash('sha256', strtolower(trim($inputUserData['ln'])))];
        }

        if (! empty($inputUserData['zp'])) {
            $cleanedZp = preg_replace('/[^0-9]/', '', (string) $inputUserData['zp']);
            if (! empty($cleanedZp)) {
                $userData['zp'] = [hash('sha256', $cleanedZp)];
            }
        }

        if (! empty($inputUserData['external_id'])) {
            $userData['external_id'] = [hash('sha256', (string) $inputUserData['external_id'])];
        }

        return $userData;
    }
}
