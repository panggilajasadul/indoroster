<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizationService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver);
    }

    /**
     * Validasi file gambar sebelum diproses.
     */
    public function isValidImage(UploadedFile|string $file): bool
    {
        if ($file instanceof UploadedFile) {
            if (! $file->isValid()) {
                return false;
            }

            $mime = $file->getMimeType();
            $allowedMimes = config('image-optimizer.allowed_mimes', ['image/jpeg', 'image/png', 'image/webp']);
            if (! in_array($mime, $allowedMimes, true)) {
                return false;
            }

            $maxSizeKb = config('image-optimizer.max_upload_size_kb', 10240);
            if (($file->getSize() / 1024) > $maxSizeKb) {
                return false;
            }

            // Validasi integritas biner via getimagesize
            $realPath = $file->getRealPath();
            if (! $realPath || @getimagesize($realPath) === false) {
                return false;
            }

            return true;
        }

        if (is_string($file) && file_exists($file)) {
            return @getimagesize($file) !== false;
        }

        return false;
    }

    /**
     * Optimasi gambar upload: validasi, resize (tanpa upscale), konversi ke WebP Q80, simpan ke storage.
     *
     * @return string|null Path relatif di dalam disk publik (contoh: 'product-media/motif-bintang-abc12345.webp')
     */
    public function optimizeUploadedFile(UploadedFile $file, string $directory = 'product-media', ?string $customPrefix = null): ?string
    {
        if (! $this->isValidImage($file)) {
            Log::warning('ImageOptimizationService: File upload tidak valid atau bukan gambar yang diizinkan.', [
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            return null;
        }

        $disk = config('image-optimizer.disk', 'public');
        $maxWidth = config('image-optimizer.max_width', 1600);
        $maxHeight = config('image-optimizer.max_height', 1600);
        $quality = config('image-optimizer.webp_quality', 80);

        try {
            // Baca gambar dengan Intervention Image 3
            $image = $this->manager->read($file->getRealPath());

            // Dapatkan dimensi asli
            $origWidth = $image->width();
            $origHeight = $image->height();

            // Resize proporsional HANYA jika melebihi batas maksimum (TIDAK melakukan upscale)
            if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
                $image->scaleDown($maxWidth, $maxHeight);
            }

            // Encode ke format WebP dengan quality yang ditentukan
            $webpData = $image->toWebp($quality)->toString();

            // Generate nama file unik & aman (mencegah collision & path traversal)
            $origName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanPrefix = $customPrefix ? Str::slug($customPrefix) : Str::slug($origName);
            if (empty($cleanPrefix)) {
                $cleanPrefix = 'img';
            }
            $cleanPrefix = substr($cleanPrefix, 0, 40);
            $uniqueFilename = $cleanPrefix.'-'.Str::random(12).'.webp';

            $relativePath = trim($directory, '/').'/'.$uniqueFilename;

            // Simpan ke storage disk
            Storage::disk($disk)->put($relativePath, $webpData);

            return $relativePath;
        } catch (\Throwable $e) {
            Log::error('ImageOptimizationService: Gagal mengonversi gambar ke WebP, fallback ke penyimpanan original.', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            // Fallback aman: simpan original tanpa memecahkan upload
            try {
                return $file->store($directory, $disk);
            } catch (\Throwable $fallbackEx) {
                Log::error('ImageOptimizationService: Fallback upload juga gagal.', ['error' => $fallbackEx->getMessage()]);

                return null;
            }
        }
    }

    /**
     * Optimasi file yang sudah ada di disk storage lokal.
     * Mengonversi ke WebP dan mengembalikan path baru.
     *
     * @return string|null Path baru (WebP) atau path lama jika sudah WebP/gagal.
     */
    public function optimizeExistingFile(string $relativePath, string $disk = 'public'): ?string
    {
        if (empty($relativePath) || str_starts_with($relativePath, 'http')) {
            return $relativePath;
        }

        if (! Storage::disk($disk)->exists($relativePath)) {
            return $relativePath;
        }

        $fullPath = Storage::disk($disk)->path($relativePath);
        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

        // Jika sudah webp, periksa apakah resolusinya perlu di-scale down
        $maxWidth = config('image-optimizer.max_width', 1600);
        $maxHeight = config('image-optimizer.max_height', 1600);
        $quality = config('image-optimizer.webp_quality', 80);

        try {
            $image = $this->manager->read($fullPath);
            $origWidth = $image->width();
            $origHeight = $image->height();

            $needsResize = ($origWidth > $maxWidth || $origHeight > $maxHeight);
            $needsWebpConversion = ($extension !== 'webp');

            if (! $needsResize && ! $needsWebpConversion) {
                return $relativePath; // Sudah optimal
            }

            if ($needsResize) {
                $image->scaleDown($maxWidth, $maxHeight);
            }

            $webpData = $image->toWebp($quality)->toString();

            $dir = dirname($relativePath);
            $filenameWithoutExt = pathinfo($relativePath, PATHINFO_FILENAME);
            $newRelativePath = ($dir === '.' ? '' : $dir.'/').$filenameWithoutExt.'.webp';

            // Jika path baru berbeda dari path lama, simpan file webp baru
            Storage::disk($disk)->put($newRelativePath, $webpData);

            return $newRelativePath;
        } catch (\Throwable $e) {
            Log::error('ImageOptimizationService: Gagal mengoptimasi file existing.', [
                'path' => $relativePath,
                'error' => $e->getMessage(),
            ]);

            return $relativePath;
        }
    }
}
