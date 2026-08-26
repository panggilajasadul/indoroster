# AUDIT EXISTING DOCUMENT BUILDER - INDOROSTER

Berikut adalah hasil audit komprehensif terhadap sistem Document Builder yang saat ini berjalan pada proyek IndoRoster.

---

## 1. ARCHITECTURE (ARSITEKTUR SEKARANG)

Sistem pembuat dokumen saat ini bertumpu pada arsitektur bawaan Laravel 12 + Filament 3 + Livewire 3 + DomPDF:

*   **Model**: [ManualDocument.php](file:///c:/xampp/htdocs/indoroster/app/Models/ManualDocument.php) merepresentasikan tabel database `manual_documents`.
*   **Filament Resource**: [ManualDocumentResource.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/ManualDocumentResource.php) menyediakan form input bagi admin untuk mengisi data dokumen offline.
*   **Form Logic & Mutasi Data**: Proses mutasi data dilakukan pada halaman Create/Edit:
    *   [CreateManualDocument.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/ManualDocumentResource/Pages/CreateManualDocument.php)
    *   [EditManualDocument.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/ManualDocumentResource/Pages/EditManualDocument.php)
    *   Setiap field kustom (seperti syarat & ketentuan, metode pembayaran) dipetakan ke dalam kolom JSON `extra_data`.
*   **PDF Generation**: Menggunakan library **DomPDF** via wrapper [PrintController.php](file:///c:/xampp/htdocs/indoroster/app/Http/Controllers/PrintController.php).
*   **Templates/Blade Views**: Terletak di folder `resources/views/print/manual/` dengan file:
    *   `faktur.blade.php` (Faktur Penjualan)
    *   `surat-jalan.blade.php` (Surat Jalan)
    *   `kwitansi.blade.php` (Kwitansi Pembayaran)
    *   `penawaran.blade.php` (Penawaran Harga)
    *   `surat-pesanan.blade.php` (Surat Pesanan Offline)

---

## 2. CURRENT FEATURES (FITUR SAAT INI)

1.  **CRUD Manajemen Dokumen Offline**: Admin dapat membuat dokumen dengan 5 jenis tipe dokumen (`faktur`, `surat_jalan`, `kwitansi`, `penawaran`, `surat_pesanan`).
2.  **Penomoran Dokumen Otomatis**: Penomoran di-generate otomatis saat `creating` dengan pola `PREFIX-YYYYMMDD-SEQ` (misalnya `SJ-20260823-001`).
3.  **Kalkulator Reactive di Filament**: Perhitungan `subtotal`, `discount`, `tax_amount` (PPN 11%), dan `grand_total` terhitung secara dinamis di form admin saat admin mengubah harga/kuantitas item.
4.  **Upload Tanda Tangan**: Field upload gambar tanda tangan (`signature_path`) yang nantinya ditampilkan di bagian bawah dokumen cetak.
5.  **Data Dinamis via JSON**: Kolom `extra_data` menampung informasi tambahan bervariasi per dokumen (seperti nama supir, plat nomor, bank, dll.).
6.  **Streaming PDF**: Dokumen di-generate menjadi file PDF via DomPDF dan langsung di-stream ke browser via print routes.

---

## 3. CURRENT DATABASE STRUCTURE (STRUKTUR DATABASE)

Tabel utama yang digunakan saat ini adalah `manual_documents` (berdasarkan migration `2026_08_21_135000_create_manual_documents_table.php`):

| Nama Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | bigint (unsigned) | Primary Key |
| `document_number` | varchar(255) | Nomor unik dokumen (Unique Index) |
| `type` | varchar(255) | Jenis dokumen (`faktur`, `surat_jalan`, `kwitansi`, `penawaran`, `surat_pesanan`) |
| `client_name` | varchar(255) | Nama penerima / klien |
| `client_address` | text | Alamat lengkap klien (Nullable) |
| `client_phone` | varchar(255) | No. HP / WhatsApp klien (Nullable) |
| `client_email` | varchar(255) | Email klien (Nullable) |
| `items` | json | JSON array berisi deskripsi produk, kuantitas, harga satuan, dan total |
| `subtotal` | decimal(15,2) | Total sebelum diskon & pajak |
| `discount` | decimal(15,2) | Jumlah diskon nominal |
| `has_tax` | boolean | Apakah dikenakan PPN 11% |
| `tax_amount` | decimal(15,2) | Nominal PPN (jika `has_tax` true) |
| `grand_total` | decimal(15,2) | Total akhir setelah dikurangi diskon & ditambah PPN |
| `document_date` | date | Tanggal penerbitan dokumen |
| `due_date` | date | Tanggal jatuh tempo (Nullable) |
| `issued_by` | varchar(255) | Nama pembuat dokumen (default: user login) |
| `status` | varchar(255) | Status dokumen (`draft`, `final`) |
| `signature_path` | varchar(255) | Path file tanda tangan (Nullable) |
| `extra_data` | json | JSON object berisi data spesifik tipe dokumen & catatan tambahan |
| `created_at` / `updated_at` | timestamp | Waktu pembuatan / update data |

---

## 4. CURRENT ROUTES (RUTE CETAK)

Rute-rute pencetakan dokumen diatur di [web.php](file:///c:/xampp/htdocs/indoroster/routes/web.php):

```php
// Print Routes for Admin Only
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/print/order/{order}', [PrintController::class, 'order'])->name('print.order');
    Route::get('/print/shipping-label/{shippingLabel}', [PrintController::class, 'shippingLabel'])->name('print.shipping-label');
    Route::get('/print/manual-document/{document}', [PrintController::class, 'manualDocument'])->name('print.manual-document');
});
```

*   `print.manual-document`: Mengakses action `manualDocument()` di `PrintController` untuk memicu render PDF dokumen manual.

---

## 5. CURRENT COMPONENTS (KOMPONEN PENYUSUN)

1.  **Filament Repeater**: Digunakan untuk menginput item secara dinamis (Nama Produk, Qty, Harga).
2.  **Filament FileUpload**: Mengunggah gambar tanda tangan digital.
3.  **DomPDF Engine**: Library PDF berbasis PHP murni untuk mengkonversi struktur HTML + CSS menjadi dokumen cetak PDF.
4.  **Static CSS di Blade**: Layout PDF diatur menggunakan gaya tabel standar (`display: table`, `border-collapse`, font Helvetica) yang aman bagi engine DomPDF.

---

## 6. CURRENT LIMITATIONS (BATASAN / MASALAH SAAT INI)

1.  **Tidak Ada Visual Layout Editor**: Admin tidak bisa memindahkan posisi logo, memosisikan tanda tangan / stempel, mengatur ukuran elemen secara visual (drag-and-drop / numerik X-Y).
2.  **Styling Hardcoded**: Semua gaya (CSS), margin halaman, ukuran kertas (selalu A4 portrait), font, dan header/footer dikunci keras (hardcoded) di file Blade masing-masing tipe dokumen.
3.  **Tidak Ada Stempel (Stamp) Management**: Belum ada fitur untuk mengunggah stempel perusahaan dan mengatur tingkat transparansi, rotasi, ukuran, serta posisinya di atas tanda tangan.
4.  **Jumlah Tipe Dokumen Terbatas**: Hanya mendukung 5 tipe. Belum mendukung dokumen tambahan seperti Sales Order, Proforma Invoice, Delivery Note, Packing List, Purchase Order, Goods Receipt, Supplier Invoice, dan Customer Statement.
5.  **Konektivitas ke Database Utama Lemah**: Dokumen manual ditulis secara manual satu per satu itemnya tanpa opsi memuat otomatis dari master data produk atau dari database pesanan/pelanggan yang sudah ada.
6.  **Branding Global Tidak Terpusat**: Logo dan informasi perusahaan di hardcode di dalam file Blade atau mengambil dari SiteSettings global tanpa pengaturan khusus layout dokumen (seperti margin, tinggi-lebar logo, rasio aspek).
7.  **Tidak Ada Versioning Template / Immutability**: Jika file CSS/Blade diubah untuk dokumen baru, dokumen lama yang dicetak ulang akan ikut berubah tata letaknya, yang melanggar audit kepatuhan bisnis.

---

## 7. RISKS (RISIKO PENGEMBANGAN)

1.  **Kompatibilitas Shared Hosting**: Menggunakan binary eksternal seperti Puppeteer/Chromium (Browsershot) akan gagal di Hostinger Shared Hosting. Kita harus mempertahankan **DomPDF** atau **mPDF** yang murni berbasis PHP.
2.  **Perbedaan Tampilan (Browser Preview vs DomPDF Output)**: DomPDF memiliki dukungan CSS yang terbatas (tidak mendukung CSS Grid, Flexbox modern, dan beberapa rule CSS3). Visual designer di web harus dikembangkan menggunakan CSS yang sangat kompatibel dengan DomPDF agar hasil "Live Preview" 100% akurat dengan PDF yang dihasilkan.
3.  **Beban Memori PDF**: Render dokumen multi-halaman dengan banyak aset gambar (Logo, TTD, Stempel) dapat menyebabkan error *Memory Exhausted* di shared hosting jika tidak dioptimalkan (misalnya kompresi aset gambar).
4.  **Regresi Nomor Dokumen Existing**: Perubahan skema penomoran atau pembaruan system builder tidak boleh mengganggu nomor dokumen yang sudah terbit di database.

---

## 8. PROPOSED IMPLEMENTATION PLAN (RENCANA PENGERJAAN)

Untuk mengupgrade sistem tanpa merusak fitur yang sudah ada, kita akan membaginya ke dalam langkah bertahap:

### Langkah 1: Struktur Data Template & Branding
Kita akan memperkenalkan model baru `DocumentTemplate` untuk menyimpan konfigurasi desain dokumen:
*   Nama Template, Tipe Dokumen (Faktur, Penawaran, Surat Jalan, dll.)
*   Ukuran Kertas (A4), Orientasi (Portrait/Landscape), Margin halaman.
*   Konfigurasi elemen (Logo, Info Perusahaan, Info Klien, Tabel Item, Subtotal, TTD, Stempel, Footer). Setiap elemen memiliki koordinat X, Y, Width, Height, Font, Alignment, dan Status Aktif.

### Langkah 2: Branding Settings Dashboard
Membuat tab / halaman pengaturan baru di Admin **Branding & Document Settings**:
*   Manajemen Aset: Upload Logo Default, TTD Digital Default, Stempel Perusahaan Default.
*   Pengaturan properti default (lebar default logo, opacity default stempel, rotasi stempel, nama penanggung jawab, dll.).

### Langkah 3: Live Preview & Canvas Visual Editor (Canva-like)
Membuat visual designer berbasis Livewire + JavaScript ringan di Filament:
*   Layout A4 visual yang interaktif (meniru ukuran asli 210 x 297 mm).
*   Slider numerik (X, Y, Width, Height, Font Size, Opacity) untuk penempatan presisi elemen (Logo, TTD, Stempel, dll.).
*   Dukungan pemindahan visual (drag & drop koordinat) yang memperbarui koordinat numerik secara real-time.

### Langkah 4: Snapshot Data & Immutability
Saat dokumen diubah statusnya menjadi **Final**, sistem akan menyimpan representasi data transaksi dan struktur visual template saat itu ke dalam JSON `extra_data` / `snapshot`. Ketika dokumen tersebut dicetak di kemudian hari, sistem akan me-render data dari snapshot tersebut sehingga output PDF tidak pernah berubah.

### Langkah 5: Ekspansi Tipe Dokumen & Integrasi Master Data
Menambahkan 12 tipe dokumen bisnis (Sales, Delivery, Purchasing, Finance). Menambahkan drop-down pencarian produk/customer dinamis di form pengisian item sehingga admin tidak perlu mengetik detail item dari nol.

---
**Persetujuan Audit**: Silakan tinjau rencana di atas. Setelah disetujui, kita akan mulai mempersiapkan migrations dan model template.
