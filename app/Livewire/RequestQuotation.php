<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\QuotationRequest;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Livewire\Component;

class RequestQuotation extends Component
{
    public Product $product;

    public $selectedVariant = null;

    public $variantModel = null;

    public $quantity = 100;

    // Form fields
    public $name = '';

    public $company_name = '';

    public $phone = '';

    public $email = '';

    public $province_id = null;

    public $city_id = null;

    public $address = '';

    public $installation_timeline = '';

    public $notes = '';

    // Location & Shipping
    public $provinces = [];

    public $cities = [];

    public $estimatedShippingCost = 0;

    public $shippingRateType = 'flat';

    public $selectedCityName = '';

    public $selectedProvinceName = '';

    public $minOrderQty = 0;

    // Submission State
    public $isSubmitted = false;

    public $submittedReference = '';

    public $whatsAppUrl = '';

    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)
            ->with(['media', 'variants', 'category'])
            ->firstOrFail();

        // Get query params
        $variantParam = request()->query('variant');
        if ($variantParam) {
            $variant = $this->product->variants->where('is_active', true)->firstWhere('id', (int) $variantParam);
            if ($variant) {
                $this->selectedVariant = $variant->id;
                $this->variantModel = $variant;
            }
        }

        $qtyParam = (int) request()->query('qty', 0);
        if ($qtyParam > 0) {
            $this->quantity = $qtyParam;
        } else {
            $this->quantity = $this->product->min_order > 0 ? $this->product->min_order : 100;
        }

        // Populate provinces
        $this->provinces = Province::orderBy('name')->get();

        // Auto-fill for logged-in user
        if (auth()->check()) {
            $user = auth()->user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';

            $defaultAddr = $user->addresses()->orderByDesc('is_default')->first();
            if ($defaultAddr) {
                $this->address = $defaultAddr->full_address ?? '';
                $prov = Province::where('name', 'like', $defaultAddr->province)->first();
                if ($prov) {
                    $this->province_id = $prov->code;
                    $this->updatedProvinceId($prov->code);

                    $city = City::where('province_code', $prov->code)
                        ->where('name', 'like', $defaultAddr->city)
                        ->first();
                    if ($city) {
                        $this->city_id = $city->code;
                        $this->updatedCityId($city->code);
                    }
                }
            }
        }
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            $prov = Province::where('code', $value)->first();
            $this->selectedProvinceName = $prov?->name ?? '';

            $this->cities = City::where('province_code', $value)
                ->orderBy('name')
                ->get();
        } else {
            $this->selectedProvinceName = '';
            $this->cities = [];
        }

        $this->city_id = null;
        $this->selectedCityName = '';
    }

    public function updatedCityId($value)
    {
        if ($value) {
            $city = City::where('code', $value)->first();
            $this->selectedCityName = $city?->name ?? '';
        } else {
            $this->selectedCityName = '';
        }
    }

    public function updatedSelectedVariant($value)
    {
        if ($value) {
            $this->variantModel = $this->product->variants->firstWhere('id', (int) $value);
        } else {
            $this->variantModel = null;
        }
    }

    public function submitQuotation()
    {
        $this->validate([
            'name' => 'required|min:3|max:100',
            'phone' => 'required|min:8|max:25',
            'province_id' => 'required',
            'city_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'company_name' => 'nullable|max:100',
            'email' => 'nullable|email|max:100',
            'notes' => 'nullable|max:1000',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'province_id.required' => 'Silakan pilih provinsi lokasi proyek.',
            'city_id.required' => 'Silakan pilih kota/kabupaten lokasi proyek.',
            'quantity.required' => 'Jumlah kebutuhan pcs wajib diisi.',
            'quantity.min' => 'Jumlah kebutuhan minimal 1 pcs.',
        ]);

        $quotation = QuotationRequest::create([
            'user_id' => auth()->id(),
            'product_id' => $this->product->id,
            'product_variant_id' => $this->selectedVariant,
            'name' => $this->name,
            'company_name' => $this->company_name ?: null,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'province_code' => $this->province_id,
            'city_code' => $this->city_id,
            'province_name' => $this->selectedProvinceName,
            'city_name' => $this->selectedCityName,
            'address' => $this->address ?: null,
            'quantity' => (int) $this->quantity,
            'installation_timeline' => $this->installation_timeline ?: null,
            'estimated_shipping_cost' => 0,
            'shipping_rate_type' => 'flat',
            'notes' => $this->notes ?: null,
            'status' => 'pending',
        ]);

        $this->isSubmitted = true;
        $this->submittedReference = $quotation->reference_number;
        $this->whatsAppUrl = $quotation->getWhatsAppUrl();

        // Dispatch JS event to open WhatsApp
        $this->dispatch('open-external-url', url: $this->whatsAppUrl);
    }

    public function render()
    {
        return view('livewire.request-quotation')
            ->layout('components.layouts.app', [
                'title' => 'Permintaan Penawaran Resmi: '.$this->product->name.' - IndoRoster',
                'description' => 'Formulir pengajuan permintaan penawaran harga resmi (SPH) untuk '.$this->product->name.' langsung dari pabrik tangan pertama Plered Purwakarta.',
            ]);
    }
}
