@props(['data'])

@php
    $title = $data['title'] ?? 'Inspirasi <span class="text-terra-600">Proyek</span> Kami';
    $description = $data['description'] ?? 'Jelajahi koleksi mahakarya pemasangan roster beton minimalis yang telah menghiasi berbagai hunian dan ruang komersial.';
@endphp

<div class="gallery-collection-block">
    @livewire('gallery', ['title' => $title, 'description' => $description])
</div>
