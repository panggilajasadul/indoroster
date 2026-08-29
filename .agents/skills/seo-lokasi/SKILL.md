---
name: seo-lokasi
description: Strategi Location Intent nasional sesuai Master Implementation Plan IndoRoster — arsitektur lokasi scalable, sistem prioritas wilayah, dan aturan anti-fake-content. Plered/Purwakarta = lokasi produksi, BUKAN target market.
---
# SEO Lokasi Cheatsheet (Location Intent — National Scalable)

Target pembaca: pencari dengan intent geografis, contoh: `roster beton Bandung`, `roster beton Bekasi`.

## 1. ATURAN UTAMA — Lokasi Produksi vs Target Market
**Plered, Purwakarta** = lokasi produksi/asal pengiriman. Boleh disebut natural sebagai info perusahaan/asal produk, **tapi tidak boleh jadi prioritas SEO** atau headline title tag/meta description hanya karena itu alamat kantor. Jangan membuat positioning seolah IndoRoster hanya melayani Purwakarta.

**Target market** = kota/kabupaten yang benar-benar dituju pembeli, prioritas awal:
Jabodetabek, Jawa Barat (Bandung Raya, Cianjur, Sukabumi, Karawang, Cirebon), lalu meluas ke seluruh Indonesia secara bertahap.

## 2. Location Engine — Struktur Data (untuk konteks konten, bukan skema DB penuh)
Setiap entri lokasi idealnya punya atribut ini agar SEO-nya bisa dikelola presisi (koordinasi dengan tim dev untuk implementasi DB):
`name`, `slug`, `type` (province / regency / city / district / area / metropolitan_area / property_area), `province_id`, `parent_id`, `priority`, `seo_enabled`, `market_priority`.

**Penting**: jangan pakai `@type: City` untuk semua level lokasi di schema.org — bedakan lokasi administratif (kota/kabupaten) dari kawasan/area (urus jenis ini di skill `seo-kawasan`).

## 3. Sistem Prioritas Wilayah
| Prioritas | Wilayah |
|---|---|
| 1 (utama) | Jakarta, Bekasi, Bogor, Depok, Tangerang, Tangerang Selatan, Bandung, Cimahi, Karawang, Cianjur, Sukabumi, Cirebon |
| 2 | Kota/kabupaten Jawa Barat lainnya + kota besar Jawa |
| 3 | Kota besar luar Jawa |
| 4 | Lokasi lain dengan demand terbukti (data dari Google Search Console) |

Jangan otomatis publish seluruh lokasi hanya karena datanya tersedia di database — publish bertahap sesuai prioritas & kesiapan konten.

## 4. Aturan Programmatic SEO (Anti Doorway Page)
Sebuah halaman lokasi **hanya boleh dipublikasikan/di-index** jika minimal memenuhi:
*   Ada search intent yang masuk akal untuk kota tersebut
*   Ada informasi unik (bukan template generik ganti nama kota saja)
*   Ada produk relevan yang ditampilkan
*   Ada informasi pengiriman ke kota tersebut
*   Ada FAQ relevan & internal linking
*   Ada CTA jelas
*   Skor kualitas halaman ≥ 75/100 (lihat rubrik di bawah)

**Jangan pernah mengarang**: alamat cabang, gudang, toko fisik, stok lokal, waktu/biaya pengiriman spesifik, testimoni, proyek, atau partnership yang tidak benar-benar ada. Jika datanya tidak tersedia, jangan dibuat-buat.

## 5. Rubrik Skor Kualitas Halaman Lokasi (maks 100)
Location data (20) + Unique content (20) + Product relevance (15) + Shipping info (10) + Internal linking (10) + FAQ (10) + Conversion CTA (10) + Schema (5). Publish/index hanya jika skor ≥ 75.

## 6. Contoh Title Tag & Meta Description
*   **Title**: `Jual Roster Beton Minimalis Jabodetabek, Bandung, Cianjur, Cirebon, Sukabumi | IndoRoster`
*   **Meta description**: sebutkan kota target eksplisit; sebutkan "langsung dari pabrik" sebagai value proposition (harga murah tanpa perantara), bukan sebagai target keyword lokasi.

## 7. Arsitektur URL Lokasi
Contoh struktur (sesuaikan dengan routing existing, audit dulu sebelum ubah URL yang sudah terindeks/dapat backlink):
```
/lokasi
/lokasi/jawa-barat
/lokasi/jawa-barat/bandung
/lokasi/jawa-barat/bekasi
/lokasi/jawa-barat/cianjur
```

## 8. Google Business Profile (GBP)
*   NAP di website harus sama persis dengan GBP (alamat pabrik tetap boleh Purwakarta — itu memang alamat legal fisik).
*   **Service Area** GBP diisi manual dengan kota-kota target, bukan hanya radius dari pabrik.
*   Embed Google Maps di halaman kontak.

## 9. Ekspansi Berbasis Data (GSC-driven)
Gunakan Google Search Console untuk menemukan lokasi baru yang layak digarap: jika ada impression/klik untuk query `roster beton [kota]` tapi belum ada halaman kota tersebut, itu sinyal untuk membuat halaman baru — tetap lewati quality check di poin 4 & 5, jangan auto-publish.

## 10. Sinonim & Variasi Istilah Lokal
`Roster` (istilah modern/arsitektur), `Loster` (istilah umum/lokal Jawa Barat), `Lubang angin`/`Ventilasi beton` (istilah umum perumahan), `Kisi-kisi beton` (istilah teknik sipil) — selipkan variasi ini secara alami di konten lokasi.
