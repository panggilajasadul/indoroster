@props(['blocks' => []])

<div class="page-builder">
    @foreach($blocks as $block)
        @php
            $type = str_replace('_', '-', $block['type']);
            $data = $block['data'];
        @endphp
        
        <x-dynamic-component :component="'blocks.' . $type" :data="$data" />
    @endforeach
</div>
