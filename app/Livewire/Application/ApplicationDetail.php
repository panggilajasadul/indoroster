<?php

namespace App\Livewire\Application;

use App\Models\ApplicationPage;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\GalleryMedia;
use App\Models\Product;
use App\Models\SeoLocation;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationDetail extends Component
{
    use WithPagination;

    public string $slug;

    public array $application;

    public string $search = '';

    public string $selectedCategory = '';

    public function mount(string $slug)
    {
        $this->slug = strtolower(trim($slug));

        try {
            $dbApp = ApplicationPage::where('slug', $this->slug)
                ->where('is_active', true)
                ->first();

            if ($dbApp) {
                $this->application = [
                    'title' => $dbApp->title,
                    'meta_title' => $dbApp->meta_title ?: ($dbApp->title.' | Pabrik IndoRoster'),
                    'meta_description' => $dbApp->meta_description ?: $dbApp->subtitle,
                    'keywords' => $dbApp->keywords,
                    'headline' => $dbApp->headline,
                    'badge' => $dbApp->badge,
                    'intro' => $dbApp->intro,
                    'deep_narrative' => $dbApp->deep_narrative ?? [],
                    'specs' => $dbApp->specs ?? [],
                    'installation_guide' => $dbApp->installation_guide ?? [],
                    'design_tips' => $dbApp->design_tips ?? [],
                    'benefits' => $dbApp->benefits ?? [],
                    'motifs' => $dbApp->motifs ?? [],
                    'gallery_images' => $dbApp->gallery_images ?? [],
                    'gallery_ids' => $dbApp->gallery_ids ?? [],
                    'faqs' => $dbApp->faqs ?? [],
                ];

                return;
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        $applications = [
            'pagar-rumah' => [
                'title' => 'Roster Beton Pagar Minimalis Modern',
                'meta_title' => 'Jual Roster Beton Pagar Rumah Minimalis Modern | Pabrik IndoRoster',
                'meta_description' => 'Inspirasi & rekomendasi motif roster beton untuk dinding pagar rumah minimalis modern. Kokoh cetak padat presisi plat baja, sirkulasi udara lancar, privasi terjaga, harga langsung pabrik Plered.',
                'headline' => 'Desain Pagar Rumah Minimalis Modern dengan Roster Beton Tumbuk Padat',
                'badge' => '🏡 Pagar & Pembatas Kavling',
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
                        [
                            'step' => '1. Pondasi Sloof Beton Bertulang',
                            'desc' => 'Pastikan dinding pagar berdiri di atas sloof beton bertulang dengan kedalaman pondasi minimal 30–40 cm agar tidak mengalami penurunan tanah di kemudian hari.',
                        ],
                        [
                            'step' => '2. Kolom Praktis & Pembesian Pengaku',
                            'desc' => 'Pasang tiang kolom cor praktis besi bertulang (Ø 8–10 mm) setiap bentang horizontal 1.5 – 2.0 meter, serta selipkan besi begel penguat di sela nat horizontal setiap 3–4 susun keping roster.',
                        ],
                        [
                            'step' => '3. Adukan Semen Instan / Mortar Presisi',
                            'desc' => 'Gunakan semen mortar perekat berkualitas dengan ketebalan nat siar 8–10 mm untuk menjaga kelurusan garis nat vertikal dan horizontal dinding pagar.',
                        ],
                        [
                            'step' => '4. Lapisan Pelindung (Clear Coating Water-Repellent)',
                            'desc' => 'Setelah adukan semen kering sempurna (3–7 hari), aplikasikan cat pelapis batu alam / clear coating anti air (water-repellent) untuk mencegah lumut, jamur, dan noda cipratan tanah saat hujan.',
                        ],
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
            ],
            'fasad-rumah' => [
                'title' => 'Fasad Dinding Roster Rumah Tropis',
                'meta_title' => 'Fasad Rumah Roster Beton Minimalis Modern | Secondary Skin IndoRoster',
                'meta_description' => 'Koleksi roster beton untuk fasad dinding rumah minimalis tropis. Menurunkan suhu ruangan, hemat AC, meredam silau matahari, harga langsung pabrik Plered.',
                'headline' => 'Fasad Rumah Tropis Modern: Secondary Skin Estetik, Meredam Panas & Hemat Listrik AC',
                'badge' => '🏛️ Fasad & Secondary Skin',
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
                        [
                            'step' => '1. Struktur Balok & Cantilever Penopang',
                            'desc' => 'Dinding secondary skin roster di lantai 2 atau 3 harus bertumpu pada balok beton struktur cantilever atau frame baja IWF/UNP yang diangkur kuat ke struktur utama gedung.',
                        ],
                        [
                            'step' => '2. Pembesian Begel Tulang Horizontal & Vertikal',
                            'desc' => 'Setiap 2–3 susun keping roster wajib diisi besi tulangan horizontal dan dikaitkan pada kolom samping agar dinding fasad kokoh menahan beban angin (wind-load).',
                        ],
                        [
                            'step' => '3. Ruang Perawatan (Maintenance Gap)',
                            'desc' => 'Sisakan jarak 30–60 cm antara dinding roster dan kaca jendela untuk mempermudah pembersihan kaca dan sirkulasi pembuangan panas.',
                        ],
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
            ],
            'ventilasi-dinding' => [
                'title' => 'Ventilasi Dinding & Lubang Angin Roster',
                'meta_title' => 'Roster Ventilasi Udara Dinding & Lubang Angin Rumah | IndoRoster',
                'meta_description' => 'Jual roster beton untuk ventilasi udara dinding rumah, dapur, kamar mandi, dan gudang. Sirkulasi lancar bebas pengap, cetak padat presisi harga pabrik.',
                'headline' => 'Ventilasi Dinding Alami: Udara Bersih, Bebas Lembap & Rumah Sejuk Tanpa Pengap',
                'badge' => '💨 Sirkulasi Udara Alami',
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
                        [
                            'step' => '1. Pasang di Atas Ketinggian Kepala',
                            'desc' => 'Udara panas memiliki massa jenis lebih ringan dan selalu bergerak ke atas. Tempatkan modul ventilasi roster di area atas dinding (ketinggian > 2 meter) untuk membuang udara panas secara efisien.',
                        ],
                        [
                            'step' => '2. Pemasangan Kawat Nyamuk Interior',
                            'desc' => 'Untuk mencegah serangga atau nyamuk masuk, Anda dapat memasang frame kawat nyamuk aluminium atau magnetik di sisi dalam dinding roster yang mudah dilepas saat dibersihkan.',
                        ],
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
            ],
            'partisi-ruangan' => [
                'title' => 'Partisi Ruangan & Sekat Interior Roster',
                'meta_title' => 'Sekat Partisi Ruangan Roster Beton Minimalis Interior | IndoRoster',
                'meta_description' => 'Ide sekat partisi ruangan roster beton untuk ruang tamu, ruang keluarga, dan dapur. Tampilan estetik industrial modern langsung dari produsen.',
                'headline' => 'Sekat Partisi Ruangan Interior: Estetik, Elegan & Menjaga Keterbukaan Visual Ruang',
                'badge' => '🚪 Partisi & Sekat Interior',
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
                        [
                            'step' => '1. Letakkan di Atas Balok / Lantai Kokoh',
                            'desc' => 'Pastikan partisi berdiri di atas lantai yang solid atau balok struktur. Pasang bracket siku penguat besi di sisi dinding samping dan lantai.',
                        ],
                        [
                            'step' => '2. Gunakan Semen Perekat Mortar Tipis',
                            'desc' => 'Untuk interior, gunakan adukan semen perekat instan tipis (neat mortar) agar garis nat terlihat sangat rapi dan presisi.',
                        ],
                        [
                            'step' => '3. Finishing Interior Sealer',
                            'desc' => 'Aplikasikan dust-proof coating / interior clear sealer agar permukaan beton bebas debu dan mudah dilap saat dibersihkan.',
                        ],
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
            ],
            'void-tangga' => [
                'title' => 'Dinding Void Tangga & Skylight Roster',
                'meta_title' => 'Dinding Void Tangga Roster Beton Minimalis | IndoRoster Pabrik',
                'meta_description' => 'Aplikasi roster beton pada dinding void tangga dan skylight. Meneruskan pencahayaan alami dan sirkulasi vertikal, pesan harga pabrik bergaransi.',
                'headline' => 'Dinding Void Tangga Roster: Maksimalkan Cahaya Alami & Efek Sirkulasi Cerobong Vertikal',
                'badge' => '🪜 Void Tangga & Skylight',
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
                        [
                            'step' => '1. Gunakan Perancah (Scaffolding) Kokoh',
                            'desc' => 'Untuk dinding void tangga dengan ketinggian 4–6 meter, pastikan pemasangan dilakukan bertahap menggunakan scaffolding yang aman dan stabil.',
                        ],
                        [
                            'step' => '2. Balok Lintel / Pengaku Horizontal',
                            'desc' => 'Pasang balok cor pengaku lintel setiap ketinggian 2.5 – 3 meter bentang dinding agar struktur roster tidak mengalami lendutan.',
                        ],
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
            ],
            'fasad-cafe' => [
                'title' => 'Fasad Cafe & Restoran Industrial',
                'meta_title' => 'Fasad Roster Beton Cafe & Resto Industrial | Spot Instagramable IndoRoster',
                'meta_description' => 'Supplier roster beton untuk fasad coffee shop, cafe, dan resto kekinian bernuansa industrial. Kualitas cetak padat presisi harga tangan pertama pabrik.',
                'headline' => 'Fasad Cafe & Resto Kekinian: Ikonik, Instagramable, Sejuk & Hemat Biaya Konstruksi',
                'badge' => '☕ Cafe & Resto Komersial',
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
                        [
                            'step' => '1. Kombinasi Neon Signage',
                            'desc' => 'Dinding roster sangat serasi dipadukan dengan neon flex sign bertuliskan logo atau nama cafe Anda.',
                        ],
                        [
                            'step' => '2. Pasang Lampu Hidden LED',
                            'desc' => 'Tanam strip LED di bawah modul roster untuk menghasilkan efek glowing yang memikat mata pengunjung di malam hari.',
                        ],
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
            ],
            'ruko' => [
                'title' => 'Fasad Ruko & Gedung Komersial',
                'meta_title' => 'Fasad Ruko Roster Beton Minimalis Modern | Renovasi Ruko IndoRoster',
                'meta_description' => 'Renovasi fasad ruko dengan roster beton minimalis. Tingkatkan nilai jual dan sewa ruko komersial dengan secondary skin modern harga pabrik.',
                'headline' => 'Renovasi Fasad Ruko Komersial: Modernisasi Tampilan, Nilai Sewa & Jual Naik 3x Lipat',
                'badge' => '🏢 Fasad Ruko Komersial',
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
                        [
                            'step' => '1. Bracket Baja IWF / Hollow Tebal',
                            'desc' => 'Gunakan struktur rangka baja penopang yang di-dynabolt kokoh ke balok lantai balkon ruko.',
                        ],
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
            ],
            'perumahan-cluster' => [
                'title' => 'Gerbang & Fasad Klaster Perumahan',
                'meta_title' => 'Pengadaan Roster Beton Klaster Perumahan & Developer | IndoRoster',
                'meta_description' => 'Suplai roster beton untuk gerbang cluster, fasad perumahan developer, dan dinding pembatas kavling. Kontrak harga pabrik dan suplai bertahap.',
                'headline' => 'Pengadaan Roster Developer: Gerbang Utama Ikonik & Fasad Klaster Perumahan',
                'badge' => '🏘️ Developer & Klaster Perumahan',
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
                        [
                            'step' => '1. Pengiriman Bertahap Sesuai SPK',
                            'desc' => 'Pengiriman armada truk pabrik disesuaikan dengan tahapan progres bangun (500 – 2.000 pcs per ritase).',
                        ],
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
            ],
            'gedung-komersial' => [
                'title' => 'Fasad Gedung, Hotel & Kantor',
                'meta_title' => 'Roster Beton Fasad Gedung, Hotel & Perkantoran | IndoRoster Proyek',
                'meta_description' => 'Suplai roster beton partai besar untuk fasad gedung komersial, hotel resort, dan gedung perkantoran. Presisi siku 90 derajat dan dokumen resmi.',
                'headline' => 'Fasad Gedung Arsitektural: Megah, Fungsional, Berskala Ribuan Pcs & Mendukung Green Building',
                'badge' => '🏨 Gedung, Hotel & Perkantoran',
                'intro' => 'Aplikasi roster beton skala ribuan pcs pada fasad gedung perkantoran, hotel resort, universitas, dan rumah sakit menghadirkan solusi pendinginan pasif alami yang ramah lingkungan (Green Architecture). Roster beton IndoRoster dibuat dari cetakan plat baja dengan sudut siku 90 derajat sempurna untuk menjamin kelurusan garis nat dinding tinggi bertingkat dengan ketahanan puluhan tahun.',
                'deep_narrative' => [
                    'title' => 'Mendukung Sertifikasi Green Building & Pengurangan Beban AC Gedung',
                    'p1' => 'Gedung komersial modern dituntut memiliki efisiensi konsumsi energi yang tinggi. Secondary skin roster beton menyaring radiasi termal matahari sebelum masuk ke kaca ruangan kantor, mereduksi kebutuhan daya chiller/AC sentral gedung hingga 30%, serta mendukung pencapaian poin sertifikasi bangunan hijau (Green Building Certification).',
                    'p2' => 'Dengan kapasitas produksi pabrik 10.000 pcs per bulan, IndoRoster siap memenuhi jadwal suplai tender kontraktor utama (Main Contractor BUMN/Swasta) dengan kelengkapan dokumen teknis, sertifikat uji karakteristik, surat jalan, dan faktur pajak resmi.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm (Toleransi presisi < 1 mm)',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Khusus',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Semen, Merah Bata',
                ],
                'installation_guide' => [
                    'title' => 'Spesifikasi Pemasangan Dinding Gedung Tinggi',
                    'steps' => [
                        [
                            'step' => '1. Struktur Frame Baja & Bracket Bersertifikat',
                            'desc' => 'Dinding secondary skin fasad gedung dipasang pada frame struktur baja dengan angkur dynabolt Hilti/Fischer yang teruji beban geser.',
                        ],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif MMC atau Petir untuk fasad gedung skala masif guna menghasilkan karakter arsitektur monumental yang kuat.',
                ],
                'benefits' => [
                    ['title' => 'Mendukung Sertifikasi Green Building', 'desc' => 'Mengurangi kebutuhan energi operasional gedung melalui pencahayaan dan sirkulasi alami.'],
                    ['title' => 'Kerapian Garis Nat Presisi Tinggi', 'desc' => 'Modul 20x20 cm presisi plat baja menjaga kelurusan nat vertikal dan horizontal dinding tinggi.'],
                ],
                'motifs' => ['MMC', 'Petir', 'Arrow', 'Nako Sipit'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah bisa menerbitkan dokumen sertifikasi uji mutu untuk konsultan pengawas proyek gedung?', 'a' => 'Bisa. Kami menyediakan dokumen spesifikasi teknis dan hasil uji karakteristik material untuk kelengkapan administrasi konsultan pengawas.'],
                ],
            ],
            'interior-cafe' => [
                'title' => 'Interior Bar & Backdrop Cafe',
                'meta_title' => 'Roster Beton Interior Bar, Meja Kasir & Backdrop Cafe | IndoRoster',
                'meta_description' => 'Inspirasi roster beton untuk meja bar cafe, counter kasir, dan backdrop estetik. Tampilan semen ekspos natural langsung dari produsen.',
                'headline' => 'Interior Bar & Backdrop Cafe: Sentuhan Semen Ekspos Rustic-Modern yang Hangat',
                'badge' => '🍸 Interior Bar & Backdrop',
                'intro' => 'Roster beton tidak hanya untuk aplikasi luar ruangan. Di dalam ruangan cafe, coffee shop, resto, maupun lobby hotel, modul roster beton sangat populer digunakan sebagai meja bar counter kasir, backdrop panggung mini, sekat area VIP, hingga pembatas booth pengunjung. Tekstur abu batu murni semen ekspos memberikan nuansa rustic-industrial yang estetik, fotogenik, dan sangat diminati pelanggan.',
                'deep_narrative' => [
                    'title' => 'Aksen Bar Counter Kokoh dengan Efek Hidden Lighting',
                    'p1' => 'Meja bar yang terbuat dari susunan roster beton tahan terhadap benturan kaki kursi dan gesekan barang, jauh lebih kokoh dibanding material kayu lapis/HPL. Keberadaan rongga roster memungkinkan penanaman lampu strip LED tersembunyi yang memancarkan cahaya lembut saat malam hari, menciptakan atmosfer santai (chill) yang mewah.',
                    'p2' => 'Permukaan beton cukup diberi clear coating anti noda agar mudah dibersihkan bila terkena tumpahan kopi atau minuman sirup.',
                ],
                'specs' => [
                    'dimensi' => '20 × 20 × 10 cm',
                    'bobot' => '3.8 – 4.2 kg / keping',
                    'kebutuhan_luas' => '25 keping per 1 meter persegi (m²)',
                    'komposisi' => 'Pasir Abu Batu Murni Plered + Semen Portland',
                    'metode_produksi' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi',
                    'pilihan_warna' => 'Abu Semen Natural, Putih Bersih, Terakota',
                ],
                'installation_guide' => [
                    'title' => 'Pemasangan Meja Bar Roster',
                    'steps' => [
                        [
                            'step' => '1. Top Table Kayu Solid / Granit',
                            'desc' => 'Dinding roster berfungsi sebagai kaki meja penopang yang sangat kokoh untuk top table papan kayu trembesi solid atau batu granit.',
                        ],
                    ],
                ],
                'design_tips' => [
                    'Gunakan motif Batman, MMC, atau JaboL untuk variasi motif yang unik pada backdrop kasir cafe.',
                ],
                'benefits' => [
                    ['title' => 'Aksen Bar Counter Kokoh', 'desc' => 'Tahan goresan dan benturan kursi bar berkat kepadatan beton tumbuk padat baja.'],
                    ['title' => 'Efek Pencahayaan Hidden LED', 'desc' => 'Dapat disisipi lampu strip LED di balik lubang roster untuk efek pencahayaan dramatis saat malam hari.'],
                ],
                'motifs' => ['Batman', 'MMC', 'JaboL', 'Petir'],
                'gallery_images' => [
                    'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
                ],
                'faqs' => [
                    ['q' => 'Apakah roster beton untuk meja bar cafe perlu di-coating?', 'a' => 'Sangat disarankan mengaplikasikan clear coating batu alam anti debu agar permukaan mudah dilap saat terkena tumpahan cairan minuman.'],
                ],
            ],
        ];

        if (! isset($applications[$this->slug])) {
            abort(404);
        }

        $this->application = $applications[$this->slug];
    }

    public function updatedSearch()
    {
        $this->resetPage('explorerPage');
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage('explorerPage');
    }

    public function getRecommendedProductsProperty()
    {
        $motifNames = $this->application['motifs'] ?? [];

        return Product::where('is_active', true)
            ->where(function ($q) use ($motifNames) {
                foreach ($motifNames as $name) {
                    $q->orWhere('name', 'like', "%{$name}%");
                }
            })
            ->with(['media', 'variants', 'category'])
            ->take(8)
            ->get();
    }

    public function render()
    {
        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $waText = "Halo Admin IndoRoster, saya tertarik dengan Roster Beton untuk aplikasi: {$this->application['title']}. Mohon rekomendasi motif terbaik, estimasi harga pabrik, dan info pengiriman armada ke lokasi saya.";
        $waUrl = "https://wa.me/{$waNumber}?text=".urlencode($waText);

        // Product Explorer Query
        $explorerQuery = Product::where('is_active', true)
            ->with(['media', 'variants', 'category']);

        if (! empty($this->search)) {
            $explorerQuery->where('name', 'like', '%'.$this->search.'%');
        }

        if (! empty($this->selectedCategory)) {
            $explorerQuery->where('category_id', $this->selectedCategory);
        }

        $explorerProducts = $explorerQuery->orderBy('total_sold', 'desc')->paginate(12, ['*'], 'explorerPage');
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        // Top Location Hubs for Silo Linking
        $topLocations = SeoLocation::where('seo_enabled', true)
            ->orderBy('priority', 'asc')
            ->take(12)
            ->get();

        // Fetch Galleries: prioritize explicitly selected gallery_ids, fallback to matching category
        $selectedGalleries = collect();
        $galleryIds = $this->application['gallery_ids'] ?? [];

        if (! empty($galleryIds)) {
            $selectedGalleries = Gallery::with(['media', 'product.variants', 'product.media'])
                ->whereIn('id', $galleryIds)
                ->where('is_active', true)
                ->get()
                ->sortBy(function ($g) use ($galleryIds) {
                    return array_search($g->id, $galleryIds);
                });
        }

        if ($selectedGalleries->isEmpty()) {
            $catMap = [
                'pagar-rumah' => 'pagar',
                'fasad-rumah' => 'fasad',
                'ventilasi-dinding' => 'dapur',
                'partisi-ruangan' => 'interior',
                'void-tangga' => 'interior',
                'fasad-cafe' => 'ruang-tamu',
                'ruko' => 'fasad',
                'perumahan-cluster' => 'pagar',
                'gedung-komersial' => 'fasad',
                'interior-cafe' => 'interior',
            ];
            $matchedCat = $catMap[$this->slug] ?? null;
            if ($matchedCat) {
                $selectedGalleries = Gallery::with(['media', 'product.variants', 'product.media'])
                    ->where('category', $matchedCat)
                    ->where('is_active', true)
                    ->orderBy('sort_order', 'asc')
                    ->limit(6)
                    ->get();
            }
        }

        // Real Project Gallery Media Fallback
        $randomGalleryMedia = GalleryMedia::with('gallery')
            ->where('media_type', 'image')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Build Structured Lightbox Items for Theatre Modal (Foto 2 & 3 upgrade)
        $lightboxItems = [];
        if ($selectedGalleries->isNotEmpty()) {
            foreach ($selectedGalleries as $idx => $gal) {
                $firstMedia = $gal->media->first();
                $photoUrl = $firstMedia?->formatted_url ?? $firstMedia?->media_url ?: 'https://images.pexels.com/photos/7946866/pexels-photo-7946866.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940';
                $prod = $gal->product;
                $lightboxItems[] = [
                    'id' => $gal->id,
                    'image' => $photoUrl,
                    'title' => $gal->title,
                    'description' => $firstMedia?->caption ?: ($gal->description ?: 'Dokumentasi inspirasi nyata penerapan roster beton arsitektural cetak padat presisi plat baja IndoRoster.'),
                    'location' => $gal->location ?: 'Proyek Hunian Modern',
                    'category' => strtoupper($gal->category ?: 'Arsitektur'),
                    'has_product' => $prod ? true : false,
                    'product_name' => $prod?->name,
                    'product_price' => $prod?->formatted_price_range,
                    'product_image' => $prod?->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'),
                    'product_url' => $prod ? route('product.detail', $prod->slug) : null,
                    'wa_link' => $prod
                        ? "https://wa.me/{$waNumber}?text=".urlencode("Halo IndoRoster, saya melihat foto inspirasi {$gal->title} dan tertarik dengan motif {$prod->name}. Apakah stok tersedia dan bisa dikirim ke lokasi saya?")
                        : $waUrl,
                ];
            }
        } elseif (! empty($this->application['gallery_images'])) {
            foreach ($this->application['gallery_images'] as $idx => $gImg) {
                $photoUrl = is_array($gImg) ? ($gImg['image_url'] ?? '') : $gImg;
                if (! str_starts_with($photoUrl, 'http')) {
                    $photoUrl = asset('storage/'.$photoUrl);
                }
                $lightboxItems[] = [
                    'id' => $idx,
                    'image' => $photoUrl,
                    'title' => 'Inspirasi '.$this->application['title'].' #'.($idx + 1),
                    'description' => 'Dokumentasi pengaplikasian nyata roster beton arsitektural cetak tumbuk padat plat baja presisi sentra pengrajin Plered Purwakarta.',
                    'location' => 'Proyek Hunian Modern',
                    'category' => strtoupper($this->application['badge'] ?? 'Aplikasi'),
                    'has_product' => false,
                    'product_name' => null,
                    'product_price' => null,
                    'product_image' => null,
                    'product_url' => null,
                    'wa_link' => "https://wa.me/{$waNumber}?text=".urlencode("Halo IndoRoster, saya tertarik dengan foto inspirasi {$this->application['title']} #".($idx + 1).'. Mohon info motif rekomendasi dan harga.'),
                ];
            }
        } elseif ($randomGalleryMedia->isNotEmpty()) {
            foreach ($randomGalleryMedia as $idx => $media) {
                $g = $media->gallery;
                $lightboxItems[] = [
                    'id' => $media->id,
                    'image' => $media->formatted_url,
                    'title' => $g?->title ?: 'Inspirasi Roster Beton #'.($idx + 1),
                    'description' => $media->caption ?: ($g?->description ?: 'Dokumentasi terpasang roster beton modern.'),
                    'location' => $g?->location ?: 'Indonesia',
                    'category' => strtoupper($g?->category ?: 'Inspirasi'),
                    'has_product' => false,
                    'product_name' => null,
                    'product_price' => null,
                    'product_image' => null,
                    'product_url' => null,
                    'wa_link' => $waUrl,
                ];
            }
        }

        return view('livewire.application.application-detail', [
            'application' => $this->application,
            'selectedGalleries' => $selectedGalleries,
            'recommendedProducts' => $this->recommendedProducts,
            'explorerProducts' => $explorerProducts,
            'categories' => $categories,
            'topLocations' => $topLocations,
            'randomGalleryMedia' => $randomGalleryMedia,
            'lightboxItems' => $lightboxItems,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $this->application['meta_title'],
            'description' => $this->application['meta_description'],
            'canonicalOverride' => route('application.detail', $this->slug),
            'keywords' => 'roster '.$this->slug.', aplikasi roster beton, loster minimalis, '.$this->application['title'].', pabrik roster purwakarta',
        ]);
    }
}
