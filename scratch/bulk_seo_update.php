<?php

use App\Models\Gallery;
use Illuminate\Support\Str;

$galleries = Gallery::where('title', 'LIKE', '%The implementation of minimalist%')
    ->orWhere('title', 'LIKE', '%Interior Living Room%')
    ->orWhere('title', 'LIKE', '%Outdoor Terrace%')
    ->orWhere('title', 'LIKE', '%Roster Architecture%')
    ->orWhere('title', 'LIKE', '%Creative Roster Wall%')
    ->get();

$seoPatterns = [
    'fasad' => [
        'titles' => [
            'Fasad Rumah Modern dengan Roster Beton Minimalis',
            'Desain Fasad Estetik Menggunakan Breeze Block Indoroster',
            'Aksen Fasad Rumah Industrial dengan Roster Beton Putih',
            'Penerapan Roster Beton pada Fasad Rumah Tropis Modern',
            'Tampilan Fasad Mewah dengan Dinding Roster Dekoratif',
        ],
        'desc' => 'Transformasi tampilan luar rumah dengan penggunaan roster beton minimalis yang memberikan sirkulasi udara maksimal dan kesan estetik.'
    ],
    'pagar' => [
        'titles' => [
            'Inspirasi Pagar Roster Beton Minimalis untuk Keamanan & Estetika',
            'Desain Pagar Rumah Modern dengan Aksen Roster Abu-abu',
            'Pagar Minimalis Kombinasi Roster Beton dan Besi Industrial',
            'Koleksi Pagar Roster Estetik untuk Hunian Masa Kini',
            'Keunggulan Pagar Roster Beton: Kuat, Kokoh, dan Berkarakter',
        ],
        'desc' => 'Dapatkan inspirasi desain pagar rumah minimalis menggunakan roster beton yang tidak hanya aman tapi juga menambah nilai artistik properti Anda.'
    ],
    'interior' => [
        'titles' => [
            'Sekat Ruang Interior Minimalis Menggunakan Roster Beton',
            'Aksen Dinding Interior dengan Roster Beton untuk Pencahayaan Alami',
            'Inspirasi Pembatas Ruangan Estetik dengan Breeze Block',
            'Interior Rumah Sejuk dengan Ventilasi Roster Beton Minimalis',
            'Penerapan Roster Beton pada Ruang Tengah Rumah Modern',
        ],
        'desc' => 'Ciptakan interior rumah yang unik dan berkarakter dengan aplikasi sekat ruang atau aksen dinding menggunakan roster beton dekoratif.'
    ],
    'ruang-tamu' => [
        'titles' => [
            'Desain Ruang Tamu Industrial dengan Sekat Roster Beton',
            'Aksen Roster pada Ruang Tamu untuk Kesan Mewah & Elegan',
            'Inspirasi Ruang Tamu Terang dengan Lubang Angin Roster',
            'Ruang Tamu Minimalis dengan Dinding Dekoratif Roster Putih',
            'Tampilan Ruang Tamu Modern dengan Breeze Block Indoroster',
        ],
        'desc' => 'Maksimalkan estetika ruang tamu Anda dengan penggunaan roster beton sebagai pembatas ruang yang fungsional dan indah.'
    ],
    'teras' => [
        'titles' => [
            'Dekorasi Teras Rumah Minimalis dengan Dinding Roster',
            'Area Teras Sejuk dengan Ventilasi Roster Beton Dekoratif',
            'Inspirasi Teras Belakang dengan Aksen Roster Industrial',
            'Teras Rumah Estetik dengan Pembatas Roster Beton Putih',
            'Suasana Teras Nyaman dengan Sirkulasi Udara dari Roster',
        ],
        'desc' => 'Buat area teras Anda menjadi tempat bersantai yang nyaman dengan sirkulasi udara lancar melalui dinding roster beton minimalis.'
    ],
    'default' => [
        'titles' => [
            'Inspirasi Pemasangan Roster Beton Minimalis Indoroster',
            'Koleksi Proyek Roster Beton untuk Rumah Modern',
            'Desain Roster Beton Estetik: Fungsional dan Dekoratif',
            'Aplikasi Breeze Block Minimalis pada Bangunan Modern',
            'Roster Beton Indoroster: Pilihan Terbaik untuk Hunian Anda',
        ],
        'desc' => 'Lihat berbagai inspirasi penggunaan roster beton atau breeze block pada berbagai bagian bangunan untuk menciptakan hunian yang estetik dan nyaman.'
    ]
];

echo "Updating " . $galleries->count() . " records...\n";

foreach ($galleries as $index => $gallery) {
    $cat = $gallery->category;
    $pattern = $seoPatterns[$cat] ?? $seoPatterns['default'];
    
    // Pick a title based on index to ensure variety
    $titles = $pattern['titles'];
    $newTitle = $titles[$index % count($titles)];
    $newDesc = $pattern['desc'];
    
    // Add some variety to the title if it's been used before in this loop
    if (Gallery::where('title', $newTitle)->exists()) {
        $newTitle .= " - Proyek " . ($index + 1);
    }

    $gallery->update([
        'title' => $newTitle,
        'description' => $newDesc,
        'slug' => Str::slug($newTitle) . '-' . Str::random(5)
    ]);
}

echo "Done updating records.\n";
