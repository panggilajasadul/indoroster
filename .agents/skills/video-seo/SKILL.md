---
name: video-seo
description: Optimasi video produk/inspirasi agar terindeks di tab Google Video Search menggunakan skema data terstruktur.
---
# Video SEO Cheatsheet

## 1. Schema JSON-LD VideoObject
Pastikan setiap video inspirasi atau produk memiliki metadata terstruktur yang dapat dirayapi Google:
*   `name`: Judul video yang mengandung kata kunci roster.
*   `description`: Ringkasan video minimal 80 karakter.
*   `thumbnailUrl`: URL gambar sampul video berkualitas tinggi.
*   `uploadDate`: Tanggal video diunggah.
*   `contentUrl` / `embedUrl`: Link langsung video.

## 2. Embed Practices
*   Gunakan YouTube lazy load iframe agar tidak menurunkan skor kecepatan Core Web Vitals halaman Anda.
