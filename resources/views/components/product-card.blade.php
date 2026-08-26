@props([
    'product',
    'badgeText' => null,
    'badgeColor' => 'bg-slate-900 dark:bg-terra-500',
])

@php
    $displayMedia = $product->primary_media;
    $hasDiscount = $product->discount_percentage > 0;
    $isOutOfStock = ($product->total_stock <= 0);
@endphp

<a href="{{ route('product.detail', $product->slug) }}" 
   {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs hover:shadow-soft-lg hover:border-terra-400/90 dark:hover:border-terra-500 transition-all duration-300 group flex flex-col overflow-hidden relative']) }}>
    
    <!-- Media Viewport (Aspect Square 1:1) -->
    <div class="relative aspect-square overflow-hidden bg-slate-100 dark:bg-slate-800">
        @if($displayMedia)
            @if($displayMedia->media_type === 'video' && !str_contains($displayMedia->media_url, 'youtube.com') && !str_contains($displayMedia->media_url, 'youtu.be'))
                <video src="{{ $displayMedia->formatted_url }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" autoplay muted loop playsinline></video>
            @else
                <img src="{{ $displayMedia->media_type === 'image' ? $displayMedia->formatted_url : $product->primary_image }}" 
                     alt="{{ $displayMedia->alt_text ?: $product->name }}" 
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                     loading="lazy">
            @endif
        @elseif($product->primary_image)
            <img src="{{ $product->primary_image }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                 loading="lazy">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-2 text-center">
                <span class="text-xl mb-1">🧱</span>
                <span class="text-[10px] font-medium text-slate-400">Foto Segera</span>
            </div>
        @endif

        <!-- Custom Badge (Misal #1 Hot) -->
        @if($badgeText)
        <div class="absolute top-0 left-0 z-10">
            <span class="{{ $badgeColor }} text-white text-[9px] font-black px-2 py-0.5 rounded-br uppercase tracking-wider shadow-xs">
                {{ $badgeText }}
            </span>
        </div>
        @endif

        <!-- Discount Badge -->
        @if($hasDiscount)
        <div class="absolute top-2 right-2 bg-red-500 text-white text-[9px] sm:text-[10px] font-black px-1.5 py-0.5 rounded-md shadow-xs z-10">
            -{{ $product->discount_percentage }}%
        </div>
        @endif

        <!-- Video Badge -->
        @if($product->has_video)
        <div class="absolute bottom-1.5 right-1.5 bg-black/50 text-white rounded-full p-1 backdrop-blur-xs z-10 shadow-xs">
            <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
        </div>
        @endif

        <!-- Out of Stock Overlay -->
        @if($isOutOfStock)
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-20">
            <span class="bg-red-500 text-white text-xs font-black px-2.5 py-1 rounded-lg shadow-lg">HABIS</span>
        </div>
        @endif
    </div>

    <!-- Product Info Box -->
    <div class="p-3 sm:p-3.5 flex flex-col flex-grow justify-between">
        <div>
            <div class="text-[9px] sm:text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5 truncate">
                {{ $product->category->name ?? 'Roster Beton' }}
            </div>
            <h3 class="text-xs sm:text-[13px] font-normal text-slate-800 dark:text-slate-200 leading-snug group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors line-clamp-2 mb-1.5">
                {{ $product->name }}
            </h3>
        </div>

        <div class="mt-auto">
            <!-- Rating & Sales Info -->
            <div class="flex items-center gap-1.5 mb-1.5 text-[10px] text-slate-500 dark:text-slate-400 flex-wrap">
                <div class="flex items-center text-amber-400">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    <span class="font-bold text-slate-700 dark:text-slate-300 ml-0.5">{{ number_format($product->average_rating, 1) }}</span>
                </div>
                @if($product->reviews_count > 0)
                    <span class="text-slate-400 dark:text-slate-500">({{ $product->reviews_count }})</span>
                @endif
                <span class="text-slate-300 dark:text-slate-700">|</span>
                <span class="truncate">{{ $product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : '0 terjual' }}</span>
            </div>

            <!-- Price Row -->
            <div class="flex items-baseline justify-between gap-1 pt-1.5 border-t border-slate-100 dark:border-slate-800 flex-wrap">
                <div class="text-xs sm:text-sm font-bold text-[#ee4d2d] dark:text-terra-400">
                    {{ $product->formatted_price_range }}
                </div>
                @if($hasDiscount && $product->original_price)
                <div class="text-[10px] text-slate-400 dark:text-slate-500 line-through">
                    Rp{{ number_format($product->original_price, 0, ',', '.') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</a>
