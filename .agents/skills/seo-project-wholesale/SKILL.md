---
name: seo-project-wholesale
description: Optimasi untuk pesanan volume besar/grosir sesuai Master Implementation Plan IndoRoster — kata kunci wholesale, MOQ, harga bertingkat, dan eskalasi ke Request Quotation/Sales Proyek.
---
# SEO Project/Wholesale Cheatsheet (Volume Besar)

Target pembaca: pembeli yang berniat order ratusan–ribuan keping untuk proyek. Beririsan dengan `seo-buyer-intent` (fokus di profesi pembeli), skill ini fokus khusus di **volume order**.

## 1. Kata Kunci Grosir
`roster beton grosir`, `harga roster partai besar`, `supplier roster ribuan pcs`, `beli roster beton borongan proyek`, `roster beton partai besar`, `harga roster per truk/per kubik`.

Halaman dedicated: `/roster-beton-grosir`, `/roster-beton-proyek` — jangan biarkan halaman ini mewarisi title/meta description dari beranda, optimasi terpisah.

## 2. MOQ & Structured Data Harga Bertingkat
*   Cantumkan MOQ (minimum order quantity) secara eksplisit di halaman wholesale.
*   Pada schema `Product`/`Offer` JSON-LD, sertakan `priceSpecification` dengan `eligibleQuantity` jika sistem mendukung, agar Google bisa tampilkan info "harga lebih murah untuk pembelian partai" di rich result.

## 3. Hierarki CTA Berdasarkan Skala Order
*   Order kecil–menengah (B2C): **"Belanja Sekarang"** → checkout langsung.
*   Order proyek/B2B (lihat `seo-buyer-intent`): **"Request Quotation"** → form quotation.
*   Order volume sangat besar: **"Hubungi Sales Proyek"** → kontak langsung WhatsApp/telepon sales.

Jangan membuat CTA palsu atau nomor kontak yang tidak aktif.

## 4. Alur Quotation untuk Volume Besar
Sama seperti alur di `seo-buyer-intent`, tapi tekankan proses cek kapasitas produksi & jadwal pengiriman armada karena skala order lebih besar:
```
Request quotation → Hitung harga volume → Cek kapasitas produksi
→ Cek jadwal pengiriman armada → Quotation → Approval → Invoice → DP
→ Produksi → Quality control → Pengiriman
```

## 5. Rubrik Kesehatan SEO Halaman Wholesale
*   Meta Title & Description terisi, mengandung kata kunci grosir (bobot tinggi — CTR)
*   Focus keyword grosir muncul di title & deskripsi
*   Alt text gambar menyebut konteks proyek/partai besar, bukan hanya motif
*   Halaman tersemat dengan benar di kategori (tidak jadi orphan page)
*   Bebas link/gambar rusak (HTTP 200) — penting karena halaman ini sering dirujuk dari proposal/RFQ eksternal

## 6. Audit Berkala
*   Cek mingguan: estimasi stok/kapasitas produksi partai besar yang ditampilkan harus selalu update — info stok tidak akurat menurunkan kepercayaan buyer besar.
*   Pastikan halaman wholesale tetap ringan & cepat dimuat di HP — banyak calon buyer besar mulai riset dari HP sebelum lanjut diskusi detail via WhatsApp/telepon.

## 7. Batas Anti-Manipulasi
Sama seperti skill lain: jangan mengarang kapasitas produksi, testimoni buyer besar, atau partnership distribusi yang tidak nyata. Data yang ditampilkan di halaman wholesale harus bisa dipertanggungjawabkan jika ditanya calon buyer besar saat negosiasi.
