# 📖 BUKU PANDUAN OPERASIONAL ADMIN: MODUL PESANAN WHATSAPP (WA ORDER)
**IndoRoster Indonesia — Sistem Manajemen Penjualan, Produksi, Pengiriman Bertahap & Kasir Proyek**

---

## 📑 DAFTAR ISI
1. [Prinsip Dasar & Pemisahan Modul](#1-prinsip-dasar--pemisahan-modul)
2. [Sistem 3 Mode Pemenuhan Barang (Fulfillment)](#2-sistem-3-mode-pemenuhan-barang-fulfillment)
   - [Mode A: Ready Stock (Stok Siap Kirim)](#mode-a-ready-stock-stok-siap-kirim)
   - [Mode B: PO Tunggal (Pre-Order 1 Truk Sekaligus)](#mode-b-po-tunggal-pre-order-1-truk-sekaligus)
   - [Mode C: PO Batch (Pengiriman Bertahap Multi-Rit Proyek)](#mode-c-po-batch-pengiriman-bertahap-multi-rit-proyek)
3. [Alur Otomatisasi Notifikasi Email & WhatsApp](#3-alur-otomatisasi-notifikasi-email--whatsapp)
4. [Sistem Pembayaran Bertahap (DP, Termin & Pelunasan 100%)](#4-sistem-pembayaran-bertahap-dp-termin--pelunasan-100)
5. [Standar Dokumen Resmi Pabrik (Kapan Harus Mencetak Apa)](#5-standar-dokumen-resmi-pabrik-kapan-harus-mencetak-apa)
6. [Tanya Jawab & Tips Praktis Lapangan (FAQ)](#6-tanya-jawab--tips-praktis-lapangan-faq)

---

## 1. PRINSIP DASAR & PEMISAHAN MODUL

Modul **`🟢 Pesanan Khusus WhatsApp`** dirancang khusus untuk menangani transaksi fleksibel yang datang dari percakapan WhatsApp, kontraktor proyek, arsitek, dan pembelian partai besar:

* **Penomoran Dokumen Khusus**:
  * Invoice: `INV-WA-YYYYMMDD-XXXX` (Terpisah dari pesanan website reguler `INV-...`).
  * Kuitansi Kasir: `KW-PAY-WA-YYYYMMDD-XXXX-N`.
  * Surat Jalan: `SJ-WA-YYYYMMDD-XXXX`.
* **Kerahasiaan Keuangan**: Catatan transfer kasir & mutasi bank tersimpan privat di modul finance dan **tidak akan bocor ke lembar surat jalan supir**.
* **Live Update**: Panel status pesanan dan foto bongkar muat dari kurir ter-update secara *real-time* tanpa perlu refresh browser berkali-kali.

---

## 2. SISTEM 3 MODE PEMENUHAN BARANG (FULFILLMENT)

Admin wajib memilih salah satu dari 3 mode pemenuhan pada saat membuat/mengedit pesanan:

```
                      ┌──────────────────────────────────────────────┐
                      │   PILIH MODE PEMENUHAN DI SECTION 3 FORM     │
                      └──────────────────────┬───────────────────────┘
                                             │
         ┌───────────────────────────────────┼───────────────────────────────────┐
         ▼                                   ▼                                   ▼
┌──────────────────┐               ┌──────────────────┐               ┌──────────────────┐
│   READY STOCK    │               │    PO TUNGGAL    │               │     PO BATCH     │
│ Barang Tersedia  │               │ Cetak Baru Pabrik│               │ Proyek Skala Bsr │
│ Langsung Muat    │               │  Kirim 1 Armada  │               │ Multi-Rit Armada │
└──────────────────┘               └──────────────────┘               └──────────────────┘
```

---

### MODE A: READY STOCK (Stok Siap Kirim)
Digunakan ketika stok roster/bata ekspose **sudah tersedia lengkap di gudang pabrik** dan siap langsung diangkut ke armada truk.

* **Karakteristik**:
  * Tidak memerlukan waktu tunggu proses cetak.
  * Dikirim sekaligus dalam 1 armada truk/mobil pickup.
* **Alur Kerja Operasional (SOP)**:
  1. Admin input pesanan $\rightarrow$ Pilih **Ready Stock**.
  2. Input nominal DP atau Lunas $\rightarrow$ Klik **Terbitkan Pesanan**.
  3. Di halaman detail pesanan: Klik **`🚚 Siapkan & Berangkatkan Armada`** (Input nama supir, plat truk, dan no HP supir).
  4. Cetak **Surat Jalan** dan serahkan ke supir bersama muatan.
  5. Supir tiba di lokasi $\rightarrow$ Foto bukti serah terima $\rightarrow$ Status berubah otomatis menjadi **Diterima di Proyek**.

---

### MODE B: PO TUNGGAL (Pre-Order 1 Truk Sekaligus)
Digunakan ketika motif roster yang dipesan **harus dicetak baru di pabrik**, tetapi jumlahnya cukup diangkut dalam **1 rit truk** (misal 500 – 1.500 pcs).

* **Karakteristik**:
  * Memerlukan estimasi tanggal mulai cetak dan estimasi siap kirim.
  * Pengiriman dilakukan 1 kali setelah seluruh produksi selesai dan lolos Quality Control (QC).
* **Alur Kerja Operasional (SOP)**:
  1. Admin input pesanan $\rightarrow$ Pilih **PO Single (1 Truk)**.
  2. Masukkan **Estimasi Siap Kirim**.
  3. Catat Pembayaran DP Masuk dari pembeli.
  4. Klik **`🔨 Mulai Produksi`** saat adonan mulai dicetak di pabrik.
  5. Klik **`📦 Tandai Siap Kirim`** saat material selesai dikeringkan & lolos QC.
  6. Klik **`🚚 Berangkatkan Truk`** pada hari H (Input supir & plat nomor).
  7. Supir menyerahkan barang dan upload foto serah terima $\rightarrow$ Transaksi Selesai.

---

### MODE C: PO BATCH (Pengiriman Bertahap Multi-Rit Proyek)
Digunakan untuk pesanan proyek volume besar (misal 2.500 – 20.000 pcs) yang **tidak muat dalam 1 truk** dan harus dikirim bertahap dalam beberapa rit armada (Batch 1, Batch 2, Batch 3, dst).

* **Karakteristik**:
  * Admin bebas menentukan jumlah rit (misal 2 rit @ 1.250 pcs, atau 5 rit @ 2.000 pcs).
  * Tiap rit memiliki timeline mandiri: Tanggal Cetak, Estimasi Berangkat, dan Estimasi Tiba.
  * Penugasan supir & plat truk dilakukan pada hari H keberangkatan tiap rit (tidak perlu pusing di awal).
* **Alur Kerja Operasional (SOP)**:
  1. Admin input pesanan $\rightarrow$ Pilih **PO Batch**.
  2. Tentukan Kuantitas Rit (Contoh: `Batch 1 = 1.250 pcs`, `Batch 2 = 1.250 pcs`).
  3. Terbitkan Pesanan $\rightarrow$ Kirim **Surat Konfirmasi & Jadwal Batch** ke pembeli.
  4. **Eksekusi Batch 1**:
     * Klik **`🔨 Mulai Produksi Batch 1`** *(Sistem otomatis mengirimkan email konfirmasi jadwal lengkap ke pelanggan)*.
     * Setelah kering: Klik **`📦 Siap Dikirim`**.
     * Hari H: Klik **`🚚 Berangkatkan Truk Batch 1`** (Pilih supir & masukkan no plat).
     * Supir tiba $\rightarrow$ Upload foto bongkar Batch 1.
  5. **Eksekusi Batch 2**:
     * Ulangi alur produksi & keberangkatan untuk Batch 2.
  6. Setelah semua rit terkirim (100%) dan sisa tagihan lunas $\rightarrow$ Pesanan otomatis terkunci **Selesai & Lunas 100%**.

---

## 3. ALUR OTOMATISASI NOTIFIKASI EMAIL & WHATSAPP

Sistem dilengkapi pemicu otomatis (*automated triggers*) serta tombol manual 1-klik agar komunikasi dengan pembeli tetap hangat, profesional, dan transparan:

| Kejadian / Aksi Admin | Otomatisasi Email | Otomatisasi WhatsApp |
| :--- | :--- | :--- |
| **Pesanan Diterbitkan (Tahap Penawaran)** | Mengirim Proforma Invoice / Penawaran Resmi ke email pembeli. | Admin klik **`📄 Kirim Rincian Tagihan`** via WhatsApp. |
| **DP / Cicilan / Termin Masuk** | Notifikasi pembayaran diverifikasi. | Admin klik **`💬 Kirim Kuitansi`** & Link Lacak Progres. |
| **Mulai Produksi Batch 1 (PO Batch)** | **Kirim Email Resmi Jadwal Pengiriman Bertahap** (Berisi tabel timeline seluruh rit). | Admin klik **`💬 Kirim WA Jadwal ke Pelanggan`** (1-Klik membuka chat berformat rapi). |
| **Mulai Produksi Rit Selanjutnya** | Tidak mengirim email (mencegah spam). | Admin klik **`💬 Kirim WA Jadwal Cetak`** per batch. |
| **Material Siap Kirim (Loading Dock)** | Status Live Tracking ter-update otomatis. | Admin klik **`💬 Kirim WA Siap Kirim`**. |
| **Truk Berangkat ke Lokasi Proyek** | Mengirim info keberangkatan truk. | Admin/Sistem kirim info nama supir, plat truk, & estimasi tiba. |
| **Truk Tiba & Foto Bukti Diupload** | Email konfirmasi penerimaan barang. | WhatsApp notifikasi barang telah mendarat di proyek. |
| **Pelunasan 100% Selesai** | Mengirim Invoice Final Berstempel Lunas. | Admin kirim Invoice Lunas + Ucapan Terima Kasih. |

---

## 4. SISTEM PEMBAYARAN BERTAHAP (DP, TERMIN & PELUNASAN 100%)

Sistem kasir IndoRoster mendukung pencatatan pembayaran **bebas tanpa batas (*unlimited installments*)**:

```
[ 1. TAHAP PENAWARAN (Rp 0) ]
Dokumen: Surat Penawaran / Proforma Invoice
Status: ⚪ MENUNGGU PEMBAYARAN DP
               │
               ▼
[ 2. PEMBAYARAN #1 (DP AWAL) ]
Admin klik "💳 Catat Pembayaran / DP Masuk" (Misal transfer Rp 10 Juta)
Dokumen Terbit: 🖨️ Kuitansi #1 (KW-...)  &  📄 Invoice Tahap #1 (Sisa Rp 20 Juta)
               │
               ▼
[ 3. PEMBAYARAN #2, #3... (CICILAN / TERMIN) ]
Admin klik "💳 Catat Pembayaran / DP Masuk" (Misal transfer lagi Rp 10 Juta)
Dokumen Terbit: 🖨️ Kuitansi #2 (KW-...)  &  📄 Invoice Tahap #2 (Sisa Rp 10 Juta)
               │
               ▼
[ 4. PEMBAYARAN AKHIR (PELUNASAN 100%) ]
Admin klik "💰 Catat Pelunasan Tagihan" (Transfer sisa Rp 10 Juta)
Dokumen Terbit: 🖨️ Kuitansi Pelunasan  &  📄 INVOICE FINAL BERSTEMPEL LUNAS SAH
```

### 💡 Keunggulan Fitur Pembayaran:
1. **Nomor Urut Kuitansi Otomatis**: Setiap ada uang masuk diterbitkan Kuitansi Resmi standar pabrik lengkap dengan nominal terbilang rupiah, kop Pabrik IndoRoster, stempel sah, dan TTD Keuangan.
2. **Snapshot Invoice Bertahap**: Riwayat invoice setiap termin tersimpan permanen dan dapat dicetak ulang kapan saja (`Invoice Tahap #1`, `Invoice Tahap #2`, dst).
3. **Tabel Riwayat Terhubung**: Di lembar Invoice Final, seluruh riwayat transfer dari awal hingga akhir tercantum berurutan di bawah Grand Total.

---

## 5. STANDAR DOKUMEN RESMI PABRIK (KAPAN HARUS MENCETAK APA)

Admin wajib memahami peruntukan tiap dokumen agar tidak salah serah ke pihak supir maupun pembeli:

| Nama Dokumen | Diberikan Kepada | Kapan Dicetak / Dikirim | Fungsi Utama |
| :--- | :--- | :--- | :--- |
| **Surat Penawaran / Proforma Invoice** | Pembeli / Kontraktor | Sebelum ada uang DP masuk | Memberikan rincian harga motif, total ongkir, dan petunjuk transfer DP bank resmi. |
| **Surat Konfirmasi & Jadwal PO Batch** | Pembeli / Kontraktor | Saat pesanan PO Batch disetujui | Memberitahu pembeli timeline rencana kedatangan tiap rit truk ke lokasi proyek. |
| **Kuitansi Pembayaran (`KW-...`)** | Pembeli / Finance Proyek | Setiap kali ada uang transferan masuk | Tanda terima sah uang masuk ke kasir pabrik IndoRoster. |
| **Invoice Tahap / Progres (`INV-...`)** | Pembeli / Owner Proyek | Saat pembeli meminta update progres | Menampilkan total nilai proyek, akumulasi uang masuk, dan sisa tagihan berjalan. |
| **Surat Jalan Pengiriman (`SJ-...`)** | **Supir Truk Armada** | **Hari H saat truk muat barang** | Dokumen fisik jalan untuk serah terima barang, barcode GPS lokasi, & TTD penerima lapangan. |
| **Invoice Final (Stempel LUNAS)** | Pembeli / Finance Proyek | Setelah seluruh tagihan lunas 100% | Dokumen pamungkas pelunasan dan penyerahan hak kepemilikan material. |

---

## 6. TANYA JAWAB & TIPS PRAKTIS LAPANGAN (FAQ)

#### Q: Mengapa Surat Jalan Batch 2 menampilkan "Terkirim Sebelumnya 1.250 pcs"?
> **A**: Karena dalam sistem PO Batch, muatan dihitung secara akumulatif berurutan. Di Batch 2, sistem otomatis mencatat bahwa 1.250 pcs dari Batch 1 sudah menjadi bagian dari progres pengiriman, sehingga total akumulasi menjadi 2.500 pcs (100%) dan sisa barang menjadi 0 pcs.

#### Q: Bagaimana jika pembeli transfer DP dengan nominal ganjil (misal Rp 13.750.000)?
> **A**: Admin cukup memasukkan angka persis `13750000` di modal *"Nominal Uang Masuk Kali Ini"*. Sistem akan otomatis menghitung sisa tagihan secara matematis dan menerbitkan kuitansi terbilang *"Tiga Belas Juta Tujuh Ratus Lima Puluh Ribu Rupiah"*.

#### Q: Kapan penugasan supir dan no plat truk diinput untuk PO Batch?
> **A**: Pada **hari H keberangkatan rit masing-masing**, saat admin mengklik tombol **`🚚 Berangkatkan Truk Batch X`**. Ini memudahkan admin karena supir/plat armada yang jalan tidak perlu dipastikan berminggu-minggu sebelumnya.

#### Q: Apakah supir bisa melihat harga jual atau sisa tagihan di surat jalan?
> **A**: **Tidak.** Surat Jalan hanya memuat kuantitas fisik (pcs), rincian motif, petunjuk koordinat GPS Google Maps, dan catatan logistik lapangan. Seluruh data keuangan kasir tersimpan aman di invoice kantor.

---
*Dokumentasi ini disusun oleh Tim IT & Pengembang Sistem IndoRoster Nusantara.*
