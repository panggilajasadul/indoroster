<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('success', 'Link verifikasi baru telah dikirim ke alamat email Anda.');
    }

    public function render()
    {
        return view('livewire.auth.verify-email')->layout('components.layouts.app', ['title' => 'Verifikasi Email']);
    }
}
