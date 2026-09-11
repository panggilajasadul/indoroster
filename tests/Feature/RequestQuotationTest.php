<?php

namespace Tests\Feature;

use App\Livewire\RequestQuotation;
use App\Models\Category;
use App\Models\Product;
use App\Models\ShippingRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Livewire\Livewire;
use Tests\TestCase;

class RequestQuotationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_quotation_form_for_hidden_price_product()
    {
        $category = Category::create(['name' => 'Roster Beton', 'slug' => 'roster-beton']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Motif Custom Proyek',
            'slug' => 'roster-motif-custom-proyek',
            'sku' => 'IR-CUSTOM01',
            'description' => 'Deskripsi roster custom',
            'price' => 15000,
            'hide_price' => true,
            'min_order' => 100,
            'stock' => 1000,
            'is_active' => true,
        ]);

        $response = $this->get(route('product.quotation', $product->slug));
        $response->assertStatus(200);
        $response->assertSee('Formulir Permintaan Penawaran Resmi');
        $response->assertSee('Roster Motif Custom Proyek');
    }

    public function test_guest_can_submit_quotation_request_successfully()
    {
        $category = Category::create(['name' => 'Roster Beton', 'slug' => 'roster-beton']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Motif Bintang',
            'slug' => 'roster-motif-bintang',
            'sku' => 'IR-BINTANG',
            'description' => 'Deskripsi roster bintang',
            'price' => 12000,
            'hide_price' => true,
            'min_order' => 50,
            'stock' => 500,
            'is_active' => true,
        ]);

        $province = Province::create(['code' => '32', 'name' => 'JAWA BARAT']);
        $city = City::create(['code' => '3273', 'province_code' => '32', 'name' => 'KOTA BANDUNG']);

        ShippingRate::create([
            'city_code' => '3273',
            'shipping_cost' => 150000,
            'rate_type' => 'flat',
            'is_active' => true,
        ]);

        Livewire::test(RequestQuotation::class, ['slug' => $product->slug])
            ->set('name', 'Bpk. Ahmad')
            ->set('company_name', 'PT Surya Konstruksi')
            ->set('phone', '081234567890')
            ->set('email', 'ahmad@example.com')
            ->set('province_id', '32')
            ->set('city_id', '3273')
            ->set('quantity', 250)
            ->set('notes', 'Mohon jadwal kirim armada ke lokasi proyek')
            ->call('submitQuotation')
            ->assertHasNoErrors()
            ->assertSet('isSubmitted', true);

        $this->assertDatabaseHas('quotation_requests', [
            'name' => 'Bpk. Ahmad',
            'company_name' => 'PT Surya Konstruksi',
            'phone' => '081234567890',
            'city_code' => '3273',
            'quantity' => 250,
            'status' => 'pending',
        ]);
    }
}
