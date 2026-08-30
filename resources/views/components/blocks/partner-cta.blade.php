@props(['data' => []])

@php
    $badge = $data['badge'] ?? 'Kemitraan Pabrik & Pengadaan Proyek';
    $title = $data['title'] ?? 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster';
    $description = $data['description'] ?? 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.';
    $ctaText1 = $data['cta_text_1'] ?? 'Daftar Akun Mitra Sekarang';
    $ctaUrl1 = $data['cta_url_1'] ?? route('register');
    $ctaText2 = $data['cta_text_2'] ?? 'Konsultasi Pengadaan via WhatsApp';
    $waNumber = config('services.whatsapp.number', '6281234567890');
    $ctaUrl2 = $data['cta_url_2'] ?? "https://wa.me/{$waNumber}?text=".urlencode("Halo Tim IndoRoster, saya ingin konsultasi kemitraan pengadaan roster untuk proyek.");
    
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
@endphp

<section class="py-16 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header Section -->
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-16">
            @if($badge)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black tracking-widest uppercase mb-4 {{ $theme->badgeClass }} shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ $badge }}</span>
                </div>
            @endif

            <h2 class="font-display text-fluid-h2 font-black tracking-tight leading-tight mb-5 {{ $theme->headingColor }}">
                {!! $title !!}
            </h2>

            <p class="text-sm sm:text-base leading-relaxed {{ $theme->subColor }}">
                {!! $description !!}
            </p>
        </div>

        <!-- 4 Kategori Kemitraan (Cards Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-14">
            <!-- 1. Kontraktor -->
            <div class="p-6 rounded-3xl transition-all duration-300 hover:-translate-y-1.5 {{ $theme->cardBg }}">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-500 dark:text-amber-400 flex items-center justify-center text-2xl mb-5">
                    🏗️
                </div>
                <h3 class="font-display text-lg font-black mb-2 {{ $theme->cardTitle }}">Kontraktor & Pemborong</h3>
                <p class="text-xs leading-relaxed {{ $theme->cardDesc }} mb-4">
                    Pricelist grosir tangan pertama, kapasitas produksi 10.000 pcs/bulan, serta dokumen surat jalan & faktur resmi.
                </p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400">
                    <span>✓ Mutu Beton K-200</span>
                </div>
            </div>

            <!-- 2. Arsitek -->
            <div class="p-6 rounded-3xl transition-all duration-300 hover:-translate-y-1.5 {{ $theme->cardBg }}">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/20 text-blue-500 dark:text-blue-400 flex items-center justify-center text-2xl mb-5">
                    📐
                </div>
                <h3 class="font-display text-lg font-black mb-2 {{ $theme->cardTitle }}">Arsitek & Desainer</h3>
                <p class="text-xs leading-relaxed {{ $theme->cardDesc }} mb-4">
                    Konsultasi motif custom, sampel fisik untuk presentasi klien, dan akurasi siku 90° yang presisi saat dipasang.
                </p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400">
                    <span>✓ Bebas Request Motif</span>
                </div>
            </div>

            <!-- 3. Kafe & Bisnis -->
            <div class="p-6 rounded-3xl transition-all duration-300 hover:-translate-y-1.5 {{ $theme->cardBg }}">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-500 dark:text-emerald-400 flex items-center justify-center text-2xl mb-5">
                    ☕
                </div>
                <h3 class="font-display text-lg font-black mb-2 {{ $theme->cardTitle }}">Kafe, Resto & Komersial</h3>
                <p class="text-xs leading-relaxed {{ $theme->cardDesc }} mb-4">
                    Bikin tempat usaha sejuk alami, estetik industrial, dan menjadi spot foto instagramable yang disukai pengunjung.
                </p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400">
                    <span>✓ Fasad Eye-Catching</span>
                </div>
            </div>

            <!-- 4. Developer -->
            <div class="p-6 rounded-3xl transition-all duration-300 hover:-translate-y-1.5 {{ $theme->cardBg }}">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-500 dark:text-purple-400 flex items-center justify-center text-2xl mb-5">
                    🏢
                </div>
                <h3 class="font-display text-lg font-black mb-2 {{ $theme->cardTitle }}">Developer Perumahan</h3>
                <p class="text-xs leading-relaxed {{ $theme->cardDesc }} mb-4">
                    Suplai bertahap skala klaster perumahan dengan jadwal presisi, dikirim langsung oleh armada truk pabrik terlatih.
                </p>
                <div class="flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400">
                    <span>✓ Armada Pabrik Sendiri</span>
                </div>
            </div>
        </div>

        <!-- CTA Action Buttons -->
        <div class="bg-gradient-to-r from-terra-500/10 via-amber-500/10 to-terra-500/15 dark:from-slate-900/90 dark:to-slate-900/90 border border-terra-500/30 dark:border-slate-800 rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6 backdrop-blur-md text-center md:text-left shadow-lg">
            <div>
                <h3 class="font-display text-xl sm:text-2xl font-black mb-2 {{ $theme->cardTitle }}">
                    Siap Memulai Kemitraan atau Pengadaan Proyek?
                </h3>
                <p class="text-xs sm:text-sm {{ $theme->cardDesc }}">
                    Daftar akun sekarang untuk menyimpan profil proyek Anda atau hubungi divisi sales proyek via WhatsApp.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto shrink-0">
                @if($ctaText1)
                    <a href="{{ $ctaUrl1 }}" class="font-display w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-terra-500 hover:bg-terra-600 text-white text-xs sm:text-sm font-black rounded-2xl shadow-xl shadow-terra-500/30 transition-all duration-200 gap-2 cursor-pointer">
                        <span>✨ {{ $ctaText1 }}</span>
                    </a>
                @endif

                @if($ctaText2)
                    <a href="{{ $ctaUrl2 }}" target="_blank" class="font-display w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-black rounded-2xl shadow-xl shadow-emerald-600/30 transition-all duration-200 gap-2 cursor-pointer">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>{{ $ctaText2 }}</span>
                    </a>
                @endif
            </div>
        </div>

    </div>
</section>
