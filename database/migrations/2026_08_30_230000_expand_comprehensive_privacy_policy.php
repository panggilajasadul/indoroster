<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $privacyContent = <<<'HTML'
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
    Selamat datang di <strong>INDOROSTER</strong> (<em>indoroster.com</em>). Kami sangat menghargai privasi dan kepercayaan Anda sebagai pelanggan, arsitek, kontraktor, developer, maupun pengunjung situs kami. Kebijakan Privasi ini merupakan bentuk komitmen nyata kami untuk menghormati, mengamankan, dan melindungi seluruh data pribadi yang Anda percayakan kepada kami selama menggunakan layanan pabrik, bertransaksi di web, berkonsultasi via WhatsApp, maupun saat pengiriman barang ke lokasi proyek Anda.
</p>

<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
    Kebijakan ini disusun dengan mematuhi ketentuan perundang-undangan yang berlaku di Indonesia, khususnya <strong>Undang-Undang Republik Indonesia No. 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP)</strong> serta regulasi terkait transaksi elektronik dan perbankan digital.
</p>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">1. Dasar Hukum Pemrosesan Data Pribadi</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Pemrosesan data pribadi oleh INDOROSTER dilakukan berdasarkan landasan hukum yang sah sesuai Pasal 20 UU PDP, meliputi:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2 mb-4">
    <li><strong>Persetujuan Eksplisit (Consent):</strong> Persetujuan yang Anda berikan saat mendaftar akun, mengisi formulir penawaran harga, atau memesan produk roster beton.</li>
    <li><strong>Kewajiban Kontraktual (Contractual Obligation):</strong> Pemrosesan data yang diperlukan untuk memenuhi kontrak pemesanan, pencetakan material, penerbitan invoice resmi, dan pengiriman kargo armada pabrik.</li>
    <li><strong>Kepatuhan Regulasi (Legal Compliance):</strong> Kewajiban penyimpanan arsip transaksi dan perpajakan (PPN/PPh faktur) sesuai hukum Indonesia.</li>
    <li><strong>Kepentingan yang Sah (Legitimate Interests):</strong> Meningkatkan performa sistem, mencegah penipuan (anti-fraud), dan menjamin mutu pelayanan pelanggan.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">2. Jenis Data Pribadi yang Kami Kumpulkan</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
    Kami mengumpulkan informasi yang relevan dan terbatas pada kebutuhan transaksi serta operasional, antara lain:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2.5 mb-4">
    <li><strong>Data Identitas Pribadi / Perusahaan:</strong> Nama lengkap pemesan, nama penanggung jawab lapangan (mandor/arsitek/penerima barang), nama badan usaha/PT/CV (untuk pemesanan proyek/B2B), dan NPWP (opsional, untuk penerbitan Faktur Pajak).</li>
    <li><strong>Informasi Kontak Komunikasi:</strong> Alamat email aktif, nomor telepon genggam, dan nomor WhatsApp aktif yang digunakan untuk koordinasi pesanan serta pengiriman surat jalan digital.</li>
    <li><strong>Data Alamat Proyek &amp; Geografis:</strong> Alamat lengkap tujuan kirim (Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan/Desa, Kode Pos, patokan jalan), catatan akses jalan armada truk (kapasitas tonase Colt Diesel/Fuso), serta titik koordinat GPS lokasi proyek jika dibagikan oleh pelanggan.</li>
    <li><strong>Data Riwayat Transaksi &amp; Dokumen:</strong> Rincian motif roster yang dipesan, volume/jumlah keping, riwayat pembayaran, salinan Surat Jalan (DO), Berita Acara Serah Terima (BAST), dan tanda tangan digital saat penerimaan barang.</li>
    <li><strong>Data Teknis &amp; Navigasi Web:</strong> Alamat Protokol Internet (IP Address), jenis browser, sistem operasi, resolusi layar, halaman yang dikunjungi, durasi sesi, dan interaksi formulir (dikumpulkan secara anonim melalui sistem analitik).</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">3. Tujuan Penggunaan Data Pribadi</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
    Data pribadi yang terkumpul digunakan semata-mata untuk tujuan operasional dan peningkatan kepuasan pelanggan:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2 mb-4">
    <li>Memvalidasi dan memproses pesanan roster beton secara akurat dari pabrik ke antrean pengiriman.</li>
    <li>Mengkoordinasikan jadwal keberangkatan armada truk pabrik dan kurir logistik rekanan ke alamat proyek Anda.</li>
    <li>Menerbitkan dokumen komersial sah meliputi Bukti Pembayaran, Faktur Komersial, Surat Jalan Pabrik, dan Resi Pelacakan Online.</li>
    <li>Memberikan layanan purnajual (<em>after-sales</em>), konsultasi teknis pemasangan, perhitungan volume dinding, dan klaim <strong>Garansi 100% Ganti Baru Roster Pecah</strong>.</li>
    <li>Mengirimkan notifikasi status pemesanan melalui WhatsApp Gateway resmi dan email transaksional otomatis.</li>
    <li>Mendeteksi, mencegah, dan menindaklanjuti aktivitas mencurigakan atau potensi penipuan transaksi online.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">4. Keamanan Transaksi &amp; Pembayaran (Midtrans Payment Gateway)</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    INDOROSTER menjunjung tinggi standar keamanan finansial tertinggi:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2 mb-4">
    <li><strong>Enkripsi Jalur Komunikasi:</strong> Seluruh pertukaran data antara browser Anda dan server kami dienkripsi menggunakan protokol <strong>Transport Layer Security (TLS 1.3 / SSL 256-bit)</strong> bersertifikat resmi.</li>
    <li><strong>Tanpa Penyimpanan Data Finansial Sensitif:</strong> Kami <strong>tidak pernah menyimpan</strong> nomor kartu kredit lengkap, tanggal kedaluwarsa, PIN, atau kode keamanan CVV/CVC di server kami.</li>
    <li><strong>Sertifikasi PCI-DSS:</strong> Pemrosesan pembayaran otomatis dijalankan sepenuhnya oleh mitra gerbang pembayaran resmi <strong>PT Midtrans (Midtrans)</strong> yang telah tersertifikasi <em>PCI-DSS Level 1</em> (standar keamanan pembayaran tertinggi dunia) dan diawasi langsung oleh Bank Indonesia.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">5. Kerahasiaan &amp; Pembagian Data kepada Pihak Ketiga</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    INDOROSTER memiliki kebijakan <strong>Nol Toleransi terhadap Penjualan Data</strong>. Kami tidak pernah dan tidak akan pernah menjual, menyewakan, memperdagangkan, atau menyebarluaskan data pribadi Anda kepada broker data atau pihak ketiga mana pun untuk tujuan periklanan yang tidak relevan.
</p>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Data Anda hanya dapat dibagikan kepada pihak ketiga dalam kondisi terbatas berikut:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2 mb-4">
    <li><strong>Mitra Pengemudi &amp; Logistik Ekspedisi:</strong> Pembagian nama penerima, nomor telepon kontak darurat di lokasi proyek, dan alamat pengantaran fisik semata-mata agar armada truk pengangkut dapat mengantarkan material roster tepat waktu.</li>
    <li><strong>Penyedia Infrastruktur Cloud &amp; Email:</strong> Layanan hosting terenkripsi dan SMTP email resmi yang terikat perjanjian kerahasiaan ketat.</li>
    <li><strong>Kewajiban Penegakan Hukum:</strong> Apabila diwajibkan oleh penetapan pengadilan, proses hukum perdata/pidana, atau regulasi perpajakan yang sah dari pemerintah Republik Indonesia.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">6. Penyimpanan &amp; Retensi Data Pribadi</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Data pribadi Anda disimpan pada server basis data yang aman dan berlokasi di pusat data bereputasi tinggi dengan perlindungan firewall bertingkat.
</p>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Kami menyimpan data transaksi selama akun Anda aktif atau selama jangka waktu yang diwajibkan oleh undang-undang perpajakan dan pembukuan komersial di Indonesia (minimal 5 hingga 10 tahun untuk arsip invoice dan faktur resmi). Setelah periode retensi berakhir, data non-transaksional akan dimusnahkan secara aman atau dianonimkan untuk keperluan statistik murni.
</p>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">7. Kebijakan Cookie &amp; Penyimpanan Lokal Browser</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Situs <em>indoroster.com</em> menggunakan cookie dan penyimpanan lokal (<em>local storage</em>) browser untuk keperluan teknis:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2 mb-4">
    <li><strong>Cookie Fungsional Esensial:</strong> Menyimpan sesi login member yang aman, token proteksi CSRF dari serangan siber, dan item sementara di keranjang belanja.</li>
    <li><strong>Cookie Preferensi Pengguna:</strong> Mengingat pilihan tema tampilan visual (Mode Gelap / Terang) dan preferensi wilayah pengiriman Anda.</li>
    <li><strong>Analitik &amp; Kinerja:</strong> Memantau kecepatan muat halaman dan menemukan kendala tautan rusak tanpa mengidentifikasi identitas personal Anda secara langsung.</li>
</ul>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Anda dapat mengatur atau mematikan penggunaan cookie melalui pengaturan browser Anda, meskipun beberapa fitur belanja otomatis mungkin menjadi terbatas.
</p>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">8. Hak-Hak Pemilik Data Pribadi</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Sesuai ketentuan UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi, Anda memiliki hak penuh terhadap data pribadi Anda yang tersimpan di sistem kami:
</p>
<ul class="list-disc pl-6 text-slate-600 dark:text-slate-300 space-y-2 mb-4">
    <li><strong>Hak Memperoleh Informasi:</strong> Mengetahui kejelasan identitas pengendali data, tujuan penggunaan, dan riwayat pemrosesan data pribadi Anda.</li>
    <li><strong>Hak Akses &amp; Salinan:</strong> Mendapatkan akses dan salinan data profil akun serta riwayat pesanan Anda.</li>
    <li><strong>Hak Pembaruan &amp; Koreksi (Rectification):</strong> Memperbarui atau memperbaiki data diri yang tidak akurat, tidak lengkap, atau sudah kedaluwarsa melalui menu Profil Member.</li>
    <li><strong>Hak Penghapusan &amp; Pemusnahan (Right to Erasure):</strong> Mengajukan permohonan penutupan akun dan penghapusan data pribadi Anda, sepanjang tidak bertentangan dengan kewajiban retensi arsip hukum transaksi.</li>
    <li><strong>Hak Menarik Persetujuan:</strong> Menolak atau menarik kembali persetujuan pemrosesan data untuk keperluan komunikasi promosi atau newsletter berkala.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">9. Privasi Anak di Bawah Umur</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    Layanan dan transaksi material bangunan di situs INDOROSTER ditujukan untuk pengguna yang telah cakap hukum (berusia minimal 18 tahun atau telah menikah) atau perwakilan sah dari badan usaha. Kami tidak secara sengaja mengumpulkan data pribadi dari anak-anak di bawah umur.
</p>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">10. Pembaruan Kebijakan Privasi</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
    INDOROSTER dapat meninjau dan memperbarui Kebijakan Privasi ini dari waktu ke waktu untuk menyesuaikan dengan pembaruan fitur teknologi, peningkatan standar keamanan siber, atau perubahan regulasi hukum pemerintah. Setiap revisi akan dicantumkan dengan tanggal pembaruan terbaru di halaman ini. Kami mengimbau Anda untuk memeriksa halaman ini secara berkala.
</p>

<h3 class="text-xl font-bold text-slate-900 dark:text-white mt-8 mb-3">11. Kontak Petugas Pelindungan Data (DPO) &amp; Bantuan Privasi</h3>
<p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
    Apabila Anda memiliki pertanyaan, klarifikasi, keluhan, atau ingin melaksanakan hak-hak data pribadi Anda sebagaimana tercantum di atas, silakan hubungi tim resmi kami:
</p>
<div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 mb-6 not-prose">
    <p class="font-bold text-slate-900 dark:text-white text-sm mb-4">PUSAT LAYANAN &amp; DATA PROTECTION INDOROSTER:</p>
    <ul class="space-y-3 text-xs sm:text-sm text-slate-700 dark:text-slate-300 font-medium">
        <li class="flex items-start gap-3">
            <span class="text-terra-600 dark:text-terra-400 font-bold shrink-0">🏭 Lokasi Pabrik:</span>
            <span>Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165</span>
        </li>
        <li class="flex items-center gap-3">
            <span class="text-terra-600 dark:text-terra-400 font-bold shrink-0">📞 WhatsApp CS Pabrik:</span>
            <a href="https://wa.me/6281389709847" target="_blank" class="text-emerald-700 dark:text-emerald-400 font-bold hover:underline">0813-8970-9847</a>
        </li>
        <li class="flex items-center gap-3">
            <span class="text-terra-600 dark:text-terra-400 font-bold shrink-0">✉️ Email Resmi:</span>
            <a href="mailto:abdulhamid66266@gmail.com" class="text-terra-600 dark:text-terra-400 font-bold hover:underline">abdulhamid66266@gmail.com</a>
        </li>
        <li class="flex items-center gap-3">
            <span class="text-terra-600 dark:text-terra-400 font-bold shrink-0">🌐 Website Resmi:</span>
            <a href="https://indoroster.com" target="_blank" class="text-terra-600 dark:text-terra-400 font-bold hover:underline">https://indoroster.com</a>
        </li>
    </ul>
</div>
HTML;

        Page::updateOrCreate(
            ['slug' => 'kebijakan-privasi'],
            [
                'title' => 'Kebijakan Privasi',
                'meta_title' => 'Kebijakan Privasi & Perlindungan Data Pelanggan (UU PDP) | IndoRoster',
                'meta_description' => 'Kebijakan privasi resmi perlindungan data pribadi pelanggan, keamanan transaksi Midtrans PCI-DSS, dan kepatuhan UU PDP No. 27 Tahun 2022 di IndoRoster Indonesia.',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'title' => 'Kebijakan Privasi & Perlindungan Data Konsumen',
                            'content' => $privacyContent,
                            'bg_theme' => 'white',
                            'max_width' => '4xl',
                            'alignment' => 'left',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
