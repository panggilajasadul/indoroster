---
name: technical-seo
description: Panduan optimasi performa web, crawlability, indexability, SSL/HTTPS, sitemap, dan canonicalization.
---
# Technical SEO Cheatsheet

## 1. Crawlability & Indexability
*   **Sitemap XML**: Pastikan sitemap dinamis otomatis terbarui saat ada produk/kategori baru. Lokasi: `/sitemap.xml`.
*   **Robots.txt**: Pastikan mengizinkan perayapan ke halaman katalog dan melarang folder `/checkout`, `/cart`, dan `/admin`.
*   **Canonical URL**: Gunakan tag `<link rel="canonical">` untuk menghindari duplikasi konten akibat parameter filter dinamis.

## 2. Page Speed & Core Web Vitals
*   **WebP Image Format**: Konversi semua media ke format WebP dan kompres ukuran file di bawah 200KB.
*   **Caching**: Gunakan Laravel Route/Config/View Cache di produksi untuk performa kilat.
*   **CSS/JS Minification**: Pastikan aset dikompilasi menggunakan `npm run build` sebelum dideploy.
