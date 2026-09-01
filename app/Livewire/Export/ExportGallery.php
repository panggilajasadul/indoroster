<?php

namespace App\Livewire\Export;

use App\Models\Category;
use App\Models\GalleryMedia;
use App\Models\SiteSetting;
use Livewire\Component;

class ExportGallery extends Component
{
    public string $selectedCategory = '';

    public string $search = '';

    public int $perPage = 12;

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function render()
    {
        $query = GalleryMedia::with(['gallery.product.category'])
            ->where('media_type', 'image');

        if (! empty($this->search)) {
            $query->whereHas('gallery', function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%')
                    ->orWhereHas('product', function ($pq) {
                        $pq->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        if (! empty($this->selectedCategory)) {
            $query->whereHas('gallery.product.category', function ($q) {
                $q->where('slug', $this->selectedCategory);
            });
        }

        $photos = $query->latest()->paginate($this->perPage);
        $categories = Category::orderBy('name')->get();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        return view('livewire.export.export-gallery', [
            'photos' => $photos,
            'categories' => $categories,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => 'International Project Portfolio & Architectural Breeze Blocks Gallery | IndoRoster Export',
            'description' => 'Explore real architectural installations of IndoRoster 90° precision breeze blocks and concrete ventilation blocks on luxury bungalows, cafes, resorts, and modern facades.',
            'canonicalOverride' => url('/export/gallery'),
        ]);
    }
}
