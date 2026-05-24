<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['data'])
<x-blocks.visual-showcase :data="$data" >

{{ $slot ?? "" }}
</x-blocks.visual-showcase>