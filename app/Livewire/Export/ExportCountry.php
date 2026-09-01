<?php

namespace App\Livewire\Export;

use App\Models\Category;
use App\Models\GalleryMedia;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\ExportCountryService;
use Livewire\Component;

class ExportCountry extends Component
{
    public string $countrySlug;

    public string $search = '';

    public string $categorySlug = '';

    public int $perPage = 8;

    // B2B RFQ Form State
    public string $fullName = '';

    public string $companyName = '';

    public string $businessEmail = '';

    public string $buyerRole = 'Architect';

    public string $productInterest = 'Concrete Ventilation Blocks';

    public string $estimatedQuantity = '1,000–5,000 Pieces';

    public string $projectDetails = '';

    public bool $rfqSubmitted = false;

    public function submitRfq()
    {
        $this->validate([
            'fullName' => 'required|min:2',
            'businessEmail' => 'required|email',
        ]);

        $this->rfqSubmitted = true;

        $countryData = ExportCountryService::resolveCountryData($this->countrySlug);
        $countryName = $countryData['name'] ?? ucfirst($this->countrySlug);

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $waMsg = "Hello IndoRoster Export Desk,\n\n"
            ."I would like to request an official B2B export quotation for a project in {$countryName}.\n\n"
            ."*Buyer Details:*\n"
            ."• Name: {$this->fullName}\n"
            .'• Company: '.($this->companyName ?: '-')."\n"
            ."• Email: {$this->businessEmail}\n"
            ."• Role: {$this->buyerRole}\n"
            ."• Product Interest: {$this->productInterest}\n"
            ."• Estimated Quantity: {$this->estimatedQuantity}\n"
            .'• Project Details: '.($this->projectDetails ?: 'Standard Project Inquiry')."\n\n"
            .'Please provide specifications, FOB/CIF pricing, and container delivery schedules. Thank you.';

        $this->dispatch('rfq-redirect', url: 'https://wa.me/'.$waNumber.'?text='.urlencode($waMsg));
    }

    public function mount(string $countrySlug)
    {
        $this->countrySlug = strtolower(trim($countrySlug));
        $countryData = ExportCountryService::resolveCountryData($this->countrySlug);

        if (empty($countryData)) {
            abort(404);
        }
    }

    public function loadMore()
    {
        $this->perPage += 8;
    }

    public function render()
    {
        $productQuery = Product::where('is_active', true)
            ->with(['media', 'variants', 'category']);

        if (! empty($this->search)) {
            $productQuery->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->categorySlug)) {
            $productQuery->whereHas('category', fn ($q) => $q->where('slug', $this->categorySlug));
        }

        $products = $productQuery->paginate($this->perPage);
        $categories = Category::orderBy('name')->get();

        $randomGalleryMedia = GalleryMedia::with('gallery.product')
            ->where('media_type', 'image')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $countryData = ExportCountryService::resolveCountryData($this->countrySlug);

        return view('livewire.export.export-country', [
            'country' => $countryData,
            'products' => $products,
            'categories' => $categories,
            'randomGalleryMedia' => $randomGalleryMedia,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $countryData['meta_title'] ?? ('Breeze Blocks Supplier '.$countryData['name']),
            'description' => $countryData['meta_description'] ?? ('Export supply of breeze blocks to '.$countryData['name']),
            'canonicalOverride' => url('/export/'.$this->countrySlug),
        ]);
    }
}
