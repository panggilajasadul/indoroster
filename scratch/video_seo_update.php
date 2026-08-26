<?php

use App\Models\Gallery;
use Illuminate\Support\Str;

$galleries = Gallery::where('category', 'video-inspirasi')->get();

$videoPatterns = [
    'titles' => [
        'Video Inspirasi: Cara Pasang Roster Beton yang Benar & Estetik',
        'Review Pemasangan Roster Beton Minimalis di Lokasi Proyek',
        'Video Tutorial: Ide Kreatif Dinding Roster untuk Rumah',
        'Inspirasi Video: Transformasi Rumah dengan Breeze Block',
        'Video Dokumentasi: Hasil Pemasangan Roster Indoroster',
        'Tonton: Keindahan Aksen Roster Beton di Hunian Modern',
        'Ide Desain Dinding Roster: Video Portfolio Indoroster',
        'Proses Pemasangan Roster Beton: Tips dan Trik Video',
        'Review Hasil Jadi Pemasangan Roster di Rumah Minimalis',
    ],
    'desc' => 'Tonton video dokumentasi inspiratif mengenai penggunaan roster beton minimalis untuk mempercantik hunian Anda.',
];

echo 'Updating '.$galleries->count()." video records...\n";

foreach ($galleries as $index => $gallery) {
    $titles = $videoPatterns['titles'];
    $newTitle = $titles[$index % count($titles)];
    $newDesc = $videoPatterns['desc'];

    // Add uniqueness
    if (Gallery::where('title', $newTitle)->where('id', '!=', $gallery->id)->exists()) {
        $newTitle .= ' '.($index + 1);
    }

    $gallery->update([
        'title' => $newTitle,
        'description' => $newDesc,
        'slug' => Str::slug($newTitle).'-'.Str::random(5),
    ]);
}

echo "Done updating video records.\n";
