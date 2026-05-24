{{-- Reusable bg_theme resolver for Page Builder blocks --}}
{{-- Usage: @include('components.blocks._bg-theme', ['bgTheme' => $bgTheme]) --}}
{{-- This sets $bgClasses, $headingColor, $subColor, $cardBg, $dividerColor in the parent scope --}}
@php
    $bgClasses = match($bgTheme ?? 'white') {
        'dark'     => 'bg-slate-900 text-white',
        'accent'   => 'bg-accent text-white',
        'slate'    => 'bg-slate-50 text-slate-900',
        'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white',
        default    => 'bg-white text-slate-900',
    };
    $headingColor = match($bgTheme ?? 'white') {
        'dark', 'gradient' => 'text-white',
        'accent'           => 'text-white',
        default            => 'text-slate-900',
    };
    $subColor = match($bgTheme ?? 'white') {
        'dark', 'gradient' => 'text-slate-300',
        'accent'           => 'text-white/80',
        default            => 'text-slate-600',
    };
    $cardBg = match($bgTheme ?? 'white') {
        'dark', 'gradient' => 'bg-white/5 border-white/10',
        'accent'           => 'bg-white/10 border-white/20',
        'slate'            => 'bg-white border-slate-200',
        default            => 'bg-slate-50 border-slate-100',
    };
    $dividerColor = match($bgTheme ?? 'white') {
        'dark', 'gradient' => 'bg-terra-500',
        'accent'           => 'bg-white',
        default            => 'bg-accent',
    };
@endphp
