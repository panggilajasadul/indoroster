<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SeoLocation;
use Illuminate\Database\Seeder;

class SeoLocationPrdSeeder extends Seeder
{
    /**
     * Seed & ensure 25 priority PRD cities and provinces with high-quality SEO content (Score >= 85).
     */
    public function run(): void
    {
        $topMotifIds = Product::where('is_active', true)->take(8)->pluck('id')->toArray();

        $locationsData = [
            [
                'name' => 'Jakarta',
                'slug' => 'roster-beton-minimalis-jakarta',
                'type' => 'metropolitan_area',
                'province_code' => '31',
                'city_code' => '3171',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'priority' => 1,
                'headline' => 'Pusat Pabrik & Supplier Roster Beton Jakarta — Pengiriman Langsung Tangan Pertama',
                'intro_content' => 'IndoRoster melayani suplai dan pengiriman langsung pabrik roster beton minimalis berkualitas premium untuk seluruh wilayah Daerah Khusus Ibukota Jakarta. Diproduksi di sentra Plered Purwakarta dengan sistem cetak tumbuk padat plat baja presisi oleh pengrajin berpengalaman menggunakan pasir abu batu murni (tanpa pasir silika). Hasil cetakan sangat padat, siku 90 derajat sempurna, kokoh, dan bertekstur halus alami. Cocok untuk arsitektur rumah tinggal modern, pagar estetik, fasad gedung perkantoran, partisi interior cafe, hingga proyek properti komersial skala besar di Jakarta.',
                'delivery_route_info' => 'Pengiriman armada truk pabrik langsung via Tol Cipularang - Tol Jakarta-Cikampek - Tol Dalam Kota / JORR.',
                'estimated_delivery_time' => '1 hari kerja (jadwal armada rutin setiap hari)',
                'shipping_guarantee_text' => 'Garansi 100% Bebas Pecah: Setiap keping roster yang pecah atau cacat dalam perjalanan armada langsung diganti baru di lokasi proyek.',
                'target_districts' => ['Menteng', 'Kebayoran Baru', 'Kelapa Gading', 'Pantai Indah Kapuk', 'Cilandak', 'Tebet', 'Puri Indah', 'Senopati', 'Kemang', 'Sunter'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama estimasi pengiriman roster beton ke Jakarta?',
                        'a' => 'Pengiriman ke seluruh wilayah DKI Jakarta memakan waktu 1 hari kerja langsung dari pabrik kami di Plered, Purwakarta via akses tol Jakarta-Cikampek.',
                    ],
                    [
                        'q' => 'Apakah ada minimal pemesanan roster untuk wilayah Jakarta?',
                        'a' => 'Kami melayani mulai dari pesanan eceran renovasi rumah (mulai 100-500 pcs) hingga partai besar borongan proyek kontraktor (ribuan hingga puluhan ribu pcs).',
                    ],
                    [
                        'q' => 'Bagaimana sistem garansi jika roster rusak saat sampai di Jakarta?',
                        'a' => 'IndoRoster memberikan garansi 100% ganti baru di tempat. Jika ada kepingan yang rusak saat dibongkar oleh armada kami, langsung kami gantikan tanpa biaya tambahan.',
                    ],
                    [
                        'q' => 'Apakah harga roster sudah termasuk ongkos kirim ke Jakarta?',
                        'a' => 'Harga yang kami tawarkan adalah harga tangan pertama langsung pabrik. Estimasi ongkir dihitung proporsional per ritase armada truk dan sangat kompetitif dibandingkan toko material retail.',
                    ],
                ],
            ],
            [
                'name' => 'Jakarta Selatan',
                'slug' => 'roster-beton-minimalis-jakarta-selatan',
                'type' => 'city',
                'province_code' => '31',
                'city_code' => '3174',
                'latitude' => -6.2615,
                'longitude' => 106.8106,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Jakarta Selatan — Fasad Minimalis & Partisi Rumah Tropis',
                'intro_content' => 'Layanan pemesanan dan pengiriman roster beton arsitektural langsung dari pabrik IndoRoster ke seluruh kawasan Jakarta Selatan. Roster beton kami dicetak padat presisi plat baja menggunakan abu batu murni Plered Purwakarta, menghasilkan daya tahan tinggi terhadap cuaca tropis perkotaan dan sudut siku yang sangat presisi memudahkan pemasangan. Pilihan favorit para arsitek dan pemilik hunian di Jaksel untuk aplikasi pagar minimalis, fasad ventilasi udara, sekat void tangga, dan dekorasi cafe aesthetic.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek tembus Tol JORR TB Simatupang langsung ke alamat proyek.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping yang pecah selama pengiriman.',
                'target_districts' => ['Kebayoran Baru', 'Kebayoran Lama', 'Cilandak', 'Pancoran', 'Tebet', 'Mampang Prapatan', 'Pasar Minggu', 'Jagakarsa', 'Pesanggrahan', 'Setiabudi'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah bisa kirim roster ke perumahan / jalan sempit di Jakarta Selatan?',
                        'a' => 'Bisa. Kami memiliki armada truk engkel maupun pick-up khusus yang fleksibel menjangkau perumahan dan klaster di area Jakarta Selatan.',
                    ],
                    [
                        'q' => 'Motif roster apa yang paling populer di Jakarta Selatan?',
                        'a' => 'Motif minimalis modern seperti Nako Sipit, Motif Petir, MMC, Bintang, dan Polos Kasar 2 Sisi menjadi pilihan utama proyek hunian di Jaksel.',
                    ],
                    [
                        'q' => 'Bagaimana cara order roster beton ke Jakarta Selatan?',
                        'a' => 'Cukup pilih motif di katalog online IndoRoster, lalu hubungi sales kami via WhatsApp untuk konfirmasi jumlah, diskon volume, dan jadwal armada.',
                    ],
                ],
            ],
            [
                'name' => 'Jakarta Timur',
                'slug' => 'roster-beton-minimalis-jakarta-timur',
                'type' => 'city',
                'province_code' => '31',
                'city_code' => '3175',
                'latitude' => -6.2250,
                'longitude' => 106.9004,
                'priority' => 1,
                'headline' => 'Jual Roster Beton Jakarta Timur — Pabrik Tangan Pertama Harga Grosir',
                'intro_content' => 'Pusat pengadaan roster beton minimalis, bata tempel expose, dan loster dinding arsitektural untuk wilayah Jakarta Timur. Suplai langsung tangan pertama dari sentra pabrikasi Purwakarta tanpa perantara. Material berkualitas tinggi dengan sistem cetak tumbuk padat plat baja, menghasilkan permukaan halus, siku 90 derajat rapi, dan kekuatan maksimal untuk konstruksi dinding ventilasi rumah, pagar hunian, ruko, serta bangunan komersial.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek / Tol Becakayu langsung ke pintu proyek Jakarta Timur.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru bila terjadi kerusakan dalam pengiriman armada pabrik.',
                'target_districts' => ['Duren Sawit', 'Cakung', 'Jatinegara', 'Matraman', 'Pulogadung', 'Kramat Jati', 'Pasar Rebo', 'Ciracas', 'Cipayung', 'Makasar'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster ke wilayah Jakarta Timur?',
                        'a' => 'Estimasi tiba 1 hari kerja setelah konfirmasi pesanan dan jadwal armada langsung dari pabrik Plered.',
                    ],
                    [
                        'q' => 'Apakah menerima pesanan borongan proyek kontraktor di Jakarta Timur?',
                        'a' => 'Ya, kami melayani pesanan partai besar (2.000 hingga puluhan ribu pcs) dengan penawaran harga pabrik khusus kontraktor dan surat jalan resmi.',
                    ],
                ],
            ],
            [
                'name' => 'Jakarta Barat',
                'slug' => 'roster-beton-minimalis-jakarta-barat',
                'type' => 'city',
                'province_code' => '31',
                'city_code' => '3173',
                'latitude' => -6.1683,
                'longitude' => 106.7588,
                'priority' => 1,
                'headline' => 'Distributor & Pabrik Roster Beton Jakarta Barat — Presisi & Bebas Retak',
                'intro_content' => 'Solusi material roster beton minimalis presisi tinggi untuk wilayah Jakarta Barat. Menggunakan formula abu batu murni Plered dengan pemadatan tumbuk baja presisi, roster kami memiliki pori-pori yang sangat rapat, kuat terhadap air hujan dan panas terik, serta tidak mudah berlumut. Sangat ideal untuk renovasi fasad ruko, pagar rumah modern, sekat partisi restoran, dan proyek perumahan di Jakarta Barat.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek terhubung Tol JORR Barat / Tol Kebon Jeruk.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi penggantian utuh 100% untuk keping roster yang rusak di perjalanan.',
                'target_districts' => ['Kebon Jeruk', 'Kembangan', 'Puri Indah', 'Palmerah', 'Grogol Petamburan', 'Tambora', 'Taman Sari', 'Cengkareng', 'Kalideres'],
                'custom_faqs' => [
                    [
                        'q' => 'Bisa kirim ke area ruko dan perumahan padat di Jakarta Barat?',
                        'a' => 'Bisa. Tim armada kami berpengalaman mengatur rute pengiriman dan waktu bongkar muat yang aman untuk lokasi padat.',
                    ],
                    [
                        'q' => 'Apakah bisa meminta sampel motif roster sebelum order banyak?',
                        'a' => 'Bisa. Kami dapat mengirimkan keping sampel motif ke alamat Anda di Jakarta Barat sebelum finalisasi pesanan partai besar.',
                    ],
                ],
            ],
            [
                'name' => 'Jakarta Utara',
                'slug' => 'roster-beton-minimalis-jakarta-utara',
                'type' => 'city',
                'province_code' => '31',
                'city_code' => '3172',
                'latitude' => -6.1384,
                'longitude' => 106.8640,
                'priority' => 1,
                'headline' => 'Pabrik Roster Beton Jakarta Utara — Tahan Udara Laut & Cuaca Ekstrem',
                'intro_content' => 'Penyedia utama roster beton minimalis untuk area Jakarta Utara (Kelapa Gading, PIK, Pluit, Sunter, Ancol). Karena area pesisir membutuhkan material yang kokoh dan tidak getas terhadap udara garam, roster beton cetak tumbuk padat IndoRoster menjadi pilihan tepat berkat densitas tinggi abu batu murni tanpa rongga keropos. Sangat cocok untuk pagar mewah, fasad rumah pantai, cafe outdoor, dan partisi komersial.',
                'delivery_route_info' => 'Pengiriman via Tol Cikampek - Tol Wiyoto Wiyono / Tol Pelabuhan langsung ke Jakarta Utara.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi aman 100% sampai di tempat tanpa risiko pecah.',
                'target_districts' => ['Kelapa Gading', 'Penjaringan', 'Pantai Indah Kapuk', 'Pluit', 'Tanjung Priok', 'Pademangan', 'Koja', 'Cilincing', 'Sunter'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah roster beton IndoRoster tahan terhadap udara laut di Jakarta Utara?',
                        'a' => 'Ya, karena dibuat dengan sistem tumbuk padat plat baja dan abu batu murni, struktur beton sangat rapat dan tahan cuaca pesisir pantai.',
                    ],
                    [
                        'q' => 'Berapa estimasi biaya kirim roster ke Pantai Indah Kapuk (PIK) atau Kelapa Gading?',
                        'a' => 'Tarif pengiriman dihitung transparan per armada truk langsung dari pabrik, dengan jaminan bebas pecah sampai ke lokasi proyek.',
                    ],
                ],
            ],
            [
                'name' => 'Bogor',
                'slug' => 'roster-beton-minimalis-bogor',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3271',
                'latitude' => -6.5971,
                'longitude' => 106.8060,
                'priority' => 1,
                'headline' => 'Jual Roster Beton Bogor — Pabrik Tangan Pertama untuk Kota & Kabupaten Bogor',
                'intro_content' => 'Layanan suplai langsung roster beton minimalis, bata expose, dan loster arsitektural ke Kota Bogor, Sentul, Cibinong, dan sekitarnya. Karakteristik iklim Bogor yang sejuk dan lembap sangat cocok dengan roster beton IndoRoster yang padat dan memiliki sudut siku 90 derajat presisi. Memaksimalkan sirkulasi udara alami dan pencahayaan tanpa khawatir dinding berlumut atau rembes.',
                'delivery_route_info' => 'Pengiriman via Tol Cipularang - Tol Cikampek - Tol Jagorawi langsung ke Bogor & Sentul.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping rusak di perjalanan.',
                'target_districts' => ['Bogor Timur', 'Bogor Barat', 'Bogor Selatan', 'Bogor Utara', 'Bogor Tengah', 'Tanah Sareal', 'Cibinong', 'Sentul City', 'Babakan Madang', 'Gunung Putri'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa hari pengiriman roster beton ke Bogor dan Sentul?',
                        'a' => 'Pengiriman ke area Bogor dan Sentul City memakan waktu 1-2 hari kerja langsung dari pabrik kami.',
                    ],
                    [
                        'q' => 'Apakah melayani pengiriman ke villa atau proyek di kawasan Puncak / Ciawi?',
                        'a' => 'Ya, armada kami siap mengirimkan material langsung ke lokasi villa, resort, dan hunian di kawasan Puncak dan Ciawi.',
                    ],
                ],
            ],
            [
                'name' => 'Depok',
                'slug' => 'roster-beton-minimalis-depok',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3276',
                'latitude' => -6.4025,
                'longitude' => 106.7942,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Depok — Harga Pabrik Langsung untuk Hunian & Klaster',
                'intro_content' => 'Pusat pemesanan roster beton minimalis modern tangan pertama untuk wilayah Kota Depok (Margonda, Cinere, Sawangan, Cimanggis, Tapos, Beji). Dibuat oleh pengrajin Plered Purwakarta dengan standar cetak tumbuk padat plat baja presisi tinggi. Solusi terbaik untuk pemilik rumah dan developer klaster yang menginginkan fasad rumah modern hemat energi dan sirkulasi udara optimal.',
                'delivery_route_info' => 'Pengiriman via Tol Jagorawi / Tol Cijago langsung ke seluruh kecamatan di Kota Depok.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi bebas pecah 100% diganti langsung di tempat.',
                'target_districts' => ['Margonda', 'Cinere', 'Sawangan', 'Cimanggis', 'Sukmajaya', 'Pancoran Mas', 'Beji', 'Tapos', 'Cilodong', 'Bojongsari'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa biaya ongkir roster beton ke Depok?',
                        'a' => 'Ongkos kirim disesuaikan dengan volume pesanan dan armada yang digunakan, dengan tarif langsung pabrik yang sangat terjangkau.',
                    ],
                    [
                        'q' => 'Apakah bisa kirim bertahap untuk proyek perumahan di Depok?',
                        'a' => 'Bisa. Kami melayani jadwal pengiriman bertahap (batch delivery) sesuai tahapan pengerjaan proyek dinding Anda.',
                    ],
                ],
            ],
            [
                'name' => 'Tangerang',
                'slug' => 'roster-beton-minimalis-tangerang',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3671',
                'latitude' => -6.1783,
                'longitude' => 106.6319,
                'priority' => 1,
                'headline' => 'Pabrik & Supplier Roster Beton Tangerang — Suplai Cepat Pabrik Purwakarta',
                'intro_content' => 'Layanan suplai roster beton cetak tumbuk padat presisi untuk Kota dan Kabupaten Tangerang. IndoRoster menyediakan aneka motif loster modern mulai dari motif geometris, kotak nako, bintang, bunga, hingga pola arsitektural custom. Kualitas padat tanpa rongga, hasil cetak rapi, siku 90 derajat sempurna, dan garansi aman sampai tujuan.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek terhubung Tol Jakarta-Tangerang / Tol Kunciran-Serpong.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru bila ada kepingan pecah saat pengiriman.',
                'target_districts' => ['Tangerang', 'Cipondoh', 'Ciledug', 'Karawaci', 'Batuceper', 'Benda', 'Pinang', 'Larangan', 'Jatiuwung', 'Periuk', 'Cikupa', 'Balaraja'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster ke Tangerang?',
                        'a' => 'Pengiriman rata-rata 1 hari kerja langsung dari pabrik Purwakarta dengan armada kami.',
                    ],
                    [
                        'q' => 'Apakah melayani pembelian untuk toko material di Tangerang?',
                        'a' => 'Ya, kami melayani harga grosir per ritase truk untuk depo bahan bangunan dan reseller toko material.',
                    ],
                ],
            ],
            [
                'name' => 'Tangerang Selatan',
                'slug' => 'roster-beton-minimalis-tangerang-selatan',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3674',
                'latitude' => -6.2885,
                'longitude' => 106.7179,
                'priority' => 1,
                'headline' => 'Jual Roster Beton Tangerang Selatan — BSD City, Bintaro, Alam Sutera & Gading Serpong',
                'intro_content' => 'Produsen dan supplier resmi roster beton minimalis untuk area Tangerang Selatan (BSD City, Bintaro Jaya, Alam Sutera, Serpong, Pamulang, Ciputat). Menjadi pilihan favorit arsitek dan kontraktor klaster perumahan mewah di Tangsel karena presisi tinggi sudut siku 90 derajat, tekstur semen abu batu murni yang elegan, dan ketahanan terhadap cuaca tropis.',
                'delivery_route_info' => 'Pengiriman via Tol JORR 2 / Tol Serpong-Kunciran langsung ke lokasi klaster Tangerang Selatan.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% bebas risiko pecah ganti baru.',
                'target_districts' => ['BSD City', 'Serpong', 'Serpong Utara', 'Bintaro Jaya', 'Pondok Aren', 'Ciputat', 'Ciputat Timur', 'Pamulang', 'Setu', 'Alam Sutera'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah armada IndoRoster bisa masuk ke perumahan klaster di BSD dan Bintaro?',
                        'a' => 'Bisa, armada truk engkel kami terbiasa melakukan pengiriman ke klaster-klaster perumahan modern di BSD City dan Bintaro.',
                    ],
                    [
                        'q' => 'Apa keunggulan roster beton cetak tumbuk padat IndoRoster dibanding produk lain?',
                        'a' => 'Roster kami diproduksi dengan cetakan plat baja presisi dan tumbuk padat abu batu murni (bukan pasir silika / bukan cor biasa), sehingga tidak mudah rontok, tidak keropos, dan siku 90 derajat sangat rapi.',
                    ],
                ],
            ],
            [
                'name' => 'Bekasi',
                'slug' => 'roster-beton-minimalis-bekasi',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3275',
                'latitude' => -6.2383,
                'longitude' => 106.9756,
                'priority' => 1,
                'headline' => 'Pusat Pabrik Roster Beton Bekasi — Suplai Cepat Kota & Kabupaten Bekasi',
                'intro_content' => 'Layanan pemesanan roster beton minimalis berkualitas pabrik langsung untuk Kota dan Kabupaten Bekasi (Summarecon Bekasi, Harapan Indah, Grand Galaxy, Tambun, Cibitung). Jarak yang dekat dari pabrik Plered Purwakarta melalui Tol Jakarta-Cikampek memungkinkan pengiriman super cepat dengan ongkir sangat hemat. Sangat cocok untuk pagar rumah, dinding roster angin-angin, dan fasad bangunan industri.',
                'delivery_route_info' => 'Pengiriman langsung via Tol Cipularang - Tol Jakarta-Cikampek keluar gerbang Tol Bekasi Barat / Timur / Tambun / Cibitung.',
                'estimated_delivery_time' => '1 hari kerja (jadwal armada harian)',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping yang pecah.',
                'target_districts' => ['Bekasi Barat', 'Bekasi Timur', 'Bekasi Selatan', 'Bekasi Utara', 'Medan Satria', 'Rawalumbu', 'Pondok Gede', 'Jatiasih', 'Mustika Jaya', 'Tambun Selatan', 'Cibitung'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster ke Bekasi?',
                        'a' => 'Pengiriman ke area Bekasi hanya memakan waktu 1 hari kerja langsung dari pabrik Purwakarta.',
                    ],
                    [
                        'q' => 'Apakah melayani pembelian eceran untuk renovasi rumah di Bekasi?',
                        'a' => 'Ya, kami melayani mulai dari pesanan renovasi kecil (100-300 pcs) hingga pesanan proyek ribuan pcs.',
                    ],
                ],
            ],
            [
                'name' => 'Cikarang',
                'slug' => 'roster-beton-minimalis-cikarang',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3216',
                'latitude' => -6.3055,
                'longitude' => 107.1539,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Cikarang — Proyek Kawasan Industri & Perumahan',
                'intro_content' => 'Pusat pengadaan roster beton arsitektural dan industri untuk wilayah Cikarang (Jababeka, Lippo Cikarang, MM2100, GIIC, Delta Silicon, Grand Cikarang City). Kualitas cetak tumbuk padat plat baja presisi tinggi sangat tangguh untuk pagar pabrik, dinding ventilasi gudang, fasad ruko perkantoran, dan klaster perumahan baru di Cikarang.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek keluar gerbang Cikarang Barat / Utama / Pusat / Cibatu.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% aman bebas pecah.',
                'target_districts' => ['Cikarang Pusat', 'Cikarang Barat', 'Cikarang Timur', 'Cikarang Utara', 'Cikarang Selatan', 'Lippo Cikarang', 'Jababeka', 'Serang Baru', 'Cibarusah'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah bisa menerbitkan faktur pajak dan surat jalan resmi untuk perusahaan di Cikarang?',
                        'a' => 'Bisa. Kami melengkapi setiap transaksi pengadaan korporat/kontraktor dengan dokumen resmi (Invoice, Surat Jalan, dan Faktur).',
                    ],
                    [
                        'q' => 'Berapa minimal pemesanan roster untuk proyek pabrik di Cikarang?',
                        'a' => 'Kami melayani mulai dari kebutuhan renovasi 500 pcs hingga puluhan ribu pcs untuk fasad gedung dan pagar industri.',
                    ],
                ],
            ],
            [
                'name' => 'Bandung',
                'slug' => 'roster-beton-minimalis-bandung',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3273',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'priority' => 1,
                'headline' => 'Pabrik & Supplier Roster Beton Bandung — Harga Grosir Tangan Pertama',
                'intro_content' => 'IndoRoster adalah produsen utama roster beton minimalis berkualitas tinggi untuk wilayah Bandung Raya (Kota Bandung, Bandung Barat, Cimahi, Sumedang, Jatinangor). Terletak sangat dekat di Plered Purwakarta, jalur Tol Purbaleunyi / Cipularang menjamin pengiriman cepat dalam 1 hari kerja dengan ongkir sangat kompetitif. Pilihan nomor satu arsitek Bandung untuk fasad cafe estetik, villa pegunungan, pagar rumah modern tropis, dan dinding ventilasi arsitektural.',
                'delivery_route_info' => 'Pengiriman langsung via Tol Cipularang - Tol Purbaleunyi keluar gerbang Pasteur, Buah Batu, Kopo, atau Pasir Koja.',
                'estimated_delivery_time' => '1 hari kerja (pengiriman rutin setiap hari)',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping yang pecah.',
                'target_districts' => ['Coblong', 'Dago', 'Sukajadi', 'Lengkong', 'Buahbatu', 'Bandung Wetan', 'Cidadap', 'Sumur Bandung', 'Arcamanik', 'Batununggal', 'Lembang', 'Padalarang'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama estimasi waktu kirim roster dari pabrik ke Bandung?',
                        'a' => 'Pengiriman dari pabrik Plered Purwakarta ke Bandung hanya memerlukan waktu beberapa jam perjalanan (tiba di hari yang sama atau H+1 sesuai jadwal antrian).',
                    ],
                    [
                        'q' => 'Berapa kisaran ongkos kirim roster beton ke Bandung?',
                        'a' => 'Karena jarak Purwakarta - Bandung sangat dekat via Tol Cipularang, biaya pengiriman armada pabrik sangat terjangkau dibandingkan membeli dari toko retail lokal.',
                    ],
                    [
                        'q' => 'Apakah roster beton IndoRoster cocok untuk iklim Bandung yang sering hujan?',
                        'a' => 'Sangat cocok. Dengan teknik tumbuk padat plat baja dan abu batu murni, pori-pori roster sangat rapat sehingga tidak mudah berlumut dan tahan lembap puluhan tahun.',
                    ],
                ],
            ],
            [
                'name' => 'Karawang',
                'slug' => 'roster-beton-minimalis-karawang',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3215',
                'latitude' => -6.3073,
                'longitude' => 107.3019,
                'priority' => 1,
                'headline' => 'Pabrik Roster Beton Karawang — Langsung Produsen Tangan Pertama',
                'intro_content' => 'Distribusi dan suplai roster beton cetak tumbuk padat presisi langsung ke Karawang Barat, Karawang Timur, Telukjambe, KIIC, Surya Cipta, dan Klari. Sebagai daerah tetangga pabrik Purwakarta, pelanggan di Karawang menikmati pengiriman tercepat dan biaya logistik paling hemat untuk proyek rumah tinggal, ruko, pagar kawasan industri, dan cluster perumahan.',
                'delivery_route_info' => 'Pengiriman via jalur arteri Purwakarta - Klari - Karawang atau via Tol Jakarta-Cikampek.',
                'estimated_delivery_time' => '1 hari kerja (pengiriman cepat)',
                'shipping_guarantee_text' => 'Garansi aman 100% ganti baru di tempat.',
                'target_districts' => ['Karawang Barat', 'Karawang Timur', 'Telukjambe Timur', 'Telukjambe Barat', 'Klari', 'Cikampek', 'Kotabaru', 'Rengasdengklok', 'Majalaya'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah bisa beli langsung dan ambil di pabrik untuk area Karawang?',
                        'a' => 'Bisa. Pelanggan Karawang dapat mengambil langsung di lokasi workshop pabrik kami di Plered, atau memanfaatkan armada kirim kami langsung ke proyek.',
                    ],
                    [
                        'q' => 'Berapa minimal order roster untuk dikirim ke Karawang?',
                        'a' => 'Kami melayani mulai dari 100 pcs hingga ribuan pcs untuk kontraktor perumahan di Karawang.',
                    ],
                ],
            ],
            [
                'name' => 'Cianjur',
                'slug' => 'roster-beton-minimalis-cianjur',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3203',
                'latitude' => -6.8222,
                'longitude' => 107.1394,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Cianjur — Fasad Rumah Minimalis & Material Presisi',
                'intro_content' => 'Penyedia resmi roster beton arsitektural dan loster dinding minimalis untuk wilayah Kabupaten Cianjur (Cianjur Kota, Cipanas, Pacet, Ciranjang, Karangtengah). Terbuat dari campuran abu batu murni presisi plat baja pengrajin Plered Purwakarta, menghasilkan roster beton tahan gempa, kuat tekan tinggi, dan sudut siku rapi.',
                'delivery_route_info' => 'Pengiriman via jalur Plered - Ciranjang - Cianjur langsung ke lokasi proyek.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru bila ada kerusakan di jalan.',
                'target_districts' => ['Cianjur', 'Karangtengah', 'Ciranjang', 'Cipanas', 'Pacet', 'Sukaluyu', 'Warungkondang', 'Cugenang', 'Mande', 'Bojongpicung'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster ke Cianjur dan Cipanas?',
                        'a' => 'Pengiriman ke area Cianjur memakan waktu 1 hari kerja langsung via jalur darat armada pabrik.',
                    ],
                    [
                        'q' => 'Apakah motif roster IndoRoster cocok untuk desain villa di Cipanas?',
                        'a' => 'Sangat cocok. Karakter roster minimalis kami memberikan sirkulasi udara sejuk pegunungan sekaligus privasi dan estetika modern.',
                    ],
                ],
            ],
            [
                'name' => 'Cirebon',
                'slug' => 'roster-beton-minimalis-cirebon',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3274',
                'latitude' => -6.7063,
                'longitude' => 108.5570,
                'priority' => 1,
                'headline' => 'Pabrik & Distributor Roster Beton Cirebon — Suplai Cepat via Tol Cipali',
                'intro_content' => 'Layanan suplai langsung roster beton minimalis berkualitas tinggi ke Kota dan Kabupaten Cirebon (Kejaksan, Kesambi, Lemahwungkuk, Harjamukti, Sumber, Kedawung). Didukung akses Tol Cipali langsung dari Purwakarta, pesanan sampai cepat dan aman bergaransi. Cocok untuk iklim tropis pesisir Cirebon yang membutuhkan sirkulasi angin maksimal dan dinding tahan cuaca panas.',
                'delivery_route_info' => 'Pengiriman via Tol Cipali langsung keluar gerbang Tol Plumbon / Ciperna / Kanci ke Cirebon.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping rusak.',
                'target_districts' => ['Kejaksan', 'Kesambi', 'Lemahwungkuk', 'Harjamukti', 'Pekalipan', 'Kedawung', 'Sumber', 'Weru', 'Plumbon', 'Palimanan', 'Astanajapura'],
                'custom_faqs' => [
                    [
                        'q' => 'Bagaimana rute pengiriman roster beton ke Cirebon?',
                        'a' => 'Armada kami melewati Tol Cipali langsung dari Purwakarta sehingga waktu tempuh sangat singkat (sekitar 2-3 jam) dan material tiba dalam kondisi sempurna.',
                    ],
                    [
                        'q' => 'Apakah melayani pembelian untuk kontraktor proyek di Cirebon?',
                        'a' => 'Ya, kami melayani proyek perumahan, cafe, hotel, dan kantor di Cirebon dengan harga grosir pabrik.',
                    ],
                ],
            ],
            [
                'name' => 'Purwakarta',
                'slug' => 'roster-beton-minimalis-purwakarta',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3214',
                'latitude' => -6.5569,
                'longitude' => 107.4428,
                'priority' => 1,
                'headline' => 'Pusat Pabrik Roster Beton Plered Purwakarta — Produsen Tangan Pertama',
                'intro_content' => 'IndoRoster berbasis di Plered, Purwakarta — sentra legendaris pengrajin roster beton cetak tumbuk padat di Jawa Barat. Kami memproduksi puluhan ribu keping roster beton arsitektural setiap bulan menggunakan cetakan plat baja presisi tinggi dan pasir abu batu murni. Kami melayani pembelian langsung di pabrik maupun pengiriman ke seluruh wilayah Purwakarta (Kota, Jatiluhur, Campaka, Bungursari, Pasawahan, Wanayasa) dengan garansi mutu dan harga termurah tangan pertama.',
                'delivery_route_info' => 'Pengiriman langsung dari lokasi workshop pabrik Plered ke seluruh penjuru Purwakarta.',
                'estimated_delivery_time' => '1 hari kerja / siap kirim hari yang sama',
                'shipping_guarantee_text' => 'Garansi 100% penggantian baru langsung dari workshop pabrik.',
                'target_districts' => ['Purwakarta Kota', 'Plered', 'Jatiluhur', 'Campaka', 'Bungursari', 'Pasawahan', 'Wanayasa', 'Darangdan', 'Tegalwaru', 'Sukasari', 'Babakancikao'],
                'custom_faqs' => [
                    [
                        'q' => 'Bolehkah saya berkunjung langsung ke pabrik IndoRoster di Purwakarta?',
                        'a' => 'Sangat boleh! Anda dapat melihat langsung proses pembuatan cetak tumbuk padat plat baja dan memilih aneka motif di workshop kami di Plered, Purwakarta.',
                    ],
                    [
                        'q' => 'Apakah ada diskon untuk pembelian langsung di pabrik?',
                        'a' => 'Tentu ada. Pembelian langsung mendapatkan harga tangan pertama resmi tanpa perantara toko.',
                    ],
                ],
            ],
            [
                'name' => 'Subang',
                'slug' => 'roster-beton-minimalis-subang',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3213',
                'latitude' => -6.5683,
                'longitude' => 107.7600,
                'priority' => 1,
                'headline' => 'Jual Roster Beton Subang — Pabrik Tangan Pertama untuk Kota & Pelabuhan Patimban',
                'intro_content' => 'Suplai roster beton minimalis presisi untuk wilayah Kabupaten Subang (Subang Kota, Kalijati, Pegaden, Jalancagak, Ciater, hingga kawasan Pelabuhan Patimban). Lokasi pabrik Purwakarta yang bersebelahan langsung menjamin distribusi material super cepat dengan jaminan mutu terbaik untuk hunian, ruko, dan proyek kawasan komersial Subang.',
                'delivery_route_info' => 'Pengiriman via jalur Purwakarta - Kalijati - Subang atau via Tol Cipali.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru di tempat.',
                'target_districts' => ['Subang', 'Kalijati', 'Pagaden', 'Jalancagak', 'Ciater', 'Pamanukan', 'Pusakajaya (Patimban)', 'Cipunagara', 'Cibogo'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama waktu pengiriman roster ke Subang?',
                        'a' => 'Pengiriman ke Subang hanya butuh 1 hari kerja langsung dari pabrik Plered Purwakarta.',
                    ],
                ],
            ],
            [
                'name' => 'Sukabumi',
                'slug' => 'roster-beton-minimalis-sukabumi',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3272',
                'latitude' => -6.9277,
                'longitude' => 106.9297,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Sukabumi — Fasad Dinding & Pagar Estetik',
                'intro_content' => 'Pengadaan roster beton minimalis arsitektural untuk Kota dan Kabupaten Sukabumi (Cikole, Baros, Gunungpuyuh, Cibadak, Cisaat, Pelabuhan Ratu). Kualitas cetak tumbuk padat plat baja memberikan kekuatan prima untuk bangunan di dataran tinggi maupun pesisir pantai Sukabumi.',
                'delivery_route_info' => 'Pengiriman via Tol Bocimi / jalur Cianjur-Sukabumi langsung ke proyek.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat.',
                'target_districts' => ['Cikole', 'Baros', 'Gunungpuyuh', 'Citamiang', 'Cibadak', 'Cisaat', 'Sukaraja', 'Cicurug', 'Palabuhanratu'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa hari pengiriman roster beton ke Sukabumi?',
                        'a' => 'Rata-rata 1-2 hari kerja via rute armada langsung dari pabrik.',
                    ],
                ],
            ],
            [
                'name' => 'Indramayu',
                'slug' => 'roster-beton-minimalis-indramayu',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3212',
                'latitude' => -6.3264,
                'longitude' => 108.3200,
                'priority' => 1,
                'headline' => 'Jual Roster Beton Indramayu — Harga Pabrik Langsung Kirim via Cipali',
                'intro_content' => 'Suplai roster beton minimalis untuk area Kabupaten Indramayu (Jatibarang, Haurgeulis, Karangampel, Balongan, Indramayu Kota). Tahan cuaca panas terik pantura dan bergaransi aman sampai di lokasi.',
                'delivery_route_info' => 'Pengiriman via Tol Cipali keluar gerbang Tol Cikedung / Kertajati ke Indramayu.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru bila ada keping pecah.',
                'target_districts' => ['Indramayu', 'Jatibarang', 'Haurgeulis', 'Karangampel', 'Balongan', 'Kandanghaur', 'Losarang', 'Sindang'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah roster beton tahan terhadap cuaca panas pantura Indramayu?',
                        'a' => 'Ya, campuran abu batu murni dengan proses tumbuk padat membuat roster sangat kuat dan tidak mudah retak akibat suhu tinggi.',
                    ],
                ],
            ],
            [
                'name' => 'Serang',
                'slug' => 'roster-beton-minimalis-serang',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3673',
                'latitude' => -6.1200,
                'longitude' => 106.1500,
                'priority' => 1,
                'headline' => 'Pabrik & Supplier Roster Beton Serang — Suplai Cepat Ibu Kota Banten',
                'intro_content' => 'Pusat pemesanan roster beton minimalis modern tangan pertama untuk Kota Serang, Ciruas, Kragilan, dan sekitarnya. Cocok untuk proyek perumahan baru, masjid arsitektural, pagar kantor, dan ruko di Banten.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Merak keluar gerbang Tol Serang Timur / Barat.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% aman bebas pecah.',
                'target_districts' => ['Serang', 'Cipocok Jaya', 'Curug', 'Kasemen', 'Taktakan', 'Walantaka', 'Ciruas', 'Kragilan', 'Kramatwatu'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster ke Serang?',
                        'a' => 'Pengiriman memakan waktu 1-2 hari kerja langsung dari pabrik via Tol Merak.',
                    ],
                ],
            ],
            [
                'name' => 'Cimahi',
                'slug' => 'roster-beton-minimalis-cimahi',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3277',
                'latitude' => -6.8722,
                'longitude' => 107.5422,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Cimahi — Presisi 90° Fasad Hunian & Komersial',
                'intro_content' => 'Layanan kirim langsung roster beton minimalis kualitas premium ke Cimahi Tengah, Cimahi Utara, dan Cimahi Selatan. Densitas padat dan siku presisi memudahkan tukang memasang dinding roster dengan rapi dan cepat.',
                'delivery_route_info' => 'Pengiriman via Tol Cipularang keluar gerbang Tol Baros / Pasteur ke Cimahi.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru di tempat.',
                'target_districts' => ['Cimahi Tengah', 'Cimahi Utara', 'Cimahi Selatan', 'Leuwigajah', 'Cibeber', 'Cipageran', 'Pasirkaliki'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa ongkir roster beton ke Cimahi?',
                        'a' => 'Karena jarak Purwakarta - Cimahi sangat dekat via Tol Cipularang, biaya kirim sangat murah dan cepat sampai.',
                    ],
                ],
            ],
            [
                'name' => 'Sumedang',
                'slug' => 'roster-beton-minimalis-sumedang',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3211',
                'latitude' => -6.8583,
                'longitude' => 107.9250,
                'priority' => 1,
                'headline' => 'Jual Roster Beton Sumedang — Fasad Hunian, Kampus Jatinangor & Perumahan',
                'intro_content' => 'Pusat pengadaan roster beton arsitektural untuk Sumedang Kota, Jatinangor, Tanjungsari, dan sekitarnya. Akses cepat via Tol Cisumdawu menjamin pengiriman kilat dan aman.',
                'delivery_route_info' => 'Pengiriman via Tol Cisumdawu langsung ke Sumedang & Jatinangor.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat.',
                'target_districts' => ['Sumedang Utara', 'Sumedang Selatan', 'Jatinangor', 'Tanjungsari', 'Cimalaka', 'Paseh', 'Pamulihan'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa hari pengiriman ke area kampus Jatinangor Sumedang?',
                        'a' => 'Hanya 1 hari kerja via Tol Cisumdawu langsung dari pabrik Purwakarta.',
                    ],
                ],
            ],
            [
                'name' => 'Garut',
                'slug' => 'roster-beton-minimalis-garut',
                'type' => 'regency',
                'province_code' => '32',
                'city_code' => '3205',
                'latitude' => -7.2167,
                'longitude' => 107.9000,
                'priority' => 1,
                'headline' => 'Supplier Roster Beton Garut — Harga Pabrik untuk Villa & Hunian Tropis',
                'intro_content' => 'Suplai roster beton minimalis presisi untuk wilayah Garut Kota, Tarogong, Cilawu, Samarang, dan Kadungora. Roster beton cetak tumbuk padat tahan cuaca dingin pegunungan dan tidak mudah berjamur.',
                'delivery_route_info' => 'Pengiriman via Tol Purbaleunyi - Cileunyi - Nagreg - Garut.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi aman 100% sampai tujuan.',
                'target_districts' => ['Garut Kota', 'Tarogong Kidul', 'Tarogong Kaler', 'Samarang', 'Kadungora', 'Leles', 'Cilawu', 'Karangpawitan'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa estimasi waktu kirim ke Garut?',
                        'a' => 'Estimasi 1-2 hari kerja menggunakan armada truk khusus material pabrik kami.',
                    ],
                ],
            ],
            [
                'name' => 'Tasikmalaya',
                'slug' => 'roster-beton-minimalis-tasikmalaya',
                'type' => 'city',
                'province_code' => '32',
                'city_code' => '3278',
                'latitude' => -7.3274,
                'longitude' => 108.2207,
                'priority' => 1,
                'headline' => 'Pabrik & Supplier Roster Beton Tasikmalaya — Suplai Priangan Timur',
                'intro_content' => 'Layanan suplai langsung roster beton minimalis tangan pertama ke Kota dan Kabupaten Tasikmalaya (Cihideung, Cipedes, Tawang, Singaparna). Mutu cetak padat siku 90 derajat dengan pilihan motif lengkap untuk masjid, pagar, dan fasad hunian.',
                'delivery_route_info' => 'Pengiriman via jalur Bandung - Malangbong - Tasikmalaya.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru bila terjadi kerusakan di jalan.',
                'target_districts' => ['Cihideung', 'Cipedes', 'Tawang', 'Indihiang', 'Kawalu', 'Mangkubumi', 'Tamansari', 'Singaparna'],
                'custom_faqs' => [
                    [
                        'q' => 'Bisa kirim roster beton ke Tasikmalaya dan Ciamis?',
                        'a' => 'Bisa, armada pabrik kami rutin melayani rute Priangan Timur (Tasikmalaya, Ciamis, Banjar).',
                    ],
                ],
            ],
            [
                'name' => 'Banten',
                'slug' => 'roster-beton-minimalis-banten',
                'type' => 'province',
                'province_code' => '36',
                'city_code' => null,
                'latitude' => -6.4058,
                'longitude' => 106.0640,
                'priority' => 1,
                'headline' => 'Distributor & Pabrik Roster Beton Provinsi Banten — Ekspedisi Seluruh Wilayah',
                'intro_content' => 'IndoRoster melayani pengadaan dan distribusi skala besar roster beton minimalis, bata expose, dan loster dinding untuk seluruh wilayah Provinsi Banten (Tangerang Raya, Serang, Cilegon, Pandeglang, Lebak). Kualitas cetak padat presisi pabrik dengan garansi bebas pecah.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Merak / Tol JORR 2 mencakup seluruh penjuru Banten.',
                'estimated_delivery_time' => '1-2 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat.',
                'target_districts' => ['Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon', 'Pandeglang', 'Rangkasbitung', 'Lebak'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah melayani pengiriman ke seluruh kabupaten/kota di Banten?',
                        'a' => 'Ya, kami melayani suplai pengiriman ke seluruh wilayah Banten mulai dari Tangerang hingga Serang, Cilegon, Lebak, dan Pandeglang.',
                    ],
                ],
            ],
        ];

        foreach ($locationsData as $data) {
            $slug = $data['slug'];
            $cleanSlug = str_replace('roster-beton-minimalis-', '', $slug);

            $location = SeoLocation::where('slug', $slug)
                ->orWhere('slug', $cleanSlug)
                ->orWhere('name', $data['name'])
                ->first();

            if (! $location) {
                $location = new SeoLocation;
            }

            $location->name = $data['name'];
            $location->slug = $slug;
            $location->type = $data['type'];
            $location->province_code = $data['province_code'];
            $location->city_code = $data['city_code'];
            $location->latitude = $data['latitude'];
            $location->longitude = $data['longitude'];
            $location->priority = $data['priority'];
            $location->seo_enabled = true;
            $location->headline = $data['headline'];
            $location->intro_content = $data['intro_content'];
            $location->delivery_route_info = $data['delivery_route_info'];
            $location->estimated_delivery_time = $data['estimated_delivery_time'];
            $location->shipping_guarantee_text = $data['shipping_guarantee_text'];
            $location->target_districts = $data['target_districts'];
            $location->custom_faqs = $data['custom_faqs'];
            $location->recommended_motif_ids = $topMotifIds;

            $cityName = $data['name'];
            $location->meta_title = "Jual Roster Beton {$cityName} Harga Pabrik | IndoRoster";
            $location->meta_description = "Pusat produsen & supplier roster beton minimalis di {$cityName}. Kualitas cetak padat presisi plat baja Plered Purwakarta, garansi pecah ganti baru, kirim cepat.";

            $location->seo_score = $location->calculateSeoScore();
            $location->save();

            $this->command->info("Processed location: {$location->name} (Score: {$location->seo_score})");
        }
    }
}
