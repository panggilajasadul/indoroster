---
name: google-images-diagnostics
description: Mendiagnosis gambar yang tidak muncul di pencarian Google Images serta optimasi metadata gambar.
---
# Google Images Diagnostics Cheatsheet

## 1. Penyebab Gambar Tidak Terindeks
*   **File terlalu besar**: Pastikan gambar dikompres menjadi WebP di bawah 300KB.
*   **Gambar diblokir robots.txt**: Pastikan folder `/storage` diizinkan dirayapi oleh robot Googlebot-Image.
*   **Tidak ada Alt Text**: Robot pencari tidak bisa membaca isi visual gambar tanpa Alt Text.

## 2. Langkah Solusi
Jalankan regenerasi Alt Text massal secara otomatis dan laporkan URL sitemap gambar produk Anda ke Google Search Console.
