<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Article Categories Table
        if (! Schema::hasTable('article_categories')) {
            Schema::create('article_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Create Articles Table
        if (! Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_category_id')->nullable()->constrained('article_categories')->nullOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('thumbnail')->nullable();
                $table->string('thumbnail_alt')->nullable();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->json('tags')->nullable();
                $table->string('author_name')->default('Tim Redaksi IndoRoster');
                $table->unsignedInteger('views_count')->default(0);
                $table->unsignedSmallInteger('reading_time')->default(3);
                $table->boolean('is_published')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_description', 500)->nullable();
                $table->string('meta_keywords')->nullable();
                $table->timestamps();

                $table->index(['is_published', 'published_at']);
            });
        }

        // 3. Seed Article Categories
        $now = now();
        $categories = [
            [
                'name' => 'Inspirasi Desain',
                'slug' => 'inspirasi-desain',
                'description' => 'Ide, konsep arsitektur, dan tren desain fasad serta partisi dinding menggunakan roster beton minimalis.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Panduan & Tips Pemasangan',
                'slug' => 'tips-pemasangan',
                'description' => 'Panduan teknis, tips tukang, cara pasang roster yang kokoh, dan perawatan dinding roster arsitektural.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Info Material & Mutu Beton',
                'slug' => 'info-material',
                'description' => 'Edukasi spesifikasi mutu beton K-200, ketahanan cuaca, perbedaan varian roster semen abu, putih, dan terakota.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Proyek & Realisasi Fasad',
                'slug' => 'proyek-fasad',
                'description' => 'Dokumentasi proyek hunian, kafe, masjid, dan gedung komersial yang menggunakan produk pabrik IndoRoster.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($categories as $cat) {
            DB::table('article_categories')->updateOrInsert(['slug' => $cat['slug']], $cat);
        }

        $catInspirasi = DB::table('article_categories')->where('slug', 'inspirasi-desain')->value('id');
        $catTips = DB::table('article_categories')->where('slug', 'tips-pemasangan')->value('id');
        $catMaterial = DB::table('article_categories')->where('slug', 'info-material')->value('id');

        // 4. Seed High-Value Initial Articles (Optimized for Google SEO & Images)
        $articles = [
            [
                'article_category_id' => $catInspirasi,
                'title' => '7 Inspirasi Desain Fasad Roster Beton Minimalis untuk Hunian Tropis Modern',
                'slug' => '7-inspirasi-desain-fasad-roster-beton-minimalis-rumah-tropis',
                'thumbnail' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'thumbnail_alt' => 'Inspirasi Fasad Roster Beton Minimalis Tropis Modern Mutu K-200 IndoRoster',
                'excerpt' => 'Temukan 7 konsep desain dinding fasad roster beton minimalis yang memaksimalkan sirkulasi udara alami dan pencahayaan matahari tanpa mengorbankan privasi hunian Anda.',
                'content' => <<<'HTML'
<p class="lead">
    Di tengah iklim tropis Indonesia yang cenderung hangat dan lembap, penggunaan <strong>roster beton minimalis (breeze blocks)</strong> menjadi solusi arsitektur yang kian digemari. Selain menghadirkan estetika geometris yang estetik dan mewah, susunan roster memungkinkan angin dan cahaya alami mengalir leluasa ke dalam hunian.
</p>

<h2>1. Fasad Depan Lantai 2 sebagai Secondary Skin</h2>
<p>
    Salah satu aplikasi paling populer dari roster beton adalah sebagai <em>secondary skin</em> pada lantai dua rumah. Penempatan roster di sisi luar jendela kamar atau balkon mampu mereduksi panas matahari langsung (radiasi termal) hingga 40%, sehingga ruangan di dalamnya tetap sejuk dan hemat penggunaan pendingin ruangan (AC).
</p>

<h2>2. Pagar Dinding Kombinasi Roster dan Taman Vertikal</h2>
<p>
    Pagar rumah yang terlalu tertutup sering kali membuat halaman terasa pengap. Dengan mengombinasikan dinding roster beton motif minimalis (seperti motif Kotak Silang, Bunga, atau L-Shape) dengan tanaman rambat hijau, batas teritorial rumah tetap aman namun tetap terlihat ramah dan bernapas.
</p>

<h2>3. Partisi Dinding Pemisah Ruang Tamu dan Ruang Makan</h2>
<p>
    Untuk konsep rumah *open-space*, roster beton berfungsi sebagai sekat semi-transparan yang elegan. Cahaya lampu di malam hari akan menciptakan bayangan siluet bayangan artistik di lantai yang menambah kehangatan suasana rumah.
</p>

<blockquote>
    "Roster beton bukan sekadar elemen ventilasi konvensional, melainkan kanvas arsitektural yang menyatukan fungsi pencahayaan, privasi, dan keindahan estetika fasad."
</blockquote>

<h2>4. Dinding Aksen Mushola & Ruang Doa</h2>
<p>
    Ruang ibadah di dalam rumah membutuhkan ketenangan dan kesejukan. Dinding roster dengan motif geometris simetris membantu menciptakan suasana hening, adem, dan sakral dengan sirkulasi udara alami yang segar.
</p>

<h2>Tips Memilih Roster yang Tepat</h2>
<ul>
    <li><strong>Pilih Mutu Beton K-200:</strong> Pastikan roster diproduksi dengan presisi tinggi dan mutu beton padat agar tidak mudah patah saat dipasang maupun terpapar hujan asam.</li>
    <li><strong>Gunakan Mortar Khusus:</strong> Untuk perekat antar-blok roster, gunakan semen instan tipis (thinbed) agar garis nat terlihat rapi dan presisi.</li>
    <li><strong>Beli Langsung dari Pabrik:</strong> Dapatkan jaminan harga tangan pertama dan garansi ganti pecah 100% dari pabrik terpercaya seperti <strong>IndoRoster</strong>.</li>
</ul>
HTML,
                'tags' => json_encode(['Fasad Rumah', 'Roster Beton Minimalis', 'Desain Tropis', 'Arsitektur']),
                'author_name' => 'Tim Desain Arsitektur IndoRoster',
                'views_count' => 128,
                'reading_time' => 4,
                'is_published' => true,
                'is_featured' => true,
                'published_at' => $now->copy()->subDays(2),
                'meta_title' => '7 Inspirasi Desain Fasad Roster Beton Minimalis Rumah Tropis | IndoRoster',
                'meta_description' => 'Inspirasi desain fasad rumah modern menggunakan roster beton minimalis mutu K-200. Sirkulasi udara lancar, sejuk alami, dan fasad tampak mewah.',
                'meta_keywords' => 'fasad roster beton, desain rumah tropis, roster minimalis, ventilasi beton, secondary skin roster',
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now,
            ],
            [
                'article_category_id' => $catTips,
                'title' => 'Panduan Lengkap: Cara Menghitung Kebutuhan Roster Beton per Meter Persegi (m²)',
                'slug' => 'cara-menghitung-kebutuhan-roster-beton-per-meter-persegi',
                'thumbnail' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?auto=format&fit=crop&w=1200&q=80',
                'thumbnail_alt' => 'Cara Menghitung Kebutuhan Roster Beton Dinding per Meter Persegi IndoRoster',
                'excerpt' => 'Ketahui rumus mudah dan tepat untuk menghitung jumlah blok roster beton ukuran 20x20 cm per meter persegi dinding serta estimasi cadangan pecah.',
                'content' => <<<'HTML'
<p class="lead">
    Sebelum memulai pemasangan pagar atau dinding fasad, langkah krusial yang wajib dilakukan adalah menghitung estimasi jumlah keping roster yang dibutuhkan secara akurat.
</p>

<h2>Ukuran Standar Roster Beton di Indonesia</h2>
<p>
    Ukuran standar roster beton yang paling banyak digunakan di Indonesia adalah <strong>20 cm x 20 cm</strong> dengan ketebalan standar 10 cm.
</p>

<h2>Rumus Perhitungan Dasar per Meter Persegi (m²)</h2>
<p>
    Untuk luas area 1 meter persegi (1 m² atau 100 cm x 100 cm):
</p>
<ul>
    <li>Jumlah vertikal: 100 cm ÷ 20 cm = 5 keping</li>
    <li>Jumlah horizontal: 100 cm ÷ 20 cm = 5 keping</li>
    <li><strong>Total kebutuhan = 5 × 5 = 25 keping per m²</strong></li>
</ul>

<h2>Contoh Simulasi Perhitungan Dinding Fasad</h2>
<p>
    Jika Anda memiliki bidang dinding dengan panjang 4 meter dan tinggi 3 meter:
</p>
<ol>
    <li>Luas dinding = 4 meter × 3 meter = <strong>12 m²</strong></li>
    <li>Kebutuhan keping = 12 m² × 25 keping/m² = <strong>300 keping</strong></li>
    <li>Cadangan potong & sudut (+5%) = 300 × 5% = 15 keping</li>
    <li><strong>Total yang disarankan untuk diorder = 315 keping</strong></li>
</ol>

<blockquote>
    <strong>Catatan Pabrik:</strong> Selalu lebihkan pesanan 3% hingga 5% sebagai cadangan pemotongan pada sudut kolom atau tepi dinding agar pekerjaan tukang tidak terhenti di tengah jalan.
</blockquote>
HTML,
                'tags' => json_encode(['Tips Bangunan', 'Kalkulator Roster', 'Hitung Roster', 'Panduan Tukang']),
                'author_name' => 'Divisi Teknis Pabrik IndoRoster',
                'views_count' => 95,
                'reading_time' => 3,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => $now->copy()->subDays(1),
                'meta_title' => 'Cara Menghitung Kebutuhan Roster Beton per Meter Persegi (m²) | IndoRoster',
                'meta_description' => 'Rumus praktis menghitung jumlah roster beton ukuran 20x20 cm per meter persegi dinding. Lengkap dengan contoh simulasi dan estimasi cadangan.',
                'meta_keywords' => 'hitung roster per meter, kebutuhan roster 20x20, rumus roster beton, estimasi pasang roster',
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now,
            ],
            [
                'article_category_id' => $catMaterial,
                'title' => 'Mengapa Harus Roster Beton Mutu K-200? Mengenal Kekuatan dan Daya Tahan Cuaca',
                'slug' => 'keunggulan-roster-beton-mutu-k200-tahan-cuaca-ekstrem',
                'thumbnail' => 'https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?auto=format&fit=crop&w=1200&q=80',
                'thumbnail_alt' => 'Uji Kekuatan dan Kualitas Mutu Beton K-200 Roster IndoRoster Purwakarta',
                'excerpt' => 'Pahami perbedaan signifikan antara roster pasir semen konvensional dengan roster cetak hidrolik mutu K-200 dari pabrik IndoRoster.',
                'content' => <<<'HTML'
<p class="lead">
    Banyak orang tergiur dengan harga roster yang sangat murah di pasaran tanpa mengetahui komposisi dan mutu kekuatannya. Akibatnya, roster mudah retak, berlumut, atau bahkan hancur saat terkena hujan dan panas matahari dalam hitungan bulan.
</p>

<h2>Apa itu Standar Mutu Beton K-200?</h2>
<p>
    Mutu beton <strong>K-200</strong> mengacu pada kuat tekan karakteristik beton sebesar 200 kg/cm² pada umur 28 hari. Untuk kategori material roster arsitektural dinding, kekuatan ini menjamin struktur yang sangat padat, minim pori-pori air liar, dan tidak mudah getas.
</p>

<h2>3 Keunggulan Utama Roster Mutu K-200 IndoRoster</h2>
<ol>
    <li><strong>Kepadatan Ekstra dengan Mesin Press Presisi:</strong> Dipadatkan menggunakan tekanan hidrolik tinggi sehingga sudut dan garis kisi-kisi roster sangat tajam dan presisi.</li>
    <li><strong>Tahan Lumut & Jamur:</strong> Pori-pori mikro yang rapat meminimalkan penyerapan air (daya serap air rendah), mencegah timbulnya bercak jamur hitam di dinding fasad luar.</li>
    <li><strong>Garansi Pengiriman 100%:</strong> Karena mutunya yang kokoh, risiko retak selama distribusi kargo sangat rendah, dan kami memberikan jaminan ganti baru bila ada yang pecah saat pengiriman.</li>
</ol>
HTML,
                'tags' => json_encode(['Mutu Beton K-200', 'Material Roster', 'Pabrik Plered', 'Kualitas Produk']),
                'author_name' => 'Quality Control IndoRoster',
                'views_count' => 74,
                'reading_time' => 3,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => $now,
                'meta_title' => 'Keunggulan Roster Beton Mutu K-200 Tahan Cuaca Ekstrem | IndoRoster',
                'meta_description' => 'Mengenal kualitas roster beton mutu K-200 pabrik IndoRoster. Sangat kokoh, presisi, anti lumut, dan awet puluhan tahun untuk fasad eksterior.',
                'meta_keywords' => 'mutu beton k200, roster beton berkualitas, roster plered kuat, spesifikasi roster beton',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($articles as $art) {
            DB::table('articles')->updateOrInsert(['slug' => $art['slug']], $art);
        }

        // 5. Add "Artikel & Inspirasi" to navigation menus if not already present
        $navExists = DB::table('navigation_menus')->where('url', '/artikel')->exists();
        if (! $navExists) {
            $maxOrder = DB::table('navigation_menus')->max('order') ?? 5;
            DB::table('navigation_menus')->insert([
                'label' => 'Artikel & Tips',
                'url' => '/artikel',
                'order' => $maxOrder + 1,
                'is_active' => true,
                'target' => '_self',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
        DB::table('navigation_menus')->where('url', '/artikel')->delete();
    }
};
