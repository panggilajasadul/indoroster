<?php

namespace App\Helpers;

/**
 * CloudinaryHelper — Transformasi URL Cloudinary untuk optimasi performa.
 *
 * Cara pakai di Blade:
 *   {{ cloudinary_img($url, 800, 600) }}         → WebP, lebar 800, tinggi 600
 *   {{ cloudinary_img($url, 400) }}              → WebP, lebar 400, tinggi auto
 *   {{ cloudinary_thumb($url, 200) }}            → Thumbnail 200×200, crop center
 *   {{ cloudinary_banner($url) }}                → Banner hero, full-width optimized
 *
 * Semua transformasi menggunakan:
 *   - f_auto  → format otomatis (WebP/AVIF tergantung browser)
 *   - q_auto  → kualitas otomatis (Cloudinary AI compression)
 *   - dpr_auto → densitas piksel sesuai layar (Retina support)
 */
class CloudinaryHelper
{
    /**
     * Transformasi URL Cloudinary dengan parameter custom.
     *
     * @param  string  $url  URL Cloudinary asli
     * @param  int|null  $width  Lebar target (pixel)
     * @param  int|null  $height  Tinggi target (pixel), null = auto
     * @param  string  $crop  Mode crop: scale, fill, fit, thumb, crop
     * @param  string  $quality  Kualitas: auto, auto:good, auto:eco, atau angka 1-100
     * @return string URL yang sudah dioptimasi
     */
    public static function transform(
        ?string $url,
        ?int $width = null,
        ?int $height = null,
        string $crop = 'scale',
        string $quality = 'auto:good'
    ): string {
        if (empty($url)) {
            return '';
        }

        // Hanya proses URL Cloudinary — URL lain dikembalikan apa adanya
        if (! str_contains($url, 'res.cloudinary.com')) {
            return $url;
        }

        // Bangun string transformasi
        $params = [];
        $params[] = 'f_auto';           // Format otomatis (WebP/AVIF)
        $params[] = "q_{$quality}";     // Kualitas otomatis Cloudinary AI
        $params[] = 'dpr_auto';         // Retina/HiDPI support

        if ($width) {
            $params[] = "w_{$width}";
        }
        if ($height) {
            $params[] = "h_{$height}";
        }
        if ($width || $height) {
            $params[] = "c_{$crop}";    // Mode crop hanya diperlukan jika ada dimensi
        }

        $transformation = implode(',', $params);

        // Sisipkan transformasi setelah /upload/ di URL Cloudinary
        // Contoh: .../image/upload/v1234/file.jpg → .../image/upload/f_auto,q_auto,w_800/v1234/file.jpg
        return preg_replace(
            '#(res\.cloudinary\.com/.+/image/upload/)#',
            '$1'.$transformation.'/',
            $url
        );
    }

    /**
     * Gambar produk standar — WebP, lebar 600px, kualitas baik
     */
    public static function product(?string $url, int $width = 600, int $height = 600): string
    {
        return static::transform($url, $width, $height, 'fill', 'auto:good');
    }

    /**
     * Thumbnail kecil — untuk kartu produk di katalog, 300×300
     */
    public static function thumb(?string $url, int $size = 300): string
    {
        return static::transform($url, $size, $size, 'fill', 'auto:eco');
    }

    /**
     * Banner hero — lebar penuh, optimasi agresif untuk LCP
     */
    public static function banner(?string $url, int $width = 1280): string
    {
        return static::transform($url, $width, null, 'scale', 'auto:good');
    }

    /**
     * Gambar galeri — medium size
     */
    public static function gallery(?string $url, int $width = 800, int $height = 600): string
    {
        return static::transform($url, $width, $height, 'fill', 'auto:good');
    }

    /**
     * Gambar lokasi/artikel — landscape 800×500
     */
    public static function article(?string $url, int $width = 800, int $height = 500): string
    {
        return static::transform($url, $width, $height, 'fill', 'auto:good');
    }
}
