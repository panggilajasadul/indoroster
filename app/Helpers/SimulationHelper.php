<?php

namespace App\Helpers;

use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Like;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SimulationHelper
{
    // List 200+ Nama Pembeli (Perorangan, Kontraktor, Cafe/Resto, Studio Arsitek, & Developer)
    protected static $indonesianNames = [
        // 1. Perorangan & Pemilik Rumah (100 Nama)
        'Bpk. Hendra Saputra', 'Ibu Rina Maharani', 'dr. Arief Budiman', 'Dewi Anggraeni, S.T.', 'Fajar Hidayat',
        'Bpk. Budi Santoso', 'Ibu Siti Nurhaliza', 'Agus Pramono', 'Lina Wijaya', 'Rudi Hartono',
        'Maya Indah Puspita', 'Putri Ayu Lestari', 'Ir. Bambang Wicaksono', 'Tommy Halim', 'Deni Kurniawan',
        'H. Yusuf Rahman', 'Hj. Ratna Sari', 'Ahmad Fauzi', 'Nadia Permata', 'Joko Susilo',
        'Siska Amelia', 'Andi Wijaya Kusuma', 'Dedi Heryanto', 'Euis Kartini', 'Fandi Ahmad',
        'Hadi Pranoto', 'Ika Kartika', 'Junaidi Saleh', 'Maman Suherman', 'Rendy Pandugo',
        'Taufik Hidayat', 'Aditya Pratama', 'Citra Kirana', 'Dimas Anggara', 'Hesti Purwadinata',
        'Indra Herlambang', 'Reza Rahadian', 'Dian Sastrowardoyo', 'Arifin Putra', 'Nadine Chandrawinata',
        'Chicco Jerikho', 'Rio Dewanto', 'Tara Basro', 'Vino G. Bastian', 'Marsha Timothy',
        'Bpk. Gunawan Wibisono', 'Ibu Yuliana Salim', 'drg. Felicia Tan', 'Bpk. Irawan Setiadi', 'Ibu Martha Tilaar',
        'Bpk. Suryadi Halim', 'Ibu Veronica Ong', 'Bpk. Eko Prasetyo', 'Ibu Wulandari', 'Bpk. Tono Sudirgo',
        'Bpk. Heru Cahyono', 'Ibu Sri Wahyuni', 'Bpk. Donny Alamsyah', 'Ibu Maya Safitri', 'Bpk. Anton Hartono',
        'Bpk. Lukman Sardi', 'Ibu Cynthia Lamusu', 'Bpk. Surya Saputra', 'Ibu Nia Ramadhani', 'Bpk. Ananda Mikola',
        'Bpk. Denny Sumargo', 'Ibu Olivia Allan', 'Bpk. Raditya Dika', 'Ibu Anissa Aziza', 'Bpk. Atta Halilintar',
        'Bpk. Raffi Ahmad', 'Ibu Nagita Slavina', 'Bpk. Baim Wong', 'Ibu Paula Verhoeven', 'Bpk. Irwansyah',
        'Ibu Zaskia Sungkar', 'Bpk. Teuku Wisnu', 'Ibu Shireen Sungkar', 'Bpk. Dude Harlino', 'Ibu Alyssa Soebandono',
        'Bpk. Christian Sugiono', 'Ibu Titi Kamal', 'Bpk. Rio Febrian', 'Ibu Sabria Kono', 'Bpk. Judika Sihotang',
        'Ibu Duma Riris', 'Bpk. Darius Sinathrya', 'Ibu Donna Agnesia', 'Bpk. Gilang Dirga', 'Ibu Adiezty Fersa',
        'Bpk. Gading Marten', 'Ibu Gisella Anastasia', 'Bpk. Ruben Onsu', 'Ibu Sarwendah', 'Bpk. Wendy Cagur',
        'dr. Kevin Sanjaya', 'Ibu Valencia Tanoe', 'Ir. Wahyu Pratama', 'Bpk. Satria Dewantara', 'Ibu Clara Shinta',

        // 2. Kontraktor Bangunan & Aplikator (40 Entitas)
        'PT Sinar Bangun Mandiri', 'CV Graha Cipta Konstruksi', 'PT Karya Megah Nusantara',
        'CV Utama Jaya Contractor', 'PT Adhi Mitra Abadi', 'CV Mitra Bangun Sejahtera', 'PT Pilar Utama Konstruksi',
        'CV Duta Sarana Mandiri', 'PT Tri Tunggal Jaya Konstruksi', 'CV Berkah Jaya Abadi Kontraktor',
        'PT Mega Cipta Pratama', 'CV Sumber Bangunan Mandiri', 'PT Wijaya Kreasi Propertindo',
        'CV Multi Karya Perkasa', 'PT Bangun Griya Nusantara', 'CV Cipta Pesona Mandiri',
        'PT Artha Buana Konstruksi', 'CV Bina Sarana Kontraktor', 'PT Satria Jaya Gemilang',
        'CV Graha Mandiri Kontruksi', 'PT Pratama Mitra Sejati', 'CV Karya Bersama Mandiri',
        'PT Selaras Abadi Properti', 'CV Dinamika Cipta Mandiri', 'PT Pandawa Lima Konstruksi',
        'CV Sinar Mulia Konstruksi', 'PT Jaya Makmur Perkasa', 'CV Griya Arsitektur Mandiri',
        'PT Mitra Konstruksi Nusantara', 'CV Agung Sejahtera Mandiri', 'PT Mahakarya Bangun Persada',
        'CV Tri Sakti Kontraktor', 'PT Indo Bangun Cemerlang', 'CV Mandiri Perkasa Abadi',
        'PT Sentra Bangun Mandiri', 'CV Harmoni Cipta Graha', 'PT Prima Karya Megah',
        'CV Langgeng Jaya Kontraktor', 'PT Estetika Bangun Selaras', 'CV Karya Prima Mandiri',

        // 3. Studio Arsitektur & Interior Design (30 Entitas)
        'Studio Reka Ruang Arsitektur', 'Atelier 8 Design & Build', 'Kalam Studio Arsitek',
        'Dinding Estetika Interior', 'Ruang Tropis Architects', 'Garis Studio Bandung',
        'Nirmana Design Studio', 'Forma Arsitektur Jakarta', 'Lantai Dua Interior',
        'Bumi Hijau Architecture', 'Spasi Ruang Design', 'Monokrom Studio Interior',
        'Selasar Arsitektur', 'Bentuk Ruang Design Lab', 'Batu & Bata Design',
        'Studio Sekat Bali', 'Tropika Living Studio', 'Urban Spasi Architecture',
        'Habitat Design Studio', 'Arsitektur Nusantara Studio', 'Warna Ruang Interior',
        'Kreasi Fasad Modern Studio', 'Ruang Teduh Design', 'Arsitek Fasad Indonesia',
        'Studio Beton Minimalis', 'Line & Dot Architect', 'Rumah & Ruang Design',
        'Bambu & Bata Studio', 'Terracotta Concept Architecture', 'Kompak Living Interior',

        // 4. Cafe, Resto, Hotel & Komersial (20 Entitas)
        'Kopi Senja Terrace Cafe', 'The Foliage Coffee & Eatery', 'Dapur Nusantara Resto & Cafe',
        'Kopi Toko Djawa Cabang BSD', 'Tanamera Coffee Outlet', 'Anomali Coffee Shop Kemang',
        'Warung Kopi Klotok Cabang', 'Titik Temu Coffee Senopati', 'Work Coffee Indonesia',
        'Common Grounds Cafe PIK', 'Sudut Selatan Eatery', 'Ruang Kopi Roastery Dago',
        'Kopi Nako Official Project', 'Fore Coffee Store BSD', 'Tuku Kopi Tetangga',
        'Barber & Co Kemang', 'Salon & Spa Tirta Ayu', 'Boutique Hotel Lembang',
        'Villa Asri Sunset Puncak', 'The Greenhouse Coworking Hub',

        // 5. Developer Kawasan & Perumahan Besar (15 Entitas)
        'Summarecon Serpong (Proyek Klaster)', 'Summarecon Bekasi Development', 'Summarecon Bandung',
        'Sinarmas Land (BSD City Project)', 'CitraRaya Tangerang (Ciputra Group)', 'CitraIndah City Jonggol',
        'Podomoro Golf View Cimanggis', 'Agung Podomoro Park Bandung', 'Paramount Land Gading Serpong',
        'Lippo Cikarang Properti', 'Grand Wisata Bekasi (Sinarmas)', 'Kota Baru Parahyangan',
        'Sentul City Development', 'Pakuwon City Residence', 'Alam Sutera Property Development',
    ];

    // List username sosial media untuk Dummy Users
    protected static $indonesianUsernames = [
        'hendra_sap', 'rina_maharani', 'arief_bud', 'dewi_anggr', 'fajar_h',
        'budi_sant', 'siti_nur', 'agus_pram', 'lina_wj', 'rudi_hart',
        'maya_indah', 'putri_ayu99', 'bambang_w', 'tommy_halim', 'deni_kurnia',
        'yusuf_rahman', 'ratna_sari', 'ahmad_fauzi', 'nadia_permata', 'joko_susilo',
        'siska_amel', 'andi_wij', 'cici_paramida', 'dedi_hery', 'euis_k',
        'fandi_ahmad', 'gita_g', 'hadi_p', 'ika_kartika', 'junaidi_j',
        'kiki_amelia', 'lulu_t', 'maman_s', 'nana_mirdad', 'oki_setiana',
        'pipit_dian', 'qori_s', 'rendy_p', 'sari_nila', 'taufik_h',
        'adit_pratama', 'bella_cantik', 'citra_k', 'dimas_ang', 'elina_j',
        'febby_r', 'gading_m', 'hesti_p', 'indra_h', 'jessica_mila',
        'sinar_bangun_m', 'graha_cipta_konst', 'karya_megah_n', 'utama_jaya_c',
        'studio_reka_ruang', 'atelier8_design', 'kopi_senja_cafe', 'the_foliage_coffee',
        'summarecon_project', 'bsd_architect_group', 'podomoro_view', 'citraraya_home',
    ];

    // Template Komentar Video (TikTok Style)
    protected static $videoCommentTemplates = [
        // Pujian Estetik
        'Aesthetic banget sih pagarnya 😍 jd pengen pasang dirumah.',
        'Rapi banget pemasangannya min, keren pol! 👍',
        'Definisi rumah impian, roster bikin adem & mewah banget.',
        'Gila sih ini cakep parah finishingnya 🔥 minimalis banget.',
        'Suka banget sama motif yang ini, elegan dan mewah.',
        'Desain minimalis modern gini yang lagi hits sekarang.',
        'Masya Allah cakep bgt rumahnya pake roster ini 😍',
        'View-nya dapet bgt, berkelas finishingnya!',
        'Sirkulasi udaranya pasti seger bgt nih dalem rumah.',

        // Tanya Harga & Logistik
        'Min, ongkir ke Surabaya/Solo berapa ya?',
        'Bisa kirim ke Jogja ga kak? Butuh sekitar 150 pcs.',
        'Ada minimal pemesanan ga min untuk area Jabodetabek?',
        'Tipe roster yang dipasang di video ini namanya apa min?',
        'PM harga per pcs dong kak, mau order buat teras depan.',
        'Satu meter persegi butuh berapa pcs ya min untuk motif ini?',
        'Lokasi pabriknya dimana kak? Bisa ambil langsung ke lokasi?',
        'Ongkir ke Malang kena berapa ya min?',
        'Bisa sekalian jasa pasang ga min area Bandung?',

        // Tanya Spesifikasi & Kualitas
        'Betonnya tebel ga gampang gumpil ya min?',
        'Ini yang bahan dolomit putih atau abu-abu semen min?',
        'Ukuran roster nya berapa x berapa kak? Presisi ga?',
        'Kualitas betonnya pake K-berapa ya? Tahan banting ga?',
        'Anti tampias ga min kalau hujan badai angin kencang?',
        'Keliatan kokoh banget dibanding roster biasa di pasaran.',
        'Finishing-nya halus bgt ya, presisi.',

        // Kasual & Slang Medsos
        'Bismillah nular rejekinya biar bisa renov pager pake ini 🤲',
        'Definisi nabung dulu, beli rosternya kemudian 😂',
        'Tag suami ah biar peka wkwkw @suamiku',
        'Save dulu, tar pas bangun rumah auto order disini.',
        'Tetangga auto kepo nanya-nanya beli dimana sih wkwk.',
        'Keracunan fyp ini, besok langsung wa adminnya.',
        'Sirkulasi dapet, estetik dapet. Paket lengkap banget!',
        'Racun fyp pagi-pagi bikin pengen renov rumah 😂',

        // Sudah Pakai / Testimoni / Pemasangan
        'Saya udah pasang di teras depan min, rumah jd keliatan luas & seger bgt!',
        'Rosternya kokoh bgt, kemarin pesen 200 pcs ga ada yg pecah satupun. Top!',
        'Beneran memuaskan belanja disini. Hasil pasang di pagar rumah saya cakep bgt.',
        'Udah pake hampir 6 bulan, kena hujan panas tetep kokoh & ga lumutan 👍',
        'Recommended bgt! Kiriman cepet & adminnya super ramah ngebantu ngitung kebutuhan.',
        'Roster betonnya tebal & kokoh, kemarin order 350 pcs buat pagar depan rumah, mantap!',
        'Udah dipasang di mushola rumah saya kak, jadi adem & estetik bgt ventilasinya.',
        'Puas banget beli disini, dikirim langsung pake truk pabriknya jadi aman ga ada yg pecah.',
        'Bulan lalu baru beres pasang roster type ini buat sekat ruang tamu, mewah bgt hasilnya.',
        'Tukang bangunan saya aja muji rosternya presisi bgt, gampang dipasang katanya.',
        'Udah setahun pasang di area outdoor kena hujan panas mulu, masih kokoh & ga berlumut.',
        'Baru kemarin order lagi buat proyek ke-2, pelayanan adminnya selalu responsif & ramah.',
        'Pesenan nyampe tepat waktu, sopir armada pabriknya juga ramah & ngebantu nurunin barang.',
        'Awalnya ragu beli online, pas dateng barangnya beneran tebel & padat, puas bgt belanja disini.',
        'Roster minimalisnya beneran bikin fasad rumah saya keliatan beda sendiri di komplek.',
        'Udah nyoba beli di tempat lain tapi kualitas beton indoroster emang paling juara.',
        'Desain roster ini dipaduin sama tanaman hijau estetik parah sih, aslinya cakep bgt.',
        'Harga pabrik langsung emang paling bersahabat buat renovasi budget minimalis.',
        'Kemarin sempet kurang 15 pcs, langsung direspon cepat & dikirim susulan. Pelayanan top!',
    ];

    // List 50+ Lokasi Pengiriman Nyata
    protected static $locations = [
        // Jabodetabek & Banten
        'Summarecon Bekasi', 'BSD City Tangerang', 'PIK 2 Jakarta Utara', 'Gading Serpong', 'Bintaro Jaya Sektor 9',
        'Cibubur Junction Area', 'Kelapa Gading Jakarta Utara', 'Pondok Indah Jakarta Selatan', 'Kemang Jakarta Selatan',
        'Bekasi Barat', 'Bekasi Timur', 'Cikarang Selatan', 'Sentul City Bogor', 'Bogor Kota', 'Cibinong Bogor',
        'Depok Margonda', 'Sawangan Depok', 'Cinere Depok', 'Tangerang Kota', 'Karawaci Tangerang', 'Alam Sutera Serpong',
        'Ciputat Tangerang Selatan', 'Pamulang', 'Cilegon Banten', 'Serang Banten',

        // Jawa Barat
        'Bandung Kota (Dago)', 'Kota Baru Parahyangan Padalarang', 'Buahbatu Bandung', 'Cimahi', 'Purwakarta (Plered)',
        'Karawang Barat', 'Karawang Timur', 'Cirebon Kota', 'Kuningan Jawa Barat', 'Sukabumi Kota', 'Cianjur', 'Garut Kota',
        'Tasikmalaya', 'Sumedang',

        // Jawa Tengah & DIY
        'Semarang Barat', 'Semarang Kota (Candi)', 'Solo (Surakarta)', 'Yogyakarta (Sleman)', 'Bantul Yogyakarta',
        'Magelang', 'Pekalongan', 'Tegal Kota', 'Purwokerto', 'Kudus',

        // Jawa Timur & Bali
        'Surabaya Barat (Pakuwon)', 'Surabaya Timur', 'Malang Kota', 'Batu Malang', 'Sidoarjo Kota', 'Gresik',
        'Denpasar Bali', 'Canggu Bali', 'Ubud Bali', 'Sanur Bali',

        // Luar Pulau
        'Bandar Lampung', 'Palembang Kota', 'Medan Kota', 'Pekanbaru', 'Balikpapan', 'Makassar Kota',
    ];

    // Template Komentar Foto (Instagram Style)
    protected static $photoCommentTemplates = [
        // Pujian Estetik
        'Cantik banget fasad-nya! Perpaduan warna semen & rosternya pas.',
        'Aesthetic parah! Detail minimalisnya dapet banget 😍',
        'Inspirasi banget nih buat renovasi rumah tipe 36.',
        'Kombinasi industrial & scandinavian-nya kece parah 🔥',
        'Suka sekali dengan konsep ventilasi roster seperti ini.',
        'Rapi, bersih, dan sangat berkelas layoutnya.',
        'Ini pake tipe roster yang mana ya? Bagus bgt.',
        'Spot foto instagramable banget nih di rumah sendiri.',
        'Modern tapi tetap homey, suka sekali konsepnya.',

        // Tanya Harga & Logistik
        'Bisa kirim ke Tangerang Selatan/BSD ga min?',
        'Ada minimal pemesanan untuk area Jawa Tengah ga kak?',
        'Tolong PM info harga dan ongkir ke Cirebon dong min.',
        'Ada katalog lengkapnya ga kak? Mau liat motif lain.',
        'Bisa custom warna putih ga min rosternya?',
        'Berapa estimasi biaya buat pagar seukuran foto ini kak?',
        'Ready stok motif ini min?',

        // Tanya Spesifikasi & Kualitas
        'Betonnya anti lumut ga kak kalau dipasang outdoor?',
        'Ini warnanya emang natural semen atau dicat lagi min?',
        'Roster merah tanah liat ada ga kak motif kayak gini?',
        'Bahan betonnya anti rembes air ga min?',
        'Tebal roster nya berapa cm ya?',

        // Kasual & Slang Medsos
        'Nabung dulu buat beli roster indoroster, bismillah 💸',
        'Inspirasi renovasi masa depan, ijin save kak.',
        'Rumah impian bgt sih ini, adem liatnya.',
        'Suka banget lewat beranda konsep-konsep kayak gini.',
        'Makin hari racun indoroster makin di depan mata wkwk.',

        // Sudah Pakai / Testimoni / Pemasangan
        'Kemarin beli disini dikirim langsung pake truk pabriknya, aman banget.',
        'Rosternya tebal dan berat, kualitas beton asli K-200 ini mah, mantap.',
        'Tetangga pada nanya pas pager rumah kelar dipasangin roster ini wkwk.',
        'Udah langganan beli di indoroster buat proyek perumahan saya, amanah terus.',
        'Pelayanannya juara! Admin sabar bgt ngejelasin hitungan kebutuhan roster saya.',
        'Fasad rumah saya sekarang jadi pusat perhatian tetangga sejak dipasangin roster ini.',
        'Pemasangan di pagar luar rapi banget, betonnya halus & presisi.',
        'Udah langganan beli di Indoroster untuk proyek perumahan saya, kualitasnya selalu stabil.',
        'Order 500 pcs untuk sekat kantor, finishingnya rapi banget ga perlu banyak didempul.',
        'Beneran presisi, tukang pasangnya jadi cepet kerjanya ga perlu banyak motong.',
        'Udah dipasang hampir 8 bulan kak, warnanya tetep bagus & ga gampang lumutan.',
        'Pengiriman aman banget pake truk Indoroster langsung, ga ada yang cuil atau pecah.',
        'Adminnya sabar banget ngebantu ngitung kebutuhan roster untuk pagar keliling rumah.',
        'Bahan betonnya kerasa premium padat, ga gampang gumpil pas dipotong tukang.',
        'Rumah type 36 dipasangin roster ini di teras jadi keliatan estetik & luas.',
        'Recommended seller! Barang dikirim sesuai schedule & drivernya ngebantu banget.',
        'Puas sama hasilnya, rumah jadi lebih adem karena angin bisa masuk dengan bebas.',
        'Roster semen abu naturalnya pas banget dipaduin sama cat putih minimalis.',
        'Kemarin beli 250 pcs, kualitas barang dari atas sampe bawah sama rata rapinya.',
    ];

    // Template Isi Ulasan Produk (4-5 Bintang)
    protected static $productReviewContents = [
        'Roster nya solid banget, tebal dan berat. Kualitas beton pabrik emang beda, rapi finishingnya. Pengiriman aman pakai truk pabrik langsung sampai depan rumah 👍',
        'Fasad rumah jadi aesthetic parah setelah dipasang roster beton ini. Tetangga banyak yang nanya belinya dimana. Bener-bener worth it dengan harga pabrik!',
        'Sangat puas belanja disini! Respon admin cepat & ramah bgt ngebantu hitung kebutuhan jumlah roster. Pengiriman juga sesuai jadwal.',
        'Kualitas roster beton dolomitnya cakep bener, warnanya natural bagus buat konsep industrial minimalis. Sirkulasi udara rumah jadi lancar bgt.',
        'Barang mendarat dengan selamat tanpa ada yang pecah atau rompal. Packing rapi, semennya kerasa kokoh dan padat. Bakal order lagi buat proyek berikutnya.',
        'Look-nya mewah bgt. Presisi pas dipasang tukang jadi ga repot ngerapihin lagi. Harga sangat bersahabat dibanding toko bangunan biasa.',
        'Beton K-200 asli ini mah, tebel kokoh bukan kaleng-kaleng. Sangat rekomen buat yang lagi cari roster beton kualitas premium tapi harga bersahabat.',
        'Udah dibandingin sama beberapa vendor lain, Indoroster paling mantap respon dan harganya. Roster presisi dan pengiriman super cepat.',
    ];

    // Template Ulasan Khusus Rating Rendah (1-3 Bintang) - Komplain Realistis dengan Aspek Positif
    protected static $productReviewLowRatingContents = [
        'Kualitas roster betonnya tebal dan kokoh banget, tapi sayang sekali pengiriman ekspedisi agak telat dari jadwal yang dijanjikan.',
        'Bahan rosternya solid dan presisi pas dipasang tukang, cuma pas sampai ada 3 pcs yang cuil ujungnya karena diturunin buru-buru.',
        'Roster dolomit putihnya bagus bersih & estetik parah, tapi chat admin waktu tanya stok agak lambat dibalesnya.',
        'Pengiriman cepat sekali pakai truk pabrik langsung, tapi kemarin pesanan kurang 5 pcs, untung setelah dikomplain langsung dikirim susulan.',
        'Barangnya bagus dan tebal, tapi salah pesan motif pas checkout karena foto di web mirip-mirip, saran dikasih nama motif yang jelas.',
        'Sebenernya rosternya kokoh banget kualitas jempolan, cuma pas dipasang ada beberapa yang beda mili ukurannya jadi tukang harus sedikit ngebubut.',
        'Roster abu semennya presisi & rapi, tapi driver yang anter kurang ramah pas nurunin barang di depan gang rumah.',
        'Kualitas produknya memuaskan bgt tebal & padat, tapi paking palet kayunya agak kurang aman jadi ada beberapa yang gores.',
        'Barang bagus, kokoh dan rapi. Tapi pengiriman ke area luar kota lumayan mahal ongkirnya.',
        'Roster betonnya paling juara dibanding toko lain, cuma respon admin pas weekend slow respon bgt padahal mau urgent pasang.',
        'Kualitas betonnya sebenernya bagus tebal, tapi kecewa berat karena barangnya salah motif dikirim semua dan harus nunggu retur lagi.',
        'Pengiriman cepat dan driver ramah, tapi banyak yang pecah di jalan sampai hampir 15 pcs gara-gara tidak diikat rapi di truk.',
        'Barangnya bagus presisi, tapi pesanan saya tertukar dengan proyek lain jadi kacau jadwal tukang pasangnya.',
        'Adminnya ramah dan fast respon, tapi barang yang datang warnanya belang-belang abu gelap dan abu terang, kurang konsisten.',
    ];

    // Helper untuk membuat user dummy
    public static function seedDummyUsers($count = 50): array
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $existingDummyCount = User::where('email', 'like', 'dummy_user_%@indoroster.com')->count();
        if ($existingDummyCount >= $count) {
            // Limit the number of IDs plucked to avoid memory exhaustion
            $limit = max($count, 5000);

            return User::where('email', 'like', 'dummy_user_%@indoroster.com')->limit($limit)->pluck('id')->toArray();
        }

        $needed = $count - $existingDummyCount;
        $insertData = [];
        $now = Carbon::now();

        // Hash password once to bypass bcrypt computation overhead per loop
        $hashedPassword = Hash::make('password123');

        for ($i = 0; $i < $needed; $i++) {
            $nameIdx = rand(0, count(self::$indonesianNames) - 1);
            $name = self::$indonesianNames[$nameIdx];
            $email = 'dummy_user_'.Str::random(8).'_'.uniqid().'@indoroster.com';
            $phone = '08'.rand(111111111, 999999999);

            $insertData[] = [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'customer',
                'phone' => $phone,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($insertData)) {
            $chunks = array_chunk($insertData, 500);
            foreach ($chunks as $chunk) {
                User::insert($chunk);
            }
        }

        // Ambil id user dummy dengan limit untuk mencegah kehabisan memori
        $limit = max($count, 5000);

        return User::where('email', 'like', 'dummy_user_%@indoroster.com')->limit($limit)->pluck('id')->toArray();
    }

    // Generator Komentar Video
    public static function generateVideoComments($count = 500): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $requiredUsers = max(50, min(5000, (int) ceil($count / 5)));
        $userIds = self::seedDummyUsers($requiredUsers);
        if (empty($userIds)) {
            return 0;
        }

        $adminVideos = Gallery::where('category', 'video-inspirasi')->where('is_active', true)->get();
        $reviewVideos = ProductReview::where('is_approved', true)
            ->whereNotNull('images')
            ->get()
            ->filter(function ($review) {
                if (! $review->images) {
                    return false;
                }
                foreach ($review->images as $path) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (in_array($ext, ['mp4', 'mov', 'avi'])) {
                        return true;
                    }
                }

                return false;
            });

        $videoCommentables = [];
        foreach ($adminVideos as $v) {
            $videoCommentables[] = ['type' => Gallery::class, 'id' => $v->id];
        }
        foreach ($reviewVideos as $v) {
            $videoCommentables[] = ['type' => ProductReview::class, 'id' => $v->id];
        }

        if (empty($videoCommentables)) {
            return 0;
        }

        // Weighted distribution
        $weightedCommentables = [];
        $totalWeight = 0;
        foreach ($videoCommentables as $item) {
            $weight = rand(1, 10) * rand(1, 10);
            $item['weight'] = max(1, $weight);
            $weightedCommentables[] = $item;
            $totalWeight += $item['weight'];
        }

        $pickWeightedCommentable = function () use ($weightedCommentables, $totalWeight) {
            $rand = rand(1, $totalWeight);
            $sum = 0;
            foreach ($weightedCommentables as $item) {
                $sum += $item['weight'];
                if ($rand <= $sum) {
                    return $item;
                }
            }

            return $weightedCommentables[0];
        };

        $insertData = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $commentable = $pickWeightedCommentable();
            $userId = Arr::random($userIds);
            $body = Arr::random(self::$videoCommentTemplates);

            if (rand(1, 10) <= 2) {
                $body .= ' '.Arr::random(['🔥', '👍', '😍', '🏠', '😂', '💯']);
            }

            $insertData[] = [
                'user_id' => $userId,
                'commentable_type' => $commentable['type'],
                'commentable_id' => $commentable['id'],
                'body' => $body,
                'is_seeded' => true,
                'created_at' => $now->copy()->subDays(rand(1, 60))->subMinutes(rand(1, 1440)),
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            Comment::insert($chunk);
        }

        return count($insertData);
    }

    // Generator Komentar Foto
    public static function generatePhotoComments($count = 500): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $requiredUsers = max(50, min(5000, (int) ceil($count / 5)));
        $userIds = self::seedDummyUsers($requiredUsers);
        if (empty($userIds)) {
            return 0;
        }

        $adminPhotos = Gallery::where('category', '!=', 'video-inspirasi')->where('is_active', true)->get();
        $reviewPhotos = ProductReview::where('is_approved', true)
            ->whereNotNull('images')
            ->get()
            ->filter(function ($review) {
                if (! $review->images) {
                    return false;
                }
                foreach ($review->images as $path) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (! in_array($ext, ['mp4', 'mov', 'avi'])) {
                        return true;
                    }
                }

                return false;
            });

        $photoCommentables = [];
        foreach ($adminPhotos as $p) {
            $photoCommentables[] = ['type' => Gallery::class, 'id' => $p->id];
        }
        foreach ($reviewPhotos as $p) {
            $photoCommentables[] = ['type' => ProductReview::class, 'id' => $p->id];
        }

        if (empty($photoCommentables)) {
            return 0;
        }

        // Weighted distribution
        $weightedCommentables = [];
        $totalWeight = 0;
        foreach ($photoCommentables as $item) {
            $weight = rand(1, 10) * rand(1, 10);
            $item['weight'] = max(1, $weight);
            $weightedCommentables[] = $item;
            $totalWeight += $item['weight'];
        }

        $pickWeightedCommentable = function () use ($weightedCommentables, $totalWeight) {
            $rand = rand(1, $totalWeight);
            $sum = 0;
            foreach ($weightedCommentables as $item) {
                $sum += $item['weight'];
                if ($rand <= $sum) {
                    return $item;
                }
            }

            return $weightedCommentables[0];
        };

        $insertData = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $commentable = $pickWeightedCommentable();
            $userId = Arr::random($userIds);
            $body = Arr::random(self::$photoCommentTemplates);

            if (rand(1, 10) <= 2) {
                $body .= ' '.Arr::random(['🔥', '👍', '😍', '🏠', '🙌', '💯']);
            }

            $insertData[] = [
                'user_id' => $userId,
                'commentable_type' => $commentable['type'],
                'commentable_id' => $commentable['id'],
                'body' => $body,
                'is_seeded' => true,
                'created_at' => $now->copy()->subDays(rand(1, 90))->subMinutes(rand(1, 1440)),
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            Comment::insert($chunk);
        }

        return count($insertData);
    }

    // Generator Ulasan Produk
    public static function generateProductReviews($count = 50): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $products = Product::all();
        if ($products->isEmpty()) {
            return 0;
        }

        $insertData = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $product = $products->random();
            $nameIdx = rand(0, count(self::$indonesianNames) - 1);
            $reviewerName = self::$indonesianNames[$nameIdx];
            $location = Arr::random(self::$locations);

            $randRating = rand(1, 100);
            if ($randRating <= 75) {
                $rating = 5;
            } elseif ($randRating <= 90) {
                $rating = 4;
            } elseif ($randRating <= 95) {
                $rating = 3;
            } elseif ($randRating <= 98) {
                $rating = 2;
            } else {
                $rating = 1;
            }

            $content = self::getRandomReviewContent($rating, $product->name, $reviewerName);

            $insertData[] = [
                'product_id' => $product->id,
                'reviewer_name' => $reviewerName,
                'reviewer_location' => $location,
                'rating' => $rating,
                'content' => $content,
                'is_approved' => true,
                'is_seeded' => true,
                'created_at' => $now->copy()->subDays(rand(1, 180))->subMinutes(rand(1, 1440)),
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            ProductReview::insert($chunk);
        }

        return count($insertData);
    }

    // Target Generator Ulasan Produk untuk Produk Spesifik
    public static function generateProductReviewsForProduct(int $productId, ?int $rating, int $count = 5): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $product = Product::find($productId);
        if (! $product) {
            return 0;
        }

        $insertData = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $nameIdx = rand(0, count(self::$indonesianNames) - 1);
            $reviewerName = self::$indonesianNames[$nameIdx];
            $location = Arr::random(self::$locations);

            $finalRating = $rating;
            if ($finalRating === null || $finalRating === 0) {
                // acak rating
                $randRating = rand(1, 100);
                if ($randRating <= 75) {
                    $finalRating = 5;
                } elseif ($randRating <= 90) {
                    $finalRating = 4;
                } elseif ($randRating <= 95) {
                    $finalRating = 3;
                } elseif ($randRating <= 98) {
                    $finalRating = 2;
                } else {
                    $finalRating = 1;
                }
            }

            $content = self::getRandomReviewContent($finalRating, $product->name, $reviewerName);

            $insertData[] = [
                'product_id' => $product->id,
                'reviewer_name' => $reviewerName,
                'reviewer_location' => $location,
                'rating' => $finalRating,
                'content' => $content,
                'is_approved' => true,
                'is_seeded' => true,
                'created_at' => $now->copy()->subMinutes(rand(1, 1440)), // Scattered within the last 24 hours
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            ProductReview::insert($chunk);
        }

        return count($insertData);
    }

    // Target Generator Komentar Video untuk Media Spesifik
    public static function generateVideoCommentsForMedia(string $commentableType, int $commentableId, int $count = 10): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $userIds = self::seedDummyUsers(max(50, $count));
        if (empty($userIds)) {
            return 0;
        }

        $insertData = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $userId = Arr::random($userIds);
            $body = Arr::random(self::$videoCommentTemplates);

            if (rand(1, 10) <= 2) {
                $body .= ' '.Arr::random(['🔥', '👍', '😍', '🏠', '😂', '💯']);
            }

            $insertData[] = [
                'user_id' => $userId,
                'commentable_type' => $commentableType,
                'commentable_id' => $commentableId,
                'body' => $body,
                'is_seeded' => true,
                'created_at' => $now->copy()->subMinutes(rand(1, 1440)), // Scattered within the last 24 hours
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            Comment::insert($chunk);
        }

        return count($insertData);
    }

    // Target Generator Komentar Foto untuk Media Spesifik
    public static function generatePhotoCommentsForMedia(string $commentableType, int $commentableId, int $count = 10): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $userIds = self::seedDummyUsers(max(50, $count));
        if (empty($userIds)) {
            return 0;
        }

        $insertData = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $userId = Arr::random($userIds);
            $body = Arr::random(self::$photoCommentTemplates);

            if (rand(1, 10) <= 2) {
                $body .= ' '.Arr::random(['🔥', '👍', '😍', '🏠', '🙌', '💯']);
            }

            $insertData[] = [
                'user_id' => $userId,
                'commentable_type' => $commentableType,
                'commentable_id' => $commentableId,
                'body' => $body,
                'is_seeded' => true,
                'created_at' => $now->copy()->subMinutes(rand(1, 1440)), // Scattered within the last 24 hours
                'updated_at' => $now,
            ];
        }

        $chunks = array_chunk($insertData, 500);
        foreach ($chunks as $chunk) {
            Comment::insert($chunk);
        }

        return count($insertData);
    }

    // Target Generator Like untuk Media Spesifik
    public static function generateLikesForMedia(string $likeableType, int $likeableId, int $count = 10): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        // Check if item exists
        $item = $likeableType::find($likeableId);
        if (! $item) {
            return 0;
        }

        // Get all user IDs that have ALREADY liked this item
        $existingLikes = Like::where('likeable_type', $likeableType)
            ->where('likeable_id', $likeableId)
            ->pluck('user_id')
            ->toArray();

        // Get dummy users who haven't liked it yet, limited to what we need
        $query = User::where('email', 'like', 'dummy_user_%@indoroster.com');
        if (! empty($existingLikes)) {
            // chunk the existing likes if it's too large, but typically an item has < 10k likes
            // However, to be perfectly safe against placeholders:
            if (count($existingLikes) > 50000) {
                // Highly unlikely for a single item, but just in case
                $existingLikes = array_slice($existingLikes, 0, 50000);
            }
            $query->whereNotIn('id', $existingLikes);
        }
        $availableUserIds = $query->limit($count)->pluck('id')->toArray();

        // If we don't have enough available users, create more!
        if (count($availableUserIds) < $count) {
            $neededNewUsers = $count - count($availableUserIds);

            // Bypass seedDummyUsers because it only ensures total DB count.
            // We need GUARANTEED NEW users.
            $insertData = [];
            $now = Carbon::now();
            $hashedPassword = Hash::make('password123');

            for ($i = 0; $i < $neededNewUsers; $i++) {
                $nameIdx = rand(0, count(self::$indonesianNames) - 1);
                $name = self::$indonesianNames[$nameIdx];
                $email = 'dummy_user_'.Str::random(8).'_'.uniqid().'@indoroster.com';
                $phone = '08'.rand(111111111, 999999999);

                $insertData[] = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => 'customer',
                    'phone' => $phone,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            User::insert($insertData);

            // Fetch the newly created users
            $newDummyIds = User::where('email', 'like', 'dummy_user_%@indoroster.com')
                ->whereNotIn('id', $existingLikes)
                ->orderBy('id', 'desc')
                ->limit($neededNewUsers)
                ->pluck('id')
                ->toArray();

            $availableUserIds = array_merge($availableUserIds, $newDummyIds);
        }

        // Pick random users (or just use them since we limited to $count)
        $selectedUsers = array_slice($availableUserIds, 0, $count);
        if (! is_array($selectedUsers)) {
            $selectedUsers = [$selectedUsers];
        }

        $insertData = [];
        $now = Carbon::now();
        foreach ($selectedUsers as $userId) {
            $insertData[] = [
                'user_id' => $userId,
                'likeable_type' => $likeableType,
                'likeable_id' => $likeableId,
                'created_at' => $now->copy()->subMinutes(rand(1, 1440)), // Scattered within the last 24 hours
                'updated_at' => $now,
            ];
        }

        if (! empty($insertData)) {
            $chunks = array_chunk($insertData, 500);
            foreach ($chunks as $chunk) {
                Like::insert($chunk);
            }
        }

        return count($insertData);
    }

    // Generator Like Acak ke Semua Video & Foto
    public static function generateRandomLikes(int $count = 1000): int
    {
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        // 1. Dapatkan semua item media yang valid (video & foto)
        $likeableItems = [];

        // Videos
        $adminVideos = Gallery::where('category', 'video-inspirasi')->where('is_active', true)->get();
        foreach ($adminVideos as $v) {
            $likeableItems[] = ['type' => Gallery::class, 'id' => $v->id];
        }
        $reviewVideos = ProductReview::where('is_approved', true)
            ->whereNotNull('images')
            ->get()
            ->filter(function ($review) {
                if (! $review->images) {
                    return false;
                }
                foreach ($review->images as $path) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (in_array($ext, ['mp4', 'mov', 'avi'])) {
                        return true;
                    }
                }

                return false;
            });
        foreach ($reviewVideos as $v) {
            $likeableItems[] = ['type' => ProductReview::class, 'id' => $v->id];
        }

        // Photos
        $adminPhotos = Gallery::where('category', '!=', 'video-inspirasi')->where('is_active', true)->get();
        foreach ($adminPhotos as $p) {
            $likeableItems[] = ['type' => Gallery::class, 'id' => $p->id];
        }
        $reviewPhotos = ProductReview::where('is_approved', true)
            ->whereNotNull('images')
            ->get()
            ->filter(function ($review) {
                if (! $review->images) {
                    return false;
                }
                foreach ($review->images as $path) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (! in_array($ext, ['mp4', 'mov', 'avi'])) {
                        return true;
                    }
                }

                return false;
            });
        foreach ($reviewPhotos as $p) {
            $likeableItems[] = ['type' => ProductReview::class, 'id' => $p->id];
        }

        if (empty($likeableItems)) {
            return 0;
        }

        // 2. Assign popularity weights to each item using a Power-Law distribution
        $itemsWithWeights = [];
        $totalWeight = 0;
        foreach ($likeableItems as $item) {
            $weight = pow(rand(1, 10), 3); // skewed distribution (1 to 1000)
            $item['weight'] = $weight;
            $itemsWithWeights[] = $item;
            $totalWeight += $weight;
        }

        // 3. Distribute the requested $count likes among items according to weights
        $distributedCounts = [];
        $remainingCount = $count;
        foreach ($itemsWithWeights as $index => $item) {
            if ($index === count($itemsWithWeights) - 1) {
                $target = $remainingCount;
            } else {
                $target = (int) round(($item['weight'] / $totalWeight) * $count);
                if ($target > $remainingCount) {
                    $target = $remainingCount;
                }
            }
            $remainingCount -= $target;
            $distributedCounts[] = [
                'type' => $item['type'],
                'id' => $item['id'],
                'target' => $target,
            ];
        }

        // 4. Find the maximum target likes on any single item
        $maxTarget = 0;
        foreach ($distributedCounts as $dist) {
            if ($dist['target'] > $maxTarget) {
                $maxTarget = $dist['target'];
            }
        }

        // Use a larger pool of dummy users so they are distributed randomly and naturally
        $requiredUsersCount = min(5000, max([200, $maxTarget * 2, (int) ($count / 4)]));

        // Seed dummy users up to the required count
        $userIds = self::seedDummyUsers($requiredUsersCount);
        if (empty($userIds)) {
            return 0;
        }

        // 5. Fetch existing likes by dummy users to avoid duplicate key errors
        $existingLikes = Like::whereIn('user_id', $userIds)->get();
        $likedKeys = [];
        foreach ($existingLikes as $like) {
            $likedKeys["{$like->user_id}-{$like->likeable_type}-{$like->likeable_id}"] = true;
        }

        // 6. Generate like rows
        $insertData = [];
        $now = Carbon::now();

        foreach ($distributedCounts as $dist) {
            $itemType = $dist['type'];
            $itemId = $dist['id'];
            $target = $dist['target'];

            if ($target <= 0) {
                continue;
            }

            // Find all user IDs that have NOT liked this item yet
            $availableUserIds = [];
            foreach ($userIds as $userId) {
                $key = "{$userId}-{$itemType}-{$itemId}";
                if (! isset($likedKeys[$key])) {
                    $availableUserIds[] = $userId;
                }
            }

            // We can only assign as many as available
            $toAssign = min($target, count($availableUserIds));
            if ($toAssign <= 0) {
                continue;
            }

            // Pick a random subset of user IDs
            $selectedUsers = Arr::random($availableUserIds, $toAssign);
            if (! is_array($selectedUsers)) {
                $selectedUsers = [$selectedUsers];
            }

            foreach ($selectedUsers as $userId) {
                $insertData[] = [
                    'user_id' => $userId,
                    'likeable_type' => $itemType,
                    'likeable_id' => $itemId,
                    'created_at' => $now->copy()->subDays(rand(1, 60))->subMinutes(rand(1, 1440)),
                    'updated_at' => $now,
                ];
                $likedKeys["{$userId}-{$itemType}-{$itemId}"] = true;
            }
        }

        // 7. Bulk insert likes in chunks of 500
        if (! empty($insertData)) {
            $chunks = array_chunk($insertData, 500);
            foreach ($chunks as $chunk) {
                Like::insert($chunk);
            }
        }

        return count($insertData);
    }

    // Bersihkan semua data simulasi
    public static function clearAllSimulation(): array
    {
        $deletedReviews = ProductReview::where('is_seeded', true)->delete();
        $deletedComments = Comment::where('is_seeded', true)->delete();

        // Deleting dummy users will cascade delete their likes as defined in foreignId('user_id')->cascadeOnDelete()
        $deletedUsers = User::where('email', 'like', 'dummy_user_%@indoroster.com')->delete();

        return [
            'reviews' => $deletedReviews,
            'comments' => $deletedComments,
            'users' => $deletedUsers,
        ];
    }

    /**
     * Menghasilkan isi ulasan produk acak dengan mix-and-match frase
     * serta personalisasi nama produk & persona pembeli (kontraktor, cafe, developer, retail).
     */
    public static function getRandomReviewContent(int $rating, string $productName, string $reviewerName = ''): string
    {
        $isContractor = str_contains($reviewerName, 'PT') || str_contains($reviewerName, 'CV') || str_contains($reviewerName, 'Kontraktor') || str_contains($reviewerName, 'Konstruksi');
        $isDeveloper = str_contains($reviewerName, 'Summarecon') || str_contains($reviewerName, 'Sinarmas') || str_contains($reviewerName, 'Podomoro') || str_contains($reviewerName, 'Citra') || str_contains($reviewerName, 'Land') || str_contains($reviewerName, 'Lippo') || str_contains($reviewerName, 'Pakuwon');
        $isCafe = str_contains($reviewerName, 'Kopi') || str_contains($reviewerName, 'Cafe') || str_contains($reviewerName, 'Resto') || str_contains($reviewerName, 'Eatery') || str_contains($reviewerName, 'Hotel') || str_contains($reviewerName, 'Barber');
        $isArchitect = str_contains($reviewerName, 'Studio') || str_contains($reviewerName, 'Arsitek') || str_contains($reviewerName, 'Design') || str_contains($reviewerName, 'Atelier');

        if ($rating <= 3) {
            $positives = [
                "Sebenarnya roster {$productName} tebal, padat dan kokoh banget,",
                "Kualitas cetakan {$productName} memuaskan dan presisi siku 90 derajat,",
                "Roster {$productName}-nya bagus bersih dan estetik parah,",
                'Pengiriman cepat sekali pakai truk pabrik langsung,',
                'Adminnya ramah dan fast respon pas konsultasi hitungan kebutuhan,',
                "Barang {$productName} sendiri sangat bagus dan permukaannya halus,",
                'Respon admin ramah dan kooperatif dari awal transaksi,',
            ];

            $complaints = [
                ' cuma pengiriman armada agak telat dari estimasi jadwal koordinasi.',
                ' tapi kemarin pas sampai ada 3 pcs yang cuil ujungnya karena diturunin buru-buru.',
                ' hanya saja chat admin agak lambat dibalas saat weekend.',
                ' tapi pesanan sempat kurang 5 pcs, untungnya admin langsung kirim susulan gratis.',
                ' cuma paking palet kayunya agak renggang jadi ada yang sedikit kegores.',
                ' tapi driver yang antar kurang ramah pas nurunin barang di lokasi proyek.',
                ' sayang sekali ongkos kirim ke luar pulau lumayan mahal menurut saya.',
                ' tapi ada sedikit perbedaan warna abu antara cetakan lama dan baru.',
                ' cuma saat sampai ada beberapa yang retak karena jalanan jelek dan tidak diikat kencang.',
            ];

            $closings = [
                '',
                ' Semoga ke depannya layanannya bisa ditingkatkan lagi.',
                ' Tapi overall barangnya ok dan tetap bisa dipakai.',
                ' Untung diganti kekurangannya oleh admin.',
                ' Mungkin pakingnya perlu dipertebal lagi.',
                ' Tapi untungnya kualitas rosternya tetap mantap.',
            ];

            $text = Arr::random($positives).Arr::random($complaints).Arr::random($closings);

            return trim(preg_replace('/\s+/', ' ', $text));
        }

        // 5-Star Reviews tailored to Buyer Persona
        if ($isDeveloper || $isContractor) {
            $qty = Arr::random(['1.500 pcs', '2.400 pcs', '3.500 pcs', '5.000 pcs', '8.000 pcs', '12.000 pcs']);
            $developerReviews = [
                "Pengadaan {$qty} roster {$productName} untuk proyek fasad cluster perumahan mendarat tepat waktu. Kualitas beton K-200 sangat padat dan presisi sudut memudahkan tukang kami memasang nat rapi.",
                "Order {$qty} untuk dinding ventilasi dan pagar proyek. Dokumen surat jalan, faktur, dan koordinasi sopir armada pabrik sangat profesional. Rekomendasi vendor roster terbaik!",
                "Sangat puas bermitra dengan IndoRoster untuk suplai {$qty} roster {$productName}. Kualitas cetakan sangat stabil dan lolos standar QC pengawas lapangan kami.",
                "Pemesanan volume besar {$qty} dilayani dengan cepat dan diskon pabrik tangan pertama yang sangat kompetitif. Roster padat, tidak mudah gumpil.",
                "Suplai bertahap {$qty} roster {$productName} untuk proyek ruko dan rukan berjalan lancar tanpa kendala. Garansi pecah ganti baru beneran amanah.",
            ];

            return Arr::random($developerReviews);
        }

        if ($isCafe || $isArchitect) {
            $qty = Arr::random(['350 pcs', '550 pcs', '800 pcs', '1.200 pcs', '1.800 pcs']);
            $commercialReviews = [
                "Pemasangan {$qty} roster {$productName} di area outdoor cafe kami bikin suasana jadi adem dan estetik banget. Banyak customer yang foto-foto di spot ini!",
                "Desain minimalis {$productName} pas banget dengan konsep industrial modern studio kami. Sirkulasi udara lancar dan pencahayaan alami maksimal.",
                "Kualitas permukaan roster {$productName} sangat halus dan rapi. Klien kami sangat puas dengan hasil akhir fasad bangunan.",
                "Order {$qty} buat partisi sekat ruang dan fasad kedai kopi. Tampilan visualnya sangat eye-catching dan kokoh terkena hujan panas.",
                "Roster beton {$productName} presisi tinggi, nat dinding terlihat lurus sempurna tanpa banyak dempul tambahan. Hasil akhir mewah!",
            ];

            return Arr::random($commercialReviews);
        }

        // Retail / Homeowner Reviews
        $qty = Arr::random(['80 pcs', '120 pcs', '220 pcs', '350 pcs', '450 pcs', '600 pcs']);
        $openings = [
            '',
            'Mantap! ',
            'Alhamdulillah, ',
            'Barang sudah sampai selamat. ',
            'Puas banget belanja disini! ',
            'Rekomended seller pabrik langsung. ',
            'Bintang 5 buat pelayanannya. ',
        ];

        $qualities = [
            "Order {$qty} roster {$productName}, betonnya tebal dan kokoh banget.",
            "Kualitas beton {$productName} emang juara, cetakannya padat dan rapi.",
            "Bahannya solid dan finishing {$productName} presisi pas dipasang tukang.",
            "Pesan {$qty} buat pagar depan rumah, hasilnya keliatan mewah dan minimalis.",
            "Barang solid, tebal, berat dan kerasa premium beton {$productName}-nya.",
            "Roster {$productName} bikin fasad rumah kami jadi sejuk dan adem sirkulasinya.",
            "Kualitas {$productName} luar biasa, permukaannya halus dan tidak berlumut.",
        ];

        $deliveries = [
            ' Pengiriman aman pakai armada truk pabrik langsung.',
            ' Sampai lokasi aman tanpa ada yang pecah atau cuil.',
            ' Packing aman dan rapi, drivernya juga ramah bantu nurunin.',
            ' Dikirim tepat waktu sesuai jadwal koordinasi admin.',
            ' Pengiriman cepat dan barang selamat sampai depan rumah.',
            ' Sopir truk pabrik ramah banget dan kerjanya profesional.',
        ];

        $admins = [
            ' Adminnya responsif dan ramah banget pas konsultasi hitung kebutuhan.',
            ' Pelayanan ramah, dibantu hitung jumlah keping yang pas.',
            ' Tanya-tanya admin dilayani dengan sabar dan cepat responnya.',
            ' Penjual sangat amanah, responsif dari awal pemesanan.',
        ];

        $closings = [
            ' Next bakal order lagi buat renovasi berikutnya.',
            ' Bakal jadi langganan ini mah. Sukses terus IndoRoster!',
            ' Rekomen buat yang cari roster berkualitas harga pabrik.',
            ' Sangat memuaskan belanja di Indoroster.',
            ' Hasil akhir di teras rumah jadi estetik parah 👍',
            ' Fasad rumah keliatan mewah dan minimalis banget 😍',
        ];

        // Randomly build the review
        $parts = [];
        $parts[] = Arr::random($openings);
        $parts[] = Arr::random($qualities);

        if (rand(1, 10) <= 8) {
            $parts[] = Arr::random($deliveries);
        }

        if (rand(1, 10) <= 6) {
            $parts[] = Arr::random($admins);
        }

        $parts[] = Arr::random($closings);

        return trim(preg_replace('/\s+/', ' ', implode('', $parts)));
    }
}
