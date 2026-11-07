@props(['data'])

@php
    $title = $data['title'] ?? 'Statistik';
    $subtitle = $data['subtitle'] ?? null;
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'light');

    $stats = $data['stats'] ?? [];
    if (empty($stats)) {
        $stats = [
            [
                'icon' => 'arch',
                'color' => 'orange',
                'number' => '20+',
                'label' => 'Model Roster',
            ],
            [
                'icon' => 'users',
                'color' => 'green',
                'number' => '120+',
                'label' => 'Pelanggan Terlayani',
            ],
            [
                'icon' => 'truck',
                'color' => 'blue',
                'number' => '15+',
                'label' => 'Wilayah Pengiriman',
            ],
            [
                'icon' => 'satisfaction',
                'color' => 'pink',
                'number' => '95%',
                'label' => 'Kepuasan Pelanggan',
            ],
        ];
    }
@endphp

<section class="py-16 sm:py-20 lg:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold font-display {{ $theme->headingColor }} tracking-tight">
                {{ $title }}
            </h2>
            {{-- Green Accent Line --}}
            <div class="w-12 h-1 bg-emerald-600 rounded-full mx-auto mt-3 sm:mt-4"></div>

            @if($subtitle)
                <p class="{{ $theme->subColor }} text-sm sm:text-base mt-4 max-w-2xl mx-auto leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- 4 Stat Cards in a row --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($stats as $stat)
                @php
                    $iconType = $stat['icon'] ?? 'arch';
                    $color = $stat['color'] ?? 'green';
                    $number = $stat['number'] ?? '0';
                    $label = $stat['label'] ?? '';

                    // Icon color styles
                    $iconColorClass = match($color) {
                        'orange' => 'text-amber-500 dark:text-amber-400',
                        'green' => 'text-emerald-600 dark:text-emerald-400',
                        'blue' => 'text-sky-500 dark:text-sky-400',
                        'pink' => 'text-pink-600 dark:text-pink-400',
                        default => 'text-emerald-600 dark:text-emerald-400',
                    };
                @endphp
                <div class="bg-white dark:bg-neutral-900 rounded-2xl p-6 sm:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] hover:shadow-lg transition-all duration-300 border border-stone-100 dark:border-neutral-800 flex flex-col items-center text-center group">
                    {{-- Icon Container --}}
                    <div class="w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center {{ $iconColorClass }} mb-4 group-hover:scale-110 transition-transform duration-300">
                        @if($iconType === 'arch')
                            {{-- Gate / Arch / Portal Icon (Orange) --}}
                            <svg class="w-12 h-12 sm:w-14 sm:h-14" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v12h1a1 1 0 0 1 0 2H3a1 1 0 0 1 0-2h1V8H3a1 1 0 0 1-1-1V4Zm3 16h3v-7a3 3 0 0 1 6 0v7h3V8H6v12Z" clip-rule="evenodd" />
                            </svg>
                        @elseif($iconType === 'users')
                            {{-- Customers / People with Gear / Team Icon (Green) --}}
                            <svg class="w-12 h-12 sm:w-14 sm:h-14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.122H1.5v-.125ZM16.5 19.25a5.99 5.99 0 0 0-1.89-4.32 7.87 7.87 0 0 1 6.89 4.32H16.5Z" />
                            </svg>
                        @elseif($iconType === 'truck')
                            {{-- Pickup / Delivery Truck Icon (Blue) --}}
                            <svg class="w-12 h-12 sm:w-14 sm:h-14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h1.591a3 3 0 0 1 5.818 0H14.25V9.375a2.625 2.625 0 0 0-2.625-2.625h-8.25ZM15.75 9.75V13.5h.341a3 3 0 0 1 5.818 0h.591a1.5 1.5 0 0 0 1.5-1.5v-1.628a2.25 2.25 0 0 0-.659-1.591l-2.022-2.022A2.25 2.25 0 0 0 19.728 6H16.5a.75.75 0 0 0-.75.75v3ZM6 16.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm13.5 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                            </svg>
                        @elseif($iconType === 'satisfaction')
                            {{-- Community / Satisfaction / Stars / Customers Icon (Pink) --}}
                            <svg class="w-12 h-12 sm:w-14 sm:h-14" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 5.69 3.117C18.99 17.26 17.5 20.25 14.887 20.25H9.113c-2.613 0-4.103-2.99-2.803-5.133ZM18.25 14.25a5.23 5.23 0 0 1 3.5 3.75c.5 1.5-.5 2.25-1.75 2.25h-1.5a6.6 6.6 0 0 0 .5-3.5c0-.9-.25-1.76-.75-2.5ZM5.75 14.25c-.5.74-.75 1.6-.75 2.5 0 1.25.17 2.45.5 3.5H4c-1.25 0-2.25-.75-1.75-2.25a5.23 5.23 0 0 1 3.5-3.75Z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <span class="text-3xl">{{ $iconType }}</span>
                        @endif
                    </div>

                    {{-- Bold Dark Green Stat Number --}}
                    <div class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-emerald-800 dark:text-emerald-400 mb-2 tracking-tight">
                        {{ $number }}
                    </div>

                    {{-- Stat Label --}}
                    <div class="text-xs sm:text-sm font-semibold text-stone-700 dark:text-neutral-300">
                        {{ $label }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

