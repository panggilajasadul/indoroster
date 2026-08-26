@props(['data' => []])

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <x-trust-payment-shipping 
        :badge="$data['badge'] ?? null"
        :title="$data['title'] ?? null"
        :description="$data['description'] ?? null"
        :payments="$data['payments'] ?? null"
        :shippings="$data['shippings'] ?? null"
    />
</div>
