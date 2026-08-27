<?php

namespace App\Livewire\Member;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
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

    // Alamat Proyek Utama
    public $label = 'Lokasi Proyek';

    public $recipient_name;

    public $address_phone;

    public $province_id;

    public $city_id;

    public $district_id;

    public $postal_code;

    public $full_address;

    public $truck_access_notes;

    // Dropdowns
    public $provinces = [];

    public $cities = [];

    public $districts = [];

    public $defaultAddressId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:10|max:20',
            'customer_type' => 'required|in:individual,contractor,architect,commercial,developer',
            'company_name' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'nullable|string|min:3|max:255',
            'address_phone' => 'nullable|string|min:10|max:20',
            'province_id' => 'nullable',
            'city_id' => 'nullable',
            'district_id' => 'nullable',
            'postal_code' => 'nullable|string|max:10',
            'full_address' => 'nullable|string|min:5|max:1000',
            'truck_access_notes' => 'nullable|string|max:500',
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

        // Load wilayah
        $this->provinces = Province::orderBy('name')->get();

        // Load alamat default / pertama
        $defaultAddress = $user->addresses()->where('is_default', true)->first() ?: $user->addresses()->first();
        if ($defaultAddress) {
            $this->defaultAddressId = $defaultAddress->id;
            $this->label = $defaultAddress->label;
            $this->recipient_name = $defaultAddress->recipient_name;
            $this->address_phone = $defaultAddress->phone;
            $this->postal_code = $defaultAddress->postal_code;
            $this->full_address = $defaultAddress->full_address;
            $this->truck_access_notes = $defaultAddress->truck_access_notes;

            // Cari ID wilayah berdasarkan nama
            $prov = Province::where('name', $defaultAddress->province)->first();
            if ($prov) {
                $this->province_id = $prov->code;
                $this->cities = City::where('province_code', $prov->code)->orderBy('name')->get();

                $city = City::where('name', $defaultAddress->city)->where('province_code', $prov->code)->first();
                if ($city) {
                    $this->city_id = $city->code;
                    $this->districts = District::where('city_code', $city->code)->orderBy('name')->get();

                    $dist = District::where('name', $defaultAddress->district)->where('city_code', $city->code)->first();
                    if ($dist) {
                        $this->district_id = $dist->code;
                    }
                }
            }
        } else {
            $this->recipient_name = $this->name;
            $this->address_phone = $this->phone;
        }
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

    public function save()
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();

        // 1. Simpan Data User
        $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'customer_type' => $this->customer_type,
            'company_name' => $this->company_name,
        ]);

        // 2. Simpan / Perbarui Alamat Proyek jika diisi
        if ($this->full_address && $this->province_id && $this->city_id && $this->district_id) {
            $provinceName = Province::where('code', $this->province_id)->value('name') ?: '';
            $cityName = City::where('code', $this->city_id)->value('name') ?: '';
            $districtName = District::where('code', $this->district_id)->value('name') ?: '';

            $addressData = [
                'user_id' => $user->id,
                'label' => $this->label ?: 'Lokasi Proyek',
                'recipient_name' => $this->recipient_name ?: $user->name,
                'phone' => $this->address_phone ?: $user->phone,
                'province' => $provinceName,
                'city' => $cityName,
                'district' => $districtName,
                'postal_code' => $this->postal_code ?: '-',
                'full_address' => $this->full_address,
                'truck_access_notes' => $this->truck_access_notes,
                'is_default' => true,
            ];

            if ($this->defaultAddressId) {
                Address::where('id', $this->defaultAddressId)->where('user_id', $user->id)->update($addressData);
            } else {
                $createdAddress = Address::create($addressData);
                $this->defaultAddressId = $createdAddress->id;
            }
        }

        session()->flash('success', 'Profil dan data kemitraan Anda berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.member.profile')->layout('components.layouts.app', ['title' => 'Profil & Kemitraan - IndoRoster']);
    }
}
