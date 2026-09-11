<?php

use App\Helpers\CloudinaryHelper;

if (! function_exists('cloudinary_img')) {
    /**
     * Transformasi URL Cloudinary ke WebP + resize + quality otomatis.
     *
     * @param  string|null  $url  URL Cloudinary asli
     * @param  int|null  $width  Lebar (px), null = auto
     * @param  int|null  $height  Tinggi (px), null = auto
     * @param  string  $crop  Mode crop: scale, fill, fit, thumb
     */
    function cloudinary_img(?string $url, ?int $width = null, ?int $height = null, string $crop = 'scale'): string
    {
        return CloudinaryHelper::transform($url, $width, $height, $crop);
    }
}

if (! function_exists('cloudinary_thumb')) {
    /** Thumbnail kotak untuk kartu produk/katalog */
    function cloudinary_thumb(?string $url, int $size = 300): string
    {
        return CloudinaryHelper::thumb($url, $size);
    }
}

if (! function_exists('cloudinary_banner')) {
    /** Gambar hero banner — full-width optimized */
    function cloudinary_banner(?string $url, int $width = 1280): string
    {
        return CloudinaryHelper::banner($url, $width);
    }
}

if (! function_exists('cloudinary_gallery')) {
    /** Gambar galeri — medium size */
    function cloudinary_gallery(?string $url, int $width = 800, int $height = 600): string
    {
        return CloudinaryHelper::gallery($url, $width, $height);
    }
}

if (! function_exists('cloudinary_product')) {
    /** Gambar produk standar */
    function cloudinary_product(?string $url, int $width = 600, int $height = 600): string
    {
        return CloudinaryHelper::product($url, $width, $height);
    }
}

if (! function_exists('cloudinary_article')) {
    /** Gambar artikel/lokasi — landscape */
    function cloudinary_article(?string $url, int $width = 800, int $height = 500): string
    {
        return CloudinaryHelper::article($url, $width, $height);
    }
}
