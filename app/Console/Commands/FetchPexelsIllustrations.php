<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchPexelsIllustrations extends Command
{
    protected $signature = 'blog:fetch-illustrations {--key= : Pexels API Key}';

    protected $description = 'Fetch and assign high-quality architectural illustrations from Pexels API for all articles';

    public function handle(): int
    {
        $apiKey = $this->option('key') ?: 'QWfuKh9eXYF9WbbHAtEFRiPGwyEceJUSY199h9vwbfPRbwGLg7DY32Ap';

        // Save key to site_settings for future AI generation
        SiteSetting::updateOrCreate(
            ['key' => 'pexels_api_key'],
            ['value' => $apiKey, 'group' => 'seo', 'type' => 'text']
        );

        $queries = [
            'cara-menghitung-kebutuhan-roster-beton-per-m2' => [
                'query' => 'brick wall construction architecture',
                'alt' => 'Perhitungan dan pemasangan dinding roster beton minimalis',
            ],
            'fasad-rumah-hadap-barat-secondary-skin-roster' => [
                'query' => 'modern house facade architecture concrete',
                'alt' => 'Desain fasad secondary skin roster beton penahan panas matahari',
            ],
            'perbedaan-roster-beton-vs-roster-tanah-liat-terakota' => [
                'query' => 'terracotta brick texture wall',
                'alt' => 'Komparasi material roster beton abu dan roster terakota tanah liat',
            ],
            '5-kesalahan-fatal-pemasangan-dinding-roster-retak-rambut' => [
                'query' => 'masonry wall construction bricklayer',
                'alt' => 'Proses pemasangan dinding roster beton dengan kolom praktis dan nat rapi',
            ],
            'mengintip-dapur-pabrik-roster-plered-purwakarta-mutu-k200' => [
                'query' => 'concrete factory manufacture architecture',
                'alt' => 'Sentra pabrik pembuatan roster beton presisi mutu K-200 Plered Purwakarta',
            ],
            'desain-partisi-ruang-tamu-dapur-roster-minimalis' => [
                'query' => 'minimalist living room interior divider partition',
                'alt' => 'Inspirasi partisi pembatas ruang tamu dan dapur dengan roster minimalis',
            ],
            'panduan-memilih-ketebalan-ukuran-roster-beton-10cm-vs-8cm' => [
                'query' => 'concrete blocks architecture geometry',
                'alt' => 'Spesifikasi ukuran dan ketebalan roster beton 10 cm vs 8 cm',
            ],
            'solusi-ruang-cuci-jemur-laundry-room-anti-pengap-roster' => [
                'query' => 'modern laundry room architecture terrace',
                'alt' => 'Desain ruang cuci jemur laundry room dengan dinding ventilasi roster',
            ],
            'estimasi-biaya-bangun-pagar-dinding-roster-panjang-10-meter' => [
                'query' => 'modern house fence wall gate',
                'alt' => 'Pembangunan pagar rumah minimalis dengan roster beton cetak presisi',
            ],
            'perawatan-coating-roster-beton-outdoor-anti-lumut' => [
                'query' => 'concrete wall texture outdoor sunlight',
                'alt' => 'Aplikasi coating pelindung anti lumut pada dinding roster luar ruangan',
            ],
        ];

        $articles = Article::all();
        $this->info("Fetching illustrations for {$articles->count()} articles using Pexels API...");

        foreach ($articles as $article) {
            $config = $queries[$article->slug] ?? [
                'query' => 'modern concrete architecture',
                'alt' => $article->title,
            ];

            $searchQuery = urlencode($config['query']);
            $url = "https://api.pexels.com/v1/search?query={$searchQuery}&per_page=1&orientation=landscape";

            try {
                $response = Http::withHeaders([
                    'Authorization' => $apiKey,
                ])->timeout(15)->get($url);

                if ($response->successful() && ! empty($response->json('photos'))) {
                    $photo = $response->json('photos.0');
                    $imageUrl = $photo['src']['large2x'] ?? ($photo['src']['large'] ?? $photo['src']['original']);
                    $altText = $config['alt'];

                    $article->update([
                        'thumbnail' => $imageUrl,
                        'thumbnail_alt' => $altText,
                    ]);

                    $this->info("📸 Updated: [{$article->title}] with image: {$imageUrl}");
                } else {
                    $this->warn("⚠️ No image found for: {$article->title} (Query: {$config['query']})");
                }
            } catch (\Exception $e) {
                $this->error("❌ Error fetching for {$article->title}: {$e->getMessage()}");
            }
        }

        $this->info('✨ All article illustrations have been successfully updated!');

        return 0;
    }
}
