---
name: technical-seo
description: Panduan optimasi performa web, crawlability, indexability, SSL/HTTPS, sitemap, canonicalization, dan checklist audit teknis lengkap sebelum melakukan perubahan besar.
---
# Technical SEO Cheatsheet

## 1. Crawlability & Indexability
*   **Sitemap XML**: Pastikan sitemap dinamis otomatis terbarui saat ada produk/kategori/halaman lokasi baru. Lokasi: `/sitemap.xml`.
*   **Robots.txt**: Pastikan mengizinkan perayapan ke halaman katalog & lokasi, dan melarang folder `/checkout`, `/cart`, dan `/admin`.
*   **Canonical URL**: Gunakan tag `<link rel="canonical">` untuk menghindari duplikasi konten akibat parameter filter dinamis.

## 2. Page Speed & Core Web Vitals
*   **WebP Image Format**: Konversi semua media ke format WebP dan kompres ukuran file di bawah 200KB.
*   **Caching**: Gunakan Laravel Route/Config/View Cache di produksi untuk performa kilat.
*   **CSS/JS Minification**: Pastikan aset dikompilasi menggunakan `npm run build` sebelum dideploy.

## 3. Checklist Audit Teknis (WAJIB sebelum perubahan besar)
Sebelum menambah halaman baru dalam jumlah besar (lokasi, kawasan, dsb), audit dulu:
existing routes, sitemap, robots.txt, canonical, meta title, meta description, H1, heading hierarchy, Open Graph, schema, internal links, pagination, duplicate URLs, query parameters, product variant URLs, image alt, image filenames, Core Web Vitals, indexability, redirect, 404, soft 404, trailing slash, HTTP/HTTPS, www/non-www, canonicalization.

**Jangan merusak URL existing yang sudah mendapatkan traffic/backlink** — kalau struktur URL perlu berubah, gunakan redirect 301 yang benar, jangan biarkan 404.

## 4. Prinsip Implementasi Bertahap
Untuk perubahan besar (misal menambah ratusan halaman lokasi baru), jangan deploy sekaligus. Lakukan bertahap: audit → desain arsitektur → implementasi kecil → testing → baru scale up. Setiap tahap harus tidak merusak fitur yang sudah berjalan.
