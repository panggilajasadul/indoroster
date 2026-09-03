<?php

namespace App\Livewire;

use App\Models\Cart;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class CartCount extends Component
{
    public $count = 0;

    public $cartItems = [];

    protected $listeners = ['cart-updated' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $sessionId = Cookie::get('cart_session_id');

        $query = Cart::query();

        if (auth()->check()) {
            $query->where('user_id', auth()->id())
                ->orWhere('session_id', $sessionId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $this->count = $query->count();

        // If items are loaded, refresh them
        if (! empty($this->cartItems)) {
            $this->loadCartItems();
        }
    }

    public function loadCartItems()
    {
        $sessionId = Cookie::get('cart_session_id');

        $query = Cart::with(['product.media', 'variant']);

        if (auth()->check()) {
            $query->where('user_id', auth()->id())
                ->orWhere('session_id', $sessionId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $items = $query->latest()->take(5)->get();

        $this->cartItems = $items->map(function ($item) {
            $price = $item->variant ? $item->variant->final_price : ($item->product?->price ?? 0);

            // Check primary media first
            $image = $item->product?->primary_image;
            if (empty($image)) {
                $image = asset('assets/logo_indoroster_no_text.PNG');
            }

            return [
                'id' => $item->id,
                'name' => $item->product?->name ?? 'Produk',
                'quantity' => $item->quantity,
                'price' => 'Rp'.number_format($price, 0, ',', '.'),
                'image' => $image,
                'url' => '/produk/'.($item->product?->slug ?? ''),
                'variant_name' => $item->variant?->name ?? null,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}
