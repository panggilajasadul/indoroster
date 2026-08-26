<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class ScrapedDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Site Settings
        $settings = [
            ['key' => 'whatsapp_number', 'value' => '0813-8970-9847', 'group' => 'contact', 'type' => 'text', 'description' => 'Nomor WA utama'],
            ['key' => 'contact_email', 'value' => 'abdulhamid66266@gmail.com', 'group' => 'contact', 'type' => 'text', 'description' => 'Email kontak'],
            ['key' => 'factory_address', 'value' => 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165', 'group' => 'contact', 'type' => 'textarea', 'description' => 'Alamat pabrik'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 2. Banner
        Banner::truncate();
        Banner::create([
            'title' => 'Ubah Tampilan Rumah Anda Jadi Lebih Mewah & Estetik Hanya Dalam 1 Hari!',
            'subtitle' => 'Gak perlu renovasi total. Cukup ganti pagar atau sekat ruangan dengan Roster Beton Minimalis. Rumah jadi lebih adem dan sirkulasi lancar.',
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg', // Professional image from their about page
            'button_text' => 'CEK KATALOG & PROMO',
            'button_url' => '/katalog',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Roster Beton Kualitas Ekspor',
            'subtitle' => 'Kami menyediakan roster beton kualitas ekspor untuk pasar lokal dengan harga yang jauh lebih terjangkau langsung dari Pabrik Plered.',
            'image_url' => 'https://indoroster.com/wp-content/uploads/2025/12/97.jpg',
            'button_text' => 'Hubungi Kami',
            'button_url' => '/kontak',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 3. Pages
        Page::truncate();
        Page::create([
            'title' => 'Tentang Kami',
            'slug' => 'tentang-kami',
            'content' => '
                <h2>Pabrik Roster Minimalis Modern & Terlengkap</h2>
                <p><strong>IndoRoster</strong> hadir sebagai solusi untuk kebutuhan sirkulasi udara dan estetika hunian Anda. Kami menyediakan roster beton kualitas ekspor untuk pasar lokal dengan harga yang jauh lebih terjangkau karena Anda membeli langsung dari pabrik (First Hand) di Plered, Purwakarta.</p>
                <br>
                <img src="https://res.cloudinary.com/indoroster/image/upload/v1765260853/162067858_988931008308004_8757323712171815873_n_kpbq7h.jpg" alt="Pabrik Indoroster" style="max-width:100%; border-radius: 8px; margin: 20px 0;">
                <br>
                <h3>Kenapa Memilih Kami?</h3>
                <ul>
                    <li><strong>Material Beton High-Grade:</strong> Tidak hanya kuat, tapi permukaannya halus dan presisi (siap pasang tanpa banyak semen).</li>
                    <li><strong>50+ Pilihan Motif:</strong> Mulai dari gaya Industrial, Minimalis, hingga Klasik tersedia di sini.</li>
                    <li><strong>Harga Pabrik:</strong> Anda beli langsung dari produsen di Plered, Purwakarta. Tanpa perantara, harga lebih hemat!</li>
                    <li><strong>Bergaransi:</strong> Barang pecah di jalan? Kami ganti baru! (S&K Berlaku).</li>
                </ul>
            ',
            'meta_title' => 'Tentang Kami - Indoroster Pabrik Roster Purwakarta',
            'meta_description' => 'Pabrik roster beton minimalis berkualitas dari Plered, Purwakarta. Harga pabrik, kualitas ekspor, dan bergaransi.',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Kontak',
            'slug' => 'kontak',
            'content' => '
                <h2>Hubungi IndoRoster</h2>
                <p>Kami siap membantu kebutuhan arsitektur dan material roster Anda. Silakan hubungi kami melalui kontak di bawah ini:</p>
                <br>
                <p><strong>WhatsApp (Respon Cepat):</strong> <a href="https://wa.me/6281389709847">+62 813-8970-9847</a></p>
                <p><strong>Email:</strong> abdulhamid66266@gmail.com</p>
                <br>
                <h3>Alamat Pabrik & Showroom</h3>
                <p>Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165</p>
            ',
            'is_active' => true,
        ]);

        // 4. Testimonials
        Testimonial::truncate();
        Testimonial::create([
            'customer_name' => 'Bapak Budi',
            'customer_role' => 'Homeowner – Jakarta',
            'location' => 'Jakarta',
            'rating' => 5,
            'content' => 'Barang sampai tepat waktu. Kualitas betonnya memang beda, jauh lebih halus dan presisi dibanding beli di toko material biasa. Nanti proyek perumahan saya berikutnya saya order lagi 1.000 pcs.',
            'photo_url' => null,
            'is_active' => true,
        ]);
        Testimonial::create([
            'customer_name' => 'Andi',
            'customer_role' => 'Contractor – Bandung',
            'location' => 'Bandung',
            'rating' => 5,
            'content' => 'Mas, rosternya sudah terpasang semua. Suka banget sama hasilnya, rumah jadi kelihatan mewah padahal cuma ganti pagar depan. Tetangga pada nanya beli di mana, saya kasih kontak IndoRoster ya!',
            'photo_url' => null,
            'is_active' => true,
        ]);
        Testimonial::create([
            'customer_name' => 'Hendra',
            'customer_role' => 'Residential Project – Cikarang',
            'location' => 'Cikarang',
            'rating' => 5,
            'content' => 'Awalnya ragu beli online karena takut pecah, tapi ternyata packingnya aman banget. Ada pecah 2 biji langsung diganti tanpa ribet. Mantap pelayanannya!',
            'photo_url' => null,
            'is_active' => true,
        ]);

        // 5. FAQs
        Faq::truncate();
        Faq::create([
            'question' => 'Apakah barang aman dan tidak pecah saat pengiriman?',
            'answer' => '<p>Kami memiliki standar packing yang sangat aman untuk ekspedisi. Selain itu, kami memberikan <strong>garansi retur / ganti baru</strong> jika terjadi kerusakan fatal atau pecah saat pengiriman (Syarat dan Ketentuan berlaku).</p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        Faq::create([
            'question' => 'Bisa kirim ke daerah mana saja?',
            'answer' => '<p>Fokus utama pengiriman kami adalah area <strong>Jabodetabek dan Jawa Barat</strong>. Namun, kami juga melayani pengiriman ke seluruh pulau Jawa.</p>',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        Faq::create([
            'question' => 'Apakah bisa kirim ke luar kota/pulau?',
            'answer' => '<p>Pengiriman ke luar kota jarak jauh atau luar pulau bisa dilakukan khusus untuk pemesanan dalam partai besar (minimal di atas 1.000 pcs).</p>',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 6. Gallery
        Gallery::truncate();
        $gallery = Gallery::create([
            'title' => 'Inspirasi Fasad & Pagar',
            'slug' => 'inspirasi-fasad-pagar',
            'description' => 'Kumpulan video dan foto pemasangan roster beton minimalis pada berbagai proyek.',
            'location' => 'Berbagai Proyek',
            'is_active' => true,
        ]);

        $gallery->media()->create([
            'media_type' => 'image',
            'media_url' => 'https://indoroster.com/wp-content/uploads/2025/12/47.jpg',
            'caption' => 'Pemasangan Roster Fasad',
        ]);
        $gallery->media()->create([
            'media_type' => 'image',
            'media_url' => 'https://indoroster.com/wp-content/uploads/2025/12/16-768x768.webp',
            'caption' => 'Pemasangan Pagar Roster',
        ]);
        $gallery->media()->create([
            'media_type' => 'video',
            'media_url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765639031/TikDownloader.io_7572766490286591240_hd_a6b4nk.mp4',
            'caption' => 'Video Review Roster Minimalis',
        ]);
        $gallery->media()->create([
            'media_type' => 'video',
            'media_url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765259252/26_shmagn.mp4',
            'caption' => 'Proses Produksi Pabrik',
        ]);
    }
}
