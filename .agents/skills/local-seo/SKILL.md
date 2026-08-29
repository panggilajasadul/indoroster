---
name: local-seo
description: Strategi memenangkan pencarian lokal berskala nasional yang scalable — target market resmi Jabodetabek, Jawa Barat (Bandung, Cianjur, Sukabumi, Karawang, Cirebon), lalu meluas nasional. Plered/Purwakarta = lokasi produksi, BUKAN target market.
---
# Local SEO Cheatsheet (National Scalable)

## 1. ATURAN UTAMA — Lokasi Produksi vs Target Market
**Plered, Purwakarta** adalah lokasi produksi/pabrik. Boleh disebut natural sebagai info perusahaan/asal produk/pengiriman, **tapi tidak boleh jadi prioritas SEO** — jangan jadikan headline title tag atau meta description utama hanya karena itu alamat kantor. Jangan membuat positioning seolah IndoRoster hanya melayani Purwakarta.

**Target market resmi** (urutan prioritas):
1.  Jakarta, Bekasi, Bogor, Depok, Tangerang, Tangerang Selatan, Bandung, Cimahi, Karawang, Cianjur, Sukabumi, Cirebon
2.  Kota/kabupaten Jawa Barat lainnya + kota besar Jawa
3.  Kota besar luar Jawa
4.  Lokasi lain dengan demand terbukti dari data (Google Search Console)

## 2. Contoh Title Tag & Meta Description yang Benar
*   **Title**: `Jual Roster Beton Minimalis Jabodetabek, Bandung, Cianjur, Cirebon, Sukabumi | IndoRoster`
*   **Meta description**: sebutkan kota target eksplisit dan merata; "langsung dari pabrik" ditulis sebagai *value proposition* (harga murah tanpa perantara), bukan sebagai lokasi target pencarian.
*   *Contoh salah* (hindari): "Pabrik Roster Beton Minimalis Plered Purwakarta" sebagai judul utama — ini membuat Google mengasosiasikan situs dengan pencarian lokal Purwakarta, padahal itu bukan target pembeli.

## 3. Struktur Data Lokasi (Location Engine)
Untuk sistem yang scalable ke seluruh Indonesia, setiap entri lokasi idealnya punya atribut: `name`, `slug`, `type` (province/regency/city/district/area/metropolitan_area), `province_id`, `parent_id`, `priority`, `seo_enabled`, `market_priority`. Koordinasikan dengan tim developer untuk implementasi database-nya.

## 4. Aturan Programmatic SEO (Anti Doorway Page)
Sebuah halaman lokasi **hanya boleh dipublikasikan** jika minimal memenuhi:
*   Ada search intent yang masuk akal untuk kota tersebut
*   Ada informasi unik (bukan template generik ganti nama kota saja)
*   Ada produk relevan, informasi pengiriman, FAQ relevan, dan internal linking
*   Ada CTA jelas
*   Skor kualitas halaman ≥ 75/100 (lihat skill `seo-scoring`)

**Jangan pernah mengarang**: alamat cabang, gudang, toko fisik, stok lokal, waktu/biaya pengiriman spesifik per kota, testimoni, proyek, atau partnership yang tidak nyata. Jangan otomatis publish seluruh lokasi hanya karena datanya sudah ada di database — publish bertahap sesuai prioritas.

## 5. Google Business Profile (GBP) Integration
*   Pastikan nama, alamat, dan nomor telepon (NAP) di website sama persis dengan GBP (alamat pabrik tetap boleh Purwakarta — itu memang alamat legal fisik).
*   **Service Area** di GBP diisi manual dengan kota-kota target (Jabodetabek, Bandung, Cianjur, Cirebon, Sukabumi, dst), bukan hanya radius otomatis dari alamat pabrik.
*   Pasang peta Google Maps tersemat (embed) di halaman kontak website.

## 6. Arsitektur URL Lokasi
Contoh struktur (audit dulu struktur existing sebelum ubah, jangan rusak URL yang sudah terindeks/dapat backlink):
```
/lokasi
/lokasi/jawa-barat
/lokasi/jawa-barat/bandung
/lokasi/jawa-barat/cianjur
```

## 7. Ekspansi Berbasis Data (GSC-driven)
Gunakan Google Search Console: jika ada impression/klik untuk query "roster beton [kota]" tapi belum ada halaman kota tersebut, itu sinyal untuk membuat halaman baru — tetap lewati quality check di poin 4, jangan auto-publish tanpa audit.

## 8. Schema untuk Lokasi
Jangan pakai `@type: City` untuk semua level lokasi. Jangan buat schema `LocalBusiness` palsu seolah ada cabang/outlet di setiap kota target — gunakan `Organization` dengan properti `areaServed` untuk mencantumkan wilayah yang dilayani tanpa mengklaim ada cabang fisik.
