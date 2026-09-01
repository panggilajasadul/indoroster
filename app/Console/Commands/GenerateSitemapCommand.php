<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--url= : Base URL website (default: APP_URL atau https://indoroster.com)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate seluruh file physical XML sitemap IndoRoster (Master Index + 8 Sub-Sitemaps)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Memulai proses generate ulang seluruh berkas Sitemap XML fisik...');

        $baseUrl = $this->option('url') ?: config('app.url', 'https://indoroster.com');
        $baseUrl = rtrim($baseUrl, '/');

        // Pastikan tidak menggunakan localhost di mode produksi
        if (str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            $baseUrl = 'https://indoroster.com';
        }

        $this->line("Target Base URL: <comment>{$baseUrl}</comment>");

        try {
            SitemapController::generate($baseUrl);

            $this->newLine();
            $this->info('✅ Seluruh berkas Sitemap XML fisik berhasil diperbarui!');
            $this->table(
                ['Sub-Sitemap XML', 'Jumlah URL Terdaftar', 'Status File'],
                $this->getSitemapSummary()
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Gagal meng-generate sitemap: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function getSitemapSummary(): array
    {
        $files = glob(public_path('sitemap-*.xml'));
        $rows = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $content = File::get($file);
            $count = substr_count($content, '<loc>');
            $rows[] = [$filename, number_format($count) . ' URL', 'Aktif & Siap Diindeks'];
        }

        return $rows;
    }
}
