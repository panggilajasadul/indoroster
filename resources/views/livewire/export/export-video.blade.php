@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'International Export',
                'item' => url('/export'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'Production Video Gallery',
                'item' => url('/export/video'),
            ],
        ],
    ];
@endphp

@push('seo')
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

<div class="min-h-screen bg-slate-950 text-slate-100 selection:bg-terra-500 selection:text-white">
    {{-- Top Bar Navigation --}}
    <div class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between text-xs sm:text-sm">
            <div class="flex items-center gap-2">
                <a href="{{ url('/export') }}" class="font-bold text-terra-400 hover:text-terra-300 flex items-center gap-1.5 transition">
                    <span>← Export Portal</span>
                </a>
                <span class="text-slate-600">/</span>
                <span class="text-slate-400 font-medium">Workshop Production Videos</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ url('/export/gallery') }}" class="text-slate-300 hover:text-white transition">📸 Photo Gallery</a>
                <a href="{{ asset('downloads/indoroster-export-breeze-blocks-catalog.pdf') }}" target="_blank" class="text-terra-400 font-bold hover:underline">📥 Download PDF Catalog</a>
            </div>
        </div>
    </div>

    {{-- Hero Section --}}
    <section class="relative py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-terra-500/10 border border-terra-500/20 text-terra-400 text-xs font-bold uppercase tracking-wider mb-4">
            <span>🎥 Authentic Workshop Media</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight mb-4 max-w-3xl mx-auto">
            The Hands & Craft of Plered Stonemasons
        </h1>
        <p class="text-slate-400 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed mb-6">
            Witness the genuine manual compaction and precision steel moulding at our Citeko, Plered workshop in Purwakarta. Pure fine crushed stone ash, dolomite, and red gravel powder crafted with care.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Desk, I watched your workshop production videos and would like to request an EXW container quotation.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm shadow-lg shadow-terra-500/25 transition">
                <span>💬 Chat on WhatsApp (+62 813-8970-9847)</span>
            </a>
            <a href="{{ url('/export/gallery') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-900 border border-slate-700 hover:bg-slate-800 text-slate-200 font-bold text-xs sm:text-sm transition">
                <span>📸 View Finished Installation Photos</span>
            </a>
        </div>
    </section>

    {{-- Videos Grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        @if($videos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($videos as $video)
                    @php
                        $media = $video->media->first();
                        $videoUrl = $media ? (str_starts_with($media->media_url, 'http') ? $media->media_url : asset('storage/' . $media->media_url)) : '';
                    @endphp
                    <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden shadow-xl flex flex-col group hover:border-terra-500/50 transition">
                        <div class="relative bg-black aspect-video flex items-center justify-center">
                            @if($videoUrl)
                                <video controls preload="metadata" class="w-full h-full object-cover">
                                    <source src="{{ $videoUrl }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <div class="text-slate-600 text-xs">Video file processing</div>
                            @endif
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                                    <span class="inline-flex items-center gap-1 font-semibold text-terra-400">
                                        📍 {{ $video->location ?: 'Plered Workshop, Purwakarta' }}
                                    </span>
                                    <span>{{ $video->created_at ? $video->created_at->format('M Y') : 'Genuine Batch' }}</span>
                                </div>
                                <h3 class="text-base font-bold text-white group-hover:text-terra-400 transition mb-2">
                                    {{ $video->title }}
                                </h3>
                                @if($video->description)
                                    <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed mb-4">
                                        {{ $video->description }}
                                    </p>
                                @endif
                            </div>

                            @if($video->product)
                                <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                                    <span class="text-xs text-slate-300 font-medium truncate max-w-[180px]">
                                        Pattern: {{ $video->product->name }}
                                    </span>
                                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster, I saw the video of '.$video->title.' (Pattern: '.$video->product->name.') and want to inquire for EXW export.') }}" target="_blank" class="text-xs font-bold text-terra-400 hover:text-terra-300">
                                        Inquire Pattern →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($videos->hasMorePages())
                <div class="mt-12 text-center">
                    <button wire:click="loadMore" class="px-8 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-white font-bold text-xs sm:text-sm transition">
                        <span>Load More Production Videos</span>
                    </button>
                </div>
            @endif
        @else
            <div class="text-center py-16 bg-slate-900/50 rounded-2xl border border-slate-800 p-8">
                <p class="text-slate-400 text-sm mb-4">Workshop video media is being synced from our Purwakarta production facility.</p>
                <a href="{{ url('/export/gallery') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm">
                    Browse Installed Photo Gallery Instead
                </a>
            </div>
        @endif
    </section>

    {{-- Footer CTA --}}
    <section class="border-t border-slate-800/80 bg-slate-900/40 py-12 px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-2">Ready to Order Container Loads Directly from Our Workshop?</h2>
        <p class="text-slate-400 text-xs sm:text-sm max-w-xl mx-auto mb-6">Terms: EXW (Ex Works — Workshop Gate, Plered, Purwakarta). Inquire with our international export sales desk today.</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Team, I am ready to request an EXW container load quotation.') }}" target="_blank" rel="noopener noreferrer" class="px-6 py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm shadow-lg transition">
                <span>Direct WhatsApp Inquiry</span>
            </a>
            <a href="mailto:indoroster.official@gmail.com" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-750 text-slate-200 font-bold text-xs sm:text-sm border border-slate-700 transition">
                <span>Email: indoroster.official@gmail.com</span>
            </a>
        </div>
    </section>
</div>

