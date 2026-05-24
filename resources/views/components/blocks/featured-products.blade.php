@props(['data'])

@php
    $title = $data['title'] ?? 'Produk Unggulan';
    $categoryIds = $data['categories'] ?? [];
    $limit = $data['limit'] ?? 8;
    $bgTheme = $data['bg_theme'] ?? 'white';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };

    $query = \App\Models\Product::with('category', 'media', 'variants')->active();
    if (!empty($categoryIds)) {
        $query->whereIn('category_id', $categoryIds);
    }
    $products = $query->latest()->limit($limit)->get();
@endphp

<section class="py-24 {{ $bgClasses }} relative overflow-hidden">
    <!-- Decoration -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -ml-48 -mb-48"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black font-display text-black leading-tight mb-8">
                {!! $title !!}
            </h2>
            <div class="flex justify-center">
                <a href="{{ route('catalog') }}" class="group flex items-center gap-4 text-black font-black text-sm uppercase tracking-widest hover:text-accent transition-all">
                    <span>Lihat Semua Katalog</span>
                    <div class="w-12 h-12 rounded-full border border-black/10 flex items-center justify-center group-hover:bg-accent group-hover:border-accent transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Product Grid (Centered) -->
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4 lg:gap-6">
            @foreach($products as $product)
            <a href="{{ route('product.detail', $product->slug) }}" class="bg-white rounded-md border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400 w-[calc(50%-0.75rem)] sm:w-[calc(33.333%-1rem)] md:w-[calc(25%-1.5rem)] lg:w-[calc(20%-1.5rem)] xl:w-[calc(16.666%-1.5rem)] max-w-[220px]">
                
                <!-- Media Section -->
                <div class="relative aspect-square overflow-hidden bg-gray-100">
                    @php
                        $displayMedia = $product->primary_media;
                    @endphp

                    @if($displayMedia)
                        @if($displayMedia->media_type === 'video' && !str_contains($displayMedia->media_url, 'youtube.com') && !str_contains($displayMedia->media_url, 'youtu.be'))
                            <video src="{{ $displayMedia->formatted_url }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                autoplay muted loop playsinline></video>
                        @else
                            <img src="{{ $displayMedia->media_type === 'image' ? $displayMedia->formatted_url : $product->primary_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                    @endif

                    <!-- Discount Badge -->
                    @if($product->discount_percentage > 0)
                        <div class="absolute top-0 right-0 bg-[#ffeee8] text-[#ee4d2d] border border-[#ffc9b8] text-[10px] font-bold px-1.5 py-0.5 rounded-bl z-10">
                            {{ $product->discount_percentage }}% OFF
                        </div>
                    @endif

                    <!-- Best Seller Badge -->
                    @if($loop->first)
                        <div class="absolute top-0 left-0 bg-black text-accent text-[9px] font-black px-2 py-1 rounded-br z-10 tracking-wider uppercase">
                            #1 Best
                        </div>
                    @endif

                    <!-- Video Indicator -->
                    @if($product->has_video)
                        <div class="absolute bottom-1 right-1 bg-black/40 text-white rounded-full p-1 backdrop-blur-sm z-10 shadow-sm">
                            <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- Info Section -->
                <div class="p-2 flex flex-col flex-grow">
                    <div class="text-xs text-slate-800 leading-snug mb-1 line-clamp-2 font-medium group-hover:text-terra-600 transition-colors">
                        {{ $product->name }}
                    </div>
                    
                    <div class="mt-auto">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="text-sm font-bold text-[#ee4d2d] leading-none">{{ $product->formatted_price_range }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            @if($product->has_discount)
                                <span class="text-[9px] text-slate-400 line-through leading-none">Rp{{ number_format($product->original_price, 0, ',', '.') }}</span>
                            @else
                                <span></span>
                            @endif
                            <span class="text-[9px] text-slate-500 whitespace-nowrap">
                                {{ $product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
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
