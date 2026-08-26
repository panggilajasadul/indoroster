@props(['data'])

@php
    $title = $data['title'] ?? 'Apa Kata Klien & Arsitek?';
    $mode = $data['mode'] ?? 'latest';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');
    
    $testimonials = \App\Models\Testimonial::latest()->limit(3)->get();
@endphp

@if($testimonials->count() > 0)
<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-14 sm:mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-4">
                <span>Bukti Nyata Kepuasan Klien</span>
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-4">{!! $title !!}</h2>
            <p class="text-sm sm:text-base {{ $theme->subColor }} max-w-xl mx-auto leading-relaxed">Ulasan jujur dari kontraktor, arsitek, dan pemilik rumah yang mempercayakan kebutuhan roster pada pabrik kami.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            @foreach($testimonials as $testimoni)
            @php $rating = min(max(intval($testimoni->rating ?? 5), 1), 5); @endphp
            <div class="rounded-2xl p-7 sm:p-8 border shadow-soft-xs hover:shadow-soft-lg transition-all duration-300 {{ $theme->cardBg }} flex flex-col justify-between group">
                <div>
                    <!-- Star Ratings -->
                    <div class="flex text-amber-400 gap-1 mb-5">
                        @for($i=0; $i<$rating; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                    
                    <p class="text-sm sm:text-base leading-relaxed italic {{ $theme->headingColor }} opacity-90 mb-8">
                        "{!! $testimoni->content !!}"
                    </p>
                </div>

                <div class="flex items-center gap-3.5 pt-4 border-t border-slate-200/40">
                    @if($testimoni->photo_url)
                        <img src="{{ $testimoni->photo_url }}" alt="{{ $testimoni->customer_name }}" class="w-11 h-11 rounded-full object-cover shadow-xs">
                    @else
                        <div class="w-11 h-11 bg-terra-500 text-white font-bold text-sm rounded-full flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr($testimoni->customer_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-sm {{ $theme->headingColor }}">{{ $testimoni->customer_name }}</div>
                        <div class="text-xs {{ $theme->subColor }}">{{ $testimoni->customer_role ?? 'Pembeli Terverifikasi' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
