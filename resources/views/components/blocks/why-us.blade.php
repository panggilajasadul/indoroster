@props(['data'])

@php
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $items = $data['items'] ?? [];
    $videos = $data['videos'] ?? [];
    $bgTheme = $data['bg_theme'] ?? 'dark';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
@endphp

<section class="py-20 {{ $bgClasses }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold font-display mb-6">{!! $title !!}</h2>
                <p class="text-slate-400 mb-8 text-lg leading-relaxed">{!! $description !!}</p>
                <div class="space-y-6">
                    @foreach($items as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-accent/20 text-accent rounded-lg flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">{{ $item['title'] }}</h3>
                            <p class="text-slate-400 leading-relaxed">{{ $item['content'] ?? $item['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="relative flex flex-col gap-6">
                @foreach($videos as $video)
                @php
                    $finalVideoUrl = !empty($video['video_upload']) ? asset('storage/' . $video['video_upload']) : ($video['url'] ?? '');
                    $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($finalVideoUrl), 'video');
                @endphp
                <div class="relative rounded-2xl shadow-2xl border border-slate-700 overflow-hidden aspect-video bg-slate-800">
                    @if($isVideo)
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                        <source src="{{ $finalVideoUrl }}" type="video/mp4">
                    </video>
                    @elseif($finalVideoUrl)
                    <img src="{{ $finalVideoUrl }}" class="w-full h-full object-cover">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
