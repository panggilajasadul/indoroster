<?php

namespace App\Livewire\Export;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;

class ExportCatalog extends Component
{
    public string $selectedCategory = '';

    public string $search = '';

    public int $perPage = 16;

    public function loadMore()
    {
        $this->perPage += 16;
    }

    public function render()
    {
        $query = Product::where('is_active', true)
            ->with(['media', 'variants', 'category']);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->selectedCategory)) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->selectedCategory);
            });
        }

        $products = $query->orderBy('name')->paginate($this->perPage);
        $categories = Category::orderBy('name')->get();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        return view('livewire.export.export-catalog', [
            'products' => $products,
            'categories' => $categories,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => 'Complete International Breeze Blocks & Roster Export Catalog (No Prices) | IndoRoster',
            'description' => 'Browse full export collection of 45+ architectural 90° precision steel-mould breeze blocks, screen blocks, and terracotta ventilation blocks for Singapore, Malaysia & Brunei.',
            'canonicalOverride' => url('/export/catalog'),
        ]);
    }
}
