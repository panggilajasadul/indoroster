<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BulkCatalogProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan kategori Roster Beton tersedia
        $category = Category::firstOrCreate(
            ['slug' => 'roster-beton'],
            [
                'name' => 'Roster Beton',
                'description' => 'Koleksi roster beton minimalis berkualitas tinggi dengan cetak padat presisi dalam berbagai motif modern.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // 2. Pastikan 3 varian material dasar tersedia
        $materialAbu = Material::firstOrCreate(
            ['slug' => 'abu-abu'],
            ['name' => 'Abu-Abu', 'description' => 'Beton warna abu-abu natural presisi dan kokoh']
        );

        $materialDolomit = Material::firstOrCreate(
            ['slug' => 'dolamit'],
            ['name' => 'Dolomit', 'description' => 'Beton putih semen dolomit halus elegan']
        );

        $materialTerracota = Material::firstOrCreate(
            ['slug' => 'terracota'],
            ['name' => 'Terracota', 'description' => 'Beton merah terakota klasik hangat berkarakter']
        );

        // Deskripsi default standar IndoRoster
        $defaultDescription = '<h3>📝 PANDUAN PEMESANAN & LAYANAN KONSUMEN</h3>'
            .'<p>Di Indoroster, belanja roster jadi jauh lebih praktis. <strong>Anda tidak perlu login atau daftar akun</strong> untuk melakukan pemesanan. Cukup pilih motif, sesuaikan jumlah kebutuhan dinding Anda, dan tunggu pesanan sampai langsung di proyek Anda!</p>'
            .'<h4>1. Cara Pemesanan Mudah</h4>'
            .'<ul>'
            .'<li><strong>Pilih & Hitung:</strong> Gunakan kalkulator kebutuhan di halaman ini untuk estimasi akurat jumlah pcs yang diperlukan.</li>'
            .'<li><strong>Beli Langsung:</strong> Tentukan jumlah pcs dan klik Beli Sekarang atau hubungi WhatsApp Official kami.</li>'
            .'<li><strong>Isi Data Proyek:</strong> Masukkan nama dan alamat tujuan pengiriman tanpa proses registrasi yang rumit.</li>'
            .'<li><strong>Terima Invoice Resmi:</strong> Setelah transaksi terkonfirmasi, Invoice Resmi terbit otomatis sebagai bukti transaksi yang sah.</li>'
            .'</ul>'
            .'<h4>🚚 JANGKAUAN PENGIRIMAN CEPAT ARMADA INDOROSTER</h4>'
            .'<p>Sebagai produsen tangan pertama di Plered, Purwakarta, armada truk kami siap mendistribusikan pesanan langsung ke lokasi Anda dengan garansi aman 100%:</p>'
            .'<ul>'
            .'<li><strong>📍 Wilayah Jabodetabek:</strong> Melayani seluruh DKI Jakarta (Jakarta Selatan, Jakarta Barat, Jakarta Timur, Jakarta Utara, Jakarta Pusat), Bogor, Depok, Tangerang, Tangerang Selatan, dan Bekasi.</li>'
            .'<li><strong>📍 Wilayah Jawa Barat:</strong> Plered, Purwakarta, Karawang, Cikampek, Subang, Bandung Raya, Cimahi, Sumedang, Cirebon, Indramayu, Sukabumi, dan Cianjur.</li>'
            .'<li><strong>📍 Pengiriman Nasional & Luar Pulau:</strong> Ekspedisi kargo khusus material konstruksi menjangkau Jawa Tengah, Jawa Timur, Bali, Sumatera, Kalimantan, dan Sulawesi.</li>'
            .'</ul>'
            .'<h4>3. Hubungi Kami</h4>'
            .'<p>Butuh info lebih lanjut? Hubungi kami langsung di:</p>'
            .'<ul>'
            .'<li><strong>WhatsApp Official:</strong> <a href="https://wa.me/6281389709847">0813 8970 9847</a></li>'
            .'<li><strong>Jam Operasional:</strong> Senin - Sabtu (08.00 - 17.00 WIB)</li>'
            .'</ul>'
            .'<p>🛡️ <strong>Garansi Pengiriman:</strong> Setiap keping roster yang pecah dalam proses pengiriman armada kami akan diganti baru 100% tanpa biaya tambahan.</p>';

        // 3. Dataset 45 Produk dari Pengguna
        $rawProducts = [
            ['no' => 1, 'name' => 'Melati', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 2, 'name' => 'JaboL', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 3, 'name' => 'Tapak Ucing', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 4, 'name' => 'PCL', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 5, 'name' => 'Daun', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 9', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 6, 'name' => 'Petir', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 7, 'name' => 'Kincir', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 8, 'name' => 'Nako LS', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 9, 'name' => 'MD', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 10, 'name' => 'Nako Sipit', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 11, 'name' => 'LB 4', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 12, 'name' => 'MMC', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 13, 'name' => 'Z', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 14, 'name' => 'M', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 15, 'name' => 'Labirin', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 16, 'name' => 'Nako 1', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 17, 'name' => 'Robot', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 18, 'name' => 'Arrow', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 19, 'name' => 'NRT', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 20, 'name' => 'Dosol (LB4 2 Muka)', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 21, 'name' => 'Diamon', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 22, 'name' => '2L', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 23, 'name' => 'Eceng', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 24, 'name' => 'LDR', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 25, 'name' => 'Kawung', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 26, 'name' => 'Donat', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 8', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 27, 'name' => 'PLS', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 28, 'name' => 'Kupu Kupu 2', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 29, 'name' => 'Bintang', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 30, 'name' => 'VC (Congklak)', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 31, 'name' => 'Diamon 2', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 32, 'name' => 'BLK', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 8', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 33, 'name' => 'LB 5', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 34, 'name' => 'Tanjakan Emen', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 35, 'name' => 'Dadu (Lubang 1/2/3)', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 36, 'name' => 'DJL', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 37, 'name' => 'Yamaha', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 38, 'name' => 'Bodrex', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 8', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 39, 'name' => 'L Nako', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 40, 'name' => 'Batman', 'type' => 'Dua Sisi', 'dims' => '20 x 20 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
            ['no' => 41, 'name' => 'ADDS', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 42, 'name' => 'Panah', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 43, 'name' => 'PLS 2', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 10', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 44, 'name' => 'Wayang', 'type' => 'Satu Sisi', 'dims' => '20 x 20 x 8', 'white' => 11500, 'red' => 11500, 'grey' => 10000],
            ['no' => 45, 'name' => 'Nako Panjang', 'type' => 'Dua Sisi', 'dims' => '30 x 15 x 10', 'white' => 12000, 'red' => 12000, 'grey' => 11000],
        ];

        foreach ($rawProducts as $item) {
            $cleanDimensions = $item['dims'].' cm';
            $formattedTitle = 'Roster Beton Minimalis Motif '.$item['name'].' ('.$item['type'].') '.$cleanDimensions;
            $slug = Str::slug('roster-beton-minimalis-motif-'.$item['name'].'-'.$item['type'].'-'.str_replace(' ', '', $item['dims']));
            $sku = 'IR-MOTIF-'.str_pad((string) $item['no'], 3, '0', STR_PAD_LEFT);
            $weight = ($item['dims'] === '30 x 15 x 10') ? 4.2 : 3.5;

            // Generate metadata SEO lengkap
            // Generate metadata SEO lengkap
            $focusKeyword = 'roster beton minimalis motif '.strtolower($item['name']).' '.strtolower($item['type']);
            $secondaryKeywords = [
                'loster motif '.strtolower($item['name']),
                'roster '.strtolower($item['type']).' minimalis',
                'roster beton '.str_replace(' ', '', strtolower($item['dims'])),
                'roster pagar minimalis modern',
                'roster fasad dinding ventilasi',
                'pabrik roster plered purwakarta',
                'jual roster jakarta jabodetabek',
                'supplier roster bandung jawa barat',
                'ekspedisi roster kargo nasional',
            ];
            $seoH1 = 'Jual '.$formattedTitle.' Harga Pabrik Langsung';
            $metaTitle = 'Jual '.$formattedTitle.' Murah Harga Pabrik | IndoRoster';
            if (mb_strlen($metaTitle) > 65) {
                $metaTitle = $formattedTitle.' | IndoRoster';
            }

            $productIntro = "Jual {$formattedTitle} harga pabrik termurah kualitas cetak padat dan presisi. Roster beton minimalis modern presisi, tahan cuaca, ideal untuk pagar rumah, fasad dinding, ventilasi udara, serta sekat partisi interior & eksterior estetis.";
            $deliveryCoverage = "Pengiriman Cepat Jabodetabek, Jawa Barat & Ekspedisi Nasional.\n\nSebagai pusat produksi tangan pertama di Plered, Purwakarta, armada truk kami siap mengirimkan pesanan partai kecil maupun ribuan pieces langsung ke gerbang proyek Anda dengan jaminan garansi aman sampai tujuan:\n\n📍 Wilayah Jabodetabek: Melayani seluruh kawasan DKI Jakarta (Jakarta Selatan, Jakarta Barat, Jakarta Timur, Jakarta Utara, Jakarta Pusat), Bogor, Depok, Tangerang, Tangerang Selatan, dan Bekasi.\n📍 Wilayah Jawa Barat: Plered, Purwakarta, Karawang, Cikampek, Subang, Bandung Raya, Cimahi, Sumedang, Cirebon, Indramayu, Sukabumi, dan Cianjur.\n📍 Pengiriman Nasional & Luar Pulau: Ekspedisi kargo khusus material aman menjangkau Jawa Tengah, Jawa Timur, Bali, Sumatera, Kalimantan, dan Sulawesi.";
            $metaDesc = "{$productIntro}\n\n{$deliveryCoverage}";

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'slug' => $slug,
                    'category_id' => $category->id,
                    'name' => $formattedTitle,
                    'description' => $defaultDescription,
                    'short_description' => 'Roster beton minimalis arsitektural motif '.$item['name'].' tipe '.$item['type'].' ukuran '.$cleanDimensions.'. Kokoh, padat, presisi, dan cocok untuk dinding ventilasi fasad maupun pagar.',
                    'dimensions' => $cleanDimensions,
                    'weight' => $weight,
                    'price' => 0, // Menggunakan harga di level varian
                    'original_price' => $item['white'] + 2000,
                    'min_order' => 1,
                    'stock' => 85000,
                    'total_sold' => 150 + (($item['no'] * 17) % 700),
                    'is_featured' => ($item['no'] <= 8), // 8 produk teratas featured
                    'is_active' => true,
                    'best_for' => 'Fasad Rumah, Pagar Minimalis, Dinding Ventilasi, Sekat Partisi Interior & Eksterior',
                    // SEO Growth Engine fields
                    'focus_keyword' => $focusKeyword,
                    'secondary_keywords' => $secondaryKeywords,
                    'seo_h1' => $seoH1,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDesc,
                    'og_title' => $metaTitle,
                    'og_description' => $metaDesc,
                    'seo_score' => 100,
                    'opportunity_score' => 98,
                    'seo_issues' => [],
                    'seo_last_analyzed' => now(),
                ]
            );

            // Varian 1: Abu-Abu
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'material_id' => $materialAbu->id,
                ],
                [
                    'name' => 'Abu-Abu',
                    'price_adjustment' => $item['grey'],
                    'stock' => 85000,
                    'weight' => $weight,
                    'is_active' => true,
                ]
            );

            // Varian 2: Dolomit (Putih)
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'material_id' => $materialDolomit->id,
                ],
                [
                    'name' => 'Dolomit',
                    'price_adjustment' => $item['white'],
                    'stock' => 85000,
                    'weight' => $weight,
                    'is_active' => true,
                ]
            );

            // Varian 3: Terracota (Merah)
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'material_id' => $materialTerracota->id,
                ],
                [
                    'name' => 'Terracota',
                    'price_adjustment' => $item['red'],
                    'stock' => 85000,
                    'weight' => $weight,
                    'is_active' => true,
                ]
            );
        }
    }
}
