# 📦 Dokumentasi Lengkap Sistem Pesanan Khusus WhatsApp (WA Order)
> 📘 **Buku Panduan & SOP Lengkap Admin**: Tersedia di [PANDUAN_OPERASIONAL_PESANAN_WA.md](file:///c:/xampp/htdocs/indoroster/docs/admin/PANDUAN_OPERASIONAL_PESANAN_WA.md) (Format SOP panduan operasional langkah demi langkah).

---

## 📌 1. Rangkuman Pengecekan Sistem Sebelumnya

1. **CRM & Database Pelanggan**:
   * Modul CRM aktif di menu **"CRM & Pelanggan" → "Database Pelanggan & Lead"** ([CustomerResource.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/CustomerResource.php)).
   * Memiliki segmentasi mitra (Pemilik Rumah, Kontraktor, Arsitek, Kafe/Resto, Developer), tahapan status lead, tombol 1-klik kirim WA, dan export CSV.
2. **Pengiriman Email & Konfigurasi SMTP**:
   * Menggunakan driver SMTP Google Mail (`smtp.gmail.com:587`, TLS).
   * **Pengirim Resmi (*From Address*)**: `noreply.indoroster@gmail.com`.
   * **Nama Pengirim (*From Name*)**: `Indoroster` / `Tim Indoroster` / `Tim Keamanan Indoroster`.
3. **Pendaftaran Akun Baru & Ganti Password**:
   * Pendaftaran di `/register`: Validasi, auto-merge keranjang belanja, auto-hash password, dan pemicu email verifikasi otomatis.
   * Lupa / Reset Password di `/forgot-password` & `/reset-password/{token}`: Token aman 60 menit dengan notifikasi email resmi.

---

## 🚀 2. Fitur Baru yang Selesai Dibangun: "Pesanan Khusus WhatsApp"

Sistem kasir dan penerimaan pesanan jalur WhatsApp yang terpisah dari pesanan reguler web:

### A. Pemisahan Modul & Penomoran Dokumen (Dijamin Tidak Bentrok)
* **Menu Tersendiri**: Modul **`🟢 Pesanan Khusus WhatsApp`** di grup menu **Penjualan## 🚀 Fitur Utama

1. **Format Penomoran Otomatis**: Prefix `INV-WA-YYYYMMDD-XXXX` (membedakan pesanan WhatsApp dari website online).
2. **Siklus Lengkap (Penawaran ➔ DP ➔ Termin ➔ Pelunasan 100%)**:
   - **Tahap Penawaran**: Status `DRAFT / PENAWARAN / MENUNGGU PEMBAYARAN DP` dengan petunjuk rekening resmi pabrik.
   - **Pembayaran Bertahap & Kuitansi Terhubung**: Setiap uang DP atau termin masuk dapat dicatat, menerbitkan Kuitansi Resmi (`KW-WA-...`), dan otomatis terhubung di tabel riwayat pembayaran Invoice PDF.
   - **Pelunasan 100%**: 1-klik catat pelunasan sisa tagihan dengan stempel & watermark LUNAS sah.
3. **Fulfillment 3 Mode**: `Ready Stock`, `PO Single`, `PO Batch (Pengiriman Bertahap Truk)`.ery**: [OrderResource.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/OrderResource.php) otomatis memfilter agar pesanan WA tidak bercampur di tabel pesanan web.

### B. Form Input Kasir / Admin Fleksibel
* **Data Pembeli & Titik GPS**:
  * Input Nama Pembeli, No. WhatsApp, Email (opsional), dan Alamat Proyek.
  * Input **Latitude & Longitude GPS** (lengkap dengan tombol *"Peta"* yang membuka Google Maps navigasi untuk supir truk).
  * Opsional menghubungkan ke akun pelanggan terdaftar (auto-fill kontak & alamat).
* **Daftar Barang (Multi-Item Repeater)**:
  * **Mode Katalog DB**: Pilih motif dari database produk IndoRoster, otomatis kalkulasi harga varian.
  * **Mode Ketik Manual / Custom**: Bebas mengetik nama pesanan khusus (misal *"Roster Custom Motif Kawung 20x20 Abu"*), varian/spek custom (*"Abu Natural K-200 Tebal 10cm"*), harga satuan kesepakatan, jumlah keping (qty), dan catatan item.
  * Kalkulasi otomatis Subtotal, Ongkir Truk Pabrik, Diskon, dan Grand Total.

### C. 3 Tipe Pemenuhan (*Fulfillment Mode*) & Penugasan Kurir
* **📦 Ready Stock**: Input jadwal keberangkatan truk gudang.
* **🏭 PO Single**: Input tanggal mulai cetak pabrik, tanggal selesai cetak & QC, dan estimasi tiba di lokasi proyek.
* **🚛 PO Multi-Batch**: Input rencana jumlah rit truk armada (batch) dan alokasi pembagian keping per rit.
* **Penugasan Kurir**: Pilihan akun supir internal (`role = courier`) atau input ekspedisi/supir sewa luar.

### D. Otomasi Dokumen Resmi & Notifikasi WhatsApp
* **Invoice Resmi (PDF)**: Otomatis memuat rincian barang kustom, status bayar/DP, serta **Tanda Tangan & Stempel Resmi Basah IndoRoster** (Divisi Keuangan & Distribusi Pabrik).
* **Surat Jalan (PDF)**: Otomatis memuat detail muatan keping, nama mandor/penerima, dan nomor supir truk.
* **Aksi Cepat WhatsApp (1-Klik)**: Pilihan template pesan instan (Tagihan & Invoice Baru + Link PDF, Update Produksi Pabrik, Update Truk Meluncur ke Proyek, Update Material Sampai di Lokasi, dan Pesan Kustom Bebas).
* **Lacak Live Pesanan**: Pembeli dapat melacak status live pesanan & peta rute armada truk di `/lacak-pesanan` menggunakan nomor invoice WA dan nomor WhatsApp mereka.

---

## 📁 3. Daftar File yang Dibuat & Dimodifikasi

### File Baru:
1. `database/migrations/2026_08_28_160000_add_whatsapp_order_and_custom_items_fields.php`
2. `app/Filament/Resources/WaOrderResource.php`
3. `app/Filament/Resources/WaOrderResource/Pages/ListWaOrders.php`
4. `app/Filament/Resources/WaOrderResource/Pages/CreateWaOrder.php`
5. `app/Filament/Resources/WaOrderResource/Pages/EditWaOrder.php`
6. `app/Filament/Resources/WaOrderResource/Pages/ViewWaOrder.php`
7. `tests/Feature/WaOrderFulfillmentTest.php`

### File Dimodifikasi:
1. `app/Models/Order.php`: Menambahkan `order_source` ke fillable & helper `generateWaOrderNumber()`.
2. `app/Models/OrderItem.php`: Menambahkan dukungan item kustom manual tanpa `product_id`, `custom_variant_name`, dan `item_notes`.
3. `app/Models/Invoice.php`: Menambahkan helper generator `generateWaInvoiceNumber()`.
4. `app/Models/ShippingLabel.php`: Menambahkan helper generator `generateWaLabelNumber()`.
5. `app/Observers/OrderObserver.php`: Integrasi pembuatan invoice & shipping label berformat WA.
6. `app/Filament/Resources/OrderResource.php`: Filter query pesanan web reguler agar tidak bercampur dengan pesanan WA.
7. `resources/views/print/invoice.blade.php` & `invoice-preview.blade.php`: Penanganan null-safe pada item custom dan render varian kustom.

---

## 🛠️ 4. Status Lingkungan Dev & Langkah Terakhir

* **Database MySQL XAMPP**: Aktif normal di port **`3306`** (`DB_PORT=3306` di `.env`).
* **Perintah Migrasi**:
  Cukup jalankan satu kali di terminal jika belum dieksekusi:
  ```bash
  php artisan migrate
  ```
* **Akses Panel Admin**:
  Buka browser di `http://127.0.0.1:8000/admin` → Masuk ke menu **`🟢 Pesanan Khusus WhatsApp`**.
