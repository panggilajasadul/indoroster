<?php

namespace Tests\Unit;

use App\Services\LegacyUrlRedirectService;
use Tests\TestCase;

class LegacyUrlRedirectTest extends TestCase
{
    public function test_resolves_legacy_blog_url(): void
    {
        $redirect = LegacyUrlRedirectService::resolveRedirect('blog');
        $this->assertStringContainsString('/artikel', $redirect);
    }

    public function test_resolves_legacy_location_urls(): void
    {
        $redirectJaksel = LegacyUrlRedirectService::resolveRedirect('jual-roster-beton-di-jakarta-selatan');
        $this->assertNotNull($redirectJaksel);
        $this->assertStringContainsString('/lokasi', $redirectJaksel);

        $redirectBandung = LegacyUrlRedirectService::resolveRedirect('pabrik-roster-beton-di-bandung');
        $this->assertNotNull($redirectBandung);
        $this->assertStringContainsString('/lokasi', $redirectBandung);

        $redirectBogor = LegacyUrlRedirectService::resolveRedirect('pusat-roster-beton-di-bogor');
        $this->assertNotNull($redirectBogor);
        $this->assertStringContainsString('/lokasi', $redirectBogor);
    }

    public function test_resolves_legacy_article_keywords(): void
    {
        $redirectPaint = LegacyUrlRedirectService::resolveRedirect('cara-mengecat-roster-beton-agar-warna-tahan-lama-dan-tidak-mengelupas');
        $this->assertNotNull($redirectPaint);
        $this->assertStringContainsString('/artikel', $redirectPaint);
    }
}
