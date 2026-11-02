@props([
    'blocks' => [],
    'pageTitle' => null,
])

<div class="page-builder">
    @foreach($blocks as $block)
        @php
            $type = str_replace('_', '-', $block['type'] ?? '');

            // Announcement bar sudah di-render di atas navbar (paling atas halaman)
            if ($type === 'announcement-bar') {
                continue;
            }
            
            // Map legacy block names
            $aliasMap = [
                'product-grid' => 'featured-products',
                'features' => 'why-us',
            ];
            if (isset($aliasMap[$type])) {
                $type = $aliasMap[$type];
            }

            $data = $block['data'] ?? [];
            $isFirst = $loop->first;
        @endphp
        
        @if (view()->exists('components.blocks.' . $type))
            <x-dynamic-component :component="'blocks.' . $type" :data="$data" :page-title="$isFirst ? $pageTitle : null" />
        @endif
    @endforeach
</div>

