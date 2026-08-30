@props(['data'])

@php
    $badge = $data['badge'] ?? 'Pusat Informasi & FAQ';
    $title = $data['title'] ?? 'Pertanyaan yang Sering Diajukan (FAQ)';
    $description = $data['description'] ?? 'Jawaban lengkap seputar spesifikasi roster, pengiriman, minimal order, dan cara pasang.';
    $alignment = $data['alignment'] ?? 'center';
    $sourceType = $data['source_type'] ?? 'custom';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');
    
    $headerAlign = match($alignment) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        default => 'text-center items-center mx-auto'
    };

    $faqList = [];

    if ($sourceType === 'custom' && !empty($data['custom_faqs'])) {
        foreach ($data['custom_faqs'] as $item) {
            if (!empty($item['question']) && !empty($item['answer'])) {
                $faqList[] = [
                    'question' => $item['question'],
                    'answer' => $item['answer'],
                ];
            }
        }
    }

    // If source is database or if custom is empty, pull from database
    if (empty($faqList)) {
        $limit = (int) ($data['limit'] ?? 10);
        $dbFaqs = \App\Models\Faq::active()->orderBy('sort_order')->limit($limit)->get();
        foreach ($dbFaqs as $f) {
            $faqList[] = [
                'question' => $f->question,
                'answer' => $f->answer,
            ];
        }
    }

    // Default fallback if database is empty
    if (empty($faqList)) {
        $faqList = [
            [
                'question' => 'Berapa berat rata-rata per keping roster beton?',
                'answer' => 'Rata-rata berat per keping roster beton ukuran standar 20x20x10 cm adalah sekitar 4.0 hingga 4.5 kg karena diproduksi dengan teknik cetak tumbuk padat mutu K-200 tanpa rongga rapuh.',
            ],
            [
                'question' => 'Apakah ada minimal order untuk pengiriman luar kota?',
                'answer' => 'Tidak ada minimal order khusus. Anda bisa memesan sesuai kebutuhan proyek. Namun untuk efisiensi ongkos kirim armada truk pabrik, disarankan menggabungkan pesanan atau memanfaatkan rute pengiriman berkala kami.',
            ],
            [
                'question' => 'Bagaimana jika ada roster yang pecah saat tiba di lokasi proyek?',
                'answer' => 'IndoRoster memberikan Garansi Bebas Pecah 100%. Cukup foto keping yang rusak saat serah terima barang bersama supir kami, dan unit pengganti baru akan segera kami kirimkan gratis tanpa biaya tambahan.',
            ],
        ];
    }

    // Build FAQ Schema JSON-LD cleanly in PHP
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [],
    ];
    foreach ($faqList as $faq) {
        $faqSchema['mainEntity'][] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($faq['answer']),
            ],
        ];
    }
@endphp

@if(count($faqList) > 0)
<section class="py-20 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col {{ $headerAlign }} mb-12 sm:mb-14" data-motion="fade-up">
            @if($badge)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-4 shadow-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif
            
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-4">
                {!! $title !!}
            </h2>
            
            @if($description)
            <p class="{{ $theme->subColor }} text-base sm:text-lg max-w-2xl leading-relaxed">
                {!! $description !!}
            </p>
            @endif
        </div>
        
        <div x-data="{ activeAccordion: 0 }" class="space-y-4" data-motion="stagger">
            @foreach($faqList as $index => $faq)
            <div data-motion-item class="rounded-3xl border {{ $theme->cardBg }} shadow-soft-xs hover:shadow-soft-md transition-all duration-300 overflow-hidden">
                <button 
                    @click="activeAccordion === {{ $index }} ? activeAccordion = null : activeAccordion = {{ $index }}"
                    class="w-full flex items-center justify-between p-5 sm:p-6 text-left font-bold text-base sm:text-lg transition-colors cursor-pointer group"
                >
                    <span class="pr-4 leading-snug {{ $theme->cardTitle }} group-hover:text-terra-500 transition-colors">
                        {{ $faq['question'] }}
                    </span>
                    <div class="w-9 h-9 rounded-2xl {{ $theme->isDark ? 'bg-white/10 text-white group-hover:bg-terra-500' : 'bg-slate-100 text-slate-600 group-hover:bg-terra-500 group-hover:text-white' }} flex items-center justify-center shrink-0 transition-all duration-300 shadow-xs">
                        <svg class="w-4 h-4 transition-transform duration-300" 
                             :class="{ 'rotate-180 text-white': activeAccordion === {{ $index }} }" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="activeAccordion === {{ $index }}" x-collapse style="display: none;">
                    <div class="px-5 sm:px-6 pb-6 pt-1 {{ $theme->cardDesc }} prose prose-base max-w-none {{ $theme->isDark ? 'prose-invert' : 'prose-slate' }} leading-relaxed border-t border-slate-200/40 dark:border-slate-800/60 mt-1">
                        {!! nl2br(e($faq['answer'])) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ Schema Markup for Google Rich Results --}}
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
