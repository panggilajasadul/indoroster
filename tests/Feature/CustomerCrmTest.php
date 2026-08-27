<?php

namespace Tests\Feature;

use App\Livewire\Auth\Register;
use App\Livewire\Member\Profile;
use App\Mail\CustomerQuotationMail;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerCrmTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_access_and_update_crm_profile()
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'customer_type' => 'individual',
            'phone' => '081234567890',
        ]);

        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->assertStatus(200)
            ->set('name', 'Bpk. Hendra Saputra')
            ->set('customer_type', 'contractor')
            ->set('company_name', 'PT Sinar Bangun Mandiri')
            ->set('phone', '081298765432')
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('Bpk. Hendra Saputra', $user->name);
        $this->assertEquals('contractor', $user->customer_type);
        $this->assertEquals('PT Sinar Bangun Mandiri', $user->company_name);
        $this->assertEquals('081298765432', $user->phone);
    }

    public function test_register_creates_user_and_redirects_to_member_profile()
    {
        Livewire::test(Register::class)
            ->set('name', 'Ibu Rina Maharani')
            ->set('email', 'rina.maharani@example.com')
            ->set('phone', '081311223344')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('member.profile'));

        $this->assertDatabaseHas('users', [
            'email' => 'rina.maharani@example.com',
            'name' => 'Ibu Rina Maharani',
            'phone' => '081311223344',
            'role' => 'customer',
        ]);
    }

    public function test_partner_cta_block_renders_properly()
    {
        $page = Page::create([
            'title' => 'Kemitraan IndoRoster',
            'slug' => 'kemitraan-indoroster',
            'is_active' => true,
            'content' => [
                [
                    'type' => 'partner_cta',
                    'data' => [
                        'badge' => 'Kemitraan Khusus',
                        'title' => 'Bergabung Jadi Mitra IndoRoster',
                        'description' => 'Diskon khusus kontraktor dan developer perumahan.',
                        'cta_text_1' => 'Daftar Sekarang',
                        'cta_url_1' => '/register',
                        'bg_theme' => 'dark',
                    ],
                ],
            ],
        ]);

        $response = $this->get('/page/kemitraan-indoroster');
        $response->assertStatus(200);
        $response->assertSee('Bergabung Jadi Mitra IndoRoster');
        $response->assertSee('Kemitraan Khusus');
        $response->assertSee('Daftar Sekarang');
    }

    public function test_customer_quotation_mail_can_be_sent()
    {
        Mail::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'name' => 'dr. Arief Budiman',
            'email' => 'arief@example.com',
        ]);

        Mail::to($user->email)->send(new CustomerQuotationMail(
            user: $user,
            subjectText: 'Penawaran Harga Roster Proyek',
            messageBody: 'Berikut informasi pricelist khusus mitra kami.',
            offerTitle: 'Penawaran Spesial IndoRoster'
        ));

        Mail::assertSent(CustomerQuotationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) &&
                   $mail->subjectText === 'Penawaran Harga Roster Proyek';
        });
    }
}
