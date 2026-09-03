<?php

namespace App\Livewire;

use App\Mail\OrderStatusMail;
use App\Models\Address;
use App\Models\Cart as CartModel;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingRate;
use App\Models\SiteSetting;
use App\Models\Voucher;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Livewire\Component;

class Checkout extends Component
{
    public $cartItems = [];

    public $subtotal = 0;

    public $shippingCost = 0; // Default 0, could be calculated based on city

    public $discountAmount = 0;

    public $grandTotal = 0;

    // Form Fields
    public $name;

    public $email;

    public $phone;

    // Address fields (Laravolt)
    public $provinces = [];

    public $cities = [];

    public $districts = [];

    public $villages = [];

    public $province_id;

    public $city_id;

    public $district_id;

    public $village_id;

    public $address;

    public $postal_code;

    public $postalCodes = [];

    public $latitude = null;

    public $longitude = null;

    public $notes;

    public $minOrderQty = 0;

    public $totalQty = 0;

    public $snapToken = null;

    public $mode = '';

    public $isProcessing = false;

    // Requested Batch Delivery (Large order options)
    public $requestedBatchDelivery = false;

    public $batchPreference = 'tiap_minggu';

    public $batchCustomNotes = '';

    // Saved Addresses
    public $savedAddresses = [];

    public $selectedAddressId = null;

    // Voucher & Promo
    public $voucherCode = '';

    public $appliedVoucher = null;

    public $voucherMessage = '';

    public $voucherError = '';

    public function applyVoucher()
    {
        $this->voucherError = '';
        $this->voucherMessage = '';

        if (empty(trim($this->voucherCode))) {
            $this->voucherError = 'Silakan masukkan kode voucher.';

            return;
        }

        $voucher = Voucher::where('code', strtoupper(trim($this->voucherCode)))->active()->first();

        if (! $voucher) {
            $this->voucherError = 'Kode voucher tidak valid atau telah kedaluwarsa.';

            return;
        }

        if ($this->totalQty < $voucher->min_order_qty) {
            $this->voucherError = "Syarat minimal order voucher ini adalah {$voucher->min_order_qty} pcs (Pesanan Anda: {$this->totalQty} pcs).";

            return;
        }

        if ($this->subtotal < $voucher->min_order_amount) {
            $this->voucherError = 'Syarat minimal belanja voucher ini adalah Rp'.number_format($voucher->min_order_amount, 0, ',', '.').'.';

            return;
        }

        // Cek kecocokan wilayah pengiriman pembeli
        $locationName = '';
        if ($this->selectedAddressId) {
            $savedAddr = Address::find($this->selectedAddressId);
            if ($savedAddr) {
                $locationName = trim("{$savedAddr->province} {$savedAddr->city} {$savedAddr->district} {$savedAddr->full_address}");
            }
        }

        if (empty($locationName)) {
            $cityName = '';
            $provName = '';
            if ($this->city_id) {
                $city = City::where('code', $this->city_id)->first() ?? City::find($this->city_id);
                $cityName = $city?->name ?? '';
            }
            if ($this->province_id) {
                $prov = Province::where('code', $this->province_id)->first() ?? Province::find($this->province_id);
                $provName = $prov?->name ?? '';
            }
            $locationName = trim("{$provName} {$cityName} {$this->address}");
        }

        if (! empty($locationName) && ! $voucher->isEligibleForLocation($locationName)) {
            $allowedStr = implode(', ', $voucher->allowed_regions ?? []);
            $this->voucherError = "Voucher ini berlaku khusus wilayah: {$allowedStr} (Alamat Anda: {$locationName}).";

            return;
        }

        $this->appliedVoucher = $voucher;
        $this->voucherMessage = "Voucher {$voucher->name} berhasil digunakan!";
        $this->calculateTotal();
    }

    public function removeVoucher()
    {
        $this->appliedVoucher = null;
        $this->voucherCode = '';
        $this->voucherMessage = '';
        $this->voucherError = '';
        $this->calculateTotal();
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|min:3',
            'phone' => 'required|min:8',
            'address' => 'required|min:5',
        ];

        if ($this->orderMode === 'midtrans') {
            $rules['email'] = 'required|email';
            $rules['postal_code'] = 'required';
        } else {
            $rules['email'] = 'nullable|email';
            $rules['postal_code'] = 'nullable';
        }

        // If a saved address is not selected, require location selection fields (Laravolt)
        if (! $this->selectedAddressId) {
            $rules['province_id'] = 'required';
            $rules['city_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['village_id'] = 'nullable';
        }

        return $rules;
    }

    public function useManualAddress()
    {
        $this->selectedAddressId = null;

        // Reset form fields
        $this->name = auth()->check() ? auth()->user()->name : '';
        $this->email = auth()->check() ? auth()->user()->email : '';
        $this->phone = auth()->check() ? auth()->user()->phone : '';
        $this->address = '';
        $this->postal_code = '';
        $this->postalCodes = [];
        $this->latitude = null;
        $this->longitude = null;
        $this->province_id = null;
        $this->city_id = null;
        $this->district_id = null;
        $this->village_id = null;
        $this->cities = [];
        $this->districts = [];
        $this->villages = [];
        $this->shippingCost = 0;
        $this->minOrderQty = 0;

        $this->calculateTotal();
    }

    public string $orderMode = 'midtrans';

    public function getOrderModeProperty(): string
    {
        return $this->orderMode ?: SiteSetting::getValue('order_mode', 'midtrans');
    }

    public function mount()
    {
        $this->orderMode = SiteSetting::getValue('order_mode', 'midtrans');
        $this->mode = request()->query('mode', '');

        $this->loadCart();

        if (count($this->cartItems) === 0) {
            return redirect('/keranjang');
        }

        $this->provinces = Province::orderBy('name')->get();

        if (auth()->check()) {
            $user = auth()->user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;

            // Pemuatan Alamat Tersimpan
            $this->savedAddresses = $user->addresses()->orderByDesc('is_default')->get();
            $defaultAddress = $this->savedAddresses->where('is_default', true)->first() ?? $this->savedAddresses->first();
            if ($defaultAddress) {
                $this->selectedAddressId = $defaultAddress->id;
                $this->selectAddress($defaultAddress->id);
            }
        } else {
            $this->savedAddresses = collect([]);
        }
    }

    public function selectAddress($addressId)
    {
        $address = Address::find($addressId);
        if ($address && $address->user_id === auth()->id()) {
            $this->selectedAddressId = $address->id;
            $this->name = $address->recipient_name;
            $this->phone = $address->phone;
            $this->address = $address->full_address;
            $this->postal_code = $address->postal_code;
            $this->latitude = $address->latitude;
            $this->longitude = $address->longitude;

            // Cari kode Laravolt berdasarkan teks alamat tersimpan
            $prov = Province::where('name', 'like', $address->province)->first();
            if ($prov) {
                $this->province_id = $prov->code;

                // Trigger updatedProvinceId manual
                $this->cities = City::where('province_code', $prov->code)
                    ->select(['code', 'name'])
                    ->orderBy('name')
                    ->get()
                    ->map(fn ($c) => ['value' => $c->code, 'text' => $c->name])
                    ->toArray();

                $city = City::where('province_code', $prov->code)
                    ->where('name', 'like', $address->city)
                    ->first();
                if ($city) {
                    $this->city_id = $city->code;

                    // Trigger updatedCityId manual
                    $this->districts = District::where('city_code', $city->code)
                        ->select(['code', 'name'])
                        ->orderBy('name')
                        ->get()
                        ->map(fn ($d) => ['value' => $d->code, 'text' => $d->name])
                        ->toArray();

                    $dist = District::where('city_code', $city->code)
                        ->where('name', 'like', $address->district)
                        ->first();
                    if ($dist) {
                        $this->district_id = $dist->code;

                        // Muat kelurahan manual
                        $this->villages = Village::where('district_code', $dist->code)
                            ->select(['code', 'name'])
                            ->orderBy('name')
                            ->get()
                            ->map(fn ($v) => ['value' => $v->code, 'text' => $v->name])
                            ->toArray();

                        // Karena tabel address tidak menyimpan kelurahan terpisah secara eksplisit, kita biarkan kosong agar diisi/bisa dideteksi dari teks
                        $this->village_id = null;
                    }
                }
            }
            $this->calculateTotal();
        }
    }

    public function saveCoordinateAndAddress()
    {
        if (! $this->latitude || ! $this->longitude) {
            $this->dispatch('alert', ['type' => 'warning', 'message' => 'Silakan tentukan titik koordinat pada peta terlebih dahulu.']);

            return;
        }

        if (auth()->check()) {
            $prov = Province::where('code', $this->province_id)->first();
            $city = City::where('code', $this->city_id)->first();
            $dist = District::where('code', $this->district_id)->first();
            $vill = Village::where('code', $this->village_id)->first();

            $user = auth()->user();
            $hasAddress = $user->addresses()->exists();

            $fullText = $this->address.($vill ? ', '.$vill->name : '');

            $addressRecord = $user->addresses()->create([
                'label' => 'Alamat Pengiriman',
                'recipient_name' => $this->name ?: $user->name,
                'phone' => $this->phone ?: $user->phone,
                'province' => $prov ? $prov->name : '',
                'city' => $city ? $city->name : '',
                'district' => $dist ? $dist->name : '',
                'postal_code' => $this->postal_code,
                'full_address' => $fullText,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'is_default' => ! $hasAddress,
            ]);

            $this->savedAddresses = $user->addresses()->orderByDesc('is_default')->get();
            $this->selectedAddressId = $addressRecord->id;

            $this->dispatch('alert', ['type' => 'success', 'message' => 'Alamat dan titik koordinat berhasil disimpan ke Buku Alamat Anda!']);
        } else {
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Titik koordinat berhasil diset untuk pengiriman pesanan ini!']);
        }
    }

    public function updatedProvinceId($value)
    {
        $this->cities = City::where('province_code', $value)
            ->select(['code', 'name'])
            ->orderBy('name')
            ->get()
            ->map(fn ($c) => ['value' => $c->code, 'text' => $c->name])
            ->toArray();
        $this->city_id = null;
        $this->district_id = null;
        $this->village_id = null;
        $this->districts = [];
        $this->villages = [];
        $this->postalCodes = [];
        $this->calculateTotal();
    }

    public function updatedCityId($value)
    {
        $this->districts = District::where('city_code', $value)
            ->select(['code', 'name'])
            ->orderBy('name')
            ->get()
            ->map(fn ($d) => ['value' => $d->code, 'text' => $d->name])
            ->toArray();
        $this->district_id = null;
        $this->village_id = null;
        $this->villages = [];
        $this->postalCodes = [];
        $this->calculateTotal();
    }

    public function updatedDistrictId($value)
    {
        $villagesList = Village::where('district_code', $value)
            ->select(['code', 'name', 'meta'])
            ->orderBy('name')
            ->get();

        $this->villages = $villagesList
            ->map(fn ($v) => ['value' => $v->code, 'text' => $v->name])
            ->toArray();
        $this->village_id = null;

        $codes = $villagesList->map(function ($v) {
            $meta = is_string($v->meta) ? json_decode($v->meta, true) : $v->meta;

            return $meta['pos'] ?? null;
        })->filter()->unique()->values()->toArray();

        $this->postalCodes = $codes;

        if (! empty($codes)) {
            $this->postal_code = (string) $codes[0];
        }
    }

    public function updatedVillageId($value)
    {
        if ($value) {
            $village = Village::where('code', $value)->first();
            if ($village) {
                $meta = is_string($village->meta) ? json_decode($village->meta, true) : $village->meta;
                if (! empty($meta['pos'])) {
                    $this->postal_code = (string) $meta['pos'];
                }
            }
        }
    }

    public function selectPostalCode($code)
    {
        $this->postal_code = (string) $code;
    }

    public function loadCart()
    {
        if ($this->isProcessing) {
            return;
        }

        if ($this->mode === 'buy_now' && session()->has('buy_now_item')) {
            $buyNowData = session()->get('buy_now_item');
            $cartItem = new CartModel([
                'product_id' => $buyNowData['product_id'],
                'product_variant_id' => $buyNowData['product_variant_id'],
                'quantity' => $buyNowData['quantity'],
            ]);

            $product = Product::find($buyNowData['product_id']);
            if (! $product) {
                $this->cartItems = collect([]);

                return;
            }
            $cartItem->setRelation('product', $product);

            if ($buyNowData['product_variant_id']) {
                $variant = ProductVariant::find($buyNowData['product_variant_id']);
                if (! $variant) {
                    $this->cartItems = collect([]);

                    return;
                }
                $cartItem->setRelation('variant', $variant);
            }

            $this->cartItems = collect([$cartItem]);
        } else {
            $sessionId = Cookie::get('cart_session_id');
            $query = CartModel::with(['product', 'variant']);

            if (auth()->check()) {
                $query->where(function ($q) use ($sessionId) {
                    $q->where('user_id', auth()->id())
                        ->orWhere('session_id', $sessionId);
                });
            } else {
                $query->where('session_id', $sessionId);
            }

            if (session()->has('selected_cart_items')) {
                $selectedIds = (array) session()->get('selected_cart_items');
                $query->whereIn('id', array_map('intval', $selectedIds));
            }

            $this->cartItems = $query->get();
        }

        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        $this->totalQty = $this->cartItems->sum('quantity');

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->isProcessing) {
            return;
        }

        if ($this->orderMode === 'whatsapp') {
            $this->shippingCost = 0;
            $this->minOrderQty = 0;
            $this->discountAmount = 0;
            $this->grandTotal = $this->subtotal;

            return;
        }

        if ($this->city_id) {
            $rate = ShippingRate::where('city_code', $this->city_id)->where('is_active', true)->first();
            if ($rate) {
                $this->shippingCost = $rate->shipping_cost;
                $this->minOrderQty = $rate->min_order_qty;
            } else {
                $this->shippingCost = 180000; // Default
                $this->minOrderQty = 0;
            }
        } else {
            $this->shippingCost = 0;
            $this->minOrderQty = 0;
        }

        if ($this->appliedVoucher) {
            if ($this->appliedVoucher->type === 'free_shipping') {
                $this->discountAmount = (float) $this->shippingCost;
            } elseif ($this->appliedVoucher->type === 'fixed_discount') {
                $this->discountAmount = min((float) $this->appliedVoucher->discount_amount, (float) ($this->subtotal + $this->shippingCost));
            } elseif ($this->appliedVoucher->type === 'percent_discount') {
                $this->discountAmount = ((float) $this->subtotal * (float) $this->appliedVoucher->discount_amount) / 100;
            }
        } else {
            $this->discountAmount = 0;
        }

        $this->grandTotal = max(0, $this->subtotal + $this->shippingCost - $this->discountAmount);
    }

    public function processCheckout()
    {
        $this->loadCart(); // Ensure cart items and relations are fresh
        $this->validate();

        if (count($this->cartItems) === 0) {
            session()->flash('error', 'Keranjang belanja kosong.');

            return;
        }

        $this->calculateTotal();

        // Check product-level min order first
        foreach ($this->cartItems as $cartItem) {
            $min = $cartItem->product->min_order > 0 ? $cartItem->product->min_order : 1;
            if ($cartItem->quantity < $min) {
                session()->flash('error', 'Maaf, minimal order untuk produk '.$cartItem->product->name.' adalah '.$min.' pcs. Pesanan Anda saat ini baru '.$cartItem->quantity.' pcs.');

                return;
            }
        }

        if ($this->orderMode === 'whatsapp') {
            return $this->processWhatsappCheckout();
        }

        if ($this->totalQty < $this->minOrderQty) {
            session()->flash('error', 'Maaf, minimal order untuk wilayah ini adalah '.$this->minOrderQty.' pcs. Pesanan Anda saat ini baru '.$this->totalQty.' pcs.');

            return;
        }

        $this->isProcessing = true;
        $this->calculateTotal(); // Recalculate one last time to ensure accuracy

        DB::beginTransaction();
        try {
            $userId = auth()->id();

            // Get names for storage
            $provinceName = Province::where('code', $this->province_id)->first()?->name;
            $cityName = City::where('code', $this->city_id)->first()?->name;
            $districtName = District::where('code', $this->district_id)->first()?->name;
            $villageName = Village::where('code', $this->village_id)->first()?->name;

            // Resolve values if using saved address
            $shippingAddressText = '';
            if ($this->selectedAddressId) {
                $addressModel = Address::find($this->selectedAddressId);
                if ($addressModel) {
                    $provinceName = $provinceName ?: $addressModel->province;
                    $cityName = $cityName ?: $addressModel->city;
                    $districtName = $districtName ?: $addressModel->district;
                    $shippingAddressText = $addressModel->full_address;
                }
            }

            if (! $shippingAddressText) {
                $shippingAddressText = $this->address.($villageName ? ', '.$villageName : '').($districtName ? ', '.$districtName : '');
            }

            $batchNotesText = null;
            if ($this->requestedBatchDelivery) {
                $prefLabel = match ($this->batchPreference) {
                    'tiap_minggu' => 'Kirim Bertahap Tiap Minggu',
                    '2_tahap' => 'Kirim dalam 2 Tahap',
                    '4_tahap' => 'Kirim dalam 4 Tahap',
                    '8_tahap' => 'Kirim dalam 8 Tahap',
                    default => 'Kirim Bertahap Sesuai Kesiapan',
                };
                $batchNotesText = $prefLabel.($this->batchCustomNotes ? " (Catatan Proyek: {$this->batchCustomNotes})" : '');
            }

            // Create Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'status' => 'pending_payment',
                'payment_status' => 'unpaid',
                'subtotal' => $this->subtotal,
                'shipping_cost' => $this->shippingCost,
                'discount_amount' => $this->discountAmount,
                'grand_total' => $this->grandTotal,
                'shipping_name' => $this->name,
                'shipping_email' => $this->email,
                'shipping_phone' => $this->phone,
                'shipping_address' => $shippingAddressText,
                'shipping_city' => $cityName,
                'shipping_province' => $provinceName,
                'shipping_postal_code' => $this->postal_code,
                'shipping_latitude' => $this->latitude ?: ($addressModel?->latitude ?? null),
                'shipping_longitude' => $this->longitude ?: ($addressModel?->longitude ?? null),
                'notes' => $this->notes,
                'requested_batch_delivery' => $this->requestedBatchDelivery,
                'requested_batch_notes' => $batchNotesText,
            ]);

            // Create Order Items & Reduce Stock
            foreach ($this->cartItems as $cartItem) {
                if (! $cartItem->product_id) {
                    throw new \Exception('Informasi produk tidak lengkap. Silakan ulangi proses checkout.');
                }

                $price = $cartItem->variant ? $cartItem->variant->final_price : ($cartItem->product?->price ?? 0);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product?->name ?? 'Produk Tidak Tersedia',
                    'product_price' => $price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $price * $cartItem->quantity,
                ]);

                // Reduce stock
                if ($cartItem->variant) {
                    $cartItem->variant->decrement('stock', $cartItem->quantity);
                } else {
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }
            }

            // Generate Midtrans Snap Token
            $midtrans = new MidtransService;
            if (! $order->user) {
                $order->setRelation('user', (object) [
                    'name' => $this->name,
                    'email' => $this->email,
                ]);
            }

            $this->snapToken = $midtrans->getSnapToken($order);
            $order->update(['snap_token' => $this->snapToken]);

            if ($this->mode === 'buy_now') {
                session()->forget('buy_now_item');
            } else {
                session()->forget('selected_cart_items');
                $this->dispatch('cart-updated');
            }

            DB::commit();

            // Trigger JS to open Snap
            $this->dispatch('snap-pay', token: $this->snapToken, order_id: $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isProcessing = false;
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function processWhatsappCheckout()
    {
        $this->isProcessing = true;

        DB::beginTransaction();
        try {
            $userId = auth()->id();

            // Resolve location names
            $provinceName = Province::where('code', $this->province_id)->first()?->name;
            $cityName = City::where('code', $this->city_id)->first()?->name;
            $districtName = District::where('code', $this->district_id)->first()?->name;
            $villageName = Village::where('code', $this->village_id)->first()?->name;

            $shippingAddressText = '';
            $finalLat = $this->latitude;
            $finalLng = $this->longitude;

            if ($this->selectedAddressId) {
                $addressModel = Address::find($this->selectedAddressId);
                if ($addressModel) {
                    $provinceName = $provinceName ?: $addressModel->province;
                    $cityName = $cityName ?: $addressModel->city;
                    $districtName = $districtName ?: $addressModel->district;
                    $villageName = $villageName ?: $addressModel->village;
                    $this->postal_code = $this->postal_code ?: $addressModel->postal_code;
                    $shippingAddressText = $addressModel->full_address;
                    $finalLat = $finalLat ?: $addressModel->latitude;
                    $finalLng = $finalLng ?: $addressModel->longitude;
                }
            }

            if (! $shippingAddressText) {
                $shippingAddressText = $this->address;
            }

            // Susun alamat lengkap terpadu (Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos)
            $addressParts = array_filter([
                $shippingAddressText,
                ($villageName && ! str_contains(strtolower($shippingAddressText), strtolower($villageName))) ? 'Desa/Kel. '.$villageName : null,
                ($districtName && ! str_contains(strtolower($shippingAddressText), strtolower($districtName))) ? 'Kec. '.$districtName : null,
                ($cityName && ! str_contains(strtolower($shippingAddressText), strtolower($cityName))) ? $cityName : null,
                ($provinceName && ! str_contains(strtolower($shippingAddressText), strtolower($provinceName))) ? $provinceName : null,
            ]);
            $completeShippingAddress = implode(', ', $addressParts);
            if ($this->postal_code && ! str_contains($completeShippingAddress, (string) $this->postal_code)) {
                $completeShippingAddress .= ' '.$this->postal_code;
            }

            $batchNotesText = null;
            if ($this->requestedBatchDelivery) {
                $prefLabel = match ($this->batchPreference) {
                    'tiap_minggu' => 'Kirim Bertahap Tiap Minggu',
                    '2_tahap' => 'Kirim dalam 2 Tahap',
                    '4_tahap' => 'Kirim dalam 4 Tahap',
                    '8_tahap' => 'Kirim dalam 8 Tahap',
                    default => 'Kirim Bertahap Sesuai Kesiapan',
                };
                $batchNotesText = $prefLabel.($this->batchCustomNotes ? " (Catatan Proyek: {$this->batchCustomNotes})" : '');
            }

            // Create Order in Database
            $order = Order::create([
                'order_number' => Order::generateWaOrderNumber(),
                'user_id' => $userId,
                'status' => 'pending_payment',
                'payment_status' => 'unpaid',
                'order_source' => 'whatsapp',
                'subtotal' => $this->subtotal,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'grand_total' => $this->subtotal,
                'shipping_name' => $this->name ?: (auth()->user()?->name ?? 'Pelanggan'),
                'shipping_email' => $this->email ?: (auth()->user()?->email ?? null),
                'shipping_phone' => $this->phone ?: (auth()->user()?->phone ?? '-'),
                'shipping_address' => $shippingAddressText,
                'shipping_city' => $cityName,
                'shipping_district' => $districtName,
                'shipping_village' => $villageName,
                'shipping_province' => $provinceName,
                'shipping_postal_code' => $this->postal_code,
                'shipping_latitude' => $finalLat,
                'shipping_longitude' => $finalLng,
                'notes' => $this->notes,
                'requested_batch_delivery' => $this->requestedBatchDelivery,
                'requested_batch_notes' => $batchNotesText,
            ]);

            $itemLines = [];
            $idx = 1;

            // Create Order Items
            foreach ($this->cartItems as $cartItem) {
                if (! $cartItem->product_id) {
                    throw new \Exception('Informasi produk tidak lengkap. Silakan ulangi proses checkout.');
                }

                $product = $cartItem->product;
                $varName = $cartItem->variant ? ' ('.$cartItem->variant->name.')' : '';
                $price = $cartItem->variant ? $cartItem->variant->final_price : ($product?->price ?? 0);
                $itemSubtotal = $price * $cartItem->quantity;
                $prodUrl = $product ? route('product.detail', $product->slug) : '';

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $product?->name ?? 'Produk Roster',
                    'product_price' => $price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $itemSubtotal,
                ]);

                // Format WhatsApp Item Line with Direct Link
                $line = "{$idx}. *".($product?->name ?? 'Produk')."{$varName}*\n";
                $line .= '   • Jumlah: '.$cartItem->quantity.' pcs × Rp'.number_format($price, 0, ',', '.')."\n";
                $line .= '   • Subtotal: Rp'.number_format($itemSubtotal, 0, ',', '.');
                if ($prodUrl) {
                    $line .= "\n   🔗 Link: {$prodUrl}";
                }
                $itemLines[] = $line;
                $idx++;
            }

            // Clear ordered cart items
            if ($this->mode === 'buy_now') {
                session()->forget('buy_now_item');
            } else {
                $selectedIds = session()->get('selected_cart_items', []);
                if (! empty($selectedIds)) {
                    CartModel::whereIn('id', $selectedIds)->delete();
                } else {
                    $cartIds = $this->cartItems->pluck('id')->filter()->toArray();
                    if (! empty($cartIds)) {
                        CartModel::whereIn('id', $cartIds)->delete();
                    }
                }
                session()->forget('selected_cart_items');
                $this->dispatch('cart-updated');
            }

            DB::commit();

            // Pastikan Dokumen Invoice / Penawaran terbuat
            Invoice::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'invoice_number' => Invoice::generateWaInvoiceNumber(),
                    'invoice_date' => now(),
                    'subtotal' => $order->subtotal,
                    'shipping_cost' => $order->shipping_cost,
                    'discount_amount' => $order->discount_amount,
                    'grand_total' => $order->grand_total,
                    'payment_scheme' => $order->payment_scheme ?: 'full',
                    'down_payment_amount' => $order->down_payment_amount ?: 0,
                    'remaining_balance' => $order->remaining_balance ?: 0,
                    'status' => 'sent',
                ]
            );

            // Kirim Email Surat Penawaran & Proforma Tagihan ke Pembeli
            try {
                $buyerEmail = $order->shipping_email ?: (auth()->user()?->email ?? null);
                if ($buyerEmail) {
                    Mail::to($buyerEmail)->send(new OrderStatusMail($order, 'created'));
                }
            } catch (\Throwable $e) {
                Log::error('WA Checkout created email error: '.$e->getMessage());
            }

            // Prepare WhatsApp Message
            $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
            $waPhone = preg_replace('/[^0-9]/', '', (string) $rawWa);
            if (str_starts_with($waPhone, '0')) {
                $waPhone = '62'.substr($waPhone, 1);
            } elseif (str_starts_with($waPhone, '8')) {
                $waPhone = '62'.$waPhone;
            }

            $mapsLink = '';
            if ($finalLat && $finalLng) {
                $mapsLink = "\n📍 *Titik Lokasi (Google Maps):* https://maps.google.com/?q={$finalLat},{$finalLng}";
            }

            $dateNow = Carbon::now()->translatedFormat('d F Y H:i');
            $itemsString = implode("\n\n", $itemLines);
            $subtotalFormatted = 'Rp'.number_format($this->subtotal, 0, ',', '.');

            $trackingUrl = route('order.tracking', [
                'order_number' => $order->order_number,
                'contact' => $this->phone,
            ]);

            $waMessage = "Halo Admin IndoRoster, saya ingin memesan roster beton melalui website:\n\n"
                ."📋 *DETAIL PESANAN*\n"
                ."• No. Pesanan: #{$order->order_number}\n"
                ."• Tanggal: {$dateNow} WIB\n\n"
                ."📦 *DAFTAR PRODUK:*\n{$itemsString}\n\n"
                ."💰 *Total Harga Barang:* {$subtotalFormatted}\n"
                ."🚚 *Ongkir:* (Menunggu konfirmasi admin armada via WA)\n\n"
                ."👤 *DATA PEMESAN:*\n"
                ."• Nama: {$this->name}\n"
                ."• No. WhatsApp: {$this->phone}\n"
                ."• Alamat Lengkap: {$completeShippingAddress}"
                .$mapsLink."\n"
                .($this->notes ? "• Catatan: {$this->notes}\n" : '')
                ."\n🔍 *Lacak Pesanan:* {$trackingUrl}\n"
                ."\nMohon info ketersediaan stok, estimasi ongkos kirim armada pabrik, dan total pembayaran. Terima kasih!";

            $waUrl = 'https://wa.me/'.$waPhone.'?text='.rawurlencode($waMessage);

            // Buka WhatsApp di tab baru dan alihkan tab checkout ke Lacak Pesanan
            $this->dispatch('open-wa-order', [
                'waUrl' => $waUrl,
                'trackingUrl' => $trackingUrl,
            ]);

            return $this->redirect($trackingUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isProcessing = false;
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function render()
    {
        $this->loadCart();

        return view('livewire.checkout', [
            'orderMode' => $this->orderMode,
        ])->layout('components.layouts.app', [
            'title' => 'Checkout | INDOROSTER',
            'robots' => 'noindex, nofollow',
        ]);
    }
}
