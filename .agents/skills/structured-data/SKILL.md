---
name: structured-data
description: Penerapan skema JSON-LD untuk Product, Price, Offer, dan Google Merchant Center.
---
# Structured Data Cheatsheet

## 1. Skema Product JSON-LD
Wajib menyertakan properti penting agar lolos validasi Google Merchant Center:
*   `name`: Nama produk.
*   `image`: Array URL foto-foto produk.
*   `offers`: Detail harga, mata uang (IDR), ketersediaan stok (`InStock`), dan link produk.
*   `mpn` atau `sku`: Pengidentifikasi produk unik dari pabrik.

## 2. Validasi
Uji struktur data Anda menggunakan layanan resmi Google: *Schema Markup Validator* atau *Rich Results Test*.
