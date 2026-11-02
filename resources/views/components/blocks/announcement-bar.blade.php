@props(['data', 'pageTitle' => null])

@php
    $text = $data['text'] ?? 'GRATIS ONGKIR area Jabodetabek untuk pembelian minimal 50 pcs — harga langsung dari pabrik!';
    $icon = $data['icon'] ?? 'truck';
    $link = $data['link'] ?? null;
    $bgTheme = $data['bg_theme'] ?? 'emerald'; // emerald, amber, dark, slate
    $isDismissible = $data['is_dismissible'] ?? false;
@endphp

<div x-data="{ open: true }" x-show="open" 
    class="relative z-40 py-2.5 px-4 text-center text-xs sm:text-sm font-semibold tracking-wide transition-all duration-300
    {{ $bgTheme === 'emerald' ? 'bg-[#0f4f38] text-emerald-100 border-b border-[#135d42]' : '' }}
    {{ $bgTheme === 'amber' ? 'bg-amber-600 text-amber-50 border-b border-amber-700' : '' }}
    {{ $bgTheme === 'dark' ? 'bg-slate-900 text-slate-100 border-b border-slate-800' : '' }}
    {{ $bgTheme === 'slate' ? 'bg-slate-800 text-slate-200 border-b border-slate-700' : '' }}
">
    <div class="max-w-7xl mx-auto flex items-center justify-center gap-2 sm:gap-2.5">
        @if($icon === 'truck')
            <svg class="w-4 h-4 text-emerald-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
            </svg>
        @elseif($icon === 'sparkles')
            <svg class="w-4 h-4 text-amber-300 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
            </svg>
        @endif

        @if($link)
            <a href="{{ $link }}" class="hover:underline hover:text-white transition-colors duration-150">
                {{ $text }}
            </a>
        @else
            <span>{{ $text }}</span>
        @endif

        @if($isDismissible)
            <button @click="open = false" class="absolute right-4 text-white/70 hover:text-white" aria-label="Tutup baris info">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        @endif
    </div>
</div>

