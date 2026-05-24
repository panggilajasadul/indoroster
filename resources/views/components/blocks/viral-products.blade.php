@props(['data'])

@php
    $title = $data['title'] ?? 'Produk Terlaris & Viral 🔥';
    $subtitle = $data['subtitle'] ?? 'Koleksi roster terpopuler dengan penjualan dan ulasan terbanyak';
    $limit = (int) ($data['limit'] ?? 6);
    $bgTheme = $data['bg_theme'] ?? 'white';
    
    $bgClasses = match($bgTheme) { 
        'dark' => 'bg-slate-900 text-white', 
        'accent' => 'bg-accent text-white', 
        'slate' => 'bg-slate-50 text-slate-900', 
        'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', 
        default => 'bg-white text-slate-900' 
    };

    $headingClass = match($bgTheme) {
        'dark', 'gradient', 'accent' => 'text-white',
        default => 'text-slate-900 font-display font-black'
    };

    $subtitleClass = match($bgTheme) {
        'dark', 'gradient' => 'text-slate-400',
        'accent' => 'text-white/80',
        default => 'text-slate-500'
    };

    $badgeClass = match($bgTheme) {
        'accent' => 'bg-white/20 text-white border border-white/30',
        default => 'bg-terra-500/10 text-terra-600'
    };

    $badgeDotClass = match($bgTheme) {
        'accent' => 'bg-white',
        default => 'bg-terra-500'
    };

    $cardClasses = match($bgTheme) {
        'dark', 'gradient' => 'bg-slate-800/85 border-slate-700 hover:border-terra-500 shadow-none hover:shadow-2xl hover:shadow-black/50',
        'accent' => 'bg-white/10 border-white/20 hover:border-white shadow-none text-white hover:bg-white/20',
        default => 'bg-white border-slate-200/80 shadow-sm hover:shadow-xl hover:border-terra-400'
    };
    
    $productTitleClass = match($bgTheme) {
        'dark', 'gradient', 'accent' => 'text-white group-hover:text-terra-400',
        default => 'text-slate-800 group-hover:text-terra-600'
    };
    
    $productCategoryClass = match($bgTheme) {
        'dark', 'gradient', 'accent' => 'text-slate-400',
        default => 'text-slate-400'
    };

    $ratingTextClass = match($bgTheme) {
        'dark', 'gradient', 'accent' => 'text-slate-300',
        default => 'text-slate-700'
    };

    $ratingDividerClass = match($bgTheme) {
        'dark', 'gradient', 'accent' => 'text-slate-600',
        default => 'text-slate-300'
    };

    $soldTextClass = match($bgTheme) {
        'dark', 'gradient', 'accent' => 'text-slate-400',
        default => 'text-slate-500'
    };

    $priceRangeClass = match($bgTheme) {
        'accent' => 'text-white font-extrabold',
        default => 'text-terra-600 font-extrabold'
    };

    $cardBorderClass = match($bgTheme) {
        'dark', 'gradient' => 'border-slate-750',
        'accent' => 'border-white/15',
        default => 'border-slate-100'
    };

    $products = \App\Models\Product::viral()->take($limit)->get();
@endphp

<section class="py-24 {{ $bgClasses }} relative overflow-hidden">
    <!-- Decoration -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-terra-500/5 rounded-full blur-[100px] -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-terra-500/5 rounded-full blur-[100px] -ml-48 -mb-48"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <span class="{{ $badgeClass }} text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider inline-flex items-center gap-1.5 mb-4">
                <span class="w-1.5 h-1.5 {{ $badgeDotClass }} rounded-full animate-ping"></span>
                Trending Hari Ini
            </span>
            <h2 class="text-3xl md:text-5xl font-black font-display {{ $headingClass }} mb-4">
                {!! $title !!}
            </h2>
            @if($subtitle)
            <p class="{{ $subtitleClass }} text-sm max-w-xl mx-auto">
                {!! $subtitle !!}
            </p>
            @endif
        </div>

        <!-- Product Grid (Centered Responsive Columns) -->
        <div class="flex flex-wrap justify-center gap-4 sm:gap-5">
            @foreach($products as $product)
            <a href="{{ route('product.detail', $product->slug) }}" class="{{ $cardClasses }} rounded-xl border transition-all duration-300 group flex flex-col overflow-hidden relative w-[calc(50%-0.625rem)] sm:w-[calc(33.333%-0.85rem)] md:w-[calc(25%-1rem)] lg:w-[calc(20%-1.1rem)] xl:w-[calc(16.666%-1.1rem)] max-w-[220px]">
                
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

                    <!-- Badges -->
                    @if($product->discount_percentage > 0)
                        <div class="absolute top-0 right-0 bg-red-50 text-red-600 border-l border-b border-red-100 text-[10px] font-bold px-2 py-0.5 rounded-bl-lg z-10 shadow-sm">
                            {{ $product->discount_percentage }}% OFF
                        </div>
                    @endif

                    <!-- Viral Badge / Rank -->
                    <div class="absolute top-2 left-2 z-10">
                        <span class="bg-terra-500 text-white text-[9px] font-black px-2 py-0.5 rounded-md tracking-wider uppercase flex items-center gap-0.5 shadow-sm">
                            #{{ $loop->iteration }} Hot
                        </span>
                    </div>

                    <!-- Video Indicator -->
                    @if($product->has_video)
                        <div class="absolute bottom-2 right-2 bg-black/40 text-white rounded-full p-1.5 backdrop-blur-sm z-10 shadow-sm">
                            <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- Info Section -->
                <div class="p-3.5 flex flex-col flex-grow">
                    <div class="text-[10px] {{ $productCategoryClass }} font-semibold uppercase tracking-wider mb-1">
                        {{ $product->category->name ?? 'Roster' }}
                    </div>
                    <div class="text-xs {{ $productTitleClass }} leading-snug mb-2 line-clamp-2 font-bold transition-colors">
                        {{ $product->name }}
                    </div>
                    
                    <div class="mt-auto">
                        <!-- Ratings & Sales info -->
                        <div class="flex items-center gap-1 mb-2">
                            <div class="flex text-amber-400">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </div>
                            <span class="text-[10px] font-bold {{ $ratingTextClass }}">{{ $product->average_rating }}</span>
                            <span class="text-[10px] {{ $ratingDividerClass }}">|</span>
                            <span class="text-[10px] {{ $soldTextClass }} font-medium">{{ $product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : '0 terjual' }}</span>
                        </div>

                        <div class="flex items-baseline justify-between gap-1 flex-wrap pt-2 border-t {{ $cardBorderClass }}">
                            <span class="text-xs {{ $priceRangeClass }} leading-none">{{ $product->formatted_price_range }}</span>
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
