<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tncContent = <<<'HTML'
<p class="text-slate-600 leading-relaxed mb-6">
    Selamat datang di <strong>INDOROSTER</strong> (<em>indoroster.com</em>). Syarat dan Ketentuan ini mengatur penggunaan situs web, pemesanan produk roster arsitektural beton, proses transaksi pembayaran melalui gerbang pembayaran <strong>Midtrans</strong>, hingga layanan pengiriman dan klaim garansi.
</p>
<p class="text-slate-600 leading-relaxed mb-6">
    Dengan mengakses situs ini, mendaftarkan akun, atau melakukan pemesanan, Anda menyatakan telah membaca, memahami, dan menyetujui untuk terikat dengan seluruh Syarat &amp; Ketentuan di bawah ini. Jika Anda tidak menyetujui salah satu bagian dari ketentuan ini, mohon untuk tidak melanjutkan penggunaan situs atau layanan kami.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">1. Ketentuan Penggunaan (Conditions of Use &amp; Overview)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Layanan situs web INDOROSTER disediakan untuk Anda dengan syarat penerimaan penuh tanpa modifikasi atas syarat, ketentuan, dan pemberitahuan yang tercantum di sini. Penggunaan situs ini merupakan bentuk persetujuan hukum yang mengikat antara Anda sebagai Pelanggan dan INDOROSTER sebagai Penyedia Produk &amp; Layanan.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">2. Perubahan Ketentuan &amp; Koreksi Informasi (Modifications &amp; Price Errors)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    INDOROSTER berhak untuk memperbarui, mengubah, atau menyesuaikan Syarat dan Ketentuan, harga produk, deskripsi material, serta ketersediaan stok kapan saja tanpa pemberitahuan sebelumnya.
</p>
<p class="text-slate-600 leading-relaxed mb-4">
    Apabila terdapat kesalahan ketik teknis atau kekeliruan sistem mengenai harga atau ketersediaan barang pada saat pemesanan dibuat, INDOROSTER berhak untuk mengoreksi pesanan atau menolak/membatalkan pesanan tersebut dan mengembalikan dana penuh yang telah dibayarkan oleh Pelanggan.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">3. Hak Cipta &amp; Kekayaan Intelektual (Copyrights)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Seluruh materi dalam situs web ini — termasuk namun tidak terbatas pada merek/logo INDOROSTER, foto produk asli pabrik, desain roster arsitektural, grafis, teks, video dokumentasi produksi, dan perangkat lunak — adalah milik eksklusif INDOROSTER dan dilindungi oleh Undang-Undang Hak Cipta Republik Indonesia serta peraturan kekayaan intelektual internasional. Dilarang keras menyalin, memperbanyak, memodifikasi, mendistribusikan ulang, atau memanfaatkan konten situs ini tanpa izin tertulis dari INDOROSTER.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">4. Pendaftaran Akun &amp; Keamanan (Sign Up &amp; Security)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Untuk melakukan pemesanan dan pelacakan pesanan secara optimal, pengguna disarankan membuat akun dengan memberikan informasi yang akurat, valid, dan terkini (termasuk alamat pengiriman dan nomor kontak aktif). Anda bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi akun Anda dan atas semua aktivitas yang dilakukan di bawah akun tersebut.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">5. Komunikasi Elektronik (Electronic Communications)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Dengan menggunakan layanan kami, Anda menyetujui bahwa kami dapat berkomunikasi dengan Anda secara elektronik melalui email (<strong>abdulhamid66266@gmail.com</strong>) dan/atau pesan WhatsApp resmi pabrik (<strong>0813-8970-9847</strong>) untuk keperluan konfirmasi pesanan, penerbitan invoice/faktur digital, status pelacakan pengiriman kargo, serta informasi penting seputar pesanan Anda.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">6. Deskripsi &amp; Karakteristik Produk (Product Descriptions)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Kami berusaha semaksimal mungkin menampilkan foto, spesifikasi dimensi (cm), dan varian warna roster seakurat mungkin di layar. Namun demikian, karena produk roster beton dibuat menggunakan formula semen dan agregat pasir alami standar mutu K-200, variasi minor pada gradasi warna alami atau tekstur semen merupakan karakteristik wajar dari produk cetak beton arsitektural.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">7. Harga &amp; Metode Pembayaran (Pricing &amp; Payment Gateway)</h3>
<ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
    <li>Seluruh harga yang tertera di situs menggunakan mata uang resmi <strong>Rupiah (IDR)</strong>.</li>
    <li>Transaksi pembayaran diproses secara terenkripsi dan aman melalui <em>Payment Gateway</em> resmi <strong>Midtrans</strong> (PT Midtrans) yang telah berlisensi Bank Indonesia.</li>
    <li>Metode pembayaran yang tersedia meliputi: <strong>Virtual Account Bank (BCA, Mandiri, BNI, BRI, Permata), QRIS (GoPay, OVO, Dana, ShopeePay), Transfer Bank, dan Kartu Kredit/Debit</strong>.</li>
    <li>Pesanan akan diproses ke tahap verifikasi dan pengemasan/produksi setelah konfirmasi pembayaran berhasil diverifikasi oleh sistem Midtrans secara otomatis (<em>real-time</em>).</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">8. Pengiriman &amp; Pengalihan Risiko (Risk of Loss &amp; Shipping)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Pengiriman material roster dilakukan menggunakan armada logistik resmi pabrik INDOROSTER (untuk area Jabodetabek, Purwakarta, Bandung, dan sekitarnya) atau melalui jasa ekspedisi kargo rekanan terpercaya ke seluruh wilayah Indonesia. Biaya pengiriman dihitung berdasarkan lokasi tujuan dan volume/berat pesanan. Pengalihan risiko barang selama proses pengiriman dijamin sesuai dengan klausul garansi pengiriman IndoRoster.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">9. Ketentuan Pengembalian, Retur &amp; Garansi 100% Pecah (Return &amp; Refund Policy)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    INDOROSTER memberikan <strong>Garansi Penggantian Baru 100%</strong> untuk memastikan kepuasan dan ketenangan berbelanja Anda:
</p>
<ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
    <li><strong>Garansi Pecah Pengiriman:</strong> Apabila terdapat unit roster yang mengalami kerusakan, patah, atau pecah saat diterima, kami akan mengganti unit tersebut secara gratis.</li>
    <li><strong>Syarat Klaim Retur:</strong> Pelanggan wajib melaporkan kerusakan dalam kurun waktu maksimal <strong>2 x 24 jam</strong> sejak barang tiba di lokasi pengiriman, disertai bukti foto jelas dan/atau video saat pembongkaran serta foto Surat Jalan / Resi penerimaan barang.</li>
    <li><strong>Pengembalian Dana (Refund):</strong> Apabila penggantian unit tidak memungkinkan (misal stok khusus habis atau pesanan dibatalkan sesuai ketentuan), pengembalian dana (refund) akan diproses kembali ke rekening atau metode pembayaran asal pembeli melalui sistem Midtrans dalam waktu <strong>3 - 7 hari kerja</strong> sesuai prosedur perbankan.</li>
    <li>Barang yang sudah terpasang dengan adukan semen di dinding proyek tidak dapat diklaim sebagai retur pecah pengiriman.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">10. Pembatalan Pesanan (Order Cancellation)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Pembatalan pesanan hanya dapat dilakukan sebelum barang dimuat ke armada pengiriman. Untuk pesanan custom khusus yang telah memasuki proses cetak/produksi massal, pembatalan sepihak tidak dapat dilakukan. Hubungi Customer Support kami segera jika Anda membutuhkan perubahan jadwal pengiriman.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">11. Pembatasan Tanggung Jawab &amp; Ganti Rugi (Indemnity &amp; Disclaimer)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    INDOROSTER tidak bertanggung jawab atas keterlambatan pengiriman yang disebabkan oleh keadaan kahar (<em>Force Majeure</em>) seperti bencana alam, kerusuhan, blokade jalan, atau gangguan lalu lintas ekstrem di luar kendali wajar. Anda setuju untuk membela, mengganti rugi, dan membebaskan INDOROSTER dari segala bentuk tuntutan pihak ketiga yang timbul akibat penyalahgunaan situs atau pelanggaran Syarat dan Ketentuan oleh Anda.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">12. Hukum yang Berlaku (Applicable Laws)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Syarat dan Ketentuan ini diatur dan ditafsirkan sepenuhnya berdasarkan hukum yang berlaku di <strong>Republik Indonesia</strong>. Setiap sengketa yang timbul akan diselesaikan terlebih dahulu melalui musyawarah untuk mufakat, atau melalui yurisdiksi pengadilan di wilayah hukum Republik Indonesia.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">13. Pertanyaan, Saran &amp; Layanan Pelanggan (Contact &amp; Support)</h3>
<p class="text-slate-600 leading-relaxed mb-3">
    Jika Anda memiliki pertanyaan mengenai Syarat &amp; Ketentuan ini atau membutuhkan bantuan transaksi, silakan hubungi kontak resmi kami:
</p>
<div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-6 not-prose">
    <p class="font-bold text-slate-900 text-sm mb-3">LOKASI &amp; KONTAK RESMI PABRIK:</p>
    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 font-medium">
        <li class="flex items-start gap-2.5">
            <span class="text-terra-600 font-bold shrink-0">📍 Alamat:</span>
            <span>Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165</span>
        </li>
        <li class="flex items-center gap-2.5">
            <span class="text-terra-600 font-bold shrink-0">📞 WhatsApp / Telp:</span>
            <a href="https://wa.me/6281389709847" target="_blank" class="text-emerald-700 font-bold hover:underline">0813-8970-9847</a>
        </li>
        <li class="flex items-center gap-2.5">
            <span class="text-terra-600 font-bold shrink-0">✉️ Email:</span>
            <a href="mailto:abdulhamid66266@gmail.com" class="text-terra-600 font-bold hover:underline">abdulhamid66266@gmail.com</a>
        </li>
    </ul>
</div>
HTML;

        $privacyContent = <<<'HTML'
<p class="text-slate-600 leading-relaxed mb-6">
    INDOROSTER (<em>indoroster.com</em>) sangat menghargai privasi setiap pelanggan dan pengunjung situs kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, melindungi, dan memperlakukan informasi pribadi yang Anda berikan saat bertransaksi di situs kami.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">1. Data Pribadi yang Kami Kumpulkan</h3>
<p class="text-slate-600 leading-relaxed mb-3">
    Kami mengumpulkan informasi yang Anda berikan secara sukarela ketika mendaftar, melakukan pemesanan, atau menghubungi kami, antara lain:
</p>
<ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
    <li><strong>Identitas Diri:</strong> Nama lengkap dan nama penerima di lokasi proyek.</li>
    <li><strong>Informasi Kontak:</strong> Alamat email dan nomor telepon / WhatsApp aktif.</li>
    <li><strong>Alamat Pengiriman:</strong> Alamat lengkap proyek / rumah (Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan, Kode Pos, dan catatan panduan lokasi).</li>
    <li><strong>Data Transaksi:</strong> Rincian produk yang dipesan, riwayat pembayaran, dan nomor resi/surat jalan pengiriman.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">2. Penggunaan Informasi Pelanggan</h3>
<p class="text-slate-600 leading-relaxed mb-3">
    Informasi yang dikumpulkan digunakan secara ketat untuk keperluan:
</p>
<ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
    <li>Memproses, memverifikasi, dan menyelesaikan transaksi pemesanan produk roster Anda.</li>
    <li>Mengkoordinasikan pengiriman armada pabrik / kurir ekspedisi ke lokasi proyek Anda.</li>
    <li>Mengirimkan faktur/invoice digital, bukti pembayaran, dan nomor pelacakan pesanan.</li>
    <li>Memberikan layanan bantuan pelanggan (<em>Customer Support</em>) dan klaim garansi produk.</li>
    <li>Meningkatkan kualitas antarmuka dan pengalaman belanja di situs web kami.</li>
</ul>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">3. Keamanan Transaksi Pembayaran (Midtrans Payment Gateway)</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Kami tidak pernah menyimpan informasi sensitif perbankan Anda (seperti nomor kartu kredit lengkap, masa berlaku, atau kode CVV) di server kami. Seluruh proses pembayaran diproses secara langsung melalui sistem gerbang pembayaran terenkripsi <strong>Midtrans</strong> (PT Midtrans) yang berstandar keamanan internasional <em>PCI-DSS Level 1</em> dan diawasi oleh Bank Indonesia.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">4. Kerahasiaan &amp; Tidak Ada Penjualan Data</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    INDOROSTER berkomitmen penuh untuk <strong>tidak pernah menjual, menyewakan, memperdagangkan, atau membagikan</strong> data pribadi Anda kepada pihak ketiga mana pun untuk kepentingan pemasaran eksternal tanpa persetujuan Anda, kecuali diperlukan secara langsung oleh mitra logistik/ekspedisi yang bertugas mengantar barang ke alamat Anda atau diwajibkan oleh hukum yang berlaku di Indonesia.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">5. Penggunaan Cookie &amp; Teknologi Web</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Situs kami dapat menggunakan <em>cookies</em> sesi untuk mengingat preferensi keranjang belanja, status login akun Anda, serta analisis performa situs web guna memberikan pengalaman navigasi yang lebih cepat dan nyaman.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">6. Hak Pengguna &amp; Penghapusan Data</h3>
<p class="text-slate-600 leading-relaxed mb-4">
    Anda berhak untuk mengakses, memperbarui, atau meminta penghapusan informasi profil akun Anda di situs kami kapan saja melalui menu Akun Pengguna atau dengan menghubungi tim Customer Service kami.
</p>

<h3 class="text-xl font-bold text-slate-900 mt-8 mb-3">7. Kontak Resmi Privasi &amp; Perlindungan Data</h3>
<p class="text-slate-600 leading-relaxed mb-3">
    Jika Anda memiliki pertanyaan seputar Kebijakan Privasi ini atau pengelolaan data pribadi Anda, silakan hubungi kami di:
</p>
<div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-6 not-prose">
    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 font-medium">
        <li class="flex items-start gap-2.5">
            <span class="text-terra-600 font-bold shrink-0">📍 Alamat Pabrik:</span>
            <span>Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165</span>
        </li>
        <li class="flex items-center gap-2.5">
            <span class="text-terra-600 font-bold shrink-0">📞 WhatsApp CS:</span>
            <a href="https://wa.me/6281389709847" target="_blank" class="text-emerald-700 font-bold hover:underline">0813-8970-9847</a>
        </li>
        <li class="flex items-center gap-2.5">
            <span class="text-terra-600 font-bold shrink-0">✉️ Email Resmi:</span>
            <a href="mailto:abdulhamid66266@gmail.com" class="text-terra-600 font-bold hover:underline">abdulhamid66266@gmail.com</a>
        </li>
    </ul>
</div>
HTML;

        // Terms and conditions page
        $tncBlocks = [
            [
                'type' => 'rich_text',
                'data' => [
                    'title' => 'Syarat & Ketentuan Layanan',
                    'content' => $tncContent,
                    'bg_theme' => 'white',
                    'max_width' => '4xl',
                    'alignment' => 'left',
                ],
            ],
        ];

        DB::table('pages')->updateOrInsert(
            ['slug' => 'syarat-dan-ketentuan'],
            [
                'title' => 'Syarat & Ketentuan',
                'meta_title' => 'Syarat & Ketentuan Transaksi - Pabrik IndoRoster Indonesia',
                'meta_description' => 'Syarat dan Ketentuan resmi transaksi pemesanan, pembayaran Midtrans, pengiriman roster beton, dan garansi ganti baru di IndoRoster Indonesia.',
                'content' => json_encode($tncBlocks),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Privacy Policy page
        $privacyBlocks = [
            [
                'type' => 'rich_text',
                'data' => [
                    'title' => 'Kebijakan Privasi',
                    'content' => $privacyContent,
                    'bg_theme' => 'white',
                    'max_width' => '4xl',
                    'alignment' => 'left',
                ],
            ],
        ];

        DB::table('pages')->updateOrInsert(
            ['slug' => 'kebijakan-privasi'],
            [
                'title' => 'Kebijakan Privasi',
                'meta_title' => 'Kebijakan Privasi & Keamanan Data - IndoRoster',
                'meta_description' => 'Kebijakan privasi perlindungan data pelanggan dan keamanan transaksi pembayaran Midtrans di IndoRoster Indonesia.',
                'content' => json_encode($privacyBlocks),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->whereIn('slug', ['syarat-dan-ketentuan', 'kebijakan-privasi'])->delete();
    }
};
