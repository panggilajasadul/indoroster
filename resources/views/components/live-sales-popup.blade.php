@props([])

@php
    $isExcludedRoute = request()->routeIs(['login', 'register', 'checkout', 'cart', 'order.tracking', 'filament.*']);
    
    // Ambil data produk nyata dari database
    $realProducts = \App\Models\Product::where('is_active', true)
        ->select('id', 'name', 'slug')
        ->with('media')
        ->inRandomOrder()
        ->take(20)
        ->get();

    // Pool daftar pembeli (PT, CV, Arsitek, Kontraktor, Personal)
    $buyersPool = [
        ['name' => 'PT Wijaya Bangun Mandiri', 'location' => 'Jakarta Barat', 'is_company' => true],
        ['name' => 'CV Citra Graha Arsitek', 'location' => 'Bandung', 'is_company' => true],
        ['name' => 'Bpk. Hendra Wijaya', 'location' => 'Jakarta Selatan', 'is_company' => false],
        ['name' => 'PT Nusa Graha Propertindo', 'location' => 'Bekasi', 'is_company' => true],
        ['name' => 'Studio Arsitek Urban Java', 'location' => 'Tangerang Selatan', 'is_company' => true],
        ['name' => 'Ibu Dr. Maya Kartika', 'location' => 'Bandung', 'is_company' => false],
        ['name' => 'PT Adhi Prima Konstruksi', 'location' => 'Surabaya', 'is_company' => true],
        ['name' => 'Arsitek Dimas Prasetyo', 'location' => 'Yogyakarta', 'is_company' => false],
        ['name' => 'CV Mega Pilar Utama', 'location' => 'Semarang', 'is_company' => true],
        ['name' => 'Bpk. Ir. Gunawan', 'location' => 'Cikarang, Bekasi', 'is_company' => false],
        ['name' => 'PT Bumi Cipta Karya', 'location' => 'Bogor', 'is_company' => true],
        ['name' => 'Ibu Farida H.', 'location' => 'Tangerang', 'is_company' => false],
        ['name' => 'Kontraktor Mulia Jaya Fasad', 'location' => 'Depok', 'is_company' => true],
        ['name' => 'Bpk. Rahmat Santoso', 'location' => 'Purwakarta', 'is_company' => false],
        ['name' => 'PT Griya Kencana Abadi', 'location' => 'Denpasar, Bali', 'is_company' => true],
        ['name' => 'CV Multi Karya Konstruksi', 'location' => 'Jakarta Utara', 'is_company' => true],
        ['name' => 'Atelier Roster Modern', 'location' => 'Jakarta Pusat', 'is_company' => true],
        ['name' => 'Bpk. Budi Darmawan', 'location' => 'Cimahi', 'is_company' => false],
        ['name' => 'PT Cipta Sarana Fasad', 'location' => 'Karawang', 'is_company' => true],
        ['name' => 'CV Bangun Sejahtera', 'location' => 'Serang, Banten', 'is_company' => true],
    ];

    // Pool kuantitas: mayoritas di atas 1000 pcs, sebagian di atas 500 pcs
    $quantities = ['1.200', '1.500', '1.800', '2.400', '2.800', '3.500', '4.200', '5.000', '650', '750', '850', '920', '1.350', '2.100', '3.800', '600'];
    $times = ['2 menit lalu', '4 menit lalu', '7 menit lalu', '11 menit lalu', '16 menit lalu', '23 menit lalu', '31 menit lalu', '38 menit lalu', '45 menit lalu', '52 menit lalu'];

    // Susun kombinasi data pembelian nyata
    $salesList = [];
    if ($realProducts->isNotEmpty()) {
        foreach ($buyersPool as $index => $buyer) {
            $product = $realProducts[$index % $realProducts->count()];
            $salesList[] = [
                'buyer' => $buyer['name'],
                'location' => $buyer['location'],
                'is_company' => $buyer['is_company'],
                'product' => $product->name,
                'image' => $product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'),
                'url' => route('product.detail', $product->slug),
                'quantity' => $quantities[$index % count($quantities)],
                'time' => $times[$index % count($times)],
            ];
        }
    }
@endphp

@if(!$isExcludedRoute && count($salesList) > 0)
<div x-data="liveSalesPopup({{ Js::from($salesList) }})"
     x-cloak
     x-show="show"
     x-transition:enter="transition ease-out duration-500 transform"
     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-300 transform"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 translate-y-6 scale-95"
     class="fixed bottom-20 sm:bottom-6 left-4 sm:left-6 z-40 max-w-[340px] sm:max-w-sm w-full bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 shadow-luxury p-3.5 sm:p-4 select-none pointer-events-auto group/popup"
     style="display: none;">
    
    <!-- Close Button -->
    <button @click="dismiss()" class="absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors cursor-pointer z-10" aria-label="Tutup Notifikasi">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <a :href="currentItem.url" class="flex items-start gap-3 group">
        <!-- Real Product Thumbnail with Pulse Indicator -->
        <div class="relative shrink-0 mt-0.5">
            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shadow-xs">
                <img :src="currentItem.image" :alt="currentItem.product" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <!-- Live Status Pulse Dot -->
            <span class="absolute -bottom-0.5 -right-0.5 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-white"></span>
            </span>
        </div>

        <!-- Content Details -->
        <div class="flex-1 min-w-0 pr-3">
            <div class="flex items-center gap-1.5 mb-0.5">
                <h4 class="font-display font-black text-xs sm:text-sm text-slate-900 truncate" x-text="currentItem.buyer"></h4>
            </div>
            <p class="text-[11px] text-slate-500 flex items-center gap-1 mb-1.5">
                <span class="truncate" x-text="currentItem.location"></span>
                <span>•</span>
                <span class="text-slate-400 font-medium" x-text="currentItem.time"></span>
            </p>

            <div class="bg-slate-50 rounded-xl p-2 border border-slate-100 group-hover:border-terra-200 transition-colors">
                <div class="text-[11px] font-bold text-slate-800 line-clamp-1 group-hover:text-terra-600 transition-colors" x-text="currentItem.product"></div>
                <div class="flex items-center justify-between mt-1 text-[11px]">
                    <span class="font-extrabold text-terra-600 bg-terra-50 px-2 py-0.5 rounded-md border border-terra-100" x-text="currentItem.quantity + ' pcs'"></span>
                    <span class="text-[10px] text-emerald-700 font-bold flex items-center gap-0.5">
                        <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>Armada Pabrik</span>
                    </span>
                </div>
            </div>
        </div>
    </a>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('liveSalesPopup', (items) => ({
        show: false,
        dismissed: false,
        timer: null,
        currentIndex: 0,
        salesData: items || [],
        get currentItem() {
            return this.salesData[this.currentIndex] || { buyer: 'Pelanggan', location: 'Jakarta', product: 'Roster Beton', quantity: '1.200', time: 'Baru saja', image: '', url: '/katalog' };
        },
        init() {
            if (!this.salesData || this.salesData.length === 0) return;
            // Delay first notification by 6 seconds
            setTimeout(() => {
                this.cycle();
            }, 6000);
        },
        cycle() {
            if (this.dismissed) return;

            this.currentIndex = Math.floor(Math.random() * this.salesData.length);
            this.show = true;

            // Hide after 5.5 seconds
            setTimeout(() => {
                this.show = false;
                // Schedule next show between 12 and 22 seconds
                const nextInterval = Math.floor(Math.random() * (22000 - 12000 + 1)) + 12000;
                setTimeout(() => {
                    this.cycle();
                }, nextInterval);
            }, 5500);
        },
        dismiss() {
            this.show = false;
            this.dismissed = true;
        }
    }));
});
</script>
@endif
