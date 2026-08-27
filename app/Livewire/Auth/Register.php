<?php

namespace App\Livewire\Auth;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

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
        event(new Registered($user));

        // Redirect ke halaman kelola profil kemitraan
        return redirect()->route('member.profile')->with('success', 'Selamat datang di IndoRoster! Silakan lengkapi profil & lokasi proyek Anda.');
    }

    private function mergeCart()
    {
        $sessionId = Cookie::get('cart_session_id');
        if ($sessionId) {
            $userId = Auth::id();
            /** @var Collection<int, Cart> $sessionCartItems */
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
