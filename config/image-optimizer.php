<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Image Compression & WebP Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi kompresi otomatis dan konversi gambar ke format WebP.
    | Kompatibel penuh dengan Hostinger Shared Hosting (PHP GD Driver).
    |
    */

    'max_width' => (int) env('IMAGE_MAX_WIDTH', 1600),
    'max_height' => (int) env('IMAGE_MAX_HEIGHT', 1600),
    'webp_quality' => (int) env('IMAGE_WEBP_QUALITY', 80),

    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp'],

    // Ukuran maksimal upload dalam Kilobytes (10MB = 10240 KB)
    'max_upload_size_kb' => (int) env('IMAGE_MAX_UPLOAD_SIZE_KB', 10240),

    // Disk penyimpanan default untuk gambar publik
    'disk' => env('IMAGE_STORAGE_DISK', 'public'),
];
