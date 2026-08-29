---
name: seo-produk
description: Optimasi halaman produk untuk pembeli umum (B2C) — mencakup Product Intent dan Informational Intent sesuai Master Implementation Plan IndoRoster. Judul produk, gambar, deskripsi, kalkulator kebutuhan, dan konten edukasi.
---
# SEO Produk Cheatsheet (Product Intent + Informational Intent)

Target pembaca: individu/homeowner yang mencari & membandingkan produk, atau sedang riset teknis sebelum membeli.

## 1. Cakupan Intent yang Ditangani Skill Ini
*   **Product Intent**: `roster beton`, `roster beton minimalis`, `roster beton modern`, `roster dekoratif`, `roster ventilasi`, `roster untuk fasad`, `harga roster beton`, `jual roster beton`.
*   **Informational Intent**: `cara menghitung kebutuhan roster`, `berapa roster per m2`, `ukuran roster beton`, `berat roster beton`, `cara memasang roster beton`.

Kedua intent ini disatukan di sini karena sama-sama menyasar pembeli individu yang belum tentu siap transaksi — beda dengan `seo-buyer-intent` (B2B) atau `seo-project-wholesale` (volume besar).

## 2. Struktur Judul Produk (H1)
*   Format ideal: `Roster [Nama Motif] [Ukuran] [Warna/Bahan]` (Contoh: *Roster Arorow 20x20x10 Terakota*).
*   Satu H1 per halaman, tidak boleh duplikat.

## 3. Data Wajib per Halaman Produk
Sesuai standar minimal Master Plan, setiap halaman produk idealnya memuat:
*   Nama produk, SKU, motif, material, dimensi, berat
*   Harga, MOQ (jika berlaku), status stok
*   Gallery, spesifikasi teknis
*   Estimasi kebutuhan per m² (jika bisa dihitung)
*   CTA ganda: "Belanja Sekarang" (checkout langsung) **dan** "Request Quotation" (untuk kebutuhan besar — arahkan ke `seo-project-wholesale`/`seo-buyer-intent`)
*   Informasi pengiriman, produk terkait

## 4. Optimasi Konten Deskripsi
*   **Focus Keyword Density**: 1–2% sepanjang konten, ditempatkan alami di 100 kata pertama. Jangan keyword stuffing.
*   Hindari menargetkan Focus Keyword yang identik di dua produk berbeda (kanibalisasi). Gunakan kluster per motif/warna, contoh:
    *   Kluster A: `Roster Beton MMC` → target "roster MMC"
    *   Kluster B: `Roster Terakota` → target "roster terakota"
*   Kata kunci umum → halaman katalog kategori. Kata kunci spesifik → halaman detail produk (silo struktur, jangan tumpang tindih dengan struktur lokasi).

## 5. Konten Informational (Edukasi)
Bangun *content cluster* Product terpisah dari halaman jualan, contoh judul artikel:
*   "Cara Menghitung Kebutuhan Roster per M²"
*   "Ukuran & Berat Standar Roster Beton"
*   "Cara Memasang Roster Beton yang Benar"
*   "Roster untuk Ventilasi: Fungsi dan Jenisnya"

Aturan: konten ini harus faktual dan berguna, bukan artikel generik hasil AI yang dangkal — sertakan data teknis nyata (ukuran, berat, mutu beton) yang memang dimiliki IndoRoster.

## 6. Fitur Roster Calculator (rekomendasi produk, bukan sekadar konten)
Jika memungkinkan dibangun di sistem: kalkulator dengan input panjang dinding, tinggi dinding, ukuran roster, luas bukaan, waste percentage → output estimasi kebutuhan pcs + estimasi biaya. Tutup dengan CTA "Request Quotation" untuk kebutuhan besar. Ini menjawab Informational Intent sekaligus mengarahkan ke konversi.

## 7. Alt Text & Penamaan File Gambar
*   Alt text mendeskripsikan gambar dengan jelas + variasi kata kunci motif/fungsi (kota target diurus di skill `seo-lokasi`, jangan dobel di sini).
    *   *Buruk*: `alt="gambar1"` — *Baik*: `alt="Foto pemasangan roster beton MMC abu-abu untuk pagar rumah minimalis"`
*   Nama file deskriptif dengan tanda hubung: `roster-mmc-pagar-beton.webp`, bukan `IMG_12948.jpg`.

## 8. Diagnostik Google Images
*   File terlalu besar → kompres WebP di bawah 300KB.
*   Diblokir robots.txt → pastikan folder `/storage` diizinkan Googlebot-Image.
*   Tidak ada Alt Text → robot tidak bisa "membaca" visual.
*   Solusi: regenerasi Alt Text massal otomatis + submit sitemap gambar ke Google Search Console.

## 9. URL & Penanganan Stok
*   URL ramah SEO: `/katalog/roster-beton`, bukan `/katalog?category=roster-beton`.
*   Jangan hapus halaman produk stok habis (memicu 404). Biarkan aktif, ganti tombol jadi "Pre-order" atau rekomendasi motif serupa.

## 10. Video Produk & Structured Data
*   Schema `VideoObject`: `name`, `description` (min 80 karakter), `thumbnailUrl`, `uploadDate`, `contentUrl`/`embedUrl`. Gunakan lazy-load iframe YouTube.
*   Schema `Product` JSON-LD wajib: `name`, `image` (array), `offers` (harga, IDR, `InStock`/`OutOfStock`, link), `sku`/`mpn`. Validasi via Rich Results Test.
