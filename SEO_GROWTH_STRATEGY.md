# SEO Growth Strategy & Keyword Map — IndoRoster.com

Strategi ini dirancang untuk mendominasi pencarian organik roster minimalis, bata expose,
dan material dekoratif dinding di wilayah strategis Jabodetabek & Jawa Barat.

---

## 1. STRATEGI KEYWORD AUTHORITY

Mesin perayap Google harus menganggap IndoRoster sebagai **Topical Authority** (sumber terpercaya)
untuk produk roster beton. Kita membagi pencarian menjadi beberapa tingkat intent.

### 1.1 Synonym Mapping (Variasi Istilah)
Pengguna dari berbagai daerah menggunakan istilah yang berbeda untuk produk yang sama.
Python SEO Engine dan layout web dikonfigurasi untuk menyebarkan variasi ini secara alami:
- **Roster Beton**: Loster beton, roster semen, roster cetak, loster semen.
- **Lubang Angin**: Ventilasi beton, lubang angin minimalis, kisi-kisi beton, ventilasi udara.
- **Bata Expose**: Bata tempel, bata expose tanah liat, terakota, bata dinding tempel.

### 1.2 Geotargeting (Target Wilayah Jabodetabek & Jawa Barat)
Untuk memenangkan pencarian lokal, kita menargetkan keyword ekor panjang (long-tail)
yang menggabungkan nama produk + variasi + kota target:
- "Jual loster beton minimalis Tangerang"
- "Harga roster beton murah Bekasi"
- "Pabrik roster beton Plered Purwakarta"
- "Roster dinding minimalis Bogor"
- "Toko ventilasi beton terdekat Depok"
- "Distributor roster beton proyek Jakarta Barat"

---

## 2. OPTIMASI MULTI-PLATFORM SEARCH

### 2.1 Google Images
Google Images menyumbang porsi pencarian yang sangat besar untuk materi bangunan.
- **Masalah Teratasi**: Sebelumnya, ALT text gambar kosong. Sekarang, `AltTextGenerator`
  otomatis mengisi ALT text dengan keyword deskriptif per gambar.
- **ImageObject JSON-LD**: Setiap gambar di galeri proyek menyertakan skema `ImageObject`
  yang mengaitkan foto inspirasi dengan URL produk detail asli, mencegah Google merujuk
  hanya ke beranda.

### 2.2 Google Video
- Halaman `/video-inspirasi` sekarang memiliki skema `VideoObject` yang lengkap.
  Hal ini memungkinkan video proses produksi roster beton dan uji ketahanan K-200
  muncul di tab "Video" di Google Search.

### 2.3 Google Shopping & Google Merchant Center
Skema `Product` yang diperbarui di IndoRoster.com memenuhi standar kelayakan data Google Shopping:
1. **Identifikasi Unik**: `mpn` dan `sku` terisi otomatis.
2. **Ketersediaan**: Properti `availability` disinkronkan langsung dari status stok database.
3. **Harga & Mata Uang**: Menggunakan data IDR bersih.
4. **shippingDetails**: Data pengiriman (handling time, transit time) di wilayah Indonesia dimasukkan di skema.
5. **additionalProperty**: Menyertakan material, berat, dan dimensi.

---

## 3. INTERNAL LINKING & TOPICAL CLUSTERING

Untuk memperkuat struktur tautan internal website:
1. **Hub-and-Spoke**: Katalog utama (`/katalog`) berfungsi sebagai Hub, dan clean URL
   kategori (`/katalog/roster-beton`) berfungsi sebagai Spoke yang mendistribusikan
   otoritas tautan ke produk individual.
2. **Gallery ke Product**: Setiap foto galeri proyek yang menampilkan produk tertentu
   wajib diberi tautan "Beli Sekarang" langsung menuju ke detail produk terkait.
3. **Produk Terkait**: Di halaman detail produk, tampilkan 4 produk rekomendasi
   secara acak yang memiliki kategori yang sama untuk menjaga aliran crawler.

---

*Dokumen ini merupakan bagian dari panduan implementasi SEO Growth Engine.*
