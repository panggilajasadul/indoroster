<?php

namespace App\Console\Commands;

use App\Models\GalleryMedia;
use App\Models\ProductMedia;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImagesCommand extends Command
{
    protected $signature = 'images:optimize 
                            {--limit=100 : Jumlah maksimal gambar yang diproses per batch}
                            {--dry-run : Menampilkan daftar gambar yang akan dioptimasi tanpa mengubah file}';

    protected $description = 'Optimasi dan konversi gambar JPG/PNG lama ke WebP (maks 1600px, Q80) secara aman dan bertahap';

    public function handle(ImageOptimizationService $optimizer): int
    {
        $limit = (int) $this->option('limit');
        $dryRun = (bool) $this->option('dry-run');

        $this->info("Memulai Optimasi Gambar ke WebP (Limit: {$limit}, Dry Run: ".($dryRun ? 'YES' : 'NO').')...');

        $disk = config('image-optimizer.disk', 'public');
        $processed = 0;
        $totalSavedBytes = 0;
        $rows = [];

        // 1. Process ProductMedia
        $productMedias = ProductMedia::where('media_type', 'image')
            ->whereNotNull('media_url')
            ->where('media_url', 'not like', 'http%')
            ->where('media_url', 'not like', '%.webp')
            ->take($limit)
            ->get();

        foreach ($productMedias as $pm) {
            if ($processed >= $limit) {
                break;
            }

            $oldPath = $pm->media_url;
            if (! Storage::disk($disk)->exists($oldPath)) {
                continue;
            }

            $oldSize = Storage::disk($disk)->size($oldPath);

            if ($dryRun) {
                $rows[] = ['ProductMedia', $pm->id, $oldPath, 'Simulasi WebP', number_format($oldSize / 1024, 1).' KB', '-'];
                $processed++;

                continue;
            }

            $newPath = $optimizer->optimizeExistingFile($oldPath, $disk);
            if ($newPath && $newPath !== $oldPath && Storage::disk($disk)->exists($newPath)) {
                $newSize = Storage::disk($disk)->size($newPath);
                $saved = max(0, $oldSize - $newSize);
                $totalSavedBytes += $saved;

                $pm->media_url = $newPath;
                $pm->saveQuietly(); // Hindari double trigger booted

                $savingsPercent = $oldSize > 0 ? round(($saved / $oldSize) * 100, 1) : 0;
                $rows[] = ['ProductMedia', $pm->id, $oldPath, $newPath, number_format($oldSize / 1024, 1).' KB → '.number_format($newSize / 1024, 1).' KB', "Hemat {$savingsPercent}%"];
                $processed++;
            }
        }

        // 2. Process GalleryMedia
        if ($processed < $limit) {
            $galleryMedias = GalleryMedia::where('media_type', 'image')
                ->whereNotNull('media_url')
                ->where('media_url', 'not like', 'http%')
                ->where('media_url', 'not like', '%.webp')
                ->take($limit - $processed)
                ->get();

            foreach ($galleryMedias as $gm) {
                if ($processed >= $limit) {
                    break;
                }

                $oldPath = $gm->media_url;
                if (! Storage::disk($disk)->exists($oldPath)) {
                    continue;
                }

                $oldSize = Storage::disk($disk)->size($oldPath);

                if ($dryRun) {
                    $rows[] = ['GalleryMedia', $gm->id, $oldPath, 'Simulasi WebP', number_format($oldSize / 1024, 1).' KB', '-'];
                    $processed++;

                    continue;
                }

                $newPath = $optimizer->optimizeExistingFile($oldPath, $disk);
                if ($newPath && $newPath !== $oldPath && Storage::disk($disk)->exists($newPath)) {
                    $newSize = Storage::disk($disk)->size($newPath);
                    $saved = max(0, $oldSize - $newSize);
                    $totalSavedBytes += $saved;

                    $gm->media_url = $newPath;
                    $gm->saveQuietly();

                    $savingsPercent = $oldSize > 0 ? round(($saved / $oldSize) * 100, 1) : 0;
                    $rows[] = ['GalleryMedia', $gm->id, $oldPath, $newPath, number_format($oldSize / 1024, 1).' KB → '.number_format($newSize / 1024, 1).' KB', "Hemat {$savingsPercent}%"];
                    $processed++;
                }
            }
        }

        if (empty($rows)) {
            $this->info('Semua gambar sudah dalam format WebP yang optimal. Tidak ada gambar yang perlu dikonversi.');

            return self::SUCCESS;
        }

        $this->table(['Model', 'ID', 'Path Asli', 'Path WebP Baru', 'Ukuran', 'Penghematan'], $rows);

        if (! $dryRun) {
            $totalSavedKb = number_format($totalSavedBytes / 1024, 2);
            $this->info("Sukses mengoptimasi {$processed} gambar! Total bandwidth geschäft dihemat: {$totalSavedKb} KB.");
        }

        return self::SUCCESS;
    }
}
