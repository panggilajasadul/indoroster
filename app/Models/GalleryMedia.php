<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryMedia extends Model
{
    protected $table = 'gallery_media';

    protected $fillable = [
        'gallery_id',
        'media_url',
        'media_type',
        'caption',
        'alt_text',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::saving(function (GalleryMedia $media) {
            if (empty($media->alt_text)) {
                $gallery = $media->gallery;
                if ($gallery) {
                    $title = $gallery->title;
                    $category = $gallery->category ?: 'roster';

                    // Bersihkan slug/nama kategori
                    $categoryName = str_replace('-', ' ', $category);

                    // Gunakan lokasi target atau acak salah satu lokasi Jabodetabek untuk variasi SEO
                    $locations = ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 'Bandung', 'Jabodetabek'];
                    $selectedLoc = $gallery->location ?: $locations[rand(0, count($locations) - 1)];

                    $templates = [
                        "Foto {$title} - Inspirasi pemasangan roster {$categoryName} minimalis di {$selectedLoc}",
                        "Desain roster {$categoryName} {$title} - Hasil pengerjaan di {$selectedLoc}",
                        "Pemasangan roster modern {$title} untuk {$categoryName} di {$selectedLoc}",
                        "Supplier roster minimalis {$selectedLoc} - Proyek {$title} ({$categoryName})",
                    ];

                    $media->alt_text = $templates[rand(0, count($templates) - 1)];
                } else {
                    $media->alt_text = 'Inspirasi desain roster minimalis modern INDOROSTER';
                }
            }
        });
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}
