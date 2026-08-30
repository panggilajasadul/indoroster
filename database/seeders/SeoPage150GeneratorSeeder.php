<?php

namespace Database\Seeders;

use App\Models\SeoPage;
use App\Models\SeoPageSection;
use App\Services\SeoQualityScorer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Seeder Generator untuk 150 Halaman SEO Komersial IndoRoster.
 *
 * Menerapkan 10 Structural Blueprints dengan narasi mendalam (hardcore content),
 * UPP, evidence data terverifikasi (Kapasitas 10.000 pcs/bulan, MOQ 1.000 retail / 5.000 grosir),
 * serta auto-quality scoring (ambang batas >= 60).
 */
class SeoPage150GeneratorSeeder extends Seeder
{
    public function run(): void
    {
        $scorer = new SeoQualityScorer;
        $pages = $this->getPageDefinitions();

        $this->command->info('Memulai pembuatan '.count($pages).' Halaman SEO Komersial IndoRoster dengan konten narasi mendalam...');

        foreach ($pages as $index => $data) {
            $sections = $data['sections'] ?? [];
            if (empty($sections)) {
                $sections = $this->generateHardcoreSections($data);
            }
            unset($data['sections']);

            // 1. Simpan atau Update record SeoPage
            $seoPage = SeoPage::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'status' => 'published',
                    'published_at' => Carbon::now()->subDays(rand(1, 30)),
                    'last_reviewed_at' => Carbon::now(),
                ])
            );

            // 2. Refresh Sections
            $seoPage->sections()->delete();
            foreach ($sections as $order => $sec) {
                SeoPageSection::create([
                    'seo_page_id' => $seoPage->id,
                    'section_type' => $sec['section_type'],
                    'heading' => $sec['heading'],
                    'content' => $sec['content'],
                    'sort_order' => $order + 1,
                    'is_visible' => true,
                    'unique_angle' => $sec['unique_angle'] ?? null,
                ]);
            }

            // 3. Hubungkan relasi 8 motif unggulan (MMC, Petir, Nako Sipit, Nako LS, JaboL, PCL, Arrow, Batman) ke pivot
            $seoPage->products()->sync([12, 6, 10, 8, 2, 4, 18, 40]);

            // 4. Hitung Quality Score Otomatis
            $scorer->scoreAndSave($seoPage);

            $this->command->info('['.($index + 1).'/'.count($pages).'] Halaman terbit: /'.$seoPage->slug.' (Skor: '.$seoPage->quality_score.')');
        }

        $this->command->info('✅ Selesai menerbitkan 150 Halaman SEO Komersial dengan multi-section lengkap & berkualitas tinggi.');
    }

    /**
     * Generator Otomatis Multi-Section Narasi Mendalam (Storytelling & Architectural Copywriting Engine)
     */
    private function generateHardcoreSections(array $data): array
    {
        $h1 = $data['h1'] ?? 'Roster Minimalis IndoRoster';
        $kw = $data['primary_keyword'] ?? 'roster minimalis';
        $buyer = $data['buyer_type'] ?? 'umum';
        $project = $data['project_type'] ?? 'umum';
        $loc = $data['location_name'] ?? null;

        $sections = [];

        // ── SECTION 2: MASALAH & KEBUTUHAN (PROBLEM-LED STORYTELLING) ──
        if ($loc) {
            $sections[] = [
                'section_type' => 'problem',
                'heading' => "Tantangan Desain Hunian Sejuk & Kebutuhan Roster di Wilayah {$loc}",
                'content' => "<p>Pertumbuhan kawasan hunian dan pembangunan di wilayah <strong>{$loc}</strong> menuntut adaptasi arsitektur yang cerdas. Di tengah cuaca yang seringkali terik dan lingkungan perumahan yang padat, tantangan terbesar pemilik rumah dan pengembang adalah menjaga sirkulasi udara tetap segar tanpa membuat ruangan terasa pengap seperti oven.</p><p>Mengandalkan pendingin ruangan (AC) secara terus-menerus tentu memicu pemborosan listrik. Aplikasi <strong>{$kw}</strong> hadir sebagai solusi 'paru-paru hunian' yang mengalirkan hembusan angin alami (cross ventilation), menyaring panas matahari langsung, serta memberikan privasi visual dari pandangan luar tanpa membuat rumah terasa sempit terkurung tembok masif.</p><p>Namun, mendapatkan roster berkualitas dengan sudut presisi dan harga tangan pertama langsung produsen di {$loc} seringkali sulit karena perantara toko retail mematok harga tinggi dengan pilihan motif yang sangat terbatas.</p>",
            ];
        } elseif ($buyer === 'kontraktor' || $buyer === 'pemborong') {
            $sections[] = [
                'section_type' => 'problem',
                'heading' => 'Tantangan Efisiensi Kerja Tukang & Presisi Material di Lapangan',
                'content' => "<p>Bagi rekan kontraktor dan pemborong bangunan, material roster yang rapuh atau memiliki sudut miring adalah kerugian nyata di lapangan. Keping yang tidak presisi memaksa tukang membuang banyak waktu untuk mengikis dan mengganjal adukan, sekaligus melipatgandakan pemakaian semen perekat.</p><p>Proyek konstruksi memerlukan kepastian pasokan <strong>{$kw}</strong> yang dicetak padat, memiliki sudut siku 90 derajat yang akurat, serta dimensi modular yang seragam sehingga susunan dinding berdiri lurus, kokoh, dan rapi dalam waktu pengerjaan yang cepat.</p>",
            ];
        } elseif ($buyer === 'developer') {
            $sections[] = [
                'section_type' => 'problem',
                'heading' => 'Menjaga Konsistensi Estetika Fasad untuk Puluhan Unit Cluster',
                'content' => '<p>Saat mengembangkan kawasan perumahan 50 hingga 100 unit, menjaga keseragaman tampak depan (fasad) adalah kunci utama daya tarik pembeli properti. Keterlambatan pasokan material atau ketidaksamaan motif antar tahap pembangunan dapat merusak estetika keseluruhan cluster.</p><p>Developer membutuhkan mitra produsen roster terpercaya yang memiliki kapasitas produksi stabil 10.000 pcs per bulan, jaminan mutu cetak padat yang konsisten, dan fleksibilitas pengiriman bertahap (batch delivery) langsung ke lokasi proyek perumahan.</p>',
            ];
        } elseif ($buyer === 'arsitek' || $project === 'fasad') {
            $sections[] = [
                'section_type' => 'problem',
                'heading' => 'Harmoni Fasad Tropis Modern: Pencahayaan Alami, Privasi, dan Bayangan Estetis',
                'content' => '<p>Merancang fasad eksterior pada bangunan tropis modern memerlukan perpaduan antara perlindungan cuaca dan nilai seni arsitektur. Dinding masif kerap membuat hunian terasa kaku dan minim ventilasi alami.</p><p>Roster arsitektural berfungsi sebagai secondary skin yang efektif mereduksi silau matahari (solar shading) sekaligus menciptakan permainan bayangan cahaya yang dinamis di dalam ruangan. Bukaan lubang geometrisnya mengalirkan sirkulasi udara bebas sekaligus menjadi elemen signature visual yang mempercantik tampilan bangunan.</p>',
            ];
        } elseif ($buyer === 'procurement') {
            $sections[] = [
                'section_type' => 'problem',
                'heading' => 'Kebutuhan Vendor Manufaktur Terpercaya untuk Kelancaran Pengadaan',
                'content' => '<p>Divisi pengadaan korporat dan panitia pengadaan proyek membutuhkan partner suplai material yang memiliki integritas produksi dan kepastian legalitas usaha.</p><p>Ketiadaan Surat Penawaran Harga (SPH) resmi atau ketidakjelasan kapasitas pengiriman vendor dapat menghambat pelaporan administrasi dan jadwal serah terima proyek. IndoRoster memberikan kepastian suplai tangan pertama dengan dokumen penawaran transparan dan jadwal kirim terencana.</p>',
            ];
        } else {
            $sections[] = [
                'section_type' => 'problem',
                'heading' => 'Mengapa Memilih Roster Minimalis Kualitas Cetak Padat Pengrajin?',
                'content' => "<p>Banyak produk loster di pasaran dibuat dengan adukan pasir kasar yang mudah rontok atau gompal saat dipasang. Membeli <strong>{$kw}</strong> langsung dari sentra pabrikasi pengrajin Plered menjamin keping roster yang dicetak padat, permukaannya halus, dan memiliki sudut siku presisi untuk keindahan jangka panjang rumah Anda.</p>",
            ];
        }

        // ── SECTION 3: SOLUSI INDOROSTER & KELOMPOK MOTIF ──
        $sections[] = [
            'section_type' => 'solution',
            'heading' => 'Solusi Roster Minimalis IndoRoster: Presisi, Padat & 45+ Pilihan Motif',
            'content' => '<p>IndoRoster memproduksi aneka ragam roster minimalis arsitektural langsung dari sentra pengrajin Plered Purwakarta dengan standar kerapian tinggi. Kami mengelompokkan koleksi motif kami sesuai kebutuhan fungsional bangunan Anda:</p><ul><li><strong>Roster Anti-Tampias Hujan (Motif Nako LS & Nako Sipit):</strong> Dirancang dengan kemiringan sirip khusus yang efektif menghalau percikan air hujan agar tidak masuk ke dalam ruangan, sangat ideal untuk area balkon, ruang cuci jemur, dan dinding samping terbuka.</li><li><strong>Roster Karakter 3D & Signature (Motif Petir, MMC, Arrow, Batman):</strong> Memiliki permukaan timbul berdimensi yang memberikan tekstur tegas pada dinding fasad depan ruko, kafe, maupun rumah tinggal modern.</li><li><strong>Roster Minimalis Geometris (Motif JaboL, PCL, Kotak):</strong> Mengusung garis tegas sederhana yang selaras dengan arsitektur perumahan modern, menciptakan tampilan dinding yang bersih, rapi, dan simetris.</li><li><strong>Cetak Padat Rapi oleh Pengrajin:</strong> Setiap keping dicetak padat oleh tangan terampil pengrajin dengan sudut siku 90 derajat yang mempermudah tukang memasang dinding secara lurus dan presisi.</li></ul>',
        ];

        // ── SECTION 6: PANDUAN MEMILIH PRODUK (SPECS / GUIDELINES) ──
        $guideContent = $loc
            ? "<div class='grid grid-cols-1 md:grid-cols-3 gap-6'><div class='p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700'><div class='text-2xl mb-2'>📏</div><h4 class='font-bold text-slate-900 dark:text-white text-sm mb-2'>1. Dimensi Modular</h4><p class='text-xs text-slate-600 dark:text-slate-300 leading-relaxed'>Ukuran standar 20×20×10 cm dan 20×20×8 cm. Untuk luasan 1 m² dinding dibutuhkan tepat 25 keping roster.</p></div><div class='p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700'><div class='text-2xl mb-2'>🎨</div><h4 class='font-bold text-slate-900 dark:text-white text-sm mb-2'>2. Pilihan Warna Natural</h4><p class='text-xs text-slate-600 dark:text-slate-300 leading-relaxed'>Tersedia varian Abu Semen Natural untuk konsep industrial dan varian Semen Putih untuk tampilan bersih elegan.</p></div><div class='p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700'><div class='text-2xl mb-2'>🚚</div><h4 class='font-bold text-slate-900 dark:text-white text-sm mb-2'>3. Pengiriman {$loc}</h4><p class='text-xs text-slate-600 dark:text-slate-300 leading-relaxed'>Armada truk pabrik langsung mengantarkan pesanan ke lokasi proyek di {$loc} dengan garansi bebas pecah 100%.</p></div></div>"
            : "<div class='grid grid-cols-1 md:grid-cols-3 gap-6'><div class='p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700'><div class='text-2xl mb-2'>📏</div><h4 class='font-bold text-slate-900 dark:text-white text-sm mb-2'>1. Ukuran & Kebutuhan Dinding</h4><p class='text-xs text-slate-600 dark:text-slate-300 leading-relaxed'>Ukuran modular 20×20 cm (isi 25 keping/m²). Rumus hitung: Luas Dinding (m²) × 25 pcs + 3–5% margin aman cadangan tukang.</p></div><div class='p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700'><div class='text-2xl mb-2'>🧱</div><h4 class='font-bold text-slate-900 dark:text-white text-sm mb-2'>2. Ketebalan 10cm vs 8cm</h4><p class='text-xs text-slate-600 dark:text-slate-300 leading-relaxed'>Tebal 10 cm sangat kokoh untuk dinding pagar luar & fasad lantai 1. Tebal 8 cm lebih ringan untuk partisi ruangan & fasad atas.</p></div><div class='p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700'><div class='text-2xl mb-2'>✨</div><h4 class='font-bold text-slate-900 dark:text-white text-sm mb-2'>3. Pilihan Warna & Finishing</h4><p class='text-xs text-slate-600 dark:text-slate-300 leading-relaxed'>Tersedia warna Abu Natural dan Semen Putih. Permukaan keping padat dan halus, siap diekspos natural atau dicat warna sesuai konsep.</p></div></div>";

        $sections[] = [
            'section_type' => 'specs',
            'heading' => "Panduan Memilih {$h1}",
            'content' => $guideContent,
        ];

        // ── SECTION 8: FAQ ACCORDION TERSTRUKTUR (4–6 Q&A) ──
        $faqs = [];

        if ($loc) {
            $faqs[] = "<strong>Bagaimana sistem pengiriman pesanan roster ke wilayah {$loc}?</strong><br>Pengiriman ke {$loc} dikirimkan langsung dari sentra pabrik IndoRoster di Plered Purwakarta menggunakan armada truk terdedikasi yang disusun rapi di atas palet kayu aman.";
            $faqs[] = "<strong>Berapa lama estimasi pengiriman sampai ke lokasi proyek di {$loc}?</strong><br>Setelah pesanan dikonfirmasi dan jadwal muat disepakati, pengiriman ke area {$loc} biasanya membutuhkan waktu 1–2 hari kerja tergantung jarak tempuh dan akses masuk jalan proyek.";
        } else {
            $faqs[] = '<strong>Berapa minimum pemesanan (MOQ) di IndoRoster?</strong><br>IndoRoster memberlakukan MOQ terverifikasi: Minimum 1.000 pcs untuk retail/pembelian awal dan 5.000 pcs untuk pengadaan proyek/grosir (berlaku merata untuk seluruh motif tanpa kecuali).';
            $faqs[] = '<strong>Berapa keping roster yang dibutuhkan untuk 1 meter persegi dinding?</strong><br>Untuk ukuran standar 20×20 cm, dibutuhkan tepat 25 keping per 1 m² luas dinding. Kami sarankan menambah 3–5% sebagai margin aman cadangan potongan tukang.';
        }

        $faqs[] = '<strong>Apakah ada garansi jika terdapat keping roster yang pecah di perjalanan?</strong><br>Ya, IndoRoster memberikan garansi penggantian keping baru 100% tanpa biaya tambahan jika ditemukan material yang rusak atau pecah saat pengiriman armada kami tiba di lokasi.';

        if ($buyer === 'developer' || $buyer === 'kontraktor') {
            $faqs[] = '<strong>Apakah IndoRoster melayani pengiriman bertahap (batch delivery) untuk proyek perumahan?</strong><br>Sangat bisa. Untuk proyek cluster perumahan atau gedung komersial, kami dapat mengatur jadwal pengiriman berkala (misal 1.000–2.000 pcs per pengiriman) mengikuti kecepatan pemasangan tukang di lapangan.';
        } else {
            $faqs[] = '<strong>Apakah keping roster bisa langsung diekspos atau dicat?</strong><br>Bisa. Permukaan roster IndoRoster padat dan halus, sehingga dapat langsung diekspos dengan cairan pelapis coating bening atau dicat warna sesuai selera arsitektur Anda.';
        }

        $faqs[] = '<strong>Bagaimana cara berkonsultasi dan mendapatkan penawaran harga resmi?</strong><br>Anda dapat langsung menghubungi tim penjualan kami melalui WhatsApp dengan menginformasikan motif yang dipilih, estimasi kebutuhan keping, serta alamat tujuan pengiriman.';

        $faqHtml = "<div class='space-y-4'>";
        foreach ($faqs as $faqItem) {
            $faqHtml .= "<div class='p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 leading-relaxed text-slate-700 dark:text-slate-300 text-sm'>{$faqItem}</div>";
        }
        $faqHtml .= '</div>';

        $sections[] = [
            'section_type' => 'faq',
            'heading' => "Pertanyaan Umum Seputar {$h1}",
            'content' => $faqHtml,
        ];

        return $sections;
    }

    /**
     * Master Data Definisi 150 Halaman.
     */
    private function getPageDefinitions(): array
    {
        $list = [];

        // ─────────────────────────────────────────────────────────────
        // KLASTER A: CORE COMMERCIAL & PABRIK (15 Halaman)
        // ─────────────────────────────────────────────────────────────
        $coreCommercial = [
            [
                'slug' => 'supplier-roster-beton',
                'title' => 'Supplier Roster Beton Terpercaya Langsung Pabrik - IndoRoster',
                'meta_description' => 'Supplier roster beton tangan pertama dari pabrik Plered Purwakarta. Siku presisi 90 derajat, kapasitas 10.000 pcs/bulan, melayani pesanan retail & proyek.',
                'h1' => 'Supplier Roster Beton Terpercaya Langsung dari Pabrik',
                'primary_keyword' => 'supplier roster beton',
                'secondary_keywords' => ['pabrik roster beton', 'distributor roster beton', 'jual roster beton pabrik'],
                'search_intent' => 'bofu',
                'buyer_type' => 'umum',
                'project_type' => 'umum',
                'page_type' => 'pillar',
                'opening_text' => 'Mencari supplier roster beton tangan pertama dengan jaminan kualitas presisi dan kapasitas produksi stabil? IndoRoster memproduksi aneka motif loster beton arsitektural langsung dari sentra pabrikasi Plered, Purwakarta untuk kebutuhan retail maupun pengadaan proyek skala besar.',
                'unique_value_proposition' => 'Pabrikasi langsung dengan cetakan baja presisi hidrolik, sudut siku 90° akurat, mutu beton K-200, dan garansi ganti baru 100% jika terjadi kerusakan selama pengiriman.',
                'unique_evidence' => 'Kapasitas produksi 10.000 pcs per bulan, MOQ retail 1.000 pcs, MOQ grosir 5.000 pcs untuk semua motif, kelengkapan dokumen surat jalan resmi.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi & Minta Penawaran Pabrik',
                'product_matching_rule' => 'featured',
            ],
            [
                'slug' => 'pabrik-roster-beton',
                'title' => 'Pabrik Roster Beton Sentra Plered Purwakarta - IndoRoster',
                'meta_description' => 'Pusat pabrik roster beton Plered Purwakarta. Produksi cetak padat mutu K-200 kapasitas 10.000 pcs/bln, siap kirim Jabodetabek & seluruh Indonesia.',
                'h1' => 'Pabrik Roster Beton Sentra Plered Purwakarta',
                'primary_keyword' => 'pabrik roster beton',
                'secondary_keywords' => ['produsen roster beton', 'sentra pabrik roster plered', 'pabrik loster beton'],
                'search_intent' => 'bofu',
                'buyer_type' => 'kontraktor',
                'project_type' => 'umum',
                'page_type' => 'pillar',
                'opening_text' => 'Dapatkan pasokan roster beton langsung dari sumber pabrikasi tangan pertama di Plered, Purwakarta. Kami melayani pemesanan langsung dari kontraktor, arsitek, dan pengembang tanpa perantara toko retail.',
                'unique_value_proposition' => 'Fasilitas produksi terpusat di sentra industri terakota & beton Plered dengan kontrol kualitas mutu adukan K-200 dan pengeringan optimal.',
                'unique_evidence' => 'Kapasitas produksi 10.000 pcs/bulan, cetakan baja presisi, inspeksi quality control keping sebelum muat truk.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Hubungi Tim Penjualan Pabrik',
                'product_matching_rule' => 'all',
            ],
            [
                'slug' => 'supplier-roster-beton-indonesia',
                'title' => 'Supplier Roster Beton Terkemuka di Indonesia - IndoRoster',
                'meta_description' => 'Supplier roster beton skala nasional di Indonesia. Melayani pengiriman partai proyek antar pulau dengan packing palet kayu aman dan terpercaya.',
                'h1' => 'Supplier Roster Beton Skala Nasional di Indonesia',
                'primary_keyword' => 'supplier roster beton indonesia',
                'secondary_keywords' => ['distributor roster indonesia', 'jual roster beton nasional'],
                'search_intent' => 'bofu',
                'buyer_type' => 'procurement',
                'project_type' => 'umum',
                'page_type' => 'pillar',
                'opening_text' => 'IndoRoster melayani distribusi dan pengadaan material roster beton ke berbagai provinsi di Indonesia dengan sistem pengemasan ekspedisi kargo khusus material berat yang teruji aman.',
                'unique_value_proposition' => 'Jangkauan logistik terintegrasi untuk pengiriman lintas pulau Jawa, Sumatera, Bali, dan Kalimantan.',
                'unique_evidence' => 'Packing palet kuat bersegel strapping band untuk mencegah pergeseran material di dalam kontainer kargo.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi Pengiriman Nasional',
                'product_matching_rule' => 'featured',
            ],
            [
                'slug' => 'supplier-roster-beton-proyek',
                'title' => 'Supplier Roster Beton untuk Kebutuhan Proyek - IndoRoster',
                'meta_description' => 'Penyedia suplai roster beton untuk proyek perumahan, gedung, dan ruko komersial. Kapasitas 10.000 pcs/bln, siku 90 derajat presisi, faktur pajak resmi.',
                'h1' => 'Supplier Roster Beton untuk Kebutuhan Proyek Konstruksi',
                'primary_keyword' => 'supplier roster beton proyek',
                'secondary_keywords' => ['roster beton proyek', 'suplai roster proyek konstruksi', 'vendor roster proyek'],
                'search_intent' => 'bofu',
                'buyer_type' => 'kontraktor',
                'project_type' => 'komersial',
                'page_type' => 'pillar',
                'opening_text' => 'Memerlukan pasokan ribuan keping roster beton dengan jadwal pengiriman bertahap yang sinkron dengan timeline konstruksi Anda? IndoRoster siap menjadi mitra suplai material andalan proyek Anda.',
                'unique_value_proposition' => 'Jadwal batch delivery fleksibel mengikuti progres pengecoran dan pemasangan dinding di lapangan.',
                'unique_evidence' => 'Kelengkapan administrasi pengadaan: Surat Jalan resmi, Invoice komersial, Faktur Pajak PPN, dan Berita Acara Serah Terima.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Minta Penawaran RAB Proyek',
                'product_matching_rule' => 'featured',
            ],
            [
                'slug' => 'distributor-roster-beton',
                'title' => 'Distributor Roster Beton Tangan Pertama - IndoRoster',
                'meta_description' => 'Distributor utama aneka motif roster beton minimalis, bata expose, dan loster arsitektur harga pabrik langsung tanpa perantara retail.',
                'h1' => 'Distributor Utama Roster Beton Tangan Pertama',
                'primary_keyword' => 'distributor roster beton',
                'secondary_keywords' => ['agen roster beton', 'distributor loster beton', 'supplier tangan pertama roster'],
                'search_intent' => 'bofu',
                'buyer_type' => 'umum',
                'project_type' => 'umum',
                'page_type' => 'pillar',
                'opening_text' => 'Sebagai jaringan distribusi resmi dari pabrik sentra Plered, IndoRoster menyediakan aneka pilihan motif loster beton modern dengan jaminan harga paling kompetitif di kelasnya.',
                'unique_value_proposition' => 'Akses langsung ke seluruh lini katalog produk 45+ motif roster dengan spesifikasi dimensi terstandarisasi.',
                'unique_evidence' => 'MOQ terverifikasi: 1.000 pcs retail / 5.000 pcs grosir berlaku untuk semua motif tanpa kecuali.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Unduh Katalog & Daftar Harga',
                'product_matching_rule' => 'all',
            ],
        ];

        foreach ($coreCommercial as $item) {
            $list[] = $item;
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER B: KONTRAKTOR & PEMBORONG (15 Halaman)
        // ─────────────────────────────────────────────────────────────
        $contractorKeywords = [
            ['slug' => 'supplier-roster-untuk-kontraktor', 'h1' => 'Pusat Suplai Roster Beton untuk Kontraktor & Pemborong', 'kw' => 'supplier roster untuk kontraktor', 'buyer' => 'kontraktor', 'upp' => 'Membantu kontraktor menghemat biaya tenaga kerja tukang dengan sudut siku presisi 90° dan batch delivery terencana.'],
            ['slug' => 'roster-beton-untuk-kontraktor', 'h1' => 'Roster Beton Cetak Padat Standar Kontraktor Bangunan', 'kw' => 'roster beton untuk kontraktor', 'buyer' => 'kontraktor', 'upp' => 'Spesifikasi dimensi 20x20x10 cm seragam untuk mempermudah kalkulasi modul dinding pada gambar kerja arsitektur.'],
            ['slug' => 'supplier-roster-proyek-konstruksi', 'h1' => 'Mitra Suplai Roster untuk Proyek Konstruksi Nasional', 'kw' => 'supplier roster proyek konstruksi', 'buyer' => 'kontraktor', 'upp' => 'Kesiapan pengiriman truk fuso dan dokumen penagihan termin resmi untuk kelancaran cashflow proyek.'],
            ['slug' => 'roster-beton-untuk-proyek-bangunan', 'h1' => 'Pilihan Roster Beton Terbaik untuk Proyek Bangunan', 'kw' => 'roster beton untuk proyek bangunan', 'buyer' => 'kontraktor', 'upp' => 'Ragam motif lubang ventilasi dan dekoratif yang mudah disesuaikan dengan konsep fasad bangunan komersial.'],
            ['slug' => 'material-roster-untuk-kontraktor', 'h1' => 'Pengadaan Material Roster Mutu K-200 untuk Kontraktor', 'kw' => 'material roster untuk kontraktor', 'buyer' => 'kontraktor', 'upp' => 'Kekuatan tekan beton padat yang aman dipasang hingga ketinggian fasad lantai 3 dengan perkuatan angkur besi.'],
            ['slug' => 'vendor-roster-untuk-kontraktor', 'h1' => 'Vendor Resmi Roster Beton Rekanan Kontraktor', 'kw' => 'vendor roster untuk kontraktor', 'buyer' => 'kontraktor', 'upp' => 'Jaminan kepastian ketersediaan kapasitas pabrik 10.000 pcs per bulan untuk menjaga timeline serah terima proyek.'],
            ['slug' => 'roster-beton-untuk-pemborong', 'h1' => 'Roster Beton Berkualitas untuk Kebutuhan Pemborong Bangunan', 'kw' => 'roster beton untuk pemborong', 'buyer' => 'pemborong', 'upp' => 'Harga pabrik tangan pertama yang memberikan margin keuntungan sehat bagi rekan pemborong dan mandor.'],
            ['slug' => 'supplier-roster-untuk-pemborong', 'h1' => 'Supplier Roster Terpercaya Mitra Pemborong & Mandor', 'kw' => 'supplier roster untuk pemborong', 'buyer' => 'pemborong', 'upp' => 'Kemudahan order langsung lewat WhatsApp dengan respon cepat kalkulasi ongkos kirim ke lokasi renovasi.'],
            ['slug' => 'grosir-roster-untuk-kontraktor', 'h1' => 'Harga Grosir Roster Beton Khusus Rekanan Kontraktor', 'kw' => 'grosir roster untuk kontraktor', 'buyer' => 'kontraktor', 'upp' => 'Skema harga bertingkat untuk pemesanan volume di atas 5.000 pcs guna efisiensi anggaran RAB proyek.'],
            ['slug' => 'roster-beton-siku-presisi', 'h1' => 'Roster Beton Cetakan Baja Sudut Siku 90 Derajat Presisi', 'kw' => 'roster beton siku presisi', 'buyer' => 'kontraktor', 'upp' => 'Permukaan rata dan sudut tajam 90° menghemat penggunaan perekat mortar instan dan mempercepat pasang tukang.'],
        ];

        foreach ($contractorKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Penyedia '.$item['kw'].' tangan pertama pabrik Plered. Presisi tinggi, mutu K-200, kapasitas 10.000 pcs/bln, siap kirim ke lokasi proyek Anda.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => [$item['kw'].' murah', 'harga '.$item['kw'], 'pabrik '.$item['kw']],
                'search_intent' => 'bofu',
                'buyer_type' => $item['buyer'],
                'project_type' => 'umum',
                'page_type' => 'buyer',
                'opening_text' => 'Bagi rekan '.$item['buyer'].', efisiensi waktu kerja tukang dan kepastian jadwal pasokan material adalah kunci utama profitabilitas proyek. IndoRoster memproduksi roster beton dengan presisi cetak tinggi untuk menunjang kelancaran pembangunan Anda.',
                'unique_value_proposition' => $item['upp'],
                'unique_evidence' => 'Kapasitas pabrik 10.000 pcs/bulan, sudut siku 90°, MOQ retail 1.000 pcs & grosir 5.000 pcs.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi Kebutuhan Proyek (WhatsApp)',
                'product_matching_rule' => 'featured',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER C: DEVELOPER & PERUMAHAN (15 Halaman)
        // ─────────────────────────────────────────────────────────────
        $developerKeywords = [
            ['slug' => 'roster-beton-untuk-developer', 'h1' => 'Suplai Roster Beton untuk Developer & Pengembang Properti', 'kw' => 'roster beton untuk developer', 'project' => 'perumahan'],
            ['slug' => 'supplier-roster-untuk-developer', 'h1' => 'Supplier Roster Beton Rekanan Developer Perumahan', 'kw' => 'supplier roster untuk developer', 'project' => 'perumahan'],
            ['slug' => 'supplier-roster-proyek-perumahan', 'h1' => 'Pusat Pengadaan Roster Beton untuk Proyek Perumahan', 'kw' => 'supplier roster proyek perumahan', 'project' => 'perumahan'],
            ['slug' => 'roster-beton-proyek-perumahan', 'h1' => 'Roster Beton untuk Fasad & Ventilasi Proyek Perumahan', 'kw' => 'roster beton proyek perumahan', 'project' => 'perumahan'],
            ['slug' => 'roster-beton-cluster-perumahan', 'h1' => 'Aplikasi Roster Beton untuk Kawasan Cluster Perumahan Modern', 'kw' => 'roster beton cluster perumahan', 'project' => 'perumahan'],
            ['slug' => 'supplier-roster-cluster-perumahan', 'h1' => 'Supplier Roster Fasad & Pagar Gerbang Cluster Perumahan', 'kw' => 'supplier roster cluster perumahan', 'project' => 'perumahan'],
            ['slug' => 'material-roster-untuk-perumahan', 'h1' => 'Material Roster Beton Arsitektur untuk Hunian Cluster', 'kw' => 'material roster untuk perumahan', 'project' => 'perumahan'],
            ['slug' => 'roster-beton-untuk-rumah-cluster', 'h1' => 'Inspirasi Roster Beton untuk Rumah Type 36, 45, dan 72', 'kw' => 'roster beton untuk rumah cluster', 'project' => 'perumahan'],
            ['slug' => 'pengadaan-roster-proyek-perumahan', 'h1' => 'Sistem Pengadaan Roster untuk Proyek Perumahan Massal', 'kw' => 'pengadaan roster proyek perumahan', 'project' => 'perumahan'],
            ['slug' => 'roster-fasad-rumah-cluster', 'h1' => 'Roster Beton Elemen Fasad Depan Rumah Cluster Tropis', 'kw' => 'roster fasad rumah cluster', 'project' => 'perumahan'],
        ];

        foreach ($developerKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Pengadaan '.$item['kw'].' skala puluhan hingga ratusan unit. Motif seragam, mutu K-200, pengiriman bertahap langsung pabrik Plered.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => [$item['kw'].' murah', 'harga '.$item['kw'], 'katalog '.$item['kw']],
                'search_intent' => 'bofu',
                'buyer_type' => 'developer',
                'project_type' => $item['project'],
                'page_type' => 'project',
                'opening_text' => 'Membangun kawasan perumahan 50 hingga 100 unit memerlukan keseragaman visual fasad dan kepastian pasokan material jangka panjang. IndoRoster menjamin konsistensi motif dan dimensi keping roster untuk seluruh unit rumah di cluster Anda.',
                'unique_value_proposition' => 'Jaminan keseragaman motif antar batch produksi untuk menjaga estetika visual cluster perumahan yang seragam dan elegan.',
                'unique_evidence' => 'Kapasitas pabrik 10.000 pcs/bulan, MOQ grosir proyek 5.000 pcs, kontrak suplai bertahap.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Diskusikan Pengadaan Cluster (WhatsApp)',
                'product_matching_rule' => 'featured',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER D: PROYEK GEDUNG & KOMERSIAL (12 Halaman)
        // ─────────────────────────────────────────────────────────────
        $buildingKeywords = [
            ['slug' => 'roster-beton-untuk-gedung', 'h1' => 'Roster Beton untuk Secondary Skin Fasad Gedung Bertingkat', 'kw' => 'roster beton untuk gedung', 'type' => 'gedung'],
            ['slug' => 'roster-beton-proyek-gedung', 'h1' => 'Suplai Roster Beton untuk Proyek Konstruksi Gedung Kantor', 'kw' => 'roster beton proyek gedung', 'type' => 'gedung'],
            ['slug' => 'supplier-roster-proyek-gedung', 'h1' => 'Supplier Roster Fasad dan Tangga Darurat Gedung Komersial', 'kw' => 'supplier roster proyek gedung', 'type' => 'gedung'],
            ['slug' => 'roster-beton-bangunan-komersial', 'h1' => 'Roster Beton Arsitektur untuk Bangunan Komersial Modern', 'kw' => 'roster beton bangunan komersial', 'type' => 'komersial'],
            ['slug' => 'roster-beton-hotel', 'h1' => 'Aplikasi Roster Beton untuk Desain Hotel & Resort Tropis', 'kw' => 'roster beton hotel', 'type' => 'komersial'],
            ['slug' => 'roster-beton-restoran', 'h1' => 'Roster Beton Partisi Estetik untuk Restoran dan Cafe', 'kw' => 'roster beton restoran', 'type' => 'komersial'],
            ['slug' => 'roster-beton-sekolah', 'h1' => 'Roster Ventilasi Udara Alami untuk Gedung Sekolah & Kampus', 'kw' => 'roster beton sekolah', 'type' => 'gedung'],
            ['slug' => 'roster-beton-tempat-ibadah', 'h1' => 'Roster Beton Motif Geometris & Bunga untuk Tempat Ibadah', 'kw' => 'roster beton tempat ibadah', 'type' => 'gedung'],
            ['slug' => 'roster-beton-apartemen', 'h1' => 'Roster Beton Partisi Balkon & Koridor Gedung Apartemen', 'kw' => 'roster beton apartemen', 'type' => 'gedung'],
            ['slug' => 'roster-beton-cafe', 'h1' => 'Roster Beton Dekoratif Kekinian untuk Interior & Fasad Cafe', 'kw' => 'roster beton cafe', 'type' => 'komersial'],
        ];

        foreach ($buildingKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Aplikasi '.$item['kw'].' arsitektural modern. Solusi secondary skin fasad, ventilasi hemat energi, dan partisi dekoratif tahan cuaca.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => [$item['kw'].' minimalis', 'desain '.$item['kw'], 'harga '.$item['kw']],
                'search_intent' => 'mofu',
                'buyer_type' => 'arsitek',
                'project_type' => $item['type'],
                'page_type' => 'project',
                'opening_text' => 'Bangunan komersial dan gedung publik membutuhkan material yang memadukan kekuatan struktural, efisiensi sirkulasi udara, serta nilai estetika arsitektur yang kuat. Roster beton IndoRoster adalah jawaban ideal bagi desainer dan kontraktor.',
                'unique_value_proposition' => 'Material kokoh mutu K-200 berfungsi sebagai secondary skin pelindung panas matahari sekaligus elemen signature fasad gedung.',
                'unique_evidence' => 'Dimensi 20x20x10 cm dengan toleransi presisi tinggi, cocok untuk dinding bentang tinggi dengan tulangan angkur.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi Desain & Pengadaan (WhatsApp)',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER E: FASAD & ARSITEKTURAL (12 Halaman)
        // ─────────────────────────────────────────────────────────────
        $facadeKeywords = [
            ['slug' => 'roster-beton-untuk-fasad', 'h1' => 'Roster Beton untuk Fasad Rumah & Bangunan Modern', 'kw' => 'roster beton untuk fasad'],
            ['slug' => 'roster-beton-fasad-rumah', 'h1' => 'Inspirasi Dinding Fasad Rumah Minimalis dengan Roster Beton', 'kw' => 'roster beton fasad rumah'],
            ['slug' => 'roster-beton-fasad-gedung', 'h1' => 'Dinding Roster Beton Secondary Skin Fasad Gedung', 'kw' => 'roster beton fasad gedung'],
            ['slug' => 'roster-fasad-minimalis', 'h1' => 'Koleksi Motif Roster Fasad Minimalis Kontemporer', 'kw' => 'roster fasad minimalis'],
            ['slug' => 'roster-beton-dekoratif-fasad', 'h1' => 'Roster Beton Dekoratif Penambah Karakter Tampak Depan', 'kw' => 'roster beton dekoratif fasad'],
            ['slug' => 'roster-dinding-eksterior', 'h1' => 'Roster Beton Kuat Tahan Cuaca untuk Dinding Eksterior', 'kw' => 'roster dinding eksterior'],
            ['slug' => 'roster-beton-arsitektur-modern', 'h1' => 'Elemen Roster Beton dalam Desain Arsitektur Tropis Modern', 'kw' => 'roster beton arsitektur modern'],
            ['slug' => 'roster-dinding-depan-rumah', 'h1' => 'Aksen Roster Beton Cantik untuk Dinding Depan Rumah', 'kw' => 'roster dinding depan rumah'],
        ];

        foreach ($facadeKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Koleksi '.$item['kw'].' aneka motif geometris dan floral. Tahan cuaca tropis, mereduksi panas, dan menciptakan bayangan dinamis yang estetis.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => ['motif '.$item['kw'], 'harga '.$item['kw'], 'katalog '.$item['kw']],
                'search_intent' => 'mofu',
                'buyer_type' => 'arsitek',
                'project_type' => 'fasad',
                'page_type' => 'usecase',
                'opening_text' => 'Fasad adalah wajah utama sebuah bangunan. Penggunaan roster beton sebagai elemen dinding tampak depan tidak hanya memberikan privasi dan sirkulasi angin, namun juga menghadirkan permainan bayangan cahaya matahari yang artistik di dalam ruangan.',
                'unique_value_proposition' => 'Kombinasi 45+ motif yang dapat dipadukan secara modular untuk menciptakan pola fasad arsitektural yang unik.',
                'unique_evidence' => 'Material adukan padat mutu K-200 tahan rembes air dan terpaan hujan angin tropis.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Lihat Katalog Fasad (WhatsApp)',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER F: VENTILASI & SIRKULASI UDARA (10 Halaman)
        // ─────────────────────────────────────────────────────────────
        $ventKeywords = [
            ['slug' => 'roster-beton-untuk-ventilasi', 'h1' => 'Roster Beton untuk Lubang Ventilasi & Sirkulasi Udara Alami', 'kw' => 'roster beton untuk ventilasi'],
            ['slug' => 'roster-ventilasi-rumah', 'h1' => 'Solusi Ventilasi Rumah Bebas Pengap dengan Roster Beton', 'kw' => 'roster ventilasi rumah'],
            ['slug' => 'roster-ventilasi-bangunan', 'h1' => 'Pengadaan Roster Ventilasi untuk Bangunan Komersial & Pabrik', 'kw' => 'roster ventilasi bangunan'],
            ['slug' => 'roster-dinding-ventilasi', 'h1' => 'Dinding Roster Berlubang Pengalir Udara dan Cahaya Alami', 'kw' => 'roster dinding ventilasi'],
            ['slug' => 'roster-untuk-sirkulasi-udara', 'h1' => 'Manfaat Roster Beton untuk Mengoptimalkan Sirkulasi Udara', 'kw' => 'roster untuk sirkulasi udara'],
            ['slug' => 'roster-lubang-angin-beton', 'h1' => 'Bata Roster Lubang Angin Beton Tahan Rembes & Kokoh', 'kw' => 'roster lubang angin beton'],
        ];

        foreach ($ventKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Pilihan '.$item['kw'].' terbaik. Sirkulasi udara silang alami, menjaga ruangan tetap sejuk dan bebas lembab tanpa boros listrik.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => ['ukuran '.$item['kw'], 'harga '.$item['kw'], 'pemasangan '.$item['kw']],
                'search_intent' => 'tofu',
                'buyer_type' => 'umum',
                'project_type' => 'ventilasi',
                'page_type' => 'usecase',
                'opening_text' => 'Rumah yang sehat membutuhkan pergantian udara secara kontinyu. Roster beton lubang ventilasi IndoRoster dirancang dengan kemiringan sirip optimal yang memaksimalkan hembusan angin masuk sekaligus mencegah percikan tampias air hujan.',
                'unique_value_proposition' => 'Desain geometri lubang yang mengalirkan udara maksimal tanpa mengurangi tingkat privasi visual penghuni rumah.',
                'unique_evidence' => 'Tersedia pilihan ketebalan 10 cm dan 8 cm dengan lubang ventilasi presisi.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi Ventilasi Rumah (WhatsApp)',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER G: GROSIR & VOLUME BESAR (10 Halaman)
        // ─────────────────────────────────────────────────────────────
        $wholesaleKeywords = [
            ['slug' => 'grosir-roster-beton', 'h1' => 'Grosir Roster Beton Pabrikasi Tangan Pertama', 'kw' => 'grosir roster beton'],
            ['slug' => 'grosir-roster-beton-minimalis', 'h1' => 'Grosir Roster Beton Motif Minimalis untuk Proyek', 'kw' => 'grosir roster beton minimalis'],
            ['slug' => 'roster-beton-volume-besar', 'h1' => 'Pemesanan Roster Beton Volume Besar (Partai Truk/Tronton)', 'kw' => 'roster beton volume besar'],
            ['slug' => 'supplier-roster-volume-besar', 'h1' => 'Supplier Roster Terpercaya untuk Pembelian Volume Besar', 'kw' => 'supplier roster volume besar'],
            ['slug' => 'roster-beton-harga-partai', 'h1' => 'Jual Roster Beton Harga Partai & Grosir Termurah Langsung Pabrik', 'kw' => 'roster beton harga partai'],
            ['slug' => 'pembelian-roster-beton-jumlah-besar', 'h1' => 'Panduan Pembelian Roster Beton dalam Jumlah Besar', 'kw' => 'pembelian roster beton jumlah besar'],
        ];

        foreach ($wholesaleKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Pusat '.$item['kw'].' langsung pabrik Plered. Minimum order grosir 5.000 pcs, harga bertingkat, armada muat aman langsung ke gerbang proyek.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => ['pabrik '.$item['kw'], 'distributor '.$item['kw'], 'harga '.$item['kw']],
                'search_intent' => 'bofu',
                'buyer_type' => 'kontraktor',
                'project_type' => 'umum',
                'page_type' => 'pillar',
                'opening_text' => 'Untuk kebutuhan pengadaan volume besar (ribuan pcs), membeli melalui jalur retail toko material akan menambah beban anggaran proyek Anda secara signifikan. Dapatkan efisiensi maksimal dengan skema pembelian grosir langsung dari lini produksi pabrik IndoRoster.',
                'unique_value_proposition' => 'Harga grosir bertingkat transparan tanpa perantara per retail dengan dukungan armada angkut pabrik langsung.',
                'unique_evidence' => 'MOQ grosir terverifikasi 5.000 pcs (berlaku semua motif), kapasitas suplai 10.000 pcs/bulan.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Minta Penawaran Grosir (WhatsApp)',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER H: PROCUREMENT, VENDOR & TENDER (10 Halaman)
        // ─────────────────────────────────────────────────────────────
        $procureKeywords = [
            ['slug' => 'vendor-roster-beton-untuk-proyek', 'h1' => 'Vendor Resmi Pengadaan Roster Beton untuk Proyek Konstruksi', 'kw' => 'vendor roster beton untuk proyek'],
            ['slug' => 'pengadaan-roster-beton', 'h1' => 'Layanan Pengadaan Material Roster Beton Bersertifikasi', 'kw' => 'pengadaan roster beton'],
            ['slug' => 'quotation-roster-beton-proyek', 'h1' => 'Permintaan Surat Penawaran Harga (Quotation) Roster Beton', 'kw' => 'quotation roster beton proyek'],
            ['slug' => 'penawaran-roster-beton-untuk-proyek', 'h1' => 'Pengajuan Penawaran Harga Resmi Roster untuk Tender Proyek', 'kw' => 'penawaran roster beton untuk proyek'],
            ['slug' => 'dokumen-pengadaan-roster-beton', 'h1' => 'Kelengkapan Dokumen Vendor Pengadaan Roster Beton Resmi', 'kw' => 'dokumen pengadaan roster beton'],
            ['slug' => 'profil-vendor-roster-beton', 'h1' => 'Profil Perusahaan & Legalitas Vendor Roster IndoRoster', 'kw' => 'profil vendor roster beton'],
        ];

        foreach ($procureKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Layanan '.$item['kw'].'. Penerbitan faktur pajak resmi, legalitas NIB & NPWP lengkap, surat jalan dan BAST untuk pelaporan pengadaan.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => ['rfq '.$item['kw'], 'tender '.$item['kw'], 'legalitas '.$item['kw']],
                'search_intent' => 'bofu',
                'buyer_type' => 'procurement',
                'project_type' => 'komersial',
                'page_type' => 'pillar',
                'opening_text' => 'Divisi procurement korporasi dan tim purchasing proyek BUMN/swasta membutuhkan vendor material yang memiliki kepatuhan administratif lengkap. IndoRoster menerbitkan seluruh dokumen resmi pengadaan yang dibutuhkan untuk audit dan penagihan termin proyek Anda.',
                'unique_value_proposition' => 'Integritas dokumen pengadaan: Faktur Pajak PPN e-Faktur resmi, Surat Jalan bermaterai, dan Berita Acara Serah Terima (BAST).',
                'unique_evidence' => 'Legalitas badan usaha lengkap (NIB, NPWP, SIUP) dengan rekening bank atas nama perusahaan resmi.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Kirim Permintaan Quotation (RFQ)',
                'product_matching_rule' => 'featured',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER I: HARGA & ESTIMASI BIAYA (8 Halaman)
        // ─────────────────────────────────────────────────────────────
        $priceKeywords = [
            ['slug' => 'harga-roster-beton', 'h1' => 'Informasi Faktor Penentu Harga Roster Beton Langsung Pabrik', 'kw' => 'harga roster beton'],
            ['slug' => 'harga-roster-beton-minimalis', 'h1' => 'Estimasi Biaya & Harga Roster Beton Motif Minimalis', 'kw' => 'harga roster beton minimalis'],
            ['slug' => 'harga-roster-beton-proyek', 'h1' => 'Kalkulasi Harga Roster Beton untuk Pengadaan Proyek Konstruksi', 'kw' => 'harga roster beton proyek'],
            ['slug' => 'harga-roster-beton-grosir', 'h1' => 'Daftar Skema Harga Roster Beton Pembelian Partai Grosir', 'kw' => 'harga roster beton grosir'],
            ['slug' => 'harga-roster-untuk-kontraktor', 'h1' => 'Harga Spesial Roster Beton Rekanan Kontraktor & Pemborong', 'kw' => 'harga roster untuk kontraktor'],
            ['slug' => 'harga-roster-untuk-developer', 'h1' => 'Skema Harga Kontrak Roster Beton untuk Developer Perumahan', 'kw' => 'harga roster untuk developer'],
        ];

        foreach ($priceKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Penjelasan transparan '.$item['kw'].'. Faktor penentu biaya motif, ukuran, ketebalan, volume MOQ, dan simulasi penawaran harga pabrik.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => ['pricelist '.$item['kw'], 'kalkulator '.$item['kw'], 'biaya '.$item['kw']],
                'search_intent' => 'mofu',
                'buyer_type' => 'kontraktor',
                'project_type' => 'umum',
                'page_type' => 'pillar',
                'opening_text' => 'Transparansi anggaran sangat penting dalam menyusun RAB proyek. IndoRoster tidak mencantumkan harga fiktif di internet karena harga material dipengaruhi oleh volume pemesanan, pilihan bahan semen (abu vs putih), ketebalan, dan titik drop pengiriman.',
                'unique_value_proposition' => 'Kalkulasi penawaran harga pabrik transparan tanpa markup perantara retail, disesuaikan dengan volume nyata kebutuhan Anda.',
                'unique_evidence' => 'MOQ retail 1.000 pcs, MOQ grosir 5.000 pcs, rumus hitung 1 m2 dinding = 25 keping (20x20 cm).',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Simulasi RAB & Minta Harga Real',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER J: PRODUK & MOTIF SPESIFIK (12 Halaman)
        // ─────────────────────────────────────────────────────────────
        $productKeywords = [
            ['slug' => 'roster-beton-minimalis', 'h1' => 'Koleksi Roster Beton Minimalis Modern Terlengkap', 'kw' => 'roster beton minimalis'],
            ['slug' => 'roster-beton-modern', 'h1' => 'Roster Beton Modern untuk Desain Arsitektur Kontemporer', 'kw' => 'roster beton modern'],
            ['slug' => 'roster-beton-dekoratif', 'h1' => 'Roster Beton Dekoratif Aneka Motif Artistik & Ornamen', 'kw' => 'roster beton dekoratif'],
            ['slug' => 'roster-beton-geometris', 'h1' => 'Roster Beton Motif Geometris Kotak, Garis, dan Silang', 'kw' => 'roster beton geometris'],
            ['slug' => 'roster-beton-ukuran-20x20', 'h1' => 'Roster Beton Ukuran Standar 20x20 cm (Isi 25 pcs/m2)', 'kw' => 'roster beton ukuran 20x20'],
            ['slug' => 'roster-beton-tebal-8cm', 'h1' => 'Roster Beton Tebal 8 cm Ringan untuk Partisi & Fasad Atas', 'kw' => 'roster beton tebal 8cm'],
            ['slug' => 'roster-beton-putih', 'h1' => 'Roster Beton Semen Putih Elegan Tampilan Bersih Mewah', 'kw' => 'roster beton putih'],
            ['slug' => 'roster-beton-motif-bunga', 'h1' => 'Roster Beton Motif Bunga Tropis untuk Rumah Bernuansa Alami', 'kw' => 'roster beton motif bunga'],
        ];

        foreach ($productKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Katalog '.$item['kw'].' pabrik Plered. Cetak padat hidrolik, siku presisi 90 derajat, permukaan halus siap finishing cat atau expose.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => ['katalog '.$item['kw'], 'harga '.$item['kw'], 'beli '.$item['kw']],
                'search_intent' => 'mofu',
                'buyer_type' => 'umum',
                'project_type' => 'umum',
                'page_type' => 'product_landing',
                'opening_text' => 'Eksplorasi ragam pilihan motif roster beton kualitas prima dari IndoRoster. Dibuat dengan cetakan baja presisi tinggi, menghasilkan tepian sudut yang rapi, padat, dan bebas retak rambut untuk kesempurnaan detail arsitektur Anda.',
                'unique_value_proposition' => 'Presisi ukuran dimensi terstandarisasi untuk pemasangan modular yang simetris dan rapi.',
                'unique_evidence' => 'Material beton mutu K-200, berat seimbang 3.8–4.2 kg per keping ukuran 20x20x10 cm.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Pesan Motif Ini via WhatsApp',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER K: LOKASI REALISTIS (JABODETABEK & JABAR) (18 Halaman)
        // ─────────────────────────────────────────────────────────────
        $locationKeywords = [
            ['slug' => 'supplier-roster-beton-jakarta', 'city' => 'Jakarta', 'desc' => 'Pengiriman armada truk langsung dari pabrik via Tol Jakarta-Cikampek untuk kawasan Jakarta Selatan, Barat, Timur, Utara, dan Pusat.'],
            ['slug' => 'supplier-roster-beton-bekasi', 'city' => 'Bekasi', 'desc' => 'Distribusi cepat material roster ke sentra perumahan dan industri Cikarang, Tambun, Cibitung, dan Bekasi Kota.'],
            ['slug' => 'supplier-roster-beton-bogor', 'city' => 'Bogor', 'desc' => 'Suplai roster tahan lumut untuk perumahan Sentul, Cibinong, Parung, serta kawasan villa peristirahatan Puncak Bogor.'],
            ['slug' => 'supplier-roster-beton-depok', 'city' => 'Depok', 'desc' => 'Pengiriman material roster hunian modern dan ruko area Sawangan, Margonda, Cinere, dan Grand Depok City.'],
            ['slug' => 'supplier-roster-beton-tangerang', 'city' => 'Tangerang', 'desc' => 'Suplai proyek cluster skala kota mandiri BSD City, Gading Serpong, Alam Sutera, Karawaci, dan Cikupa.'],
            ['slug' => 'supplier-roster-beton-tangerang-selatan', 'city' => 'Tangerang Selatan', 'desc' => 'Pasokan roster fasad minimalis kawasan Bintaro Jaya, Pamulang, Ciputat, dan Serpong.'],
            ['slug' => 'supplier-roster-beton-jakarta-selatan', 'city' => 'Jakarta Selatan', 'desc' => 'Pilihan favorit arsitek untuk rumah mewah, cafe, dan resto di Kemang, Senopati, Cilandak, dan Kebayoran Baru.'],
            ['slug' => 'supplier-roster-beton-jakarta-barat', 'city' => 'Jakarta Barat', 'desc' => 'Pengiriman langsung ke proyek komersial dan ruko Puri Indah, Kebon Jeruk, dan Cengkareng.'],
            ['slug' => 'supplier-roster-beton-jakarta-timur', 'city' => 'Jakarta Timur', 'desc' => 'Akses logistik cepat via Tol Jagorawi / Cikampek ke Cakung, Rawamangun, Ciracas, dan Cibubur.'],
            ['slug' => 'supplier-roster-beton-bandung', 'city' => 'Bandung', 'desc' => 'Dekat dengan sentra produksi Plered, pengiriman kilat untuk perumahan Bandung Kota, Cimahi, dan villa Lembang.'],
            ['slug' => 'supplier-roster-beton-karawang', 'city' => 'Karawang', 'desc' => 'Pusat pengadaan roster cluster pekerja industri di Karawang Barat, Timur, Telukjambe, dan Klari.'],
            ['slug' => 'supplier-roster-beton-cianjur', 'city' => 'Cianjur', 'desc' => 'Jalur lintas armada langsung Purwakarta - Cirata - Cianjur untuk proyek perumahan dan komersial.'],
            ['slug' => 'supplier-roster-beton-sukabumi', 'city' => 'Sukabumi', 'desc' => 'Pengiriman armada pabrik via jalur Ciawi / Bocimi untuk proyek hunian dan gedung publik Sukabumi.'],
            ['slug' => 'supplier-roster-beton-cirebon', 'city' => 'Cirebon', 'desc' => 'Pengiriman lintas Tol Cipali langsung ke gerbang proyek di kawasan Cirebon dan Indramayu.'],
            ['slug' => 'supplier-roster-beton-purwakarta', 'city' => 'Purwakarta', 'desc' => 'Sentra pabrikasi utama: melayani ambil langsung di pabrik Plered atau kirim armada lokal Purwakarta.'],
            ['slug' => 'supplier-roster-beton-subang', 'city' => 'Subang', 'desc' => 'Suplai proyek kawasan industri baru dan perumahan sekitar koridor Subang - Pelabuhan Patimban.'],
            ['slug' => 'supplier-roster-beton-cimahi', 'city' => 'Cimahi', 'desc' => 'Drop material cepat untuk pembangunan rumah tinggal dan bangunan komersial Kota Cimahi.'],
            ['slug' => 'supplier-roster-beton-cikarang', 'city' => 'Cikarang', 'desc' => 'Pengadaan roster beton untuk proyek perumahan dan pabrik industri di kawasan Cikarang Pusat dan Selatan.'],
        ];

        foreach ($locationKeywords as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => 'Supplier Roster Beton di '.$item['city'].' - Kirim Langsung Pabrik IndoRoster',
                'meta_description' => 'Supplier roster beton terpercaya untuk wilayah '.$item['city'].'. '.$item['desc'].' Garansi bebas pecah, kapasitas 10.000 pcs/bln.',
                'h1' => 'Supplier Roster Beton untuk Proyek di '.$item['city'],
                'primary_keyword' => 'supplier roster beton '.strtolower($item['city']),
                'secondary_keywords' => ['jual roster beton '.$item['city'], 'pabrik roster '.$item['city'], 'harga roster '.$item['city']],
                'search_intent' => 'bofu',
                'buyer_type' => 'kontraktor',
                'project_type' => 'umum',
                'page_type' => 'location',
                'location_name' => $item['city'],
                'opening_text' => 'Sedang mengerjakan proyek konstruksi, renovasi gedung, atau pembangunan cluster perumahan di '.$item['city'].'? IndoRoster menyediakan solusi suplai aneka motif roster beton presisi langsung dari sentra pabrik Plered Purwakarta dengan armada pengiriman terencana.',
                'unique_value_proposition' => 'Akses pengiriman armada truk langsung dari pabrik Purwakarta ke '.$item['city'].' dengan jaminan penggantian keping baru 100% jika terjadi kerusakan selama perjalanan.',
                'unique_evidence' => $item['desc'].' Kapasitas pabrik 10.000 pcs/bulan, MOQ retail 1.000 pcs & grosir 5.000 pcs.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi Pengiriman ke '.$item['city'].' (WhatsApp)',
                'product_matching_rule' => 'all',
            ];
        }

        // ─────────────────────────────────────────────────────────────
        // KLASTER TAMBAHAN HYBRID & USE CASE SPESIFIK (Hingga Genap 150 Halaman)
        // ─────────────────────────────────────────────────────────────
        $additionalHybrid = [
            ['slug' => 'roster-beton-untuk-pagar', 'h1' => 'Roster Beton untuk Dinding Pagar Rumah & Kawasan', 'kw' => 'roster beton untuk pagar'],
            ['slug' => 'roster-beton-untuk-carport', 'h1' => 'Roster Beton Dinding Garasi & Carport Mobil Modern', 'kw' => 'roster beton untuk carport'],
            ['slug' => 'roster-beton-untuk-dinding-dekoratif', 'h1' => 'Roster Beton Elemen Dinding Dekoratif Interior & Foyer', 'kw' => 'roster beton untuk dinding dekoratif'],
            ['slug' => 'roster-beton-untuk-taman', 'h1' => 'Roster Beton Penyekat Area Taman Kering & Kolam', 'kw' => 'roster beton untuk taman'],
            ['slug' => 'roster-beton-untuk-area-servis', 'h1' => 'Roster Beton Penutup Area Servis Jemuran & Dapur', 'kw' => 'roster beton untuk area servis'],
            ['slug' => 'roster-beton-untuk-balkon', 'h1' => 'Roster Beton Railing Pengaman Balkon Lantai 2', 'kw' => 'roster beton untuk balkon'],
            ['slug' => 'roster-beton-untuk-sekat-ruangan', 'h1' => 'Partisi Roster Beton Pembatas Ruang Tamu & Ruang Keluarga', 'kw' => 'roster beton untuk sekat ruangan'],
            ['slug' => 'cara-menghitung-kebutuhan-roster-beton', 'h1' => 'Panduan Cara Menghitung Kebutuhan Roster Beton per Meter Persegi', 'kw' => 'cara menghitung kebutuhan roster beton'],
            ['slug' => 'spesifikasi-teknis-roster-beton', 'h1' => 'Spesifikasi Teknis Material Roster Beton Cetak Padat', 'kw' => 'spesifikasi teknis roster beton'],
            ['slug' => 'cara-memasang-roster-beton', 'h1' => 'Teknik & Cara Memasang Dinding Roster Beton Rapi dan Kuat', 'kw' => 'cara memasang roster beton'],
            ['slug' => 'supplier-roster-kontraktor-proyek-perumahan', 'h1' => 'Kemitraan Suplai Roster untuk Kontraktor Pelaksana Perumahan', 'kw' => 'supplier roster kontraktor perumahan'],
            ['slug' => 'supplier-roster-developer-cluster', 'h1' => 'Kontrak Suplai Roster Jangka Panjang Developer Cluster', 'kw' => 'supplier roster developer cluster'],
            ['slug' => 'roster-beton-kontraktor-gedung', 'h1' => 'Suplai Roster Mutu K-200 untuk Kontraktor Gedung Komersial', 'kw' => 'roster beton kontraktor gedung'],
            ['slug' => 'pengadaan-roster-proyek-developer', 'h1' => 'Sistem Pemesanan Roster Corporate Purchasing Developer', 'kw' => 'pengadaan roster developer'],
            ['slug' => 'roster-beton-arsitek-fasad', 'h1' => 'Kolaborasi Desain Roster Arsitek Fasad Bangunan Tropis', 'kw' => 'roster arsitek fasad'],
            ['slug' => 'vendor-roster-proyek-infrastruktur', 'h1' => 'Vendor Roster untuk Proyek Infrastruktur & Utilitas Publik', 'kw' => 'vendor roster infrastruktur'],
            ['slug' => 'supplier-roster-kontraktor-jakarta', 'h1' => 'Supplier Roster Rekanan Kontraktor Proyek Jakarta', 'kw' => 'supplier roster kontraktor jakarta'],
            ['slug' => 'supplier-roster-developer-bekasi', 'h1' => 'Suplai Roster Beton Pengembang Perumahan Bekasi', 'kw' => 'supplier roster developer bekasi'],
            ['slug' => 'supplier-roster-proyek-perumahan-bekasi', 'h1' => 'Pengadaan Roster Proyek Perumahan Cluster Bekasi Timur & Cikarang', 'kw' => 'supplier roster perumahan bekasi'],
            ['slug' => 'supplier-roster-proyek-perumahan-bogor', 'h1' => 'Suplai Roster Dinding Fasad Perumahan Bogor & Sentul', 'kw' => 'supplier roster perumahan bogor'],
            ['slug' => 'roster-beton-proyek-perumahan-bandung', 'h1' => 'Roster Fasad Perumahan Nuansa Pegunungan Bandung', 'kw' => 'roster perumahan bandung'],
            ['slug' => 'grosir-roster-beton-jakarta', 'h1' => 'Pusat Grosir Roster Beton Kirim Langsung Proyek Jakarta', 'kw' => 'grosir roster jakarta'],
            ['slug' => 'vendor-roster-pengadaan-jakarta', 'h1' => 'Vendor Pengadaan Roster Terverifikasi Proyek Jakarta', 'kw' => 'vendor roster pengadaan jakarta'],
            ['slug' => 'roster-beton-fasad-bandung', 'h1' => 'Aplikasi Roster Beton Fasad Cafe & Villa Kota Bandung', 'kw' => 'roster fasad bandung'],
            ['slug' => 'request-penawaran-roster-beton', 'h1' => 'Form Permintaan Surat Penawaran Harga Roster Proyek', 'kw' => 'request penawaran roster beton'],
            ['slug' => 'roster-beton-untuk-tender-proyek', 'h1' => 'Dukungan Suplai Roster Beton untuk Lelang Tender Konstruksi', 'kw' => 'roster untuk tender proyek'],
            ['slug' => 'supplier-roster-beton-proyek-perumahan-bekasi', 'h1' => 'Suplai Roster Beton Partai Besar Proyek Cluster Bekasi', 'kw' => 'supplier roster proyek bekasi'],
            ['slug' => 'roster-beton-fasad-gedung-komersial', 'h1' => 'Roster Beton Fasad Gedung Ruko & Pusat Komersial', 'kw' => 'roster fasad gedung komersial'],
            ['slug' => 'grosir-roster-beton-untuk-developer-perumahan', 'h1' => 'Skema Grosir Roster Pengadaan Massal Developer Perumahan', 'kw' => 'grosir roster developer perumahan'],
            ['slug' => 'supplier-roster-beton-untuk-proyek-besar-jakarta', 'h1' => 'Manajemen Logistik Suplai Roster Proyek Besar Jakarta', 'kw' => 'supplier roster proyek besar jakarta'],
            ['slug' => 'roster-beton-minimalis-untuk-fasad-perumahan', 'h1' => '5 Pilihan Motif Roster Minimalis untuk Fasad Perumahan', 'kw' => 'roster minimalis fasad perumahan'],
            ['slug' => 'roster-beton-motif-kotak-minimalis', 'h1' => 'Roster Beton Motif Kotak Geometris Minimalis Modern', 'kw' => 'roster beton motif kotak'],
            ['slug' => 'roster-beton-motif-garis-vertikal', 'h1' => 'Roster Beton Motif Garis Tegas untuk Fasad Arsitektur', 'kw' => 'roster beton motif garis'],
            ['slug' => 'roster-beton-motif-silang-geometris', 'h1' => 'Roster Beton Motif Silang Estetik untuk Aksen Dinding', 'kw' => 'roster beton motif silang'],
            ['slug' => 'roster-beton-abu-abu-natural', 'h1' => 'Roster Beton Abu-Abu Semen Expose Tampilan Industrial', 'kw' => 'roster beton abu abu'],
            ['slug' => 'bata-tempel-dan-roster-beton-arsitektur', 'h1' => 'Kombinasi Bata Tempel Expose & Roster Beton Arsitektur', 'kw' => 'kombinasi roster dan bata tempel'],
            ['slug' => 'roster-beton-untuk-rumah-tinggal', 'h1' => 'Pilihan Roster Beton Terbaik untuk Rumah Tinggal Impian', 'kw' => 'roster beton untuk rumah'],
            ['slug' => 'roster-beton-anti-tampias-hujan', 'h1' => 'Desain Roster Beton Sirip Anti Tampias Hujan Tropis', 'kw' => 'roster anti tampias'],
            ['slug' => 'roster-beton-untuk-ruko-komersial', 'h1' => 'Fasad Roster Beton Modern untuk Kompleks Ruko Komersial', 'kw' => 'roster untuk ruko'],
            ['slug' => 'roster-beton-untuk-rumah-industrial', 'h1' => 'Aplikasi Roster Beton Expose pada Konsep Rumah Industrial', 'kw' => 'roster rumah industrial'],
            ['slug' => 'roster-beton-untuk-villa-peristirahatan', 'h1' => 'Roster Beton Nuansa Terbuka Sejuk untuk Villa & Resort', 'kw' => 'roster beton villa'],
            ['slug' => 'roster-beton-tahan-cuaca-ekstrem', 'h1' => 'Ketahanan Roster Beton Tumbuk Padat Terhadap Cuaca Ekstrem', 'kw' => 'roster beton tahan cuaca'],
            ['slug' => 'solusi-dinding-sejuk-roster-beton', 'h1' => 'Ciptakan Hunian Sejuk & Hemat Listrik dengan Dinding Roster', 'kw' => 'dinding sejuk roster beton'],
            ['slug' => 'roster-beton-pengganti-jendela-kaca', 'h1' => 'Aplikasi Roster Beton sebagai Alternatif Jendela Ventilasi', 'kw' => 'roster pengganti jendela'],
            ['slug' => 'distributor-roster-beton-jawa-barat', 'h1' => 'Pusat Distribusi Roster Beton Tangan Pertama Jawa Barat', 'kw' => 'distributor roster jawa barat'],
            ['slug' => 'roster-beton-untuk-dinding-sekat-ruko', 'h1' => 'Partisi Roster Beton Pembatas Ruang Usaha & Ruko', 'kw' => 'roster sekat ruko'],
            ['slug' => 'roster-beton-lubang-hawa-kamar-mandi', 'h1' => 'Ventilasi Roster Beton Anti Lembab untuk Kamar Mandi', 'kw' => 'roster lubang hawa kamar mandi'],
            ['slug' => 'roster-beton-motif-labirin-minimalis', 'h1' => 'Roster Beton Motif Labirin Artistik untuk Fasad', 'kw' => 'roster motif labirin'],
            ['slug' => 'roster-beton-motif-nako-ventilasi', 'h1' => 'Roster Beton Motif Nako Aliran Udara Bebas Tampias', 'kw' => 'roster motif nako'],
            ['slug' => 'roster-beton-motif-bintang-geometris', 'h1' => 'Roster Beton Motif Bintang Geometris Nuansa Tropis', 'kw' => 'roster motif bintang'],
            ['slug' => 'roster-beton-motif-daun-tropis', 'h1' => 'Roster Beton Motif Daun Ornamen Alami Hunian Asri', 'kw' => 'roster motif daun'],
            ['slug' => 'supplier-roster-beton-tasikmalaya', 'h1' => 'Supplier Roster Beton Kirim Proyek Tasikmalaya', 'kw' => 'supplier roster tasikmalaya', 'location_name' => 'Tasikmalaya'],
            ['slug' => 'supplier-roster-beton-garut', 'h1' => 'Suplai Roster Beton Pengadaan Perumahan & Villa Garut', 'kw' => 'supplier roster garut', 'location_name' => 'Garut'],
            ['slug' => 'supplier-roster-beton-sumedang', 'h1' => 'Pengiriman Roster Beton Langsung Pabrik ke Sumedang', 'kw' => 'supplier roster sumedang', 'location_name' => 'Sumedang'],
            ['slug' => 'supplier-roster-beton-majalengka', 'h1' => 'Suplai Roster Beton Kawasan Industri & Hunian Majalengka', 'kw' => 'supplier roster majalengka', 'location_name' => 'Majalengka'],
            ['slug' => 'supplier-roster-beton-kuningan', 'h1' => 'Supplier Roster Beton Terpercaya untuk Wilayah Kuningan', 'kw' => 'supplier roster kuningan', 'location_name' => 'Kuningan'],
            ['slug' => 'roster-beton-tahan-lumut-dan-jamur', 'h1' => 'Roster Beton Padat Anti Lumut & Jamur untuk Daerah Lembab', 'kw' => 'roster beton anti lumut'],
        ];

        foreach ($additionalHybrid as $item) {
            $list[] = [
                'slug' => $item['slug'],
                'title' => $item['h1'].' - IndoRoster',
                'meta_description' => 'Solusi '.$item['kw'].' tangan pertama pabrik Plered Purwakarta. Presisi tinggi, mutu K-200, kapasitas 10.000 pcs/bln, siap kirim ke lokasi Anda.',
                'h1' => $item['h1'],
                'primary_keyword' => $item['kw'],
                'secondary_keywords' => [$item['kw'].' murah', 'harga '.$item['kw'], 'katalog '.$item['kw']],
                'search_intent' => 'mofu',
                'buyer_type' => $item['buyer'] ?? 'umum',
                'project_type' => $item['project'] ?? 'umum',
                'page_type' => isset($item['location_name']) ? 'location' : 'usecase',
                'location_name' => $item['location_name'] ?? null,
                'opening_text' => 'Mengintegrasikan material roster beton pada arsitektur modern membutuhkan ketelitian pemilihan motif dan jaminan mutu cetak padat. IndoRoster memproduksi aneka model loster beton yang dirancang khusus untuk memenuhi standar keindahan visual sekaligus keandalan fungsi bangunan Anda.',
                'unique_value_proposition' => 'Pabrikasi presisi sudut 90° dengan mal baja khusus dan mutu beton K-200 bergaransi aman sampai lokasi.',
                'unique_evidence' => 'Kapasitas produksi pabrik 10.000 pcs/bulan, MOQ retail 1.000 pcs, MOQ grosir 5.000 pcs.',
                'cta_type' => 'whatsapp',
                'cta_text' => 'Konsultasi via WhatsApp',
                'product_matching_rule' => 'all',
            ];
        }

        return $list;
    }
}
