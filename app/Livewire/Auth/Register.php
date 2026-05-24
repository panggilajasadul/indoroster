<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class Register extends Component
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|min:10',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        // Buat User Baru
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Login Otomatis
        Auth::login($user);

        // Gabungkan Keranjang
        $this->mergeCart();

        // Trigger event Registered agar mengirim email verifikasi bawaan Laravel
        event(new \Illuminate\Auth\Events\Registered($user));

        // Redirect ke intended URL atau halaman verifikasi
        return redirect()->route('verification.notice');
    }

    private function mergeCart()
    {
        $sessionId = Cookie::get('cart_session_id');
        if ($sessionId) {
            $userId = Auth::id();
            $sessionCartItems = Cart::where('session_id', $sessionId)->get();
            
            foreach ($sessionCartItems as $item) {
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
        return view('livewire.auth.register')->layout('components.layouts.app', ['title' => 'Daftar Akun - Indoroster']);
    }
}
