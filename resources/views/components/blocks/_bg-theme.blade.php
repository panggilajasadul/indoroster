@props(['theme' => 'white'])

@php
    $currentTheme = is_object($theme) ? ($theme->theme ?? 'white') : ($theme ?? 'white');
    $isAlwaysDark = in_array($currentTheme, ['dark', 'pattern-dark', 'gradient', 'gradient-terra', 'accent']);
@endphp

{{-- Ambient Glow --}}
@if($isAlwaysDark)
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-terra-500/15 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none translate-x-1/2 translate-y-1/2"></div>
@else
    {{-- Subtle Dark Mode Ambient Glow --}}
    <div class="hidden dark:block absolute top-0 left-1/4 w-80 h-80 bg-terra-500/10 rounded-full blur-3xl pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>
    <div class="hidden dark:block absolute bottom-0 right-1/4 w-80 h-80 bg-slate-700/20 rounded-full blur-3xl pointer-events-none translate-x-1/2 translate-y-1/2"></div>
@endif

{{-- Motif / Architectural Pattern Overlay --}}
@if(in_array($currentTheme, ['pattern-light', 'pattern-dark', 'accent', 'gradient-terra', 'dark', 'gradient']))
    <div class="absolute inset-0 pointer-events-none overflow-hidden {{ $currentTheme === 'pattern-dark' || $currentTheme === 'dark' || $currentTheme === 'gradient' ? 'opacity-[0.08]' : ($currentTheme === 'accent' || $currentTheme === 'gradient-terra' ? 'opacity-[0.10]' : 'opacity-[0.06] dark:opacity-[0.08]') }}">
        <svg class="w-full h-full text-current" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="roster-grid-pattern-{{ $currentTheme }}" width="64" height="64" patternUnits="userSpaceOnUse">
                    {{-- Minimalist architectural 4-hole ventilation roster motif --}}
                    <rect x="4" y="4" width="24" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="1.2"/>
                    <rect x="36" y="4" width="24" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="1.2"/>
                    <rect x="4" y="36" width="24" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="1.2"/>
                    <rect x="36" y="36" width="24" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="1.2"/>
                    <circle cx="32" cy="32" r="2.5" fill="currentColor"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#roster-grid-pattern-{{ $currentTheme }})"/>
        </svg>
    </div>
@endif
