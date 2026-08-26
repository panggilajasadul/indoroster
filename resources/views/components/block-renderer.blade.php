@props([
    'blocks' => [],
    'pageTitle' => null,
])

<div class="page-builder">
    @foreach($blocks as $block)
        @php
            $type = str_replace('_', '-', $block['type']);
            $data = $block['data'];
            $isFirst = $loop->first;
        @endphp
        
        <x-dynamic-component :component="'blocks.' . $type" :data="$data" :page-title="$isFirst ? $pageTitle : null" />
    @endforeach
</div>
