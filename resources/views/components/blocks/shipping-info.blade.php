@props(['data'])

@php
    $badge = $data['badge'] ?? '';
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $videoUrl = $data['video_url'] ?? '';
    $buttonText = $data['button_text'] ?? '';
    $buttonUrl = $data['button_url'] ?? '';
    $bgTheme = $data['bg_theme'] ?? 'white';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
@endphp

<section class="py-20 {{ $bgClasses }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-accent/5 rounded-3xl p-8 md:p-16 border border-accent/10 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1">
                @if($badge)
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 text-accent font-semibold text-sm mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                    {{ $badge }}
                </div>
                @endif
                <h2 class="text-3xl md:text-4xl font-bold font-display text-slate-900 mb-6">{!! $title !!}</h2>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                    {!! $content !!}
                </p>
                <a href="{{ $buttonUrl ?: 'https://wa.me/' . preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::getValue('whatsapp_number', '081234567890')) }}" target="_blank" class="inline-flex items-center gap-2 bg-accent hover:bg-accent/90 text-black px-6 py-3 rounded-md font-bold transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    {{ $buttonText ?: 'Hubungi Kami' }}
                </a>
            </div>
            <div class="flex-1 w-full relative">
                <div class="rounded-2xl shadow-xl w-full overflow-hidden aspect-[4/3] bg-slate-100">
                    @php
                        $finalVideoUrl = !empty($data['video_upload']) ? asset('storage/' . $data['video_upload']) : $videoUrl;
                        $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($finalVideoUrl), 'video');
                    @endphp
                    @if($isVideo)
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
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
