<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateProductDefaults extends Command
{
    protected $signature = 'products:update-defaults';

    protected $description = 'Update existing products with default description and SKU if missing';

    public function handle()
    {
        $template = '<h3>📝 PANDUAN PEMESANAN & LAYANAN KONSUMEN</h3><p>Di Indoroster, belanja roster jadi jauh lebih praktis. <strong>Anda tidak perlu login atau daftar akun</strong> untuk melakukan pemesanan. Cukup pilih, bayar, dan tunggu barang sampai!</p><h4>1. Cara Pemesanan (Tanpa Login)</h4><ul><li><strong>Pilih & Hitung:</strong> Gunakan kalkulator di atas untuk tahu jumlah yang dibutuhkan.</li><li><strong>Beli Langsung:</strong> Masukkan jumlah pcs dan klik Beli Sekarang.</li><li><strong>Isi Data:</strong> Langsung isi nama dan alamat pengiriman tanpa harus daftar akun.</li><li><strong>Terima Invoice:</strong> Setelah pembayaran berhasil, Anda akan langsung menerima Invoice Resmi sebagai bukti transaksi yang sah.</li></ul><h4>2. Informasi yang Akan Kami Kirimkan ke Anda</h4><p>Setelah Anda melakukan pemesanan, tim Admin kami akan menghubungi Anda melalui <strong>WhatsApp</strong> untuk memberikan informasi berikut:</p><ul><li><strong>Konfirmasi Pembayaran:</strong> Kami akan mengirimkan notifikasi bahwa dana Anda sudah kami terima dan pesanan masuk antrean.</li><li><strong>Validasi Pesanan & Alamat:</strong> Kami akan melakukan verifikasi ulang mengenai item yang dipesan dan titik lokasi pengiriman agar tidak ada kesalahan kirim.</li><li><strong>Jadwal Pengiriman:</strong> Kami akan menginfokan Hari & Jam estimasi truk kami sampai di lokasi Anda.</li><li><strong>Informasi Driver:</strong> Saat barang dalam perjalanan, kami akan memberikan informasi driver/armada yang bertugas agar Anda mudah berkoordinasi di lokasi.</li></ul><h4>3. Hubungi Kami</h4><p>Butuh info lebih lanjut? Hubungi kami langsung di:</p><ul><li><strong>WhatsApp Official:</strong> <a href="https://wa.me/6281389709847">0813 8970 9847</a></li><li><strong>Jam Operasional:</strong> Senin - Sabtu (08.00 - 17.00 WIB)</li></ul><p>🛡️ <strong>Jaminan Kami:</strong> Kami menjamin setiap pesanan akan mendapatkan layanan personal. Anda tidak akan dibiarkan menunggu tanpa kepastian. Semua status pengiriman akan diinfokan secara berkala oleh Admin kami.</p>';

        $products = Product::all();
        $count = 0;

        foreach ($products as $product) {
            $updated = false;

            // Update SKU if empty
            if (empty($product->sku)) {
                $product->sku = 'IR-'.strtoupper(Str::random(6));
                $updated = true;
            }

            // Append template if not already present
            if (! Str::contains($product->description, 'PANDUAN PEMESANAN')) {
                $product->description = $product->description.'<br><br>'.$template;
                $updated = true;
            }

            if ($updated) {
                $product->save();
                $count++;
            }
        }

        $this->info("Successfully updated {$count} products.");
    }
}
