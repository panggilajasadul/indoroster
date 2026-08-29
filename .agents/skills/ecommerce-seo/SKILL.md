---
name: ecommerce-seo
description: Optimasi halaman katalog, pagination, routing ramah SEO, penanganan out-of-stock, dan pemisahan jalur B2C vs B2B agar tidak saling mengganggu.
---
# E-commerce SEO Cheatsheet

## 1. Clean URLs untuk Kategori
*   Gunakan URL ramah SEO daripada parameter query dinamis:
    *   *Buruk*: `/katalog?category=roster-beton`
    *   *Baik*: `/katalog/roster-beton`
*   Sesuaikan dengan routing existing yang sudah terindeks/dapat backlink — audit dulu sebelum ubah struktur URL, jangan sampai merusak trafik yang sudah ada.

## 2. Penanganan Produk Habis (Out of Stock)
*   Jangan pernah menghapus halaman produk yang stoknya habis karena akan memicu 404 (menghilangkan ranking Google).
*   Biarkan halaman tetap aktif, ganti tombol beli menjadi "Pre-order" atau tawarkan rekomendasi roster bermotif serupa.

## 3. Dua Jalur Checkout — Jangan Dicampur
Website harus punya dua alur terpisah yang tidak saling memaksa:
*   **B2C**: Product → Cart → Checkout → Payment (pembelian eceran/kecil).
*   **B2B**: Product → Request Quotation → Negosiasi → Invoice → Payment (pembelian volume besar/proyek).

Jangan memaksa calon pembeli volume besar masuk ke checkout eceran biasa — sediakan CTA "Request Quotation" yang jelas di halaman produk sebagai alternatif "Belanja Sekarang", terutama untuk produk yang sering dipesan partai besar.

## 4. MOQ (Minimum Order Quantity)
Jika produk punya minimum order untuk harga grosir, cantumkan eksplisit di halaman produk/kategori — ini membantu calon buyer besar langsung tahu harus lewat jalur mana (checkout biasa atau quotation).
