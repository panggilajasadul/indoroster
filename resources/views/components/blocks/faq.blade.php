@props(['data'])

@php
    $title = $data['title'] ?? 'Pertanyaan yang Sering Diajukan';
    $description = $data['description'] ?? '';
    $limit = (int) ($data['limit'] ?? 10);
    $bg = $data['bg_theme'] ?? 'slate';
    
    $faqs = \App\Models\Faq::active()->orderBy('sort_order')->limit($limit)->get();
    
    $bgClasses = match($bg) {
        'dark' => 'bg-slate-900 text-white',
        'accent' => 'bg-accent text-black',
        'white' => 'bg-white text-slate-900',
        default => 'bg-slate-50 text-slate-900',
    };
    $cardClasses = match($bg) {
        'dark' => 'border-slate-700 hover:border-slate-600',
        'accent' => 'border-black/10 hover:border-black/20',
        default => 'border-slate-200 hover:border-terra-300',
    };
    $answerBg = match($bg) {
        'dark' => 'bg-slate-800/50',
        'accent' => 'bg-white/20',
        default => 'bg-white',
    };
    $subtextClass = match($bg) {
        'dark' => 'text-slate-400',
        'accent' => 'text-black/60',
        default => 'text-slate-500',
    };
@endphp

@if($faqs->count() > 0)
<section class="py-20 {{ $bgClasses }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-black font-display leading-tight mb-4">{!! $title !!}</h2>
            @if($description)
            <p class="{{ $subtextClass }} text-lg max-w-2xl mx-auto">{!! $description !!}</p>
            @endif
        </div>
        
        <div x-data="{ activeAccordion: null }" class="space-y-3">
            @foreach($faqs as $index => $faq)
            <div class="rounded-xl border {{ $cardClasses }} overflow-hidden transition-colors duration-200">
                <button 
                    @click="activeAccordion === {{ $index }} ? activeAccordion = null : activeAccordion = {{ $index }}"
                    class="w-full flex items-center justify-between p-5 md:p-6 text-left font-bold text-sm md:text-base transition-colors"
                >
                    <span class="pr-4">{{ $faq->question }}</span>
                    <svg class="w-5 h-5 shrink-0 transition-transform duration-300" 
                         :class="{ 'rotate-180': activeAccordion === {{ $index }} }" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeAccordion === {{ $index }}" x-collapse style="display: none;">
                    <div class="px-5 md:px-6 pb-5 md:pb-6 {{ $answerBg }} rounded-b-xl prose prose-sm max-w-none {{ $bg === 'dark' ? 'prose-invert' : 'prose-slate' }}">
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
    "@context": "https://schema.org",
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
