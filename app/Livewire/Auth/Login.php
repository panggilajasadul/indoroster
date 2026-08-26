<?php

namespace App\Livewire\Auth;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class Login extends Component
{
    public $email;

    public $password;

    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Gabungkan keranjang
            $this->mergeCart();

            // Flash success message
            session()->flash('success', 'Selamat datang kembali! Login berhasil.');

            // Redirect ke intended URL (seperti checkout jika dia sedang checkout) atau home
            return redirect()->intended(route('home'));
        }

        $this->addError('email', 'Email atau password salah.');
    }

    private function mergeCart()
    {
        $sessionId = Cookie::get('cart_session_id');
        if ($sessionId) {
            $userId = Auth::id();

            // Ambil semua barang keranjang dari session ini
            $sessionCartItems = Cart::where('session_id', $sessionId)->get();

            foreach ($sessionCartItems as $item) {
                // Cek apakah barang yang sama (produk & varian) sudah ada di keranjang user
                $existing = Cart::where('user_id', $userId)
                    ->where('product_id', $item->product_id)
                    ->where('product_variant_id', $item->product_variant_id)
                    ->first();

                if ($existing) {
                    $existing->increment('quantity', $item->quantity);
                    $item->delete();
                } else {
                    $item->update([
                        'user_id' => $userId,
                    ]);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.app', ['title' => 'Masuk - Indoroster']);
    }
}
