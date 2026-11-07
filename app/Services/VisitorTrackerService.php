<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\SiteVisitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VisitorTrackerService
{
    /**
     * Map country code to Country Name & Flag Emoji
     */
    protected static array $countryNames = [
        'ID' => ['name' => 'Indonesia', 'flag' => '🇮🇩'],
        'MY' => ['name' => 'Malaysia', 'flag' => '🇲🇾'],
        'SG' => ['name' => 'Singapura', 'flag' => '🇸🇬'],
        'AU' => ['name' => 'Australia', 'flag' => '🇦🇺'],
        'US' => ['name' => 'Amerika Serikat', 'flag' => '🇺🇸'],
        'JP' => ['name' => 'Jepang', 'flag' => '🇯🇵'],
        'KR' => ['name' => 'Korea Selatan', 'flag' => '🇰🇷'],
        'DE' => ['name' => 'Jerman', 'flag' => '🇩🇪'],
        'NL' => ['name' => 'Belanda', 'flag' => '🇳🇱'],
        'GB' => ['name' => 'Inggris (UK)', 'flag' => '🇬🇧'],
        'AE' => ['name' => 'Uni Emirat Arab', 'flag' => '🇦🇪'],
        'SA' => ['name' => 'Arab Saudi', 'flag' => '🇸🇦'],
        'VN' => ['name' => 'Vietnam', 'flag' => '🇻🇳'],
        'TH' => ['name' => 'Thailand', 'flag' => '🇹🇭'],
        'PH' => ['name' => 'Filipina', 'flag' => '🇵🇭'],
        'IN' => ['name' => 'India', 'flag' => '🇮🇳'],
        'CN' => ['name' => 'Tiongkok', 'flag' => '🇨🇳'],
        'FR' => ['name' => 'Prancis', 'flag' => '🇫🇷'],
        'CA' => ['name' => 'Kanada', 'flag' => '🇨🇦'],
        'NZ' => ['name' => 'Selandia Baru', 'flag' => '🇳🇿'],
    ];

    /**
     * Track incoming web request
     */
    public static function track(Request $request): void
    {
        // Don't track background API, asset requests, livewire polling or bots
        $uri = $request->path();
        if (
            str_starts_with($uri, 'api/') ||
            str_starts_with($uri, 'livewire/') ||
            str_starts_with($uri, 'admin') ||
            str_starts_with($uri, 'courier') ||
            str_starts_with($uri, 'build/') ||
            str_starts_with($uri, 'assets/')
        ) {
            return;
        }

        $userAgent = (string) $request->userAgent();
        if (self::isBot($userAgent)) {
            return;
        }

        $sessionId = $request->session()->getId();
        if (empty($sessionId)) {
            return;
        }

        $today = Carbon::today('Asia/Jakarta')->toDateString();
        $ip = $request->ip();

        // Detect Country
        $countryCode = self::detectCountryCode($request);
        $countryInfo = self::$countryNames[$countryCode] ?? ['name' => $countryCode, 'flag' => '🌐'];
        $countryName = $countryInfo['name'];

        try {
            $visitor = SiteVisitor::where('session_id', $sessionId)
                ->where('visit_date', $today)
                ->first();

            if ($visitor) {
                $visitor->increment('hits');
                $visitor->update(['last_activity_at' => now('Asia/Jakarta')]);
            } else {
                SiteVisitor::create([
                    'session_id' => $sessionId,
                    'ip_address' => $ip,
                    'country_code' => $countryCode,
                    'country_name' => $countryName,
                    'user_agent' => substr($userAgent, 0, 250),
                    'visit_date' => $today,
                    'hits' => 1,
                    'last_activity_at' => now('Asia/Jakarta'),
                ]);
            }
        } catch (\Throwable $e) {
            // Silently ignore tracking error so user experience is never broken
        }
    }

    /**
     * Get aggregate statistics for the footer widget
     */
    public static function getStats(): array
    {
        return Cache::remember('site_visitor_stats', 30, function () {
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            $tenMinutesAgo = Carbon::now('Asia/Jakarta')->subMinutes(10);

            // Base offsets from SiteSetting if configured (allows setting credible start count)
            $baseHits = (int) SiteSetting::getValue('visitor_base_total_hits', 11282);
            $baseVisitors = (int) SiteSetting::getValue('visitor_base_total_visitors', 8172);
            $baseTodayHits = (int) SiteSetting::getValue('visitor_base_today_hits', 32);
            $baseTodayVisitors = (int) SiteSetting::getValue('visitor_base_today_visitors', 23);
            $baseOnline = (int) SiteSetting::getValue('visitor_base_online', 1);

            $countryMy = (int) SiteSetting::getValue('visitor_country_my', 245);
            $countrySg = (int) SiteSetting::getValue('visitor_country_sg', 182);
            $countryAu = (int) SiteSetting::getValue('visitor_country_au', 128);
            $countryUs = (int) SiteSetting::getValue('visitor_country_us', 94);
            $countryJp = (int) SiteSetting::getValue('visitor_country_jp', 43);

            // DB counts
            $dbTodayVisitors = SiteVisitor::where('visit_date', $today)->count();
            $dbTodayHits = (int) SiteVisitor::where('visit_date', $today)->sum('hits');
            $dbTotalVisitors = SiteVisitor::count();
            $dbTotalHits = (int) SiteVisitor::sum('hits');

            // Online in last 10 minutes
            $dbOnline = SiteVisitor::where('last_activity_at', '>=', $tenMinutesAgo)->count();
            $onlineCount = max($baseOnline, $dbOnline);

            // Real DB per country
            $dbCountries = SiteVisitor::selectRaw('country_code, country_name, count(*) as count')
                ->groupBy('country_code', 'country_name')
                ->pluck('count', 'country_code')
                ->toArray();

            $totalInternational = $countryMy + $countrySg + $countryAu + $countryUs + $countryJp;
            $indoVisitors = max(100, ($baseVisitors + $dbTotalVisitors) - $totalInternational);

            $countryCounts = [
                ['country_code' => 'ID', 'country_name' => 'Indonesia', 'total_visitors' => $indoVisitors + ($dbCountries['ID'] ?? 0)],
                ['country_code' => 'MY', 'country_name' => 'Malaysia', 'total_visitors' => $countryMy + ($dbCountries['MY'] ?? 0)],
                ['country_code' => 'SG', 'country_name' => 'Singapura', 'total_visitors' => $countrySg + ($dbCountries['SG'] ?? 0)],
                ['country_code' => 'AU', 'country_name' => 'Australia', 'total_visitors' => $countryAu + ($dbCountries['AU'] ?? 0)],
                ['country_code' => 'US', 'country_name' => 'Amerika Serikat', 'total_visitors' => $countryUs + ($dbCountries['US'] ?? 0)],
                ['country_code' => 'JP', 'country_name' => 'Jepang', 'total_visitors' => $countryJp + ($dbCountries['JP'] ?? 0)],
            ];

            // Map flags
            foreach ($countryCounts as &$item) {
                $code = strtoupper($item['country_code'] ?? 'ID');
                $item['flag'] = self::$countryNames[$code]['flag'] ?? '🌐';
            }

            return [
                'today_hits' => $baseTodayHits + $dbTodayHits,
                'today_visitors' => $baseTodayVisitors + $dbTodayVisitors,
                'total_hits' => $baseHits + $dbTotalHits,
                'total_visitors' => $baseVisitors + $dbTotalVisitors,
                'online' => $onlineCount,
                'countries' => $countryCounts,
            ];
        });
    }

    /**
     * Detect 2-letter Country Code
     */
    protected static function detectCountryCode(Request $request): string
    {
        // 1. Cloudflare Country Header (Production on Hostinger / Cloudflare)
        if ($cfCountry = $request->header('CF-IPCountry')) {
            $code = strtoupper(trim($cfCountry));
            if (strlen($code) === 2 && $code !== 'XX' && $code !== 'T1') {
                return $code;
            }
        }

        // 2. Standard Forwarded headers
        if ($country = $request->header('X-Country-Code')) {
            return strtoupper(trim($country));
        }

        // 3. Localhost or Indonesian default
        $ip = $request->ip();
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'ID';
        }

        return 'ID';
    }

    /**
     * Check if user-agent is a web bot/crawler
     */
    protected static function isBot(string $userAgent): bool
    {
        $bots = [
            'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'lighthouse',
            'headlesschrome', 'curl', 'wget', 'postman', 'python',
        ];

        $ua = strtolower($userAgent);
        foreach ($bots as $bot) {
            if (str_contains($ua, $bot)) {
                return true;
            }
        }

        return false;
    }
}
