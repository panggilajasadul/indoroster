<?php

use App\Models\Gallery;
use Illuminate\Support\Str;

// List of words that indicate it's already an SEO title in Indonesian
$indoKeywords = ['Inspirasi', 'Desain', 'Pemasangan', 'Aksen', 'Sekat', 'Pagar', 'Fasad', 'Dinding', 'Roster Beton'];

$galleries = Gallery::all()->filter(function($gallery) use ($indoKeywords) {
    // If it's video-inspirasi, we definitely want to SEO-ify it too if it's still short/English
    if ($gallery->category === 'video-inspirasi') {
        return strlen($gallery->title) < 25 || !Str::contains($gallery->title, $indoKeywords);
    }
    
    // For others, if it's short or doesn't have a description, or matches English patterns
    return strlen($gallery->title) < 35 || 
           empty($gallery->description) || 
           Str::contains($gallery->title, ['Modern', 'Minimalist', 'Design', 'Idea', 'Highlight', 'Look', 'Architecture']);
});

$seoPatterns = [
    'fasad' => [
        'titles' => [
            'Fasad Rumah Modern dengan Roster Beton Minimalis',
            'Desain Fasad Estetik Menggunakan Breeze Block Indoroster',
            'Aksen Fasad Rumah Industrial dengan Roster Beton Putih',
            'Penerapan Roster Beton pada Fasad Rumah Tropis Modern',
            'Tampilan Fasad Mewah dengan Dinding Roster Dekoratif',
            'Inspirasi Rumah Sejuk: Fasad Roster Beton Anti Panas',
            'Facade Cantik Rumah Minimalis dengan Lubang Angin Roster',
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
            'Pagar Roster Motif Minimalis untuk Rumah Industrial Modern',
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
            'Dinding Partisi Roster untuk Interior Rumah Lebih Berwarna',
        ],
        'desc' => 'Ciptakan interior rumah yang unik dan berkarakter dengan aplikasi sekat ruang atau aksen dinding menggunakan roster beton dekoratif.'
    ],
    'video-inspirasi' => [
        'titles' => [
            'Video Inspirasi: Cara Pasang Roster Beton yang Benar & Estetik',
            'Review Pemasangan Roster Beton Minimalis di Lokasi Proyek',
            'Video Tutorial: Ide Kreatif Dinding Roster untuk Rumah',
            'Inspirasi Video: Transformasi Rumah dengan Breeze Block',
            'Video Dokumentasi: Hasil Pemasangan Roster Indoroster',
            'Tonton: Keindahan Aksen Roster Beton di Hunian Modern',
        ],
        'desc' => 'Lihat langsung video dokumentasi dan inspirasi pemasangan roster beton kami di berbagai lokasi proyek untuk hasil yang maksimal.'
    ],
    'default' => [
        'titles' => [
            'Inspirasi Pemasangan Roster Beton Minimalis Indoroster',
            'Koleksi Proyek Roster Beton untuk Rumah Modern',
            'Desain Roster Beton Estetik: Fungsional dan Dekoratif',
            'Aplikasi Breeze Block Minimalis pada Bangunan Modern',
            'Roster Beton Indoroster: Pilihan Terbaik untuk Hunian Anda',
            'Solusi Sirkulasi Udara Rumah dengan Roster Beton Estetik',
        ],
        'desc' => 'Lihat berbagai inspirasi penggunaan roster beton atau breeze block pada berbagai bagian bangunan untuk menciptakan hunian yang estetik dan nyaman.'
    ]
];

echo "Updating " . $galleries->count() . " records...\n";

foreach ($galleries as $index => $gallery) {
    $cat = $gallery->category;
    $pattern = $seoPatterns[$cat] ?? ($seoPatterns[str_replace(' ', '-', strtolower($cat))] ?? $seoPatterns['default']);
    
    $titles = $pattern['titles'];
    $newTitle = $titles[$index % count($titles)];
    $newDesc = $pattern['desc'];
    
    // Add uniqueness if title already exists
    if (Gallery::where('title', $newTitle)->where('id', '!=', $gallery->id)->exists()) {
        $newTitle .= " " . ($index + 1);
    }

    $gallery->update([
        'title' => $newTitle,
        'description' => $newDesc,
        'slug' => Str::slug($newTitle) . '-' . Str::random(5)
    ]);
}

echo "Done updating records.\n";
