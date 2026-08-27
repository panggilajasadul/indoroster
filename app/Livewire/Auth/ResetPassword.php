<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class ResetPassword extends Component
{
    public $token = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public function mount($token = null)
    {
        $this->token = $token ?: request()->route('token');
        $this->email = request()->query('email', '');
    }

    protected function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    protected $messages = [
        'email.required' => 'Silakan masukkan alamat email Anda.',
        'email.email' => 'Format alamat email tidak valid.',
        'password.required' => 'Silakan masukkan password baru Anda.',
        'password.min' => 'Password minimal terdiri dari 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function resetPassword()
    {
        $this->validate();

        $status = Password::broker()->reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordResetEvent($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru.');

            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password')
            ->layout('components.layouts.app', ['title' => 'Atur Ulang Kata Sandi - Indoroster']);
    }
}
