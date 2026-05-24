<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Cookie;

class Cart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $selectedItems = [];
    public $selectAll = true;

    protected $listeners = ['cart-updated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $sessionId = Cookie::get('cart_session_id');
        
        $query = CartModel::with(['product.variants', 'variant']);
        
        if (auth()->check()) {
            $query->where('user_id', auth()->id())
                  ->orWhere('session_id', $sessionId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $this->cartItems = $query->get();
        if (empty($this->selectedItems)) {
            $this->selectedItems = $this->cartItems->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            // Remove items that no longer exist
            $validIds = $this->cartItems->pluck('id')->map(fn($id) => (string)$id)->toArray();
            $this->selectedItems = array_intersect($this->selectedItems, $validIds);
        }
        $this->selectAll = count($this->selectedItems) === count($this->cartItems) && count($this->cartItems) > 0;
        $this->calculateSubtotal();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->cartItems->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedItems = [];
        }
        $this->calculateSubtotal();
    }

    public function updatedSelectedItems()
    {
        $this->selectAll = count($this->selectedItems) === count($this->cartItems) && count($this->cartItems) > 0;
        $this->calculateSubtotal();
    }

    public function updateQuantity($cartId, $quantity)
    {
        $cart = CartModel::with('product')->find($cartId);
        if ($cart && $quantity > 0) {
            $min = $cart->product->min_order > 0 ? $cart->product->min_order : 1;
            if ($quantity < $min) {
                session()->flash('error', 'Minimal pembelian untuk produk ' . $cart->product->name . ' adalah ' . $min . ' pcs.');
                $this->loadCart();
                return;
            }
            $cart->update(['quantity' => $quantity]);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($cartId)
    {
        $cart = CartModel::find($cartId);
        if ($cart) {
            $cart->delete();
            $this->selectedItems = array_diff($this->selectedItems, [(string)$cartId]);
            $this->loadCart();
            $this->dispatch('cart-updated');
            session()->flash('success', 'Barang dihapus dari keranjang.');
        }
    }

    public function changeVariant($cartItemId, $newVariantId)
    {
        $cartItem = CartModel::find($cartItemId);
        if ($cartItem) {
            // Check if there is already an item in the cart with the same product_id and the new product_variant_id
            $existing = CartModel::where('product_id', $cartItem->product_id)
                ->where('product_variant_id', $newVariantId)
                ->where('id', '!=', $cartItemId)
                ->where(function($q) use ($cartItem) {
                    if ($cartItem->user_id) {
                        $q->where('user_id', $cartItem->user_id);
                    } else {
                        $q->where('session_id', $cartItem->session_id);
                    }
                })
                ->first();

            if ($existing) {
                // If it already exists, merge quantities and delete the current item
                $existing->increment('quantity', $cartItem->quantity);
                $cartItem->delete();
                
                // Keep the selection updated
                $this->selectedItems = array_diff($this->selectedItems, [(string)$cartItemId]);
                if (!in_array((string)$existing->id, $this->selectedItems)) {
                    $this->selectedItems[] = (string)$existing->id;
                }
            } else {
                // Otherwise, just update the variant ID
                $cartItem->update(['product_variant_id' => $newVariantId]);
            }

            $this->loadCart();
            $this->dispatch('cart-updated');
            session()->flash('success', 'Varian produk berhasil diubah.');
        }
    }

    public function deleteSelected()
    {
        if (count($this->selectedItems) > 0) {
            CartModel::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->loadCart();
            $this->dispatch('cart-updated');
            session()->flash('success', 'Barang terpilih berhasil dihapus.');
        }
    }

    public function checkoutSelected()
    {
        if (count($this->selectedItems) == 0) {
             session()->flash('error', 'Pilih minimal satu barang untuk dicheckout.');
             return;
         }

        // Validate product-level min order for selected items
        foreach ($this->cartItems as $item) {
            if (in_array((string)$item->id, $this->selectedItems)) {
                $min = $item->product->min_order > 0 ? $item->product->min_order : 1;
                if ($item->quantity < $min) {
                    session()->flash('error', 'Minimal pembelian untuk produk ' . $item->product->name . ' adalah ' . $min . ' pcs. Kuantitas Anda saat ini: ' . $item->quantity . ' pcs.');
                    return;
                }
            }
        }

        session()->put('selected_cart_items', $this->selectedItems);
        return redirect('/checkout');
    }

    public function calculateSubtotal()
    {
        $this->subtotal = $this->cartItems->filter(function($item) {
            return in_array((string)$item->id, $this->selectedItems);
        })->sum(function ($item) {
            return $item->subtotal;
        });
    }

    public function render()
    {
        return view('livewire.cart')->layout('components.layouts.app', [
            'title'  => 'Keranjang Belanja | INDOROSTER',
            'robots' => 'noindex, nofollow',
        ]);
    }
}
