@props(['data'])

@php
    $title = $data['title'] ?? 'Inspirasi <span class="text-terra-600">Proyek</span> Kami';
    $description = $data['description'] ?? 'Jelajahi koleksi mahakarya pemasangan roster beton minimalis yang telah menghiasi berbagai hunian dan ruang komersial.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');
@endphp

<div class="gallery-collection-block relative {{ $theme->bgClasses }}">
    <x-blocks._bg-theme :theme="$theme" />
    <div class="relative z-10">
        @livewire('gallery', ['title' => $title, 'description' => $description])
    </div>
</div>
