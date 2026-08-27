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

        try {
            Auth::user()->sendEmailVerificationNotification();
            session()->flash('success', 'Link verifikasi baru telah berhasil dikirim ke alamat email Anda.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim email verifikasi: '.$e->getMessage().'. Pastikan pengaturan SMTP email Anda valid.');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email')->layout('components.layouts.app', ['title' => 'Verifikasi Email']);
    }
}
