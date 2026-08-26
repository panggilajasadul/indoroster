@props(['data'])

@php
    $badge = $data['badge'] ?? 'SPESIFIKASI STANDAR PABRIK';
    $title = $data['title'] ?? 'Data Teknis & Presisi Dimensi Roster';
    $subtitle = $data['subtitle'] ?? 'Standar modul loster modern 20x20x10 cm dengan kebutuhan 25 pcs/m² untuk perhitungan gambar kerja dan RAB proyek dinding ventilasi.';
    $alignment = $data['alignment'] ?? 'center';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');

    $headerAlign = match($alignment) {
        'left' => 'text-left flex flex-col items-start',
        'right' => 'text-right flex flex-col items-end',
        default => 'text-center flex flex-col items-center mx-auto'
    };

    $dimLabel = $data['dimension_label'] ?? 'DIMENSI MODUL STANDAR';
    $dimValue = $data['dimension_value'] ?? '20 × 20 × 10 cm';
    $dimTolerance = $data['dimension_tolerance'] ?? 'Toleransi Presisi Cetakan: ± 0.5 mm';
    $dimImage = !empty($data['dimension_image_upload']) ? asset('storage/' . $data['dimension_image_upload']) : null;

    $defaultSpecs = [
        ['label' => 'Kepadatan & Kekuatan', 'value' => 'Cetak Tumbuk Padat & Keras', 'description' => 'Padat tanpa rongga, tahan cuaca & benturan.'],
        ['label' => 'Bobot Rata-Rata', 'value' => '± 4.0 – 4.5 kg / pcs', 'description' => 'Material padat, kokoh dan tidak getas.'],
        ['label' => 'Kebutuhan per m²', 'value' => '25 Keping (pcs)', 'description' => 'Perhitungan baku luas m² dinding.'],
        ['label' => 'Finishing Permukaan', 'value' => 'Halus 2 Muka (Depan & Belakang)', 'description' => 'Rapi dari sisi luar maupun dalam.'],
        ['label' => 'Ketahanan Cuaca', 'value' => '100% Tahan UV & Hujan', 'description' => 'Bebas lumut dengan pelapis coating.'],
        ['label' => 'Pilihan Varian Warna', 'value' => 'Abu-Abu, Putih, Terakota', 'description' => 'Warna asli material tanpa pewarna luntur.'],
    ];

    $specs = !empty($data['specs']) && is_array($data['specs']) ? $data['specs'] : $defaultSpecs;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="{{ $headerAlign }} max-w-3xl mb-16">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-4">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        <!-- 3D Dimension & Specs Card Container -->
        <div class="max-w-5xl mx-auto p-8 sm:p-12 rounded-3xl {{ $theme->cardBg }} border shadow-luxury grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left: Technical Drawing Graphic -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center p-8 rounded-2xl bg-slate-950/80 border border-slate-800 text-center relative overflow-hidden">
                @if($dimImage)
                    <img src="{{ $dimImage }}" alt="{{ $dimLabel }}" class="w-40 h-40 object-contain rounded-2xl mb-4 border border-slate-700/60 p-2 bg-slate-900/60">
                @else
                    <div class="w-40 h-40 rounded-2xl border-4 border-dashed border-terra-500/60 bg-terra-500/10 flex flex-col items-center justify-center text-white relative shadow-inner mb-4">
                        <!-- 4 Hole representation -->
                        <div class="grid grid-cols-2 gap-3 p-3 w-full h-full">
                            <div class="rounded-lg bg-terra-500/30 border border-terra-500/60"></div>
                            <div class="rounded-lg bg-terra-500/30 border border-terra-500/60"></div>
                            <div class="rounded-lg bg-terra-500/30 border border-terra-500/60"></div>
                            <div class="rounded-lg bg-terra-500/30 border border-terra-500/60"></div>
                        </div>
                    </div>
                @endif

                <div class="font-mono text-xs text-terra-400 font-bold tracking-widest uppercase">
                    {{ $dimLabel }}
                </div>
                <div class="text-xl font-black text-white mt-1">
                    {{ $dimValue }}
                </div>
                @if($dimTolerance)
                <div class="text-[11px] text-slate-400 mt-1">
                    {{ $dimTolerance }}
                </div>
                @endif
            </div>

            <!-- Right: Key Specs Grid -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($specs as $spec)
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-terra-500/30 transition-all">
                    <div class="text-[11px] text-terra-400 font-black uppercase tracking-wider">{{ $spec['label'] ?? ($spec['title'] ?? 'Spesifikasi') }}</div>
                    <div class="text-base sm:text-lg font-black text-white mt-1">{{ $spec['value'] ?? ($spec['content'] ?? '') }}</div>
                    @if(!empty($spec['description']) || !empty($spec['desc']))
                    <p class="text-xs text-slate-400 mt-1 leading-snug">{{ $spec['description'] ?? $spec['desc'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>

        </div>

    </div>
</section>
