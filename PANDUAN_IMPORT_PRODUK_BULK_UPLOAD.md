# Panduan Lengkap Import Produk Massal (Bulk Upload CSV) IndoRoster

Dokumen ini berisi panduan teknis dan operasional untuk mengunggah produk dalam jumlah banyak (bulk import) ke katalog IndoRoster menggunakan file CSV di Admin Panel.

---

## 1. Lokasi Fitur di Admin Panel
1. Buka dashboard Admin IndoRoster: `https://indoroster.com/admin` (atau `http://localhost/admin` jika di lokal).
2. Masuk ke menu **Produk** (`/admin/products`).
3. Di sudut kanan atas tabel produk, klik tombol dropdown biru **"Opsi CSV"**.
4. Tersedia 3 menu:
   - **Download Template CSV**: Mengunduh template `.csv` kosong dengan baris contoh dari sistem.
   - **Import Produk dari CSV**: Membuka form modal upload untuk memproses file CSV.
   - **Export Data Produk (CSV)**: Mengunduh data seluruh produk aktif dan varian saat ini.

---

## 2. Struktur Form & Kolom CSV

Sistem import IndoRoster dibuat fleksibel dan otomatis. Anda hanya perlu menyediakan data nama produk dan harga, sedangkan slug, deskripsi standar, 3 varian material, dan metadata SEO akan di-generate otomatis oleh sistem.

### Tabel Kolom CSV

| Header Kolom | Wajib / Opsional | Tipe Data | Keterangan & Contoh Nilai |
| :--- | :---: | :---: | :--- |
| `nama_produk` | **Wajib** | Teks | Nama produk/motif. Contoh: `Roster Beton Motif Melati (Satu Sisi)` atau cukup `Melati`. *(Jika tidak diawali kata "Roster", sistem otomatis menambahkan prefix "Roster Beton Minimalis Motif ...")* |
| `kategori` | Opsional | Teks | Nama kategori. Default otomatis: `Roster Beton`. |
| `sku` | Opsional | Teks | Kode unik SKU. Contoh: `IR-MOTIF-001`. *(Jika dikosongkan, sistem otomatis membuat kode unik `IR-XXXXXX`)* |
| `tipe_motif` | Opsional | Teks | Tipe motif/sisi. Contoh: `Satu Sisi` atau `Dua Sisi`. |
| `ukuran` | Opsional | Teks | Dimensi roster. Contoh: `20 x 20 x 10 cm` atau `20 x 20 x 10`. *(Default: `20 x 20 x 10 cm`)* |
| `berat_kg` | Opsional | Angka | Berat per pcs dalam kg. Contoh: `3.5` *(Default: 3.5 kg untuk ukuran 20x20, atau 4.2 kg untuk ukuran 30x15)* |
| `min_order` | Opsional | Angka | Jumlah minimal pemesanan. Contoh: `1`. |
| `harga_abu` | Opsional | Angka | Harga varian warna Abu-Abu (angka murni tanpa titik). Contoh: `10000`. *(Default: 10000)* |
| `stok_abu` | Opsional | Angka | Jumlah stok abu. Contoh: `1000`. |
| `harga_putih_dolomit` | Opsional | Angka | Harga varian Putih Dolomit. Contoh: `11500`. *(Default: Harga Abu + Rp 1.000)* |
| `stok_putih_dolomit` | Opsional | Angka | Jumlah stok dolomit. Contoh: `1000`. |
| `harga_merah_terakota` | Opsional | Angka | Harga varian Merah Terracota. Contoh: `11500`. *(Default: sama dengan harga dolomit)* |
| `stok_merah_terakota` | Opsional | Angka | Jumlah stok terracota. Contoh: `1000`. |
| `focus_keyword` | Opsional | Teks | Target kata kunci SEO utama. *(Jika kosong, otomatis di-generate: `roster beton minimalis motif [nama] [tipe]`)* |
| `meta_title` | Opsional | Teks | Judul SEO Google (disarankan maks 65 karakter). *(Jika kosong, otomatis di-generate: `Jual [Nama Produk] Murah \| IndoRoster`)* |
| `meta_description` | Opsional | Teks | Deskripsi pencarian Google. *(Jika kosong, otomatis di-generate standar pabrik kualitas presisi se-Jabodetabek)* |

---

## 3. Fitur Otomatisasi Sistem

Saat file CSV diproses, sistem IndoRoster melakukan otomasi berikut untuk setiap baris produk:
1. **Pembuatan 3 Varian Warna Sekaligus:**
   - **Abu-Abu**: Material semen & pasir abu batu murni.
   - **Dolomit**: Beton putih semen dolomit halus elegan.
   - **Terracota**: Beton merah terakota klasik hangat.
2. **Deskripsi & Panduan Pembelian:**
   - Otomatis disisipkan panduan belanja tanpa perlu akun/login, rincian pengiriman WhatsApp, dan garansi pergantian barang pecah/rusak 100%.
3. **SEO On-Page Lengkap:**
   - Otomatis menghitung `seo_score: 98` dan `opportunity_score: 95`.
   - Mengisi OpenGraph tag (`og:title`, `og:description`).
   - Menyusun variasi keyword sekunder (`secondary_keywords`).

---

## 4. Template CSV Siap Pakai

Anda dapat menyalin teks berikut ke Notepad lalu simpan dengan ekstensi `.csv` (misalnya `daftar_produk_indoroster.csv`), atau buka di Microsoft Excel / Google Sheets:

```csv
nama_produk,kategori,sku,tipe_motif,ukuran,berat_kg,min_order,harga_abu,stok_abu,harga_putih_dolomit,stok_putih_dolomit,harga_merah_terakota,stok_merah_terakota,focus_keyword,meta_title,meta_description
Roster Beton Motif Melati (Satu Sisi),Roster Beton,IR-MOTIF-001,Satu Sisi,20 x 20 x 10 cm,3.5,1,10000,1000,11500,1000,11500,1000,roster beton motif melati,Jual Roster Beton Motif Melati 20x20 | IndoRoster,Pabrik roster motif melati 20x20x10 cm. Varian abu, putih dolomit, dan terakota kualitas presisi.
Roster Beton Motif Petir (Dua Sisi),Roster Beton,IR-MOTIF-002,Dua Sisi,20 x 20 x 10 cm,3.5,1,11000,1000,12000,1000,12000,1000,roster beton motif petir 2 muka,Jual Roster Beton Motif Petir Dua Sisi 20x20 | IndoRoster,Pabrik roster beton motif petir dua muka 20x20 cm. Cocok untuk fasad & pagar minimalis.
Roster Beton Motif Kotak Minimalis,Roster Beton,IR-MOTIF-003,Satu Sisi,20 x 20 x 10 cm,3.5,1,10000,1000,11500,1000,11500,1000,roster beton motif kotak minimalis,Jual Roster Beton Motif Kotak Minimalis | IndoRoster,Pabrik roster motif kotak minimalis 20x20x10 cm presisi kuat kokoh se-Jabodetabek.
Roster Beton Motif Nako Bulat,Roster Beton,IR-MOTIF-004,Satu Sisi,20 x 20 x 10 cm,3.5,1,10000,1000,11500,1000,11500,1000,roster beton nako bulat,Jual Roster Beton Nako Bulat 20x20 | IndoRoster,Pabrik roster motif nako bulat 20x20x10 cm sirkulasi udara maksimal estetik.
Roster Beton Motif Bintang 8,Roster Beton,IR-MOTIF-005,Dua Sisi,20 x 20 x 10 cm,3.5,1,11000,1000,12000,1000,12000,1000,roster beton motif bintang 8,Jual Roster Beton Motif Bintang 8 20x20 | IndoRoster,Pabrik roster motif bintang 8 dua muka presisi kokoh untuk dinding aksen.
```

---

## 5. Tips & Aturan Pengisian (Penting)

1. **Format Harga & Angka:**
   - Gunakan angka bulat tanpa titik, koma, atau tulisan `Rp` (misal: `10000`, **bukan** `10.000` atau `Rp 10.000`).
2. **Pemisah Kolom:**
   - Sistem mendukung pemisah koma (`,`) maupun titik koma (`;`). Jika Anda menyimpan file dari Microsoft Excel bahasa Indonesia, titik koma akan dideteksi secara otomatis.
3. **Format Dimensi & Ukuran:**
   - Gunakan spasi antar tanda kali `x`, contoh: `20 x 20 x 10 cm`. Jika akhiran `cm` lupa ditulis, sistem akan menambahkannya otomatis.
4. **Alias Nama Kolom yang Diterima Sistem:**
   - Jika membuat tabel sendiri, sistem juga mengenali nama alias berikut:
     - `nama_produk` bisa ditulis: `nama_roster`, `nama`, `name`, atau `motif`.
     - `tipe_motif` bisa ditulis: `tipe`, `type`, atau `sisi`.
     - `ukuran` bisa ditulis: `dimensi`, `dimensions`, `size`.
     - `harga_abu` bisa ditulis: `abu`, `grey`, `gray`.
     - `harga_putih_dolomit` bisa ditulis: `harga_dolomit`, `white`, `dolamit`.
     - `harga_merah_terakota` bisa ditulis: `harga_terakota`, `terracota`, `red`.
5. **Setelah Selesai Import:**
   - Masuk kembali ke menu **Produk** lalu klik tombol **"Perbarui Sitemap XML"** agar semua produk baru langsung terdaftar di Google Search Engine.
