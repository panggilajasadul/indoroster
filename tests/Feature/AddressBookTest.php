<?php

namespace Tests\Feature;

use App\Livewire\Member\AddressBook;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Livewire\Livewire;
use Tests\TestCase;

class AddressBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_and_edit_address_with_village()
    {
        $province = Province::create(['code' => '32', 'name' => 'JAWA BARAT']);
        $city = City::create(['code' => '3214', 'province_code' => '32', 'name' => 'KABUPATEN PURWAKARTA']);
        $district = District::create(['code' => '321405', 'city_code' => '3214', 'name' => 'TEGALWARU']);
        $village = Village::create([
            'code' => '3214052001',
            'district_code' => '321405',
            'name' => 'CADASMEKAR',
            'meta' => json_encode(['pos' => '41165', 'lat' => -6.6, 'long' => 107.3]),
        ]);

        $user = User::factory()->create(['role' => 'customer']);
        $this->actingAs($user);

        Livewire::test(AddressBook::class)
            ->assertStatus(200)
            ->call('openCreateForm')
            ->set('label', 'Proyek Ruko')
            ->set('recipient_name', 'Bpk. Hendra')
            ->set('phone', '081234567890')
            ->set('province_id', '32')
            ->set('city_id', '3214')
            ->set('district_id', '321405')
            ->set('village_id', '3214052001')
            ->set('postal_code', '41165')
            ->set('full_address', 'Jl. Raya Cadasmekar No. 12, RT 01/RW 02')
            ->call('saveAddress')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label' => 'Proyek Ruko',
            'recipient_name' => 'Bpk. Hendra',
            'province' => 'JAWA BARAT',
            'city' => 'KABUPATEN PURWAKARTA',
            'district' => 'TEGALWARU',
            'village' => 'CADASMEKAR',
            'postal_code' => '41165',
        ]);

        $address = Address::where('user_id', $user->id)->first();
        $this->assertStringContainsString('CADASMEKAR', $address->formatted_address);

        // Test editing address
        Livewire::test(AddressBook::class)
            ->call('openEditForm', $address->id)
            ->set('label', 'Proyek Rumah Tinggal')
            ->call('saveAddress')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Proyek Rumah Tinggal',
            'village' => 'CADASMEKAR',
        ]);
    }
}
