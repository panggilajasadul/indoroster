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
                'meta_title' => 'Pabrik & Supplier Roster Beton Jakarta | Tabir Privasi Urban & Fasad Gedung',
                'meta_description' => 'Produsen roster beton minimalis presisi plat baja Plered Purwakarta untuk DKI Jakarta. Solusi tabir privasi rumah hook, ventilasi rooftop & partisi cafe. Kirim cepat bergaransi.',
                'headline' => 'Pusat Pabrik & Supplier Roster Beton Jakarta — Fasad Privasi Urban & Pengiriman Cepat',
                'intro_content' => 'IndoRoster melayani suplai dan distribusi tangan pertama roster beton arsitektural dari sentra manufaktur Plered Purwakarta ke seluruh 5 wilayah kota administratif Daerah Khusus Ibukota Jakarta. Tingginya densitas pembangunan dan rapatnya jarak antar bangunan perkotaan di Jakarta menuntut solusi fasad yang mampu memberikan sirkulasi udara optimal sekaligus melindungi privasi visual keluarga dari keramaian jalanan depan rumah. Roster beton kami dicetak padat menggunakan matras plat baja presisi bersiku 90 derajat tegak sempurna dengan bahan pasir abu batu murni pilihan (abu batu murni Plered). Berfungsi efektif sebagai tabir pandang satu arah (one-way privacy screen) pada fasad rumah hook, pembatas balkon lantai atas (rooftop sanctuary), partisi interior ruko cafe kekinian, hingga elemen penyejuk arsitektur gedung perkantoran di ibu kota.',
                'delivery_route_info' => 'Pengiriman armada harian via Tol Cipularang - Tol Jakarta-Cikampek terhubung Tol Dalam Kota (Grogol/Cawang) dan Tol JORR 1/2 langsung ke alamat proyek di Jakarta.',
                'estimated_delivery_time' => '1 hari kerja (jadwal armada rutin setiap hari)',
                'shipping_guarantee_text' => 'Garansi 100% Bebas Pecah: Setiap keping roster yang pecah atau cacat dalam perjalanan armada langsung diganti baru di lokasi proyek tanpa biaya tambahan.',
                'target_districts' => ['Menteng', 'Kebayoran Baru', 'Senopati', 'Kelapa Gading', 'Pantai Indah Kapuk', 'Puri Indah', 'Kemang', 'Tebet', 'Kuningan', 'Sunter', 'Pluit', 'Cilandak'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah armada IndoRoster bisa mengirim roster ke gang perumahan padat di Jakarta?',
                        'a' => 'Sangat bisa. Kami mengoperasikan armada truk engkel (colt diesel 4 roda) dan armada lincah yang fleksibel bermanuver masuk ke gang perumahan padat di Jakarta.',
                    ],
                    [
                        'q' => 'Bagaimana roster beton memberikan privasi visual pada rumah di Jakarta?',
                        'a' => 'Rongga geometris roster IndoRoster dirancang dengan sudut ketebalan 10 cm yang membatasi sudut pandang orang luar yang melintas di jalan sempit, namun penghuni di dalam tetap leluasa menikmati hembusan angin alami dan pencahayaan terang.',
                    ],
                    [
                        'q' => 'Apakah melayani pemesanan skala renovasi kecil rumah tinggal di Jakarta?',
                        'a' => 'Ya, kami melayani pemesanan eceran renovasi rumah mulai dari 100–300 pcs hingga partai besar ribuan keping untuk kontraktor gedung bertingkat.',
                    ],
                    [
                        'q' => 'Berapa lama estimasi pengiriman roster beton sampai ke lokasi proyek di Jakarta?',
                        'a' => 'Pengiriman langsung dari sentra Plered Purwakarta melalui akses tol hanya memakan waktu 1 hari kerja setelah konfirmasi jadwal pesanan.',
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
                'meta_title' => 'Jual Roster Beton Jakarta Selatan | Fasad Tropis Mewah & Cafe Aesthetic',
                'meta_description' => 'Pusat roster beton arsitektural Jakarta Selatan (Kebayoran, Kemang, Senopati, Cilandak). Abu batu murni presisi plat baja siku 90°, nat tipis rapi, garansi ganti baru.',
                'headline' => 'Jual Roster Beton Jakarta Selatan — Fasad Hunian Tropis Mewah, Pagar & Cafe Estetik',
                'intro_content' => 'Produsen resmi roster beton minimalis presisi tinggi untuk wilayah Jakarta Selatan (Kebayoran Baru, Senopati, Cilandak, Pondok Indah, Kemang, Tebet, Mampang Prapatan, Jagakarsa). Menjadi preferensi utama konsultan arsitek dan pemilik rumah mewah di Jaksel yang menginginkan perpaduan harmonis antara karakter tropis modern dan presisi garis fasad kontemporer. Dibuat dengan sistem cetak tumbuk padat matras plat baja siku 90 derajat dari pasir abu batu murni Plered Purwakarta, setiap keping roster IndoRoster memiliki permukaan halus alami dan nat mortar tipis 6–8 mm yang rapi. Sangat cocok untuk dinding pagar estetis depan hunian, sekat void taman belakang, kisi ventilasi udara ruang keluarga, dan aksen interior cafe berkonsep industrial hangat.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek keluar gerbang Tol JORR TB Simatupang / Antasari langsung ke lokasi proyek.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping yang mengalami kerusakan selama pengiriman.',
                'target_districts' => ['Kebayoran Baru', 'Kebayoran Lama', 'Cilandak', 'Senopati', 'Kemang', 'Pondok Indah', 'Tebet', 'Pancoran', 'Mampang Prapatan', 'Pasar Minggu', 'Jagakarsa', 'Pesanggrahan'],
                'custom_faqs' => [
                    [
                        'q' => 'Motif roster apa yang paling banyak diaplikasikan di hunian Jakarta Selatan?',
                        'a' => 'Motif geometris minimalis modern seperti MMC, Nako Sipit, Motif Petir, Bintang, dan Polos Kasar 2 Sisi menjadi favorit utama proyek arsitektur di kawasan Kebayoran, Kemang, dan Senopati.',
                    ],
                    [
                        'q' => 'Apakah roster beton IndoRoster bisa dipasang dengan nat tipis rapi?',
                        'a' => 'Sangat bisa. Karena dicetak menggunakan matras plat baja siku 90 derajat presisi pengrajin Plered, deviasi ukuran sangat minim sehingga tukang dapat memasang nat tipis 6–8 mm yang rapi dan elegan.',
                    ],
                    [
                        'q' => 'Bagaimana jika ada kepingan yang pecah saat armada tiba di Jaksel?',
                        'a' => 'Armada pabrik kami selalu membawa kepingan cadangan dan memberikan garansi 100% ganti baru langsung saat serah terima barang di lokasi proyek.',
                    ],
                    [
                        'q' => 'Apakah bisa meminta foto atau video fisik detail roster sebelum pengiriman?',
                        'a' => 'Tentu bisa. Tim sales kami siap mengirimkan dokumentasi foto dan video close-up tekstur abu batu murni dari workshop pabrik via WhatsApp.',
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
                'meta_title' => 'Pabrik Roster Beton Jakarta Timur | Grosir Tangan Pertama Pagar & Dinding',
                'meta_description' => 'Supplier roster beton minimalis presisi plat baja Jakarta Timur (Duren Sawit, Rawamangun, Pulogadung). Harga langsung pabrik Plered, kuat kokoh, kirim cepat bergaransi.',
                'headline' => 'Pabrik Roster Beton Jakarta Timur — Grosir Tangan Pertama Pagar & Dinding Ventilasi',
                'intro_content' => 'Layanan suplai langsung tangan pertama roster beton minimalis berkualitas prima untuk kawasan Jakarta Timur (Duren Sawit, Rawamangun, Pulogadung, Cakung, Jatinegara, Ciracas, Cipayung, Kramat Jati). Akses kedekatan gerbang tol koridor timur memungkinkan pengiriman ekspres dengan tarif logistik sangat bersahabat bagi pemilik hunian mandiri maupun kontraktor perumahan. Diproduksi melalui cetak tumbuk padat plat baja siku 90 derajat di sentra Plered Purwakarta dengan bahan abu batu murni berkualitas tinggi, roster beton IndoRoster menawarkan kekuatan struktur yang kokoh, tepi siku yang rapi memudahkan pekerjaan tukang, dan ketahanan terhadap pelapukan akibat cuaca tropis.',
                'delivery_route_info' => 'Pengiriman kilat via Tol Jakarta-Cikampek tembus Tol Becakayu atau Tol JORR Timur langsung ke pintu gerbang proyek.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru bila terjadi kerusakan dalam pengiriman armada pabrik.',
                'target_districts' => ['Duren Sawit', 'Rawamangun', 'Pulogadung', 'Cakung', 'Jatinegara', 'Matraman', 'Kramat Jati', 'Ciracas', 'Cipayung', 'Pasar Rebo', 'Makasar'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster ke wilayah Jakarta Timur?',
                        'a' => 'Estimasi tiba hanya 1 hari kerja langsung dari sentra manufaktur Plered Purwakarta via akses tol Jakarta-Cikampek dan Becakayu.',
                    ],
                    [
                        'q' => 'Apakah melayani pesanan borongan kontraktor di Jakarta Timur?',
                        'a' => 'Ya, kami melayani partai besar borongan (mulai 1.000 hingga puluhan ribu keping) lengkap dengan surat jalan resmi dan faktur pabrik.',
                    ],
                    [
                        'q' => 'Apakah roster beton ini bisa langsung dicat warna lain?',
                        'a' => 'Bisa. Tekstur abu batu murni yang padat dan bebas minyak cetakan memudahkan daya lekat cat dinding eksterior waterproof maupun coating transparan matte.',
                    ],
                    [
                        'q' => 'Bagaimana cara menghitung kebutuhan roster untuk pagar di Jaktim?',
                        'a' => 'Gunakan kalkulator online IndoRoster di website kami, atau sebutkan ukuran panjang dan tinggi dinding kepada tim sales via WhatsApp untuk dibantu perhitungannya.',
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
                'meta_title' => 'Supplier Roster Beton Jakarta Barat | Fasad Ruko, Puri Indah & Kebon Jeruk',
                'meta_description' => 'Produsen roster beton minimalis Jakarta Barat. Formula abu batu murni padat plat baja presisi, tahan panas jalan arteri & anti-rontok. Kirim cepat bergaransi.',
                'headline' => 'Supplier Roster Beton Jakarta Barat — Fasad Renovasi Ruko, Rumah Modern & Presisi Siku 90°',
                'intro_content' => 'Pusat pengadaan roster beton arsitektural langsung dari pabrik untuk wilayah Jakarta Barat (Puri Indah, Kebon Jeruk, Kembangan, Meruya, Palmerah, Grogol Petamburan, Tanjung Duren, Cengkareng). Menjawab kebutuhan renovasi fasad ruko perkantoran dan hunian modern di Jakarta Barat yang mengedepankan ketahanan terhadap cuaca panas terik serta polusi jalan arteri perkotaan. Menggunakan formula abu batu murni padat hasil tumbukan plat baja presisi pengrajin Plered Purwakarta, roster beton kami bebas keropos dan tidak mudah rontok debu saat terkena getaran lalu lintas kendaraan. Pilihan ideal untuk partisi ventilasi dinding ruko, tabir privasi tangga darurat, pagar balkon lantai atas, dan ornamen dinding teras rumah modern.',
                'delivery_route_info' => 'Pengiriman armada pabrik via Tol Jakarta-Cikampek terhubung Tol Tomang / Tol Kebon Jeruk / Tol JORR Barat W2.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi penggantian utuh 100% untuk keping roster yang rusak di perjalanan.',
                'target_districts' => ['Puri Indah', 'Kebon Jeruk', 'Kembangan', 'Meruya', 'Palmerah', 'Grogol', 'Tomang', 'Tanjung Duren', 'Duri Kepa', 'Cengkareng', 'Kalideres'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah roster beton ini kuat menghadapi getaran lalu lintas ruko di Jakarta Barat?',
                        'a' => 'Sangat kuat. Pemadatan tumbuk plat baja menghasilkan densitas beton masif berbobot 3.8–4.2 kg per keping yang kokoh mengikat mortar dan tahan getaran jalan raya.',
                    ],
                    [
                        'q' => 'Bisa kirim ke area ruko padat di Kebon Jeruk atau Tanjung Duren?',
                        'a' => 'Bisa. Tim armada kami berpengalaman mengatur waktu bongkar muat pada jam-jam tenang agar tidak mengganggu aktivitas lalu lintas sekitar.',
                    ],
                    [
                        'q' => 'Apakah IndoRoster menyediakan opsi warna selain abu semen?',
                        'a' => 'Tersedia varian Abu Batu Alami, Putih Bersih (Dolomit Super), dan Merah Terakota murni tanpa pewarna kimia sintetis.',
                    ],
                    [
                        'q' => 'Bagaimana sistem pembayaran untuk pengiriman ke Jakarta Barat?',
                        'a' => 'Pembayaran aman dan transparan langsung ke rekening perusahaan resmi IndoRoster dengan konfirmasi invoice bertanda tangan pabrik.',
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
                'meta_title' => 'Jual Roster Beton Jakarta Utara | Tahan Korosi Udara Laut PIK & Kelapa Gading',
                'meta_description' => 'Supplier roster beton presisi Jakarta Utara (Pantai Indah Kapuk, Kelapa Gading, Pluit). Densitas tinggi abu batu murni tahan uap garam pesisir, garansi pecah ganti baru.',
                'headline' => 'Jual Roster Beton Jakarta Utara — Ketahanan Korosi Udara Pesisir PIK, Pluit & Kelapa Gading',
                'intro_content' => 'Spesialis penyedia roster beton arsitektural dengan ketahanan tinggi terhadap iklim pesisir laut untuk area Jakarta Utara (Pantai Indah Kapuk / PIK 1 & 2, Pluit, Muara Karang, Kelapa Gading, Sunter, Ancol). Udara pesisir Jakarta Utara yang sarat uap garam membutuhkan material dinding yang memiliki densitas padat kedap uap agar tidak mengalami korosi rapuh (getas). IndoRoster mencetak setiap keping dengan tumbuk padat plat baja presisi menggunakan pasir abu batu murni (abu batu murni Plered), menciptakan kerapatan molekuler beton yang tangguh menghadapi hembusan angin laut. Sempurna untuk fasad villa modern tepi laut, cafe outdoor estetik di PIK, dinding aksen taman hotel pesisir, dan pagar kemewahan hunian Kelapa Gading.',
                'delivery_route_info' => 'Pengiriman via Tol Cikampek - Tol Wiyoto Wiyono / Tol Pelabuhan / Tol JORR Utara langsung ke lokasi proyek di Jakarta Utara.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi aman 100% sampai di tempat tanpa risiko pecah.',
                'target_districts' => ['Pantai Indah Kapuk (PIK)', 'Pluit', 'Muara Karang', 'Kelapa Gading', 'Sunter', 'Ancol', 'Pademangan', 'Tanjung Priok', 'Koja', 'Cilincing'],
                'custom_faqs' => [
                    [
                        'q' => 'Mengapa roster cetak tumbuk abu batu IndoRoster lebih tahan udara garam dibanding produk biasa?',
                        'a' => 'Karena proses pemadatan tumbuk plat baja menghilangkan gelembung rongga udara mikro di dalam beton, mencegah uap air laut meresap ke dalam matriks material sehingga tidak terjadi pelapukan rapuh.',
                    ],
                    [
                        'q' => 'Apakah armada pabrik bisa mengirim langsung ke proyek cluster perumahan PIK 2?',
                        'a' => 'Ya, armada kami rutin mengantarkan pesanan material langsung ke alamat proyek di kawasan PIK 1, PIK 2, Golf Island, maupun Kelapa Gading.',
                    ],
                    [
                        'q' => 'Motif apa yang cocok untuk konsep cafe dan restoran outdoor tepi pantai di PIK?',
                        'a' => 'Motif Bintang Minimalis, Labirin Tropis, dan Putih Dolomit sangat digemari karena memantulkan cahaya matahari sore yang estetik dan Instagramable.',
                    ],
                    [
                        'q' => 'Berapa estimasi ongkir roster beton ke wilayah Jakarta Utara?',
                        'a' => 'Ongkir dihitung proporsional per ritase armada langsung dari pabrik Plered, jauh lebih hemat untuk volume renovasi maupun proyek komersial.',
                    ],
                ],
            ],
            [
                'name' => 'Jakarta Pusat',
                'slug' => 'roster-beton-minimalis-jakarta-pusat',
                'type' => 'city',
                'province_code' => '31',
                'city_code' => '3171',
                'latitude' => -6.1805,
                'longitude' => 106.8284,
                'priority' => 1,
                'meta_title' => 'Supplier Roster Beton Jakarta Pusat | Partisi Kantor, Cafe Menteng & Fasad',
                'meta_description' => 'Pusat produsen roster beton minimalis Jakarta Pusat (Menteng, Kemayoran, Tanah Abang). Cetak tumbuk plat baja presisi 90°, sekat ruang & ventilasi alami, kirim cepat.',
                'headline' => 'Supplier Roster Beton Jakarta Pusat — Partisi Interior Kantor, Cafe Menteng & Fasad Estetis',
                'intro_content' => 'Pusat pemesanan roster beton minimalis presisi tinggi untuk wilayah sentral ibu kota Jakarta Pusat (Menteng, Thamrin, Sudirman, Tanah Abang, Cempaka Putih, Johar Baru, Gambir, Senen, Kemayoran). Di tengah kawasan bisnis, perkantoran, dan hunian heritage bernilai tinggi di Jakarta Pusat, roster beton IndoRoster diaplikasikan secara elegan sebagai partisi sekat ruang kerja terbuka (open-plan office divider), aksen fasad kafe arsitektural Menteng, dinding ventilasi mushola kantor, serta tabir balkon rooftop apartment. Dibuat dengan sistem cetak tumbuk plat baja presisi pengrajin Plered Purwakarta dengan bahan abu batu murni bertekstur halus, memberikan aksen kontemporer berkelas dengan jaminan siku 90 derajat yang presisi dan rapi.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek terhubung Tol Dalam Kota (Exit Rawamangun / Cempaka Putih / Senayan / Semanggi) langsung ke alamat proyek.',
                'estimated_delivery_time' => '1 hari kerja (jadwal pengiriman fleksibel)',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping rusak di perjalanan.',
                'target_districts' => ['Menteng', 'Gambir', 'Cempaka Putih', 'Kemayoran', 'Tanah Abang', 'Senen', 'Johar Baru', 'Sawah Besar', 'Cideng', 'Bendungan Hilir'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah roster beton cocok digunakan sebagai partisi interior kantor di Jakarta Pusat?',
                        'a' => 'Sangat cocok. Roster beton memberikan batas visual yang tegas antar divisi tanpa menghalangi sirkulasi AC dan cahaya ruangan, menciptakan atmosfer kerja yang lapang dan modern.',
                    ],
                    [
                        'q' => 'Bagaimana pengiriman roster beton ke kawasan perkantoran Sudirman / Menteng dengan aturan ganjil-genap?',
                        'a' => 'Tim logistik kami mengatur jadwal tiba armada di luar jam pembatasan lalu lintas atau pada malam hari sesuai izin pengelola gedung / perumahan.',
                    ],
                    [
                        'q' => 'Apakah kepingan roster beton IndoRoster berdebu?',
                        'a' => 'Tidak. Karena dibuat dari formula abu batu murni padat melalui proses curing matang, permukaannya padat dan tidak mudah rontok serpihan debu.',
                    ],
                    [
                        'q' => 'Berapa minimal pemesanan untuk dikirim ke Jakarta Pusat?',
                        'a' => 'Kami melayani mulai dari pesanan renovasi kecil (100–200 pcs) hingga partai besar proyek komersial puluhan ribu keping.',
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
                'meta_title' => 'Jual Roster Beton Bogor | Arsitektur Bioklimatik Tahan Hujan & Anti-Lumut',
                'meta_description' => 'Pabrik roster beton arsitektural Bogor & Sentul City. Formula abu batu murni kedap pori, profil jalusi nako anti-tampias hujan, garansi bebas pecah ganti baru.',
                'headline' => 'Jual Roster Beton Bogor — Arsitektur Bioklimatik Tahan Curah Hujan Tinggi & Anti-Lumut',
                'intro_content' => 'Layanan pengadaan dan distribusi langsung pabrik roster beton arsitektural untuk wilayah Kota dan Kabupaten Bogor (Sentul City, Pajajaran, Baranangsiang, Yasmin, Cibinong, Bojonggede, hingga kawasan Ciawi). Dikenal sebagai Kota Hujan dengan curah presipitasi tinggi dan kelembapan ekstrem, bangunan di Bogor rentan terhadap rembesan air dan jamur lumut hitam pada dinding berpori kasar. IndoRoster memecahkan tantangan ini dengan memproduksi roster beton padat abu batu murni sentra Plered Purwakarta menggunakan sistem cetak tumbuk plat baja presisi tinggi. Kerapatan partikelnya menolak peresapan air hujan, sementara profil nako miring / chamfered membelokkan arah tampias air hujan deras ke luar sekaligus menangkap hembusan angin pegunungan yang sejuk ke dalam hunian villa, resort, maupun rumah tinggal keluarga.',
                'delivery_route_info' => 'Pengiriman via Tol Cipularang - Tol Cikampek - Tol Jagorawi (Exit Sentul Selatan, Bogor Kota / Baranangsiang, Ciawi) dan Tol Lingkar Luar Bogor (BORR Yasmin).',
                'estimated_delivery_time' => '1 hari kerja (armada rutin harian)',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping rusak di perjalanan.',
                'target_districts' => ['Bogor Timur', 'Bogor Tengah', 'Bogor Selatan', 'Bogor Barat', 'Bogor Utara', 'Tanah Sareal', 'Sentul City', 'Babakan Madang', 'Cibinong', 'Bojonggede', 'Sukaraja', 'Gunung Putri'],
                'custom_faqs' => [
                    [
                        'q' => 'Mengapa roster beton IndoRoster tidak mudah berlumut di iklim lembap Bogor?',
                        'a' => 'Karena dipadatkan dengan matras plat baja presisi berbahan pasir abu batu murni tanpa campuran pasir biasa, porositas mikronya sangat rapat sehingga air hujan tidak tertahan di dalam pori-pori yang memicu spora lumut.',
                    ],
                    [
                        'q' => 'Motif roster mana yang paling efektif mencegah tampias air hujan di Bogor?',
                        'a' => 'Motif Jalusi Miring / Nako Chamfered menjadi pilihan terbaik karena memiliki bilah miring 45 derajat yang membelokkan tetesan air hujan ke luar sambil tetap mengalirkan angin sejuk.',
                    ],
                    [
                        'q' => 'Apakah armada IndoRoster bisa mengantar ke villa berkontur perbukitan di Sentul atau Ciawi?',
                        'a' => 'Bisa. Armada truk kami terbiasa mendistribusikan material ke kawasan hunian lereng Sentul City, Babakan Madang, hingga villa kawasan Puncak Ciawi.',
                    ],
                    [
                        'q' => 'Berapa lama estimasi pengiriman dari pabrik Plered ke Bogor?',
                        'a' => 'Pengiriman memakan waktu 1 hari kerja langsung via jalan tol Jagorawi dengan jadwal keberangkatan rutin.',
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
                'meta_title' => 'Supplier Roster Beton Depok | Sirkulasi Void Rumah Kompak & Cinere',
                'meta_description' => 'Pusat produsen roster beton minimalis Depok (Sawangan, Cinere, Margonda, GDC). Solusi ventilasi vertikal stack effect void tangga hunian kompak, kirim cepat bergaransi.',
                'headline' => 'Supplier Roster Beton Depok — Solusi Sirkulasi Udara Vertikal Rumah Kompak & Void Hunian',
                'intro_content' => 'Produsen dan supplier resmi roster beton minimalis tangan pertama pabrik untuk seluruh wilayah Kota Depok (Margonda, Cinere, Sawangan, Grand Depok City, Cimanggis, Beji, Tapos). Menjawab tren pembangunan rumah kompak (compact urban housing) pada kavling sempit 60–90 m² di Depok yang tidak memiliki pekarangan samping, roster beton IndoRoster hadir sebagai solusi sirkulasi udara vertikal (stack effect ventilation). Diterapkan pada dinding void tangga, partisi skylight belakang, dan sekat ruang keluarga, kisi-kisi roster meloloskan udara panas yang terperangkap naik ke atas sekaligus meneruskan pencahayaan alami tanpa menyalakan lampu siang hari. Dicetak padat presisi dengan matras plat baja pengrajin Plered Purwakarta dari bahan abu batu murni, menghasilkan kepingan kokoh siku 90 derajat yang rapi, padat, dan berdaya tahan puluhan tahun.',
                'delivery_route_info' => 'Pengiriman armada pabrik via Tol Jagorawi tembus Tol Cijago (Cinere-Jagorawi) atau via Tol Desari (Depok-Antasari via Sawangan).',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Bebas Pecah diganti langsung di tempat.',
                'target_districts' => ['Sawangan', 'Cinere', 'Margonda', 'Pancoran Mas', 'Sukmajaya', 'Cimanggis', 'Beji', 'Bojongsari', 'Cipayung Depok', 'Cilodong', 'Tapos'],
                'custom_faqs' => [
                    [
                        'q' => 'Bagaimana roster beton membantu menurunkan suhu rumah kompak di Depok?',
                        'a' => 'Dengan memasang roster pada void tangga atau dinding pembatas belakang, terjadi efek cerobong termal (stack effect) yang menyedot hawa panas keluar atap dan menarik udara segar masuk ke lantai bawah.',
                    ],
                    [
                        'q' => 'Apakah armada IndoRoster bisa masuk ke jalan perumahan klaster di Sawangan atau Cinere?',
                        'a' => 'Bisa. Kami memiliki armada engkel 4 roda yang lincah menembus akses jalan lingkungan klaster perumahan di Depok.',
                    ],
                    [
                        'q' => 'Berapa bobot per keping roster beton ukuran 20x20 cm?',
                        'a' => 'Bobot per keping berkisar antara 3.8 hingga 4.2 kg, menandakan struktur beton padat masif tanpa rongga keropos yang rapuh.',
                    ],
                    [
                        'q' => 'Apakah ada minimal pemesanan untuk wilayah Kota Depok?',
                        'a' => 'Kami melayani mulai dari pesanan eceran renovasi 100 keping hingga ribuan keping untuk proyek klaster developer di Depok.',
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
                'meta_title' => 'Pabrik & Supplier Roster Beton Tangerang | Fasad Modern & Ventilasi Dinding',
                'meta_description' => 'Produsen roster beton minimalis presisi plat baja Kota & Kab Tangerang. Tahan cuaca pesisir, siku 90° rapi, kirim cepat via Tol Jakarta-Tangerang bergaransi 100%.',
                'headline' => 'Pabrik Roster Beton Tangerang — Fasad Modern Tropis & Ventilasi Dinding Ramah Iklim',
                'intro_content' => 'IndoRoster menghadirkan lini roster beton arsitektural kualitas premium langsung dari sentra pabrikasi Plered Purwakarta untuk seluruh wilayah Kota dan Kabupaten Tangerang. Menjawab karakteristik iklim pesisir barat metropolitan dengan tingkat kelembapan udara tinggi dan paparan panas terik siang hari, produk roster kami diproduksi menggunakan sistem cetak tumbuk padat plat baja presisi 90 derajat dengan pasir abu batu murni pilihan (abu batu murni Plered). Hasil pemadatan menghasilkan struktur beton padat dengan pori-pori mikro yang rapat, menahan rembesan air hujan asam, serta mencegah pertumbuhan lumut kerak pada dinding luar. Sangat ideal untuk aplikasi second skin fasad rumah tinggal klaster modern, pagar pembatas void, partisi ventilasi ruko komersial, hingga proyek renovasi kawasan hunian dan industri di Tangerang Raya.',
                'delivery_route_info' => 'Pengiriman langsung armada truk pabrik via Tol Cipularang terhubung Tol Jakarta-Tangerang (Exit Karawaci / Tangerang / Kebon Nanas) langsung ke alamat proyek.',
                'estimated_delivery_time' => '1 hari kerja (jadwal armada rutin setiap hari)',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru bila ada kepingan pecah saat pengiriman langsung di lokasi proyek.',
                'target_districts' => ['Karawaci', 'Cipondoh', 'Ciledug', 'Batuceper', 'Pinang', 'Larangan', 'Neglasari', 'Periuk', 'Jatiuwung', 'Benda', 'Cikupa', 'Balaraja', 'Pasar Kemis', 'Curug', 'Kelapa Dua'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama estimasi pengiriman roster dari pabrik ke Tangerang?',
                        'a' => 'Pengiriman ke area Kota dan Kabupaten Tangerang memakan waktu 1 hari kerja langsung via jalan Tol Jakarta-Tangerang.',
                    ],
                    [
                        'q' => 'Apakah roster beton ini tahan terhadap rembesan air hujan di Tangerang?',
                        'a' => 'Ya, karena dicetak padat menggunakan abu batu murni Plered dengan tumbukan plat baja, struktur beton sangat padat kedap air sehingga tidak mudah rembes dan tahan lumut bertahun-tahun.',
                    ],
                    [
                        'q' => 'Apakah bisa mengirim ke toko bangunan atau depo material di Tangerang?',
                        'a' => 'Bisa. Kami melayani harga grosir ritase truk langsung pabrik untuk mitra toko bahan bangunan dan kontraktor proyek di Tangerang.',
                    ],
                    [
                        'q' => 'Bagaimana prosedur klaim jika ada keping yang retak saat sampai?',
                        'a' => 'Cukup tunjukkan kepingan yang rusak kepada supir armada kami saat bongkar muat, supir akan langsung menggantikannya dengan keping cadangan baru.',
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
                'meta_title' => 'Jual Roster Beton Tangerang Selatan | Second Skin Fasad BSD, Bintaro & Alam Sutera',
                'meta_description' => 'Supplier resmi roster beton minimalis Tangsel (BSD City, Bintaro, Alam Sutera, Serpong). Matras plat baja presisi siku 90°, reduksi panas matahari 40%, kirim bergaransi.',
                'headline' => 'Jual Roster Beton Tangerang Selatan — Fasad Second Skin Tropis BSD City, Bintaro & Alam Sutera',
                'intro_content' => 'Spesialis manufaktur dan pengadaan roster beton arsitektural tangan pertama untuk kawasan prestisius Tangerang Selatan mencakup BSD City, Bintaro Jaya, Alam Sutera, Gading Serpong, Pamulang, dan Ciputat. Dirancang khusus untuk melengkapi fasad arsitektur modern tropis, roster beton IndoRoster berfungsi strategis sebagai second skin pengurang radiasi panas matahari barat (menurunkan beban panas interior hingga 40%) sekaligus menciptakan bayangan geometris estetis (shadow play) di dalam ruangan. Setiap keping dicetak padat presisi menggunakan matras plat baja siku 90 derajat oleh pengrajin ahli Plered Purwakarta dengan bahan abu batu murni berbobot mantap 3.8–4.2 kg. Sudut yang tegak presisi memungkinkan pemasangan dengan nat mortar tipis 6–8 mm yang rapi dan elegan, selaras dengan standar estetika tinggi hunian klaster modern di Tangsel.',
                'delivery_route_info' => 'Pengiriman armada pabrik via Tol Cipularang - Tol JORR 2 / Tol Kunciran-Serpong (Exit Parigi / BSD City / Pondok Aren).',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% bebas risiko pecah ganti baru langsung di lokasi proyek.',
                'target_districts' => ['BSD City', 'Bintaro Jaya', 'Alam Sutera', 'Serpong', 'Serpong Utara', 'Pondok Aren', 'Ciputat', 'Ciputat Timur', 'Pamulang', 'Setu'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah armada IndoRoster memiliki izin masuk ke klaster one-gate system di Tangsel?',
                        'a' => 'Ya, pengemudi armada kami terbiasa mengikuti prosedur keamanan perumahan klaster di BSD, Bintaro, dan Alam Sutera dengan membawa surat jalan resmi pengiriman.',
                    ],
                    [
                        'q' => 'Bagaimana roster beton berfungsi sebagai second skin di rumah hadap barat Tangsel?',
                        'a' => 'Roster beton dipasang berjarak 40–60 cm di depan dinding kaca atau jendela, memotong radiasi termal langsung matahari sore tanpa menggelapkan ruangan sehingga pemakaian AC jauh lebih hemat.',
                    ],
                    [
                        'q' => 'Apakah tersedia varian warna putih untuk hunian bergaya minimalis Skandinavia?',
                        'a' => 'Ya, kami menyediakan varian Putih Bersih berbahan dolomit super tanpa pewarna cat yang warnanya permanen dan elegan.',
                    ],
                    [
                        'q' => 'Berapa keping kebutuhan roster untuk luas dinding 1 meter persegi?',
                        'a' => 'Untuk ukuran standar 20x20 cm, dibutuhkan tepat 25 keping per meter persegi dinding.',
                    ],
                ],
            ],
            [
                'name' => 'CitraRaya Tangerang',
                'slug' => 'roster-beton-citraraya-tangerang',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3603',
                'latitude' => -6.2483,
                'longitude' => 106.5298,
                'priority' => 1,
                'meta_title' => 'Supplier Roster Beton CitraRaya Tangerang | Fasad Hunian Kota Mandiri & Cikupa',
                'meta_description' => 'Pusat produsen roster beton minimalis untuk CitraRaya Tangerang (Cikupa & Panongan). Kualitas tumbuk plat baja siku presisi, dinding bernapas ramah iklim, kirim cepat.',
                'headline' => 'Supplier Roster Beton CitraRaya Tangerang — Ventilasi Dinding & Fasad Hunian Kota Mandiri',
                'intro_content' => 'Penyedia resmi dan distributor langsung pabrik roster beton minimalis untuk kawasan kota mandiri CitraRaya Tangerang (Cikupa, Panongan, Curug, dan sekitarnya). Pengembangan klaster hunian modern di CitraRaya membutuhkan material dinding bernapas yang tangguh menghadapi panas dataran Tangerang sekaligus meningkatkan estetika fasad. Dibuat dengan metode cetak tumbuk padat plat baja presisi di sentra Plered Purwakarta menggunakan abu batu murni padat, roster IndoRoster memiliki ketahanan benturan tinggi, bebas retak rambut akibat muai susut panas, dan siku 90 derajat sempurna. Sangat cocok diaplikasikan sebagai pagar estetik depan rumah, partisi area cuci jemur belakang (service area) yang membutuhkan aliran angin konstan, serta aksen dinding teras modern.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Merak / Jakarta-Tangerang (Exit Bitung / Cikupa) langsung ke gerbang klaster CitraRaya.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping yang rusak di perjalanan.',
                'target_districts' => ['Cikupa', 'Panongan', 'CitraRaya EcoPolis', 'CitraRaya LakeGate', 'Curug', 'Bitung', 'Tigaraksa', 'Pasir Gadung', 'Balaraja'],
                'custom_faqs' => [
                    [
                        'q' => 'Berapa lama pengiriman roster beton ke klaster perumahan di CitraRaya?',
                        'a' => 'Pengiriman memakan waktu 1 hari kerja langsung dari workshop pabrik kami via akses Tol Jakarta-Merak keluar gerbang Bitung / Cikupa.',
                    ],
                    [
                        'q' => 'Apakah roster beton ini cocok untuk partisi area dapur dan jemuran belakang?',
                        'a' => 'Sangat ideal. Rongga ventilasi roster menjaga sirkulasi udara area servis tetap kering dan bebas bau apek tanpa mengurangi privasi dari tetangga sebelah.',
                    ],
                    [
                        'q' => 'Apakah melayani pemesanan untuk renovasi satu rumah di CitraRaya?',
                        'a' => 'Ya, kami melayani pesanan eceran renovasi rumah mulai dari 100 keping hingga ribuan keping untuk kontraktor proyek.',
                    ],
                    [
                        'q' => 'Bagaimana cara pemesanan roster untuk lokasi CitraRaya?',
                        'a' => 'Pilih motif pada katalog website IndoRoster, lalu hubungi customer service kami via WhatsApp untuk mendapatkan kalkulasi kebutuhan dan surat konfirmasi pengiriman.',
                    ],
                ],
            ],
            [
                'name' => 'BSD City',
                'slug' => 'roster-beton-bsd-city',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3674',
                'latitude' => -6.3016,
                'longitude' => 106.6534,
                'priority' => 1,
                'meta_title' => 'Pabrik Roster Beton BSD City | Fasad Tropis Modern NavaPark & The Mozia',
                'meta_description' => 'Supplier roster beton presisi BSD City Tangerang Selatan. Cetak tumbuk plat baja siku 90°, second skin fasad hemat energi, kirim armada engkel klaster bergaransi.',
                'headline' => 'Pabrik Roster Beton BSD City — Arsitektur Second Skin Modern & Partisi Void Tangga',
                'intro_content' => 'Layanan pengiriman langsung pabrik roster beton minimalis presisi tinggi untuk kawasan terpadu BSD City (The Mozia, NavaPark, Greenwich Park, Vanya Park, Tabebuya, Icon, Foresta, hingga De Park). Arsitektur hunian di BSD City yang mengusung konsep modern tropical dan eco-living sangat mengandalkan roster beton sebagai elemen bioklimatik aktif: melancarkan ventilasi silang alami tanpa mengorbankan privasi keluarga. Dibuat dengan cetak tumbuk padat matras plat baja presisi di Plered Purwakarta menggunakan formula abu batu murni berdensitas tinggi, roster IndoRoster menawarkan garis tepian tajam, warna abu semen alami yang berkelas, serta daya redam termal prima yang membuat ruangan tetap sejuk sepanjang hari.',
                'delivery_route_info' => 'Pengiriman langsung armada engkel pabrik via Tol Serpong-Balaraja / Tol Kunciran-Serpong keluar gerbang BSD Timur / BSD Barat.',
                'estimated_delivery_time' => '1 hari kerja (fleksibel jadwal masuk klaster)',
                'shipping_guarantee_text' => 'Garansi 100% Bebas Pecah ganti baru saat pembongkaran muatan.',
                'target_districts' => ['NavaPark', 'Greenwich Park', 'The Mozia', 'Vanya Park', 'Tabebuya', 'The Icon', 'Foresta', 'De Park', 'Avani', 'Eminent', 'Green Cove'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah armada IndoRoster bisa mengatur jadwal bongkar muat sesuai jam tenang klaster BSD?',
                        'a' => 'Bisa. Kami dapat menjadwalkan armada tiba pada rentang waktu yang diizinkan estate management klaster di BSD City.',
                    ],
                    [
                        'q' => 'Motif apa yang paling serasi dengan arsitektur rumah bertingkat di BSD City?',
                        'a' => 'Motif Geometris Kotak, MMC, dan Labirin sangat serasi dengan fasad modern boxy tropis yang dominan di kawasan NavaPark dan The Mozia.',
                    ],
                    [
                        'q' => 'Berapa tebal roster beton IndoRoster?',
                        'a' => 'Ketebalan standar presisi adalah 10 cm, memberikan stabilitas struktural saat dipasang tinggi sebagai dinding fasad maupun partisi.',
                    ],
                    [
                        'q' => 'Apakah ada garansi jika ada keping yang cacat saat diterima?',
                        'a' => 'Ada garansi 100% ganti baru langsung di lokasi proyek tanpa potongan atau biaya tambahan apa pun.',
                    ],
                ],
            ],
            [
                'name' => 'Gading Serpong',
                'slug' => 'roster-beton-gading-serpong',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3603',
                'latitude' => -6.2415,
                'longitude' => 106.6285,
                'priority' => 1,
                'meta_title' => 'Jual Roster Beton Gading Serpong | Fasad Ruko Komersial & Klaster Hunian',
                'meta_description' => 'Produsen roster beton arsitektural Gading Serpong (Summarecon & Paramount). Cetak tumbuk plat baja siku 90°, minim perawatan, kirim cepat bergaransi 100%.',
                'headline' => 'Jual Roster Beton Gading Serpong — Fasad Komersial Ruko & Estetika Klaster Hunian',
                'intro_content' => 'Pusat pengadaan roster beton arsitektural tangan pertama untuk kawasan berkembang pesat Gading Serpong (Kelapa Dua, Medang, Scientia Garden, Pasadena, Symphonia, IL Lago, dan koridor ruko komersial). Kombinasi dinamis antara hunian modern bertingkat dan bangunan ruko gaya kontemporer di Gading Serpong menuntut material fasad yang tidak hanya estetis namun juga minim perawatan (low maintenance). Roster beton IndoRoster dicetak padat dengan plat baja presisi pengrajin Plered menggunakan abu batu murni berdensitas tinggi, menghasilkan permukaan padat halus alami yang menolak lumut dan noda polusi jalan raya. Sangat ideal untuk renovasi fasad ruko cafe, tabir privasi balkon lantai atas, pagar pengaman klaster, dan aksen interior restoran estetik.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Tangerang keluar gerbang Tangerang / Kebon Nanas atau Tol JORR 2 tembus Gading Serpong Boulevard.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping rusak di perjalanan.',
                'target_districts' => ['Paramount Serpong', 'Summarecon Serpong', 'Scientia Garden', 'Symphonia', 'Pasadena', 'Medang', 'Curug Sangereng', 'Kelapa Dua', 'Bojong Nangka'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah roster beton ini cocok untuk renovasi fasad ruko cafe di Gading Serpong?',
                        'a' => 'Sangat cocok. Roster beton memberikan karakter arsitektural yang kuat, menarik perhatian pengunjung dari jalan raya, sekaligus menjadi tabir peneduh yang hemat listrik.',
                    ],
                    [
                        'q' => 'Berapa jarak waktu pengiriman ke kawasan Gading Serpong?',
                        'a' => 'Pengiriman dilakukan dalam 1 hari kerja langsung via jalan tol dengan armada truk pabrik kami.',
                    ],
                    [
                        'q' => 'Apakah roster beton memerlukan finishing coating tambahan?',
                        'a' => 'Warna asli abu semen alami sudah sangat indah, namun bila diinginkan, dapat dilapisi cairan coating batu alam clear matte untuk perlindungan ekstra terhadap debu jalan.',
                    ],
                    [
                        'q' => 'Bagaimana cara memesan sampel untuk konsultasi dengan owner / desainer interior?',
                        'a' => 'Hubungi sales kami via WhatsApp untuk pengiriman kepingan sampel motif ke kantor atau proyek Anda di Gading Serpong.',
                    ],
                ],
            ],
            [
                'name' => 'Bintaro',
                'slug' => 'roster-beton-bintaro',
                'type' => 'city',
                'province_code' => '36',
                'city_code' => '3674',
                'latitude' => -6.2750,
                'longitude' => 106.7289,
                'priority' => 1,
                'meta_title' => 'Pabrik Roster Beton Bintaro Jaya | Pagar Estetis & Ventilasi Rumah Tropis',
                'meta_description' => 'Supplier resmi roster beton minimalis Bintaro Jaya (Sektor 1–9, Discovery, Emerald). Presisi matras plat baja siku 90°, tekstur abu batu alami, kirim bergaransi.',
                'headline' => 'Pabrik Roster Beton Bintaro Jaya — Solusi Pagar Estetis & Ventilasi Rumah Tropis',
                'intro_content' => 'Distribusi resmi roster beton minimalis langsung dari pabrik sentra Plered Purwakarta ke seluruh sektor kawasan Bintaro Jaya (Sektor 1 hingga Sektor 9, Discovery, Emerald, Graha Raya, Pondok Aren). Konsep hunian The Garden City di Bintaro berpadu sempurna dengan roster beton cetak tumbuk padat IndoRoster yang menonjolkan tekstur abu batu murni alami dan garis geometris modern. Memberikan ventilasi alami yang menyejukkan interior rumah sembari membatasi pandangan langsung dari luar jalanan lingkungan. Presisi matras plat baja 90 derajat memastikan keseragaman dimensi yang memudahkan tukang menghasilkan pasangan dinding roster yang lurus, presisi, dan kokoh tahan lama.',
                'delivery_route_info' => 'Pengiriman langsung armada pabrik via Tol JORR 2 / Tol Pondok Aren keluar Bintaro Sektor 7 / Discovery.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% ganti baru di tempat bila ada keping yang rusak di perjalanan.',
                'target_districts' => ['Sektor 1 Bintaro', 'Sektor 3', 'Sektor 7', 'Sektor 9', 'Pondok Ranji', 'Pondok Aren', 'Jurang Mangu', 'Graha Raya Bintaro', 'Emerald', 'Discovery'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah roster beton ini cocok untuk pagar keliling rumah di Bintaro Jaya?',
                        'a' => 'Sangat cocok. Kombinasi kekuatan struktur beton padat 10 cm dengan motif berlubang estetik memberikan keamanan perimeter rumah tanpa menimbulkan kesan tertutup yang masif dan kaku.',
                    ],
                    [
                        'q' => 'Bisa kirim ke sektor perumahan lama Bintaro Sektor 1 atau Sektor 2?',
                        'a' => 'Bisa. Armada truk kami dapat menjangkau seluruh sektor lama hingga klaster baru di koridor Bintaro Jaya.',
                    ],
                    [
                        'q' => 'Berapa estimasi waktu kirim roster ke Bintaro?',
                        'a' => 'Pengiriman hanya memakan waktu 1 hari kerja setelah order terkonfirmasi langsung dari pabrik Plered Purwakarta.',
                    ],
                    [
                        'q' => 'Apakah menerima pesanan custom motif untuk proyek arsitek di Bintaro?',
                        'a' => 'Untuk pesanan volume proyek tertentu, kami melayani pembuatan matras plat baja khusus sesuai rancangan arsitek Anda.',
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
                'meta_title' => 'Pabrik Roster Beton Bekasi | Reduksi Termal Panas Ekstrem & Fasad Klaster',
                'meta_description' => 'Pusat produsen roster beton minimalis Bekasi (Summarecon, Harapan Indah, Grand Galaxy). Bobot padat 4 kg abu batu murni peredam terik matahari, kirim bergaransi.',
                'headline' => 'Pabrik Roster Beton Bekasi — Reduksi Termal Panas Ekstrem, Fasad Komersial & Klaster',
                'intro_content' => 'Pusat produksi dan pengiriman langsung pabrik roster beton arsitektural untuk Kota dan Kabupaten Bekasi (Summarecon Bekasi, Harapan Indah, Grand Galaxy, Rawalumbu, Tambun, Cibitung). Karakteristik geografis Bekasi sebagai dataran rendah dengan paparan sinar matahari terik sepanjang siang membutuhkan material selubung bangunan yang mampu meredam radiasi termal secara efektif. Roster beton IndoRoster dibuat dari campuran abu batu murni berbobot padat 3.8–4.2 kg per keping melalui penumbukan matras plat baja presisi di Plered Purwakarta. Bobot padat ini memberikan massa termal yang kokoh sebagai pemecah angin panas (wind breaker) dan pelindung dinding utama dari sengatan matahari langsung, menjaga interior rumah dan gedung komersial tetap adem serta bebas retak rambut.',
                'delivery_route_info' => 'Pengiriman super cepat via Tol Cipularang langsung terhubung Tol Jakarta-Cikampek (Exit Bekasi Barat, Bekasi Timur, Tambun Grand Wisata, Cibitung).',
                'estimated_delivery_time' => '1 hari kerja (pengiriman rutin setiap hari)',
                'shipping_guarantee_text' => 'Garansi 100% Ganti Baru di tempat untuk setiap keping yang pecah.',
                'target_districts' => ['Bekasi Barat', 'Bekasi Selatan', 'Bekasi Timur', 'Bekasi Utara', 'Medan Satria', 'Rawalumbu', 'Mustika Jaya', 'Pondok Gede', 'Jatiasih', 'Jatisampurna', 'Tambun Selatan', 'Cibitung'],
                'custom_faqs' => [
                    [
                        'q' => 'Bagaimana roster beton membantu meredam hawa panas rumah di Bekasi?',
                        'a' => 'Massa padat beton abu batu murni menyerap panas matahari di sisi luar dan melepaskannya perlahan melalui ventilasi silang, mencegah perpindahan panas langsung ke dalam kamar tidur.',
                    ],
                    [
                        'q' => 'Berapa lama estimasi pengiriman roster ke area Bekasi?',
                        'a' => 'Karena letak Purwakarta dan Bekasi sangat dekat via akses tol Jakarta-Cikampek, material tiba dalam 1 hari kerja dengan biaya logistik paling efisien.',
                    ],
                    [
                        'q' => 'Apakah melayani pembelian untuk ruko dan klaster perumahan baru di Bekasi?',
                        'a' => 'Ya, kami melayani proyek skala kecil renovasi (100–300 pcs) hingga pesanan borongan proyek developer ribuan pcs.',
                    ],
                    [
                        'q' => 'Bagaimana sistem penggantian garansi pecah untuk wilayah Bekasi?',
                        'a' => 'Setiap kepingan yang retak atau pecah saat dibongkar armada kami langsung diganti baru seketika di tempat oleh supir armada kami.',
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
                'meta_title' => 'Supplier Roster Beton Cikarang | Proyek Kawasan Industri & Klaster Jababeka',
                'meta_description' => 'Penyedia roster beton industri & arsitektural Cikarang (Jababeka, Lippo Cikarang, MM2100, GIIC). Pagar pabrik, ventilasi gudang & ruko, dokumen lengkap bergaransi.',
                'headline' => 'Supplier Roster Beton Cikarang — Spesialis Proyek Industri, Pergudangan & Klaster Modern',
                'intro_content' => 'Penyedia utama roster beton arsitektural dan heavy-duty untuk kawasan industri serta perumahan terpadu Cikarang (Jababeka, Lippo Cikarang, MM2100, GIIC Delta Mas, Delta Silicon, Grand Cikarang City). Kebutuhan konstruksi di Cikarang menuntut material yang tangguh terhadap getaran beban jalan, debu partikel industri, dan paparan cuaca terik dataran rendah. Roster IndoRoster dicetak padat dengan plat baja presisi pengrajin Plered Purwakarta dari formula abu batu murni bermassa padat tanpa rongga keropos, sangat andal diaplikasikan untuk pagar pengaman kawasan pabrik, dinding ventilasi sirkulasi gudang logistik, fasad gedung kantor perkantoran, dan klaster hunian karyawan dengan ketahanan jangka panjang tanpa lapuk.',
                'delivery_route_info' => 'Pengiriman via Tol Jakarta-Cikampek keluar gerbang Tol Cikarang Barat, Cikarang Utara, Cibatu, atau Cikarang Pusat / Delta Mas.',
                'estimated_delivery_time' => '1 hari kerja',
                'shipping_guarantee_text' => 'Garansi 100% aman bebas pecah ganti baru langsung di gerbang proyek.',
                'target_districts' => ['Cikarang Pusat', 'Cikarang Selatan', 'Cikarang Barat', 'Cikarang Utara', 'Cikarang Timur', 'Lippo Cikarang', 'Kawasan Jababeka', 'Kota Delta Mas', 'Cibarusah', 'Serang Baru'],
                'custom_faqs' => [
                    [
                        'q' => 'Apakah pengadaan roster untuk pabrik di Cikarang bisa dilengkapi faktur pajak dan surat jalan?',
                        'a' => 'Bisa. Kami melengkapi setiap transaksi pengadaan perusahaan dan kontraktor dengan dokumen resmi (Invoice, Surat Jalan resmi, dan Faktur).',
                    ],
                    [
                        'q' => 'Berapa kapasitas suplai per hari untuk proyek dinding pergudangan di Cikarang?',
                        'a' => 'Kapasitas produksi sentra kami mencapai ribuan keping per hari sehingga mampu menjaga jadwal suplai ritase truk proyek tanpa jeda keterlambatan.',
                    ],
                    [
                        'q' => 'Apakah roster beton ini tahan terhadap debu kawasan industri Cikarang?',
                        'a' => 'Ya, permukaan padat abu batu murni tidak menyerap debu secara permanen dan sangat mudah dibersihkan dengan semprotan air berkala.',
                    ],
                    [
                        'q' => 'Apakah melayani pemesanan untuk klaster perumahan di Lippo Cikarang?',
                        'a' => 'Tentu melayani, baik untuk perumahan klaster hunian, ruko komersial, maupun fasilitas umum di Lippo Cikarang dan Jababeka.',
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
            $location->meta_title = $data['meta_title'] ?? "Jual Roster Beton {$cityName} Harga Pabrik | IndoRoster";
            $location->meta_description = $data['meta_description'] ?? "Pusat produsen & supplier roster beton minimalis di {$cityName}. Kualitas cetak padat presisi plat baja Plered Purwakarta, garansi pecah ganti baru, kirim cepat.";

            $location->seo_score = $location->calculateSeoScore();
            $location->save();

            $this->command->info("Processed location: {$location->name} (Score: {$location->seo_score})");
        }
    }
}
