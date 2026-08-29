---
name: structured-data
description: Penerapan skema JSON-LD untuk Product, Price, Offer, Organization, dan lokasi — termasuk aturan tegas anti-fake LocalBusiness dan harga bertingkat untuk wholesale.
---
# Structured Data Cheatsheet

## 1. Skema Product JSON-LD
Wajib menyertakan properti penting agar lolos validasi Google Merchant Center:
*   `name`: Nama produk.
*   `image`: Array URL foto-foto produk.
*   `offers`: Detail harga, mata uang (IDR), ketersediaan stok (`InStock`/`OutOfStock`), dan link produk.
*   `mpn` atau `sku`: Pengidentifikasi produk unik dari pabrik.

## 2. Harga Bertingkat untuk Wholesale
Untuk produk yang sering dipesan volume besar, sertakan `priceSpecification` dengan `eligibleQuantity` jika sistem mendukung, agar Google bisa menampilkan info "harga lebih murah untuk pembelian partai" di rich result.

## 3. Schema Lokasi & Organisasi — ATURAN KERAS
*   Jangan pakai `@type: City` untuk semua level lokasi — sesuaikan tipe entitas schema.org dengan jenis lokasi sebenarnya (provinsi/kota/kabupaten/area).
*   **Jangan membuat schema `LocalBusiness` palsu** untuk kota-kota yang tidak benar-benar punya cabang/outlet IndoRoster. Gunakan `Organization` dengan properti `areaServed` untuk mencantumkan wilayah layanan tanpa mengklaim ada cabang fisik.
*   Alamat pabrik di Plered/Purwakarta boleh dicantumkan sebagai info perusahaan/manufacturer di schema `Organization`, tapi jangan direplikasi seolah jadi alamat cabang di kota lain.

## 4. Video & FAQ Schema
*   `VideoObject` untuk video produk/proyek: `name`, `description`, `thumbnailUrl`, `uploadDate`, `contentUrl`/`embedUrl`.
*   `FAQPage` hanya dipasang jika kontennya memang tersedia dan relevan di halaman — jangan pasang FAQ schema tanpa FAQ yang benar-benar tampil ke user.

## 5. Validasi
Uji struktur data menggunakan layanan resmi Google: *Schema Markup Validator* atau *Rich Results Test* — lakukan ini setiap kali ada perubahan schema besar, bukan hanya sekali di awal.
