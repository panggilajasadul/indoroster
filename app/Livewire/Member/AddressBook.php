<?php

namespace App\Livewire\Member;

use App\Models\Address;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Livewire\Component;

class AddressBook extends Component
{
    public $addresses = [];

    public $isFormOpen = false;

    public $addressId = null;

    // Form fields
    public $label = 'Rumah';

    public $recipient_name;

    public $phone;

    public $province_id;

    public $city_id;

    public $district_id;

    public $postal_code;

    public $full_address;

    public $latitude = null;

    public $longitude = null;

    public $is_default = false;

    // Dropdowns data
    public $provinces = [];

    public $cities = [];

    public $districts = [];

    protected $rules = [
        'label' => 'required|string|max:50',
        'recipient_name' => 'required|string|min:3',
        'phone' => 'required|string|min:10',
        'province_id' => 'required',
        'city_id' => 'required',
        'district_id' => 'required',
        'postal_code' => 'required|string|max:10',
        'full_address' => 'required|string|min:10',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'is_default' => 'boolean',
    ];

    protected $messages = [
        'label.required' => 'Label alamat wajib diisi (misal: Rumah, Kantor).',
        'recipient_name.required' => 'Nama penerima wajib diisi.',
        'phone.required' => 'Nomor HP wajib diisi.',
        'province_id.required' => 'Silakan pilih provinsi.',
        'city_id.required' => 'Silakan pilih kota/kabupaten.',
        'district_id.required' => 'Silakan pilih kecamatan.',
        'postal_code.required' => 'Kode pos wajib diisi.',
        'full_address.required' => 'Detail alamat wajib diisi.',
        'full_address.min' => 'Detail alamat minimal 10 karakter.',
    ];

    public function mount()
    {
        $this->loadAddresses();
        $this->provinces = Province::orderBy('name')->get();
    }

    public function loadAddresses()
    {
        $this->addresses = auth()->user()->addresses()->orderByDesc('is_default')->orderByDesc('created_at')->get();
    }

    public function updatedProvinceId($value)
    {
        if ($value) {
            $this->cities = City::where('province_code', $value)->orderBy('name')->get();
        } else {
            $this->cities = [];
        }
        $this->city_id = null;
        $this->district_id = null;
        $this->districts = [];
    }

    public function updatedCityId($value)
    {
        if ($value) {
            $this->districts = District::where('city_code', $value)->orderBy('name')->get();
        } else {
            $this->districts = [];
        }
        $this->district_id = null;
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function openEditForm($id)
    {
        $this->resetForm();
        $address = Address::where('user_id', auth()->id())->findOrFail($id);

        $this->addressId = $address->id;
        $this->label = $address->label;
        $this->recipient_name = $address->recipient_name;
        $this->phone = $address->phone;
        $this->postal_code = $address->postal_code;
        $this->full_address = $address->full_address;
        $this->latitude = $address->latitude;
        $this->longitude = $address->longitude;
        $this->is_default = $address->is_default;

        // Map names back to Laravolt IDs
        $prov = Province::where('name', 'like', $address->province)->first();
        if ($prov) {
            $this->province_id = $prov->code;
            $this->cities = City::where('province_code', $prov->code)->orderBy('name')->get();

            $city = City::where('province_code', $prov->code)->where('name', 'like', $address->city)->first();
            if ($city) {
                $this->city_id = $city->code;
                $this->districts = District::where('city_code', $city->code)->orderBy('name')->get();

                $dist = District::where('city_code', $city->code)->where('name', 'like', $address->district)->first();
                if ($dist) {
                    $this->district_id = $dist->code;
                }
            }
        }

        $this->isFormOpen = true;
    }

    public function saveAddress()
    {
        $this->validate();

        $provinceName = Province::where('code', $this->province_id)->first()?->name;
        $cityName = City::where('code', $this->city_id)->first()?->name;
        $districtName = District::where('code', $this->district_id)->first()?->name;

        if (! $provinceName || ! $cityName || ! $districtName) {
            session()->flash('error', 'Pilihan wilayah tidak valid.');

            return;
        }

        if ($this->is_default) {
            // Set all other default status to false
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $data = [
            'user_id' => auth()->id(),
            'label' => $this->label,
            'recipient_name' => $this->recipient_name,
            'phone' => $this->phone,
            'province' => $provinceName,
            'city' => $cityName,
            'district' => $districtName,
            'postal_code' => $this->postal_code,
            'full_address' => $this->full_address,
            'latitude' => $this->latitude ?: null,
            'longitude' => $this->longitude ?: null,
            'is_default' => $this->is_default,
        ];

        if ($this->addressId) {
            $address = Address::where('user_id', auth()->id())->findOrFail($this->addressId);
            $address->update($data);
            session()->flash('success', 'Alamat berhasil diperbarui.');
        } else {
            // If it is the first address, make it default automatically
            if (count($this->addresses) === 0) {
                $data['is_default'] = true;
            }
            Address::create($data);
            session()->flash('success', 'Alamat baru berhasil ditambahkan.');
        }

        $this->isFormOpen = false;
        $this->resetForm();
        $this->loadAddresses();
    }

    public function setDefault($id)
    {
        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address = Address::where('user_id', auth()->id())->findOrFail($id);
        $address->update(['is_default' => true]);

        session()->flash('success', 'Alamat utama berhasil diubah.');
        $this->loadAddresses();
    }

    public function deleteAddress($id)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($id);

        $wasDefault = $address->is_default;
        $address->delete();

        // If the deleted address was default, set another default if available
        if ($wasDefault) {
            $next = Address::where('user_id', auth()->id())->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        session()->flash('success', 'Alamat berhasil dihapus.');
        $this->loadAddresses();
    }

    public function resetForm()
    {
        $this->addressId = null;
        $this->label = 'Rumah';
        $this->recipient_name = '';
        $this->phone = '';
        $this->province_id = null;
        $this->city_id = null;
        $this->district_id = null;
        $this->postal_code = '';
        $this->full_address = '';
        $this->latitude = null;
        $this->longitude = null;
        $this->is_default = false;

        $this->cities = [];
        $this->districts = [];
    }

    public function render()
    {
        return view('livewire.member.address-book')
            ->layout('components.layouts.app', ['title' => 'Buku Alamat - Indoroster']);
    }
}
