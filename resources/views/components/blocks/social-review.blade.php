@props(['data'])

@php
    $badge = $data['badge'] ?? '';
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $videoUrl = $data['video_url'] ?? '';
    $creatorsCount = $data['creators_count'] ?? '100+';
    $bgTheme = $data['bg_theme'] ?? 'dark';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
@endphp

<section class="py-24 {{ $bgClasses }} overflow-hidden relative">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-accent/10 rounded-full blur-[120px]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                @if($badge)
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-accent/20 border border-accent/30 text-accent mb-8">
                    <span class="flex h-2 w-2 rounded-full bg-accent animate-ping"></span>
                    <span class="text-xs font-black uppercase tracking-[0.2em]">{{ $badge }}</span>
                </div>
                @endif
                <h2 class="text-4xl md:text-6xl font-black font-display text-white leading-tight mb-8">{!! $title !!}</h2>
                <p class="text-xl text-slate-400 mb-10 leading-relaxed">{!! $description !!}</p>
                <div class="flex items-center gap-6">
                    <div class="flex -space-x-4">
                        <img src="https://i.pravatar.cc/100?u=1" class="w-12 h-12 rounded-full border-2 border-slate-900 bg-slate-800 object-cover" alt="">
                        <img src="https://i.pravatar.cc/100?u=2" class="w-12 h-12 rounded-full border-2 border-slate-900 bg-slate-800 object-cover" alt="">
                        <img src="https://i.pravatar.cc/100?u=3" class="w-12 h-12 rounded-full border-2 border-slate-900 bg-slate-800 object-cover" alt="">
                    </div>
                    <div class="text-sm">
                        <div class="text-white font-bold text-lg">{{ $creatorsCount }} Kreator</div>
                        <div class="text-slate-500 font-medium">Telah mereview produk kami</div>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2 flex justify-center w-full">
                <div class="relative w-full max-w-[320px] aspect-[9/19] bg-slate-900 rounded-[3rem] border-[8px] border-slate-800 shadow-[0_0_80px_rgba(255,102,0,0.25)] overflow-hidden">
                    @php
                        $finalVideoUrl = !empty($data['video_upload']) ? asset('storage/' . $data['video_upload']) : $videoUrl;
                        $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($finalVideoUrl), 'video');
                    @endphp
                    @if($isVideo)
                    <video class="w-full h-full object-cover" loop playsinline controls>
                        <source src="{{ $finalVideoUrl }}" type="video/mp4">
                    </video>
                    @elseif($finalVideoUrl)
                    <img src="{{ $finalVideoUrl }}" class="w-full h-full object-cover">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
