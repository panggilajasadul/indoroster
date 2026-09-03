<?php

namespace Database\Seeders;

use App\Models\ApplicationPage;
use Illuminate\Database\Seeder;

class ApplicationPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'slug' => 'pagar-rumah',
                'title' => 'Roster Beton Pagar Minimalis Modern',
                'subtitle' => 'Kombinasi privasi, sirkulasi udara, dan estetika modern untuk batas hunian.',
                'badge' => '🏡 Pagar & Pembatas Kavling',
                'icon' => '🏡',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                'meta_title' => 'Jual Roster Beton Pagar Rumah Minimalis Modern | Pabrik IndoRoster',
                'meta_description' => 'Inspirasi & rekomendasi motif roster beton untuk dinding pagar rumah minimalis modern. Kokoh cetak padat presisi plat baja, sirkulasi udara lancar, privasi terjaga, harga langsung pabrik Plered.',
                'keywords' => 'roster pagar rumah, pagar roster beton, loster pagar minimalis, roster dinding pagar, pagar roster plered',
                'headline' => 'Desain Pagar Rumah Minimalis Modern dengan Roster Beton Tumbuk Padat',
                'intro' => 'Pagar roster beton adalah solusi arsitektural paling populer dalam rancang bangun hunian modern tropis di Indonesia. Berbeda dari dinding masif yang kaku dan membuat halaman terasa pengap seperti terkurung, aplikasi dinding pagar roster beton memberikan perpaduan sempurna antara batas keamanan kavling yang kokoh, sirkulasi udara alami yang leluasa mengalir, serta perlindungan privasi keluarga dari pandangan langsung pejalan kaki di luar jalan raya. Diproduksi dari formula pasir abu batu murni pilihan dengan pemadatan cetak plat baja presisi oleh pengrajin berpengalaman sentra Plered Purwakarta, pagar rumah Anda tampil kokoh, mewah, dan bernilai seni tinggi.',
                'deep_narrative' => [
                    'title' => 'Mengapa Roster Beton Adalah Material Terbaik untuk Pagar Rumah Tropis?',
                    'p1' => 'Iklim tropis Indonesia menuntut hunian yang memiliki sistem sirkulasi udara terbuka. Pagar solid dari bata merah atau batako seringkali menciptakan kantong udara panas di area carport dan teras depan karena angin dari jalan raya terhalang total. Dengan mengaplikasikan kisi-kisi roster beton pada pagar depan maupun dinding samping pembatas tetangga, hembusan angin alami tetap dapat bersirkulasi menyejukkan teras, sementara bias cahaya matahari menghasilkan bayangan geometris dinamis yang estetik sepanjang hari.',
                    'p2' => 'Dari segi ketahanan struktur, roster beton cetak tumbuk padat plat baja IndoRoster memiliki kepadatan material tinggi (bobot 3.8 – 4.2 kg per keping) dengan pori-pori mikro yang sangat rapat. Hal ini menjadikannya sangat tahan terhadap cuaca ekstrem hujan asam, terik matahari langsung, serta benturan fisik, tanpa risiko keropos, lapuk dimakan rayap, atau berkarat seperti halnya material pagar kayu dan besi konvensional.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm (Standar Arsitektural)',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Pilihan + Semen Khusus (Tanpa Pasir Silika / Limbah)',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Terakota',
                ],
                'installation_guide' => [
                    'title' => 'Panduan Teknis Pemasangan Pagar Roster yang Aman & Kokoh',
                    'steps' => [
                        ['step' => '1. Pondasi Sloof Beton Bertulang', 'desc' => 'Pastikan dinding pagar berdiri di atas sloof beton bertulang dengan kedalaman pondasi minimal 30–40 cm agar tidak mengalami penurunan tanah di kemudian hari.'],
                        ['step' => '2. Kolom Praktis & Pembesian Pengaku', 'desc' => 'Pasang tiang kolom cor praktis besi bertulang (Ø 8–10 mm) setiap bentang horizontal 1.5 – 2.0 meter, serta selipkan besi begel penguat di sela nat horizontal setiap 3–4 susun keping roster.'],
                        ['step' => '3. Adukan Semen Instan / Mortar Presisi', 'desc' => 'Gunakan semen mortar perekat berkualitas dengan ketebalan nat siar 8–10 mm untuk menjaga kelurusan garis nat vertikal dan horizontal dinding pagar.'],
                        ['step' => '4. Lapisan Pelindung (Clear Coating Water-Repellent)', 'desc' => 'Setelah adukan semen kering sempurna (3–7 hari), aplikasikan cat pelapis batu alam / clear coating anti air (water-repellent) untuk mencegah lumut, jamur, dan noda cipratan tanah saat hujan.'],
                    ],
                ],
                'design_tips' => [
                    'Kombinasikan dengan tiang besi hollow hitam doff untuk menghadirkan nuansa Modern Industrial yang tegas.',
                    'Pasang lampu sorot dinding (uplight/downlight) di balik roster pagar untuk menciptakan efek pencahayaan dramatis dan bayangan siluet mewah di malam hari.',
                    'Pilih motif dengan kemiringan sirip (seperti Nako Sipit atau Nako LS) untuk bidang pagar yang langsung berhadapan dengan arah angin hujan agar bebas tampias air.',
                ],
                'benefits' => [
                    ['title' => 'Kokoh & Tahan Benturan', 'desc' => 'Dibuat dengan pemadatan plat baja dan pasir abu batu murni berkualitas tinggi tanpa rongga keropos.'],
                    ['title' => 'Privasi & Sirkulasi Seimbang', 'desc' => 'Menghalangi pandangan langsung dari luar jalan raya namun tetap membiarkan udara segar mengalir bebas.'],
                    ['title' => 'Kombinasi Material Fleksibel', 'desc' => 'Sangat serasi dipadukan dengan tiang besi hollow hitam, bata ekspos, maupun pilar semen ekspos.'],
                ],
                'motifs' => ['Nako Sipit', 'Nako LS', 'MMC', 'Petir', 'Bintang', 'Kotak 4'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259930/47_dmjh8d.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259848/sg-11134201-7ra3x-mbga48q8qh9x40_resize_w450_nl_f9jbbk.webp',
                ],
                'faqs' => [
                    ['q' => 'Berapa tinggi ideal pagar roster beton untuk rumah tinggal?', 'a' => 'Tinggi ideal dinding pagar berkisar antara 1,5 meter hingga 2,2 meter. Untuk ketinggian di atas 1,8 meter, wajib menggunakan balok pinggang / kolom cor pengaku vertikal setiap 1,5-2 meter bentang agar konstruksi tetap rigid dan aman dari terpaan angin kencang.'],
                    ['q' => 'Apakah pagar roster beton aman dari risiko roboh?', 'a' => 'Sangat aman dan kokoh jika dipasang sesuai standar konstruksi dengan adukan semen mortar berkualitas dan kolom pengaku besi cor bertulang di sela-sela modul roster.'],
                    ['q' => 'Bagaimana cara menghitung jumlah kebutuhan roster untuk pagar?', 'a' => 'Ukuran standar roster beton kami adalah 20x20x10 cm, sehingga membutuhkan 25 keping per 1 meter persegi (m²). Rumus hitungnya: Luas Dinding (Panjang × Tinggi) × 25 pcs + cadangan 3-5% untuk potongan nat.'],
                    ['q' => 'Apakah IndoRoster melayani pengiriman langsung ke lokasi proyek pagar rumah?', 'a' => 'Ya, kami melayani pengiriman langsung dari pabrik Plered Purwakarta ke seluruh wilayah Jabodetabek, Bandung, Jawa Barat, hingga ke seluruh Indonesia dengan garansi 100% ganti baru jika ada keping pecah di jalan.'],
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'fasad-rumah',
                'title' => 'Fasad Dinding Roster Rumah Tropis',
                'subtitle' => 'Secondary skin penangkal panas matahari langsung dengan pola bayangan arsitektural.',
                'badge' => '🏛️ Fasad & Secondary Skin',
                'icon' => '🏛️',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260049/40_kt08ee.jpg',
                'meta_title' => 'Fasad Rumah Roster Beton Minimalis Modern | Secondary Skin IndoRoster',
                'meta_description' => 'Koleksi roster beton untuk fasad dinding rumah minimalis tropis. Menurunkan suhu ruangan, hemat AC, meredam silau matahari, harga langsung pabrik Plered.',
                'keywords' => 'fasad roster beton, fasad rumah minimalis, secondary skin roster, roster penangkal panas, roster dinding depan',
                'headline' => 'Fasad Rumah Tropis Modern: Secondary Skin Estetik, Meredam Panas & Hemat Listrik AC',
                'intro' => 'Aplikasi roster beton sebagai secondary skin (kulit kedua bangunan) pada fasad rumah menjadi tren utama dalam mahakarya arsitektur modern kontemporer. Memasang dinding roster di depan bidang kaca jendela utama terbukti mampu meredam radiasi panas matahari tropis langsung hingga 40%, menurunkan temperatur suhu ruangan di dalam rumah secara alami 3–5°C, serta menciptakan privasi maksimal bagi penghuni tanpa perlu menutup gorden jendela sepanjang hari. Diproduksi dengan presisi sudut siku 90 derajat, fasad rumah Anda menjadi karya seni geometris yang megah dan berkelas.',
                'deep_narrative' => [
                    'title' => 'Efisiensi Energi & Estetika Shadow Play pada Fasad Bangunan',
                    'p1' => 'Dinding kaca besar pada rumah menghadap barat atau timur seringkali menjadi penyumbang panas terbesar di dalam ruangan, memaksa AC bekerja ekstra keras dan membengkakkan tagihan listrik. Dengan menempatkan secondary skin roster beton dengan jarak rongga 30–50 cm di depan kaca, panas matahari terserap dan terbiaskan sebelum menyentuh kaca, sementara sirkulasi angin sepoi-sepoi tetap bergerak bebas melalui rongga ventilasi.',
                    'p2' => 'Keindahan utama fasad roster terletak pada efek bayangan dinamis (shadow play). Pola lubang roster yang presisi akan memproyeksikan pola bayangan arsitektural yang berubah-ubah secara dramatis di lantai dan dinding interior seiring pergerakan matahari dari pagi hingga sore hari, memberikan pengalaman visual yang hidup dan menenangkan.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm (Standar Modul Fasad)',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Mutu Tinggi',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Bata',
                ],
                'installation_guide' => [
                    'title' => 'Panduan Konstruksi Secondary Skin Fasad Roster Bertingkat',
                    'steps' => [
                        ['step' => '1. Struktur Balok & Cantilever Penopang', 'desc' => 'Dinding secondary skin roster di lantai 2 atau 3 harus bertumpu pada balok beton struktur cantilever atau frame baja IWF/UNP yang diangkur kuat ke struktur utama gedung.'],
                        ['step' => '2. Pembesian Begel Tulang Horizontal & Vertikal', 'desc' => 'Setiap 2–3 susun keping roster wajib diisi besi tulangan horizontal dan dikaitkan pada kolom samping agar dinding fasad kokoh menahan beban angin (wind-load).'],
                        ['step' => '3. Ruang Perawatan (Maintenance Gap)', 'desc' => 'Sisakan jarak 30–60 cm antara dinding roster dan kaca jendela untuk mempermudah pembersihan kaca dan sirkulasi pembuangan panas.'],
                    ],
                ],
                'design_tips' => [
                    'Pilih motif dengan pola garis tegas seperti Petir, Arrow, atau MMC untuk menciptakan karakter fasad yang modern dan gagah.',
                    'Kombinasikan motif polos dengan motif berongga untuk menciptakan pola gradasi kepadatan kisi-kisi fasad yang unik.',
                ],
                'benefits' => [
                    ['title' => 'Efisiensi Energi (Hemat Listrik AC)', 'desc' => 'Mereduksi panas matahari langsung sehingga beban kerja pendingin ruangan (AC) berkurang drastis.'],
                    ['title' => 'Efek Bayangan Artistik (Shadow Play)', 'desc' => 'Pola lubang geometris menghasilkan bayangan eksotis di dinding interior seiring pergerakan matahari.'],
                    ['title' => 'Perawatan Rendah & Bebas Rayap', 'desc' => 'Material beton abu batu murni tahan terhadap lumut, jamur, serta cuaca ekstrem panas dan hujan.'],
                ],
                'motifs' => ['Petir', 'Arrow', 'JaboL', 'MMC', 'Nako Sipit'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260049/40_kt08ee.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259830/36_vaxh6k.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah air hujan akan tampias ke dalam jika menggunakan fasad roster?', 'a' => 'Untuk area yang terkena hujan langsung tanpa kanopi, kami merekomendasikan motif ber-sirip miring seperti Nako Sipit atau Nako LS yang dirancang khusus menangkis percikan air hujan agar mengalir ke luar.'],
                    ['q' => 'Bagaimana cara merawat fasad dinding roster agar tidak kusam?', 'a' => 'Cukup aplikasikan cat pelapis batu alam / clear coating water-repellent (berbasis solvent atau water-based) setiap 2-3 tahun sekali agar debu tidak mudah menempel dan permukaan beton selalu bersih mengkilap.'],
                    ['q' => 'Apakah roster beton kuat menahan beban angin pada fasad gedung tinggi?', 'a' => 'Sangat kuat jika dipasang dengan frame struktur pengaku besi hollow/baja dan pembesian begel cor di setiap nat modul.'],
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'ventilasi-dinding',
                'title' => 'Ventilasi Dinding & Lubang Angin Roster',
                'subtitle' => 'Sirkulasi udara alami bebas pengap untuk dapur, kamar mandi, dan ruang keluarga.',
                'badge' => '💨 Sirkulasi Udara Alami',
                'icon' => '💨',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
                'meta_title' => 'Roster Ventilasi Udara Dinding & Lubang Angin Rumah | IndoRoster',
                'meta_description' => 'Jual roster beton untuk ventilasi udara dinding rumah, dapur, kamar mandi, dan gudang. Sirkulasi lancar bebas pengap, cetak padat presisi harga pabrik.',
                'keywords' => 'ventilasi roster beton, lubang angin rumah, roster dapur, roster kamar mandi, jalusi beton',
                'headline' => 'Ventilasi Dinding Alami: Udara Bersih, Bebas Lembap & Rumah Sejuk Tanpa Pengap',
                'intro' => 'Ventilasi alami adalah kunci utama rumah tinggal yang sehat dan nyaman dihuni. Roster lubang angin IndoRoster dirancang khusus dengan kepresisian siku 90 derajat untuk mempermudah pemasangan di atas kusen pintu, jendela, dinding dapur, kamar mandi, area cuci jemur, hingga dinding gudang. Mengalirkan pergantian udara bersih secara kontinu siang dan malam, mencegah timbulnya kelembapan berlebih, bau apek, dan pertumbuhan jamur berbahaya pada dinding hunian Anda.',
                'deep_narrative' => [
                    'title' => 'Prinsip Sirkulasi Silang (Cross-Ventilation) untuk Kesehatan Rumah',
                    'p1' => 'Rumah modern yang tertutup rapat tanpa ventilasi alami yang memadai berisiko menjebak polutan udara dalam ruangan (indoor air pollutants) dan kelembapan tinggi yang memicu timbulnya flek jamur di plafon. Menempatkan roster ventilasi pada dinding yang berseberangan akan menciptakan aliran udara silang (cross-ventilation) yang membuang udara panas dan lembap keluar secara pasif tanpa memerlukan konsumsi listrik kipas exhaust.',
                    'p2' => 'Kepadatan beton cetak padat abu batu murni IndoRoster menjamin lubang angin kokoh, tahan terhadap benturan dan tidak bisa ditembus oleh hama pengerat seperti tikus atau rayap.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm (Tersedia juga ukuran custom)',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Portland Khusus',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Bata',
                ],
                'installation_guide' => [
                    'title' => 'Tips Pemasangan Ventilasi Lubang Angin Roster',
                    'steps' => [
                        ['step' => '1. Pasang di Atas Ketinggian Kepala', 'desc' => 'Udara panas memiliki massa jenis lebih ringan dan selalu bergerak ke atas. Tempatkan modul ventilasi roster di area atas dinding (ketinggian > 2 meter) untuk membuang udara panas secara efisien.'],
                        ['step' => '2. Pemasangan Kawat Nyamuk Interior', 'desc' => 'Untuk mencegah serangga atau nyamuk masuk, Anda dapat memasang frame kawat nyamuk aluminium atau magnetik di sisi dalam dinding roster yang mudah dilepas saat dibersihkan.'],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif Kotak 4 atau Bintang untuk ventilasi kamar mandi dan dapur agar udara mengalir maksimal.',
                    'Susun horizontal 3–5 keping di atas jendela untuk aksen ventilasi yang serasi dengan fasad luar.',
                ],
                'benefits' => [
                    ['title' => 'Sirkulasi Silang (Cross Ventilation)', 'desc' => 'Mengalirkan udara kotor keluar dan memasukkan udara segar tanpa bergantung pada kipas exhaust.'],
                    ['title' => 'Pencegah Kelembapan & Jamur', 'desc' => 'Sangat efektif untuk dinding area basah seperti kamar mandi dan area cuci jemur.'],
                    ['title' => 'Kuat & Aman dari Hama', 'desc' => 'Dinding beton kokoh yang tidak bisa dirusak oleh tikus atau rayap.'],
                ],
                'motifs' => ['Nako Sipit', 'Kotak 4', 'Bintang', 'PCL', 'Nako LS'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259870/146480918_962561287611643_2630009701372432663_n_gugfhr.jpg',
                ],
                'faqs' => [
                    ['q' => 'Berapa ukuran standar roster ventilasi udara IndoRoster?', 'a' => 'Ukuran standar kami adalah 20x20x10 cm (membutuhkan 25 keping per meter persegi). Ukuran ini sangat pas dipadukan dengan modul bata ringan maupun bata merah.'],
                    ['q' => 'Apakah bisa dipasangi kawat nyamuk di bagian belakang roster?', 'a' => 'Bisa sekali. Anda bisa memasang frame kawat nyamuk aluminium atau lis magnetik di sisi belakang dinding roster agar serangga tidak bisa masuk.'],
                    ['q' => 'Apakah roster ventilasi aman dipasang di dinding kamar mandi?', 'a' => 'Sangat aman. Roster beton abu batu murni IndoRoster tahan air dan uap lembap kamar mandi tanpa risiko lapuk atau keropos.'],
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'partisi-ruangan',
                'title' => 'Partisi Ruangan & Sekat Interior Roster',
                'subtitle' => 'Pembatas ruang fleksibel dan estetik yang menjaga keterbukaan visual.',
                'badge' => '🚪 Partisi & Sekat Interior',
                'icon' => '🚪',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg',
                'meta_title' => 'Sekat Partisi Ruangan Roster Beton Minimalis Interior | IndoRoster',
                'meta_description' => 'Ide sekat partisi ruangan roster beton untuk ruang tamu, ruang keluarga, dan dapur. Tampilan estetik industrial modern langsung dari produsen.',
                'keywords' => 'partisi roster beton, sekat ruang tamu roster, partisi interior minimalis, sekat dapur roster, pembatas ruang industrial',
                'headline' => 'Sekat Partisi Ruangan Interior: Estetik, Elegan & Menjaga Keterbukaan Visual Ruang',
                'intro' => 'Membagi zonasi ruang tamu, ruang keluarga, ruang makan, atau dapur tanpa membuat rumah terasa sempit kini semakin mudah dengan partisi roster beton interior. Desain kisi-kisi arsitektural memberikan batasan fungsi ruang yang tegas (semi-private zoning) namun tetap mempertahankan konsep ruang terbuka (open space), keterbukaan pandangan visual, dan kebebasan sirkulasi udara antar ruangan. Tampilan semen ekspos natural menghadirkan atmosfer Industrial Modern yang hangat dan instagramable.',
                'deep_narrative' => [
                    'title' => 'Solusi Ruang Semi-Private Tanpa Kesan Pengap dan Gelap',
                    'p1' => 'Dinding pembatas masif (gypsum/bata) di dalam rumah berukuran kompak seringkali membuat ruangan terasa sempit, terisolasi, dan menghalangi cahaya matahari dari jendela depan masuk ke area ruang makan di belakang. Partisi roster beton menjadi jembatan visual yang meneruskan cahaya alami ke seluruh sudut rumah sembari menciptakan dinding aksen (focal point) yang memukau.',
                    'p2' => 'Modul roster beton dapat dibiarkan dengan warna abu-abu semen alami untuk tema industrial, atau dicat dengan warna putih, krem, terracotta, maupun warna pastel yang selaras dengan palet interior ruangan Anda.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Mutu Tinggi',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Bersih, Terakota',
                ],
                'installation_guide' => [
                    'title' => 'Langkah Pemasangan Partisi Interior Roster',
                    'steps' => [
                        ['step' => '1. Letakkan di Atas Balok / Lantai Kokoh', 'desc' => 'Pastikan partisi berdiri di atas lantai yang solid atau balok struktur. Pasang bracket siku penguat besi di sisi dinding samping dan lantai.'],
                        ['step' => '2. Gunakan Semen Perekat Mortar Tipis', 'desc' => 'Untuk interior, gunakan adukan semen perekat instan tipis (neat mortar) agar garis nat terlihat sangat rapi dan presisi.'],
                        ['step' => '3. Finishing Interior Sealer', 'desc' => 'Aplikasikan dust-proof coating / interior clear sealer agar permukaan beton bebas debu dan mudah dilap saat dibersihkan.'],
                    ],
                ],
                'design_tips' => [
                    'Pasang tanaman hias rambat indoor (seperti sirih gading) di sela-sela lubang roster untuk sentuhan Biophilic Design yang segar.',
                    'Gunakan motif geometris kontemporer seperti Batman, MMC, atau Arrow untuk kesan modern chic.',
                ],
                'benefits' => [
                    ['title' => 'Konsep Ruang Terbuka (Semi-Private)', 'desc' => 'Membagi fungsi ruang tanpa memblokir pandangan dan cahaya sepenuhnya.'],
                    ['title' => 'Aksen Visual Unik (Focal Point)', 'desc' => 'Menjadi dinding aksen mewah yang langsung mencuri perhatian tamu saat memasuki rumah.'],
                    ['title' => 'Bisa Diberi Warna / Cat Custom', 'desc' => 'Dapat dibiarkan bernuansa semen abu natural (industrial) atau dicat putih/warna lain sesuai tema interior.'],
                ],
                'motifs' => ['Batman', 'MMC', 'Nako LS', 'Petir', 'Arrow'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260071/19_aaa6uf.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah partisi roster beton aman dipasang di lantai 2 bangunan?', 'a' => 'Aman, pastikan partisi diletakkan di atas jalur balok struktur lantai atau gunakan frame besi pengaku agar beban merata.'],
                    ['q' => 'Bagaimana cara membersihkan debu pada kisi-kisi partisi roster dalam rumah?', 'a' => 'Gunakan kemoceng mikrofiber atau vacuum cleaner berujung sikat halus. Permukaan yang sudah diberi clear sealer sangat mudah dilap dengan lap setengah basah.'],
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'void-tangga',
                'title' => 'Dinding Void Tangga & Skylight Roster',
                'subtitle' => 'Meneruskan cahaya alami ke area tangga tanpa membuat ruangan terasa sempit.',
                'badge' => '🪜 Void Tangga & Skylight',
                'icon' => '🪜',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259870/146480918_962561287611643_2630009701372432663_n_gugfhr.jpg',
                'meta_title' => 'Dinding Void Tangga Roster Beton Minimalis | IndoRoster Pabrik',
                'meta_description' => 'Aplikasi roster beton pada dinding void tangga dan skylight. Meneruskan pencahayaan alami dan sirkulasi vertikal, pesan harga pabrik bergaransi.',
                'keywords' => 'roster void tangga, dinding void tangga, pencahayaan alami tangga, roster skylight',
                'headline' => 'Dinding Void Tangga Roster: Maksimalkan Cahaya Alami & Efek Sirkulasi Cerobong Vertikal',
                'intro' => 'Area tangga pada rumah bertingkat seringkali menjadi sudut yang gelap, lembap, dan pengap. Dengan mengaplikasikan dinding roster beton pada area void tangga yang terhubung dengan skylight atap atau inner courtyard (taman dalam), cahaya alami akan menyinari lantai 1 dan 2 secara merata tanpa perlu menyalakan lampu di siang hari. Udara panas yang naik ke lantai atas juga akan terbuang keluar dengan lancar melalui efek sirkulasi cerobong alami (Stack Effect).',
                'deep_narrative' => [
                    'title' => 'Efek Cerobong Termal (Stack Effect) dan Kemegahan Visual Tangga',
                    'p1' => 'Hawa panas di dalam rumah selalu bergerak naik ke lantai atas. Dinding void tangga yang dipasangi roster beton berfungsi sebagai cerobong pembuangan panas pasif alami. Begitu hawa panas keluar lewat lubang roster di lantai atas, udara sejuk dari lantai bawah akan tertarik masuk, menciptakan sirkulasi udara kontinu yang menyegarkan seluruh rumah.',
                    'p2' => 'Secara visual, bentang dinding tinggi pada area void yang dihiasi motif roster geometris memberikan kesan megah, artistik, dan mengangkat derajat estetika rumah secara keseluruhan.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Khusus',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Bata',
                ],
                'installation_guide' => [
                    'title' => 'Panduan Pemasangan Dinding Void Tangga Tinggi',
                    'steps' => [
                        ['step' => '1. Gunakan Perancah (Scaffolding) Kokoh', 'desc' => 'Untuk dinding void tangga dengan ketinggian 4–6 meter, pastikan pemasangan dilakukan bertahap menggunakan scaffolding yang aman dan stabil.'],
                        ['step' => '2. Balok Lintel / Pengaku Horizontal', 'desc' => 'Pasang balok cor pengaku lintel setiap ketinggian 2.5 – 3 meter bentang dinding agar struktur roster tidak mengalami lendutan.'],
                    ],
                ],
                'design_tips' => [
                    'Padukan dengan pencahayaan skylight kaca di atap void untuk menghasilkan efek berkas cahaya (sunbeam) yang dramatis di area tangga.',
                    'Gunakan motif Nako Sipit atau Petir untuk kombinasi pencahayaan dan privasi tangga yang optimal.',
                ],
                'benefits' => [
                    ['title' => 'Pencahayaan Alami Sepanjang Siang', 'desc' => 'Menghemat pemakaian lampu tangga di siang hari berkat difusi cahaya alami yang lembut.'],
                    ['title' => 'Efek Sirkulasi Cerobong (Stack Effect)', 'desc' => 'Membuang udara panas dari lantai bawah ke luar atap void secara otomatis.'],
                    ['title' => 'Kemegahan Visual Arsitektural', 'desc' => 'Menghadirkan kesan rumah mewah bertingkat dengan detail dinding bertekstur geometris.'],
                ],
                'motifs' => ['Nako Sipit', 'Petir', 'JaboL', 'MMC'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259870/146480918_962561287611643_2630009701372432663_n_gugfhr.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259896/87_pikio2.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah pemasangan roster di dinding void tangga membutuhkan besi penguat?', 'a' => 'Ya, untuk dinding void yang tinggi, disarankan memasang besi begel penguat vertikal dan horizontal setiap 3 susun keping dan diikat ke kolom praktis gedung.'],
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'slug' => 'fasad-cafe',
                'title' => 'Fasad Cafe & Restoran Industrial',
                'subtitle' => 'Spot foto instagramable dan daya tarik visual unik untuk bisnis kuliner.',
                'badge' => '☕ Cafe & Resto Komersial',
                'icon' => '☕',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
                'meta_title' => 'Fasad Roster Beton Cafe & Resto Industrial | Spot Instagramable IndoRoster',
                'meta_description' => 'Supplier roster beton untuk fasad coffee shop, cafe, dan resto kekinian bernuansa industrial. Kualitas cetak padat presisi harga tangan pertama pabrik.',
                'keywords' => 'fasad cafe roster, roster coffee shop, dinding cafe industrial, spot foto cafe roster',
                'headline' => 'Fasad Cafe & Resto Kekinian: Ikonik, Instagramable, Sejuk & Hemat Biaya Konstruksi',
                'intro' => 'Dalam bisnis kuliner dan hospitality modern, fasad depan adalah daya tarik visual utama (identity landmark) yang memikat calon pelanggan untuk berhenti, masuk, dan berfoto. Dinding roster beton industrial IndoRoster memberikan karakter arsitektur yang kuat, estetik, fotogenik, dan sangat diminati generasi muda. Cocok diaplikasikan untuk fasad depan cafe, outdoor smoking area yang sejuk, maupun dinding pembatas parkiran dengan efisiensi biaya konstruksi tinggi.',
                'deep_narrative' => [
                    'title' => 'Kekuatan Daya Tarik Visual Media Sosial (Instagramable Architecture)',
                    'p1' => 'Cafe yang sukses selalu memiliki sudut foto ikonik yang mendorong pengunjung untuk mengunggahnya ke Instagram dan TikTok. Pola roster beton geometris dengan pencahayaan warm white di malam hari menghasilkan background foto yang sangat estetis dan bernilai promosi organik tinggi bagi bisnis Anda.',
                    'p2' => 'Selain nilai estetika, roster beton menciptakan area semi-outdoor smoking yang sangat nyaman bagi pengunjung karena sirkulasi asap rokok dan hembusan angin mengalir bebas tanpa membuat area terasa gerah.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Khusus',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Bata',
                ],
                'installation_guide' => [
                    'title' => 'Tips Pemasangan Fasad Cafe',
                    'steps' => [
                        ['step' => '1. Kombinasi Neon Signage', 'desc' => 'Dinding roster sangat serasi dipadukan dengan neon flex sign bertuliskan logo atau nama cafe Anda.'],
                        ['step' => '2. Pasang Lampu Hidden LED', 'desc' => 'Tanam strip LED di bawah modul roster untuk menghasilkan efek glowing yang memikat mata pengunjung di malam hari.'],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif Petir atau MMC untuk tampilan industrial yang tegas dan fotogenik.',
                ],
                'benefits' => [
                    ['title' => 'Daya Tarik Visual Instagramable', 'desc' => 'Menjadi background foto favorit pelanggan yang otomatis mempromosikan cafe Anda di media sosial.'],
                    ['title' => 'Suasana Semi-Outdoor Nyaman', 'desc' => 'Menghadirkan hembusan angin sejuk bagi area smoking/outdoor cafe tanpa silau matahari berlebih.'],
                    ['title' => 'Biaya Konstruksi Efisien', 'desc' => 'Material kokoh tahan lama tanpa perlu biaya finishing cat mahal yang berulang.'],
                ],
                'motifs' => ['Petir', 'MMC', 'Arrow', 'PCL', 'Batman'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
                ],
                'faqs' => [
                    ['q' => 'Berapa hari waktu pengiriman roster untuk proyek cafe di luar kota?', 'a' => 'Pengiriman armada truk pabrik IndoRoster memakan waktu 1-2 hari kerja ke seluruh Jabodetabek, Bandung Raya, dan Jawa Barat.'],
                ],
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'slug' => 'ruko',
                'title' => 'Fasad Ruko & Commercial Building',
                'subtitle' => 'Transformasi visual fasad ruko menjadi bangunan komersial bernilai sewa tinggi.',
                'badge' => '🏢 Fasad Ruko Komersial',
                'icon' => '🏢',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260059/477127145_935487138780264_8156628137020905763_n_koes6o.jpg',
                'meta_title' => 'Fasad Ruko Roster Beton Minimalis Modern | Renovasi Ruko IndoRoster',
                'meta_description' => 'Renovasi fasad ruko dengan roster beton minimalis. Tingkatkan nilai jual dan sewa ruko komersial dengan secondary skin modern harga pabrik.',
                'keywords' => 'fasad ruko roster, renovasi ruko minimalis, secondary skin ruko, roster komersial',
                'headline' => 'Renovasi Fasad Ruko Komersial: Modernisasi Tampilan, Nilai Sewa & Jual Naik 3x Lipat',
                'intro' => 'Ubah tampilan ruko lama yang monoton dan kusam menjadi bangunan komersial modern bergengsi tinggi dengan secondary skin roster beton. Selain meningkatkan daya tarik bagi calon penyewa bisnis ternama (kantor, klinik, cafe, butik), kisi-kisi roster membantu meminimalisir radiasi panas matahari di lantai 2 dan 3 ruko sehingga suhu ruangan lebih sejuk dan tagihan listrik AC hemat drastis.',
                'deep_narrative' => [
                    'title' => 'Transformasi Ruko Lama Menjadi Bangunan Komersial Bernilai Tinggi',
                    'p1' => 'Deretan ruko lama dengan fasad polos seringkali sulit mendapatkan penyewa dengan harga sewa tinggi. Penambahan selubung secondary skin roster beton memberikan facelift arsitektural instan yang membuat ruko terlihat seperti gedung butik modern berstandar internasional.',
                    'p2' => 'Kisi-kisi roster beton juga berfungsi ganda menyamarkan unit outdoor AC yang biasanya terpasang berantakan di balkon depan ruko, menjaga fasad tetap rapi dan bersih.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Mutu Tinggi',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Bata',
                ],
                'installation_guide' => [
                    'title' => 'Panduan Pemasangan Fasad Ruko',
                    'steps' => [
                        ['step' => '1. Bracket Baja IWF / Hollow Tebal', 'desc' => 'Gunakan struktur rangka baja penopang yang di-dynabolt kokoh ke balok lantai balkon ruko.'],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif Nako LS atau MMC untuk kerapian garis horizontal fasad ruko berlantai 3.',
                ],
                'benefits' => [
                    ['title' => 'Menaikkan Nilai Sewa & Jual Ruko', 'desc' => 'Tampilan fasad ruko menjadi jauh lebih modern, premium, dan diminati penyewa bisnis ternama.'],
                    ['title' => 'Penangkal Panas Lantai Atas', 'desc' => 'Meredam terik matahari di jendela lantai atas ruko yang menghadap barat/timur.'],
                ],
                'motifs' => ['Nako LS', 'MMC', 'Kotak Kasar', 'Petir'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260059/477127145_935487138780264_8156628137020905763_n_koes6o.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah IndoRoster melayani pengadaan ribuan pcs untuk renovasi deretan ruko?', 'a' => 'Ya, kami melayani pengadaan partai besar grosir dengan kapasitas pabrik 10.000 pcs per bulan dan dokumen pengadaan faktur pajak lengkap.'],
                ],
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'slug' => 'perumahan-cluster',
                'title' => 'Gerbang & Fasad Klaster Perumahan',
                'subtitle' => 'Keseragaman estetika mewah untuk gerbang utama dan fasad tipe rumah developer.',
                'badge' => '🏘️ Developer & Klaster Perumahan',
                'icon' => '🏘️',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259830/36_vaxh6k.jpg',
                'meta_title' => 'Pengadaan Roster Beton Klaster Perumahan & Developer | IndoRoster',
                'meta_description' => 'Suplai roster beton untuk gerbang cluster, fasad perumahan developer, dan dinding pembatas kavling. Kontrak harga pabrik dan suplai bertahap.',
                'keywords' => 'roster perumahan, gerbang cluster roster, roster proyek developer, loster perumahan minimalis',
                'headline' => 'Pengadaan Roster Developer: Gerbang Utama Ikonik & Fasad Klaster Perumahan',
                'intro' => 'Bagi developer perumahan, keseragaman motif, ketahanan material bebas cuaca, dan kepresisian sudut siku 90 derajat adalah faktor kunci penentu kecepatan kerja tukang di lapangan. IndoRoster menyediakan kontrak pengadaan berkala untuk puluhan hingga ratusan unit rumah klaster serta gerbang utama perumahan dengan harga pabrik terkunci, dokumen faktur pajak resmi, dan jaminan pasokan rutin bergaransi 100% bebas pecah.',
                'deep_narrative' => [
                    'title' => 'Standar Presisi Tinggi untuk Kecepatan Pembangunan Unit Developer',
                    'p1' => 'Roster cetak manual non-presisi seringkali memiliki deviasi ukuran 3–5 mm yang membuat pemasangan lambat dan garis nat berkelok-kelok. Cetakan plat baja presisi IndoRoster menjamin setiap keping memiliki dimensi seragam 20x20x10 cm yang siku sempurna, mempercepat progres pemasangan dinding fasad puluhan unit rumah secara signifikan.',
                    'p2' => 'Kami melayani skema pengiriman terjadwal bertahap mengikuti jadwal pengecoran dinding perumahan, sehingga area proyek developer tetap rapi dan bebas dari penumpukan material yang berisiko rusak.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Portland Mutu Tinggi',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Bersih, Merah Terakota',
                ],
                'installation_guide' => [
                    'title' => 'Skema Pengadaan & Pengiriman Developer',
                    'steps' => [
                        ['step' => '1. Pengiriman Bertahap Sesuai SPK', 'desc' => 'Pengiriman armada truk pabrik disesuaikan dengan tahapan progres bangun (500 – 2.000 pcs per ritase).'],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif seragam pada gerbang klaster dan dinding fasad rumah untuk memperkuat identitas brand perumahan.',
                ],
                'benefits' => [
                    ['title' => 'Kontrak Harga Pabrik Terkunci', 'desc' => 'Harga dijamin stabil selama masa pembangunan proyek tanpa risiko fluktuasi harga eceran.'],
                    ['title' => 'Pengiriman Bertahap Sesuai Jadwal Proyek', 'desc' => 'Material dikirim sesuai tahapan cor dinding tanpa membuat lokasi proyek berantakan.'],
                    ['title' => 'Dokumen Pengadaan Lengkap', 'desc' => 'Surat jalan resmi, faktur pajak, dan invoice komersial siap terbit cepat.'],
                ],
                'motifs' => ['Nako Sipit', 'MMC', 'Petir', 'Bintang'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765259830/36_vaxh6k.jpg',
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260857/162301330_988931014974670_4453781190506425580_n_iu9gd2.jpg',
                ],
                'faqs' => [
                    ['q' => 'Berapa minimal order kontrak suplai developer perumahan?', 'a' => 'Kami melayani mulai dari pengadaan 1 klaster (1.000 – 5.000 pcs) hingga kontrak tahunan puluhan ribu pcs dengan harga pabrik terkunci.'],
                ],
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'slug' => 'gedung-komersial',
                'title' => 'Fasad Gedung, Hotel & Kantor',
                'subtitle' => 'Dinding secondary skin berskala ribuan pcs dengan efisiensi pendinginan AC alami.',
                'badge' => '🏨 Gedung, Hotel & Kantor',
                'icon' => '🏨',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                'meta_title' => 'Fasad Roster Gedung Komersial, Hotel & Kantor | IndoRoster Tender Proyek',
                'meta_description' => 'Suplai roster beton skala ribuan pcs untuk fasad gedung komersial, hotel, apartemen, dan perkantoran. Spek teknis arsitektur dan legalitas lengkap.',
                'keywords' => 'roster fasad gedung, secondary skin hotel, roster beton komersial, supplier tender roster',
                'headline' => 'Secondary Skin Gedung Komersial: Solusi Arsitektur Hijau & Efisiensi Energi Skala Besar',
                'intro' => 'Proyek gedung bertingkat, hotel bintang, resort tropis, dan kantor modern memerlukan material fasad yang tidak hanya spektakuler secara visual, namun juga memenuhi standar bangunan hijau (Green Building Concept). Roster beton cetak padat IndoRoster dirancang untuk menahan beban terpaan angin tinggi (wind pressure) serta meredam penyerapan panas dinding kaca luar secara signifikan.',
                'deep_narrative' => [
                    'title' => 'Solusi Green Architecture & Penghematan Biaya Operasional Gedung',
                    'p1' => 'Pada gedung komersial, biaya operasional terbesar berasal dari pendinginan ruangan (HVAC/AC). Pemasangan secondary skin roster beton bertindak sebagai tabir peneduh termal pasif yang mengurangi beban panas hingga 35–45%, memperpanjang masa pakai sistem chiller, dan membantu gedung memperoleh sertifikasi efisiensi energi hijau.',
                    'p2' => 'Kami siap mendampingi konsultan arsitek dan kontraktor utama (Main Contractor) dalam penyusunan RKS, penyediaan sampel uji laboratorium, serta dokumen administrasi tender lengkap.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm (Toleransi presisi ± 1 mm)',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Mutu Tinggi',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Custom Shade',
                ],
                'installation_guide' => [
                    'title' => 'Pemasangan Modul Roster Fasad Gedung',
                    'steps' => [
                        ['step' => '1. Subframe Baja Struktur', 'desc' => 'Dinding roster dipasang pada subframe baja struktural yang diperhitungkan terhadap gaya gempa dan beban angin.'],
                    ],
                ],
                'design_tips' => [
                    'Kombinasikan motif MMC, Petir, atau Arrow untuk fasad gedung skala monumental.',
                ],
                'benefits' => [
                    ['title' => 'Mendukung Konsep Green Building', 'desc' => 'Mengurangi radiasi panas gedung secara pasif dan menurunkan beban AC komersial.'],
                    ['title' => 'Kapasitas Suplai Skala Besar', 'desc' => 'Pabrik siap memproduksi hingga puluhan ribu pcs dengan batch warna konsisten.'],
                    ['title' => 'Dukungan Administrasi Tender Lengkap', 'desc' => 'Tersedia dokumen legalitas perusahaan, faktur pajak, dan surat keterangan pabrikasi.'],
                ],
                'motifs' => ['MMC', 'Petir', 'Arrow', 'Nako Sipit'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah IndoRoster menyediakan sampel motif untuk approval arsitek?', 'a' => 'Ya, kami siap mengirimkan paket mock-up sampel kepingan roster langsung ke kantor arsitek atau direksi keet proyek Anda.'],
                ],
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'slug' => 'interior-cafe',
                'title' => 'Interior Bar & Backdrop Cafe',
                'subtitle' => 'Aksen meja kasir, bar counter, dan background photo booth bernuansa industrial.',
                'badge' => '🍸 Interior Bar & Backdrop',
                'icon' => '🍸',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
                'meta_title' => 'Roster Beton Meja Bar & Backdrop Interior Cafe | IndoRoster',
                'meta_description' => 'Aplikasi roster beton untuk meja barista bar counter, backdrop kasir, dan photobooth cafe resto. Tampilan artistik modern harga pabrik.',
                'keywords' => 'meja bar roster, backdrop cafe roster, meja barista roster beton, interior cafe roster',
                'headline' => 'Meja Bar & Backdrop Cafe Roster: Aksen Interior Unik yang Menarik Perhatian Pengunjung',
                'intro' => 'Selain fasad luar, interior area meja bar barista dan backdrop kasir adalah pusat visual (centre of attention) dalam sebuah cafe. Menggunakan modul roster beton sebagai panel penutup meja bar atau dinding backdrop memberikan kesan arsitektur modern yang berani, tekstural, dan sangat menarik ketika disinari lampu spot hangat.',
                'deep_narrative' => [
                    'title' => 'Sentuhan Tekstur Geometris pada Interior Hospitality',
                    'p1' => 'Permukaan meja bar dari semen cor polos atau kayu seringkali terlihat monoton. Menggabungkan roster beton dengan top table kayu solid atau marmer menciptakan kontras tekstur yang sangat kaya dan mewah.',
                    'p2' => 'Modul roster juga memungkinkan pemasangan lampu indirect LED di bagian dalam meja bar, menghasilkan pendaran cahaya dari sela-sela lubang roster yang memukau di malam hari.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Mutu Tinggi',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Bersih, Terakota',
                ],
                'installation_guide' => [
                    'title' => 'Tips Pemasangan Meja Bar Roster',
                    'steps' => [
                        ['step' => '1. Frame Besi / Kayu Penopang Top Table', 'desc' => 'Pastikan top table bar ditopang oleh rangka struktur tersendiri dan tidak sepenuhnya bertumpu pada dinding roster.'],
                        ['step' => '2. Finishing Debu (Anti-Dust Sealer)', 'desc' => 'Wajib aplikasikan interior clear sealer agar abu semen tidak menempel pada pakaian pengunjung.'],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif Batman, MMC, atau JaboL untuk meja bar berkarakter kuat.',
                ],
                'benefits' => [
                    ['title' => 'Karakter Interior Berani & Mewah', 'desc' => 'Memberikan tekstur visual kuat pada meja barista dan area kasir cafe.'],
                    ['title' => 'Efek Pencahayaan Glowing', 'desc' => 'Bisa dipasangi lampu LED di balik modul roster untuk efek pendaran cahaya malam hari.'],
                ],
                'motifs' => ['Batman', 'MMC', 'JaboL'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah roster untuk meja bar aman dan tidak berdebu?', 'a' => 'Sangat aman jika setelah dipasang dilapisi cairan dust-proof clear sealer atau vernis batu alam.'],
                ],
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($applications as $app) {
            ApplicationPage::updateOrCreate(
                ['slug' => $app['slug']],
                $app
            );
        }
    }
}
