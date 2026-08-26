@props(['data'])

@php
    $title = $data['title'] ?? 'Produk Terlaris & Viral 🔥';
    $subtitle = $data['subtitle'] ?? 'Koleksi roster terpopuler dengan penjualan dan ulasan terbanyak';
    $limit = (int) ($data['limit'] ?? 6);
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $products = \App\Models\Product::viral()->take($limit)->get();
@endphp

<section class="py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="{{ $theme->badgeClass }} text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider inline-flex items-center gap-1.5 mb-4">
                <span class="w-1.5 h-1.5 bg-terra-500 rounded-full animate-ping"></span>
                Trending Hari Ini
            </span>
            <h2 class="text-3xl md:text-5xl font-black font-display {{ $theme->headingColor }} mb-4">
                {!! $title !!}
            </h2>
            @if($subtitle)
            <p class="{{ $theme->subColor }} text-sm max-w-xl mx-auto">
                {!! $subtitle !!}
            </p>
            @endif
        </div>

        <!-- Product Grid (Centered Responsive Columns) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 lg:gap-5">
            @foreach($products as $product)
                <x-product-card :product="$product" :badgeText="'#' . $loop->iteration . ' HOT'" />
            @endforeach
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.page-builder video[autoplay]').forEach(function(video) {
        video.play().catch(function() {});
    });
});
</script>
