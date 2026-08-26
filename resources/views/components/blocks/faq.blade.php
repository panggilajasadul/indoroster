@props(['data'])

@php
    $title = $data['title'] ?? 'Pertanyaan yang Sering Diajukan (FAQ)';
    $description = $data['description'] ?? 'Jawaban lengkap seputar spesifikasi roster, pengiriman, minimal order, dan cara pasang.';
    $limit = (int) ($data['limit'] ?? 10);
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');
    
    $faqs = \App\Models\Faq::active()->orderBy('sort_order')->limit($limit)->get();
@endphp

@if($faqs->count() > 0)
<section class="py-20 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-14" data-motion="fade-up">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-4">
                <span>Pusat Informasi</span>
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-4">{!! $title !!}</h2>
            @if($description)
            <p class="{{ $theme->subColor }} text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">{!! $description !!}</p>
            @endif
        </div>
        
        <div x-data="{ activeAccordion: null }" class="space-y-3.5" data-motion="stagger">
            @foreach($faqs as $index => $faq)
            <div data-motion-item class="rounded-2xl border {{ $theme->cardBg }} shadow-soft-xs hover:shadow-soft-sm transition-all duration-200 overflow-hidden">
                <button 
                    @click="activeAccordion === {{ $index }} ? activeAccordion = null : activeAccordion = {{ $index }}"
                    class="w-full flex items-center justify-between p-5 sm:p-6 text-left font-bold text-sm sm:text-base transition-colors cursor-pointer"
                >
                    <span class="pr-4 leading-snug {{ $theme->cardTitle }}">{{ $faq->question }}</span>
                    <div class="w-8 h-8 rounded-full {{ $theme->isDark ? 'bg-white/10 text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 transition-transform duration-300" 
                             :class="{ 'rotate-180 text-terra-500': activeAccordion === {{ $index }} }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === {{ $index }}" x-collapse style="display: none;">
                    <div class="px-5 sm:px-6 pb-6 pt-1 {{ $theme->cardDesc }} prose prose-sm max-w-none {{ $theme->isDark ? 'prose-invert' : 'prose-slate' }} leading-relaxed">
                        {!! $faq->answer !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ Schema Markup for SEO --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs as $faq)
        {
            "@type": "Question",
            "name": @json($faq->question),
            "acceptedAnswer": {
                "@type": "Answer",
                "text": @json(strip_tags($faq->answer))
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
