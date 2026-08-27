<?php

namespace App\Livewire\Member;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    // Identitas Dasar
    public $name;

    public $email;

    public $phone;

    // Profil CRM / Segmentasi
    public $customer_type = 'individual';

    public $company_name;

    // Ringkasan Alamat Utama (Readonly preview)
    public $defaultAddress = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:10|max:20',
            'customer_type' => 'required|in:individual,contractor,architect,commercial,developer',
            'company_name' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->customer_type = $user->customer_type ?: 'individual';
        $this->company_name = $user->company_name;

        // Load alamat default untuk preview ringkas
        $this->defaultAddress = $user->addresses()->where('is_default', true)->first() ?: $user->addresses()->first();
    }

    public function save()
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();

        $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'customer_type' => $this->customer_type,
            'company_name' => $this->company_name,
        ]);

        session()->flash('success', 'Profil kemitraan dan kontak Anda berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.member.profile')->layout('components.layouts.app', ['title' => 'Profil & Kemitraan - IndoRoster']);
    }
}
