---
name: google-images-diagnostics
description: Mendiagnosis gambar yang tidak muncul di pencarian Google Images serta optimasi metadata gambar, termasuk gambar produk, lokasi, dan proyek/kawasan.
---
# Google Images Diagnostics Cheatsheet

## 1. Penyebab Gambar Tidak Terindeks
*   **File terlalu besar**: Pastikan gambar dikompres menjadi WebP di bawah 300KB.
*   **Gambar diblokir robots.txt**: Pastikan folder `/storage` diizinkan dirayapi oleh robot Googlebot-Image.
*   **Tidak ada Alt Text**: Robot pencari tidak bisa membaca isi visual gambar tanpa Alt Text.
*   **Sitemap gambar tidak lengkap**: gambar produk, galeri proyek, dan foto kawasan harus semua tercakup di sitemap gambar, bukan hanya foto produk katalog utama.

## 2. Langkah Solusi
*   Jalankan regenerasi Alt Text massal secara otomatis dan laporkan URL sitemap gambar produk Anda ke Google Search Console.
*   Untuk foto galeri proyek/kawasan, pastikan file name & alt text menyebut nama kawasan/jenis proyek secara jujur (sesuai data nyata) — jangan menempelkan nama kota/kawasan sembarangan hanya demi SEO jika foto tersebut sebenarnya dari lokasi lain.

## 3. Audit Berkala
Cek mingguan/bulanan status indexing gambar via Google Search Console → laporan "Gambar" untuk memastikan tidak ada penurunan mendadak akibat gambar broken link atau perubahan struktur folder `/storage`.
