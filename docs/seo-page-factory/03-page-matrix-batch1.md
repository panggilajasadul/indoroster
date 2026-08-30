# Page Matrix Batch 1 — IndoRoster SEO Page Factory

> Dokumen ini adalah panduan operasional untuk Batch 1 halaman SEO.
> Setiap baris adalah satu halaman kandidat dengan brief lengkap.
> Status awal semua halaman: `DRAFT` → harus melalui QA sebelum `PUBLISHED`.

---

## Prinsip Batch 1

- **Bukan "sebanyak mungkin halaman"** — tapi sebanyak mungkin kebutuhan pembeli yang bisa dijawab
- **Setiap halaman harus punya Unique Page Purpose yang nyata**
- **Semua halaman melewati quality gate** sebelum publish
- **Tidak ada harga yang tidak akurat** — gunakan panduan quotation
- **Tidak ada klaim yang tidak terverifikasi** — kapasitas, pengiriman, MOQ

---

## ✅ Business Rules yang Sudah Dikonfirmasi Owner (31 Agustus 2026)

| Data | Nilai | Status |
|---|---|---|
| Kapasitas produksi | **10.000 pcs/bulan** | ✅ VERIFIED |
| MOQ Retail | **1.000 pcs** | ✅ VERIFIED |
| MOQ Grosir | **5.000 pcs** | ✅ VERIFIED |
| MOQ berlaku untuk semua motif? | **Ya, tanpa kecuali** | ✅ VERIFIED |
| Harga nominal per pcs | Tidak dipublikasikan | ⏳ Arahkan ke quotation |
| Estimasi waktu pengiriman | Belum terverifikasi | ❌ Jangan klaim |
| Biaya pengiriman | Belum terverifikasi | ❌ Jangan klaim |

> **Config tersimpan di:** `config/indoroster_business.php`

---


## A. Core Supplier Pages (Priority: Sangat Tinggi)

### 1. Supplier Roster Beton

| Field | Value |
|---|---|
| Slug | `pabrik-supplier-roster-beton` |
| Page Type | `pillar` |
| Primary Keyword | `supplier roster beton` |
| Intent | BOFU |
| Buyer | Umum (semua buyer) |
| Unique Purpose | Halaman pilar utama yang menjawab siapa IndoRoster sebagai supplier |
| Unique Angle | Pabrik langsung (bukan reseller) — presisi cetakan baja, dokumen resmi |
| Evidence | 45 motif katalog, dokumen pengiriman resmi, siku 90° presisi |
| CTA | WhatsApp konsultasi |
| Products | Semua produk featured |

---

### 2. Pabrik Roster Beton

| Field | Value |
|---|---|
| Slug | `pabrik-roster-beton` |
| Page Type | `pillar` |
| Primary Keyword | `pabrik roster beton` |
| Intent | BOFU |
| Buyer | Kontraktor, Developer |
| Unique Purpose | Menjawab pertanyaan "beli dari pabrik langsung" — bukan toko/reseller |
| Unique Angle | Pabrik di Plered Purwakarta, proses produksi, cetakan baja, stock ribuan |
| Evidence | Link ke halaman proses produksi, 45+ motif |
| CTA | WhatsApp minta penawaran pabrik |
| Products | Best-seller |

---

### 3. Supplier Roster Beton Grosir

| Field | Value |
|---|---|
| Slug | `supplier-roster-beton-grosir` |
| Page Type | `wholesale` |
| Primary Keyword | `supplier roster beton grosir` |
| Secondary | `grosir roster beton`, `harga grosir roster beton` |
| Intent | BOFU |
| Buyer | Kontraktor, Developer, Pemborong |
| Unique Purpose | Menjawab kebutuhan volume besar — bedakan dari retail |
| Unique Angle | MOQ grosir 5.000 pcs, harga bertingkat berdasarkan volume |
| Evidence | MOQ: Retail 1.000 pcs, Grosir 5.000 pcs |
| ⚠️ Note | JANGAN tulis harga nominal — arahkan ke quotation |
| CTA | Minta penawaran grosir via WhatsApp |
| Products | All |

---

### 4. Supplier Roster Beton Minimalis

| Field | Value |
|---|---|
| Slug | `supplier-roster-beton-minimalis` |
| Page Type | `product_landing` |
| Primary Keyword | `supplier roster beton minimalis` |
| Secondary | `roster beton minimalis murah`, `jual roster minimalis` |
| Intent | BOFU |
| Buyer | Arsitek, Developer, Owner |
| Unique Purpose | Fokus ke desain minimalis modern — beda dari halaman grosir/proyek |
| Unique Angle | 45+ motif minimalis, pilihan ketebalan, contoh aplikasi fasad |
| Evidence | Katalog produk 45 motif tersedia |
| CTA | Lihat katalog + WhatsApp |
| Products | All (roster minimalis catalog) |

---

## B. Buyer-Specific Pages (Priority: Tinggi)

### 5. Supplier Roster untuk Kontraktor

| Field | Value |
|---|---|
| Slug | `supplier-roster-beton-untuk-kontraktor` |
| Page Type | `buyer` |
| Primary Keyword | `supplier roster untuk kontraktor` |
| Secondary | `roster beton kontraktor proyek`, `supplier roster proyek` |
| Intent | BOFU |
| Buyer | Kontraktor |
| Unique Purpose | Menjawab masalah spesifik kontraktor: konsistensi dimensi, batch delivery, dokumen |
| Unique Angle | Siku 90° presisi = hemat waktu pasang, batch delivery = tidak menumpuk di area proyek |
| Evidence | Spesifikasi produk, dokumentasi pengiriman |
| CTA | Kirim kebutuhan RAB via WhatsApp |
| Products | Featured |

---

### 6. Roster Beton untuk Developer Perumahan

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-developer-perumahan` |
| Page Type | `buyer` |
| Primary Keyword | `roster beton untuk developer` |
| Secondary | `supplier roster untuk developer perumahan`, `roster cluster perumahan` |
| Intent | BOFU |
| Buyer | Developer |
| Project | Perumahan |
| Unique Purpose | Menjawab kebutuhan developer: volume banyak, konsistensi motif antar unit, timeline |
| Unique Angle | Konsistensi produksi untuk banyak unit = tampilan cluster seragam |
| Evidence | MOQ 5.000 pcs, 45+ motif untuk dipilih |
| CTA | Konsultasi pengadaan cluster via WhatsApp |
| Products | Featured |

---

### 7. Supplier Roster untuk Pemborong

| Field | Value |
|---|---|
| Slug | `supplier-roster-untuk-pemborong` |
| Page Type | `buyer` |
| Primary Keyword | `supplier roster untuk pemborong` |
| Secondary | `roster beton pemborong`, `beli roster beton pemborong` |
| Intent | BOFU |
| Buyer | Pemborong |
| Unique Purpose | Pemborong = eksekutor lapangan yang butuh material siap pakai, harga kompetitif |
| Unique Angle | Order mudah via WhatsApp, pengiriman terencana, tanpa minimum complexity |
| Evidence | Proses pemesanan sederhana, MOQ 1.000 pcs retail |
| CTA | Minta harga via WhatsApp |
| Products | Featured |

---

### 8. Vendor Roster untuk Procurement Proyek

| Field | Value |
|---|---|
| Slug | `vendor-roster-beton-untuk-procurement-proyek` |
| Page Type | `procurement` |
| Primary Keyword | `vendor roster beton untuk proyek` |
| Secondary | `pengadaan roster beton`, `vendor roster proyek tender` |
| Intent | BOFU |
| Buyer | Procurement |
| Unique Purpose | Menjawab proses pengadaan formal: kelengkapan dokumen, NIB, NPWP |
| Unique Angle | Dokumen vendor resmi tersedia: surat jalan, invoice, faktur pajak |
| Evidence | Kelengkapan dokumen perusahaan |
| CTA | Request dokumen vendor via WhatsApp |
| Products | Featured |

---

## C. Project-Specific Pages (Priority: Tinggi)

### 9. Supplier Roster untuk Proyek Perumahan

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-proyek-perumahan` |
| Page Type | `project` |
| Primary Keyword | `roster beton untuk proyek perumahan` |
| Secondary | `supplier roster perumahan`, `roster cluster perumahan` |
| Intent | BOFU |
| Buyer | Kontraktor, Developer |
| Project | Perumahan |
| Unique Purpose | Menjawab tantangan spesifik proyek perumahan multi-unit |
| Unique Angle | Volume besar, konsistensi motif, pengiriman bertahap per unit |
| Evidence | MOQ 5.000 pcs, jadwal kirim bisa disesuaikan timeline proyek |
| CTA | Kirim detail proyek via WhatsApp |
| Products | Featured |

---

### 10. Roster Beton untuk Proyek Gedung

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-proyek-gedung` |
| Page Type | `project` |
| Primary Keyword | `roster beton untuk proyek gedung` |
| Secondary | `roster beton gedung kantor`, `roster beton komersial` |
| Intent | BOFU |
| Buyer | Kontraktor, Procurement |
| Project | Gedung |
| Unique Purpose | Gedung komersial vs perumahan: volume, spesifikasi teknis, load capacity |
| Unique Angle | Roster sebagai elemen fasad gedung modern — solusi ventilasi dan estetika |
| Evidence | Spesifikasi dimensi dan material |
| CTA | Konsultasi kebutuhan gedung via WhatsApp |
| Products | Featured |

---

### 11. Roster untuk Proyek Bangunan Komersial

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-bangunan-komersial` |
| Page Type | `project` |
| Primary Keyword | `roster beton untuk bangunan komersial` |
| Secondary | `roster beton hotel`, `roster beton restoran`, `roster beton cafe` |
| Intent | MOFU → BOFU |
| Buyer | Kontraktor, Arsitek |
| Project | Komersial |
| Unique Purpose | Hotel, kafe, restoran — estetika + ventilasi, bukan hanya material murah |
| Unique Angle | Pilihan motif modern untuk branding bangunan komersial |
| CTA | Konsultasi desain via WhatsApp |
| Products | Featured motif modern |

---

## D. Use Case Pages (Priority: Sedang-Tinggi)

### 12. Roster Beton untuk Fasad

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-fasad` |
| Page Type | `usecase` |
| Primary Keyword | `roster beton untuk fasad` |
| Secondary | `roster beton fasad rumah`, `roster beton dinding depan` |
| Intent | MOFU → BOFU |
| Buyer | Arsitek, Owner, Developer |
| Use Case | Fasad |
| Unique Purpose | Fasad = tampilan luar bangunan — estetika, motif, ketebalan |
| Unique Angle | 45+ pilihan motif, visualisasi aplikasi fasad |
| CTA | Lihat katalog + konsultasi motif |
| Products | All motif |

---

### 13. Roster Beton untuk Ventilasi

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-ventilasi` |
| Page Type | `usecase` |
| Primary Keyword | `roster beton untuk ventilasi` |
| Secondary | `roster beton lubang angin`, `roster ventilasi udara` |
| Intent | TOFU → MOFU |
| Buyer | Owner, Arsitek |
| Use Case | Ventilasi |
| Unique Purpose | Ventilasi alami tanpa AC — manfaat roster untuk sirkulasi udara |
| Unique Angle | Lubang roster = sirkulasi udara natural, hemat energi |
| CTA | Lihat katalog |
| Products | Motif dengan lubang besar |

---

### 14. Roster Beton untuk Dinding Dekoratif

| Field | Value |
|---|---|
| Slug | `roster-beton-untuk-dinding-dekoratif` |
| Page Type | `usecase` |
| Primary Keyword | `roster beton dekoratif` |
| Intent | MOFU |
| Buyer | Owner, Arsitek |
| Use Case | Dekoratif |
| Unique Purpose | Roster sebagai elemen dekorasi — bukan hanya fungsi ventilasi |
| CTA | Lihat katalog motif |
| Products | All |

---

## E. Volume / Grosir (Priority: Tinggi)

### 15. Grosir Roster Beton

| Field | Value |
|---|---|
| Slug | `grosir-roster-beton` |
| Page Type | `wholesale` |
| Primary Keyword | `grosir roster beton` |
| Secondary | `jual grosir roster beton`, `harga grosir roster beton` |
| Intent | BOFU |
| Buyer | Kontraktor, Developer, Pemborong |
| Unique Purpose | Halaman grosir khusus — bedakan dari retail |
| Evidence | MOQ grosir: 5.000 pcs |
| ⚠️ Note | Jangan tulis harga nominal — arahkan ke quotation |
| CTA | Minta penawaran grosir |

---

### 16. Roster Beton Pembelian Volume Besar

| Field | Value |
|---|---|
| Slug | `roster-beton-volume-besar` |
| Page Type | `wholesale` |
| Primary Keyword | `roster beton volume besar` |
| Secondary | `roster beton banyak`, `beli roster beton proyek besar` |
| Intent | BOFU |
| Buyer | Developer, Kontraktor |
| Unique Purpose | Kebutuhan di atas 10.000 pcs — planning produksi dan pengiriman |
| Evidence | Proses konfirmasi kapasitas sebelum komitmen |
| CTA | Kirim detail kebutuhan via WhatsApp |

---

## F. Pricing (Priority: Sedang)

### 17. Harga Roster Beton

| Field | Value |
|---|---|
| Slug | `harga-roster-beton` |
| Page Type | `pricing` |
| Primary Keyword | `harga roster beton` |
| Secondary | `harga roster beton per pcs`, `harga roster minimalis` |
| Intent | MOFU |
| Buyer | Umum |
| Unique Purpose | Menjawab pertanyaan harga — tanpa mencantumkan harga fiktif |
| ⚠️ PENTING | **JANGAN cantumkan harga nominal spesifik** |
| Unique Angle | Faktor penentu harga: motif, ukuran, volume, lokasi pengiriman — request quotation |
| CTA | Minta penawaran sesuai kebutuhan |

---

### 18. Harga Roster Beton untuk Proyek

| Field | Value |
|---|---|
| Slug | `harga-roster-beton-untuk-proyek` |
| Page Type | `pricing` |
| Primary Keyword | `harga roster beton untuk proyek` |
| Intent | BOFU |
| Buyer | Kontraktor, Developer |
| Unique Purpose | Harga proyek berbeda dari retail — volume discount, jadwal, dan kondisi pengiriman |
| ⚠️ PENTING | Tidak ada harga nominal — arahkan ke proses penawaran proyek |
| CTA | Kirim kebutuhan proyek untuk harga khusus |

---

## G. Location Pages (Priority: Rendah-Sedang untuk Batch 1)

> **Aturan ketat Batch 1**: Maksimal 3 kota. Hanya kota yang benar-benar ada permintaan GSC.
> Jangan buat location page hanya karena "kota besar".

### 19. Supplier Roster Beton Jakarta

| Field | Value |
|---|---|
| Slug | `supplier-roster-beton-jakarta` |
| Page Type | `location` |
| Primary Keyword | `supplier roster beton Jakarta` |
| Location | Jakarta |
| Unique Purpose | Jakarta = pasar terbesar, jangkauan pengiriman |
| ⚠️ Shipping | Gunakan narasi aman: "Pengiriman direncanakan berdasarkan volume dan jadwal" |
| ⚠️ No fake data | Jangan tulis "1-2 hari" atau biaya spesifik |
| CTA | Minta info pengiriman ke Jakarta |

---

### 20. Supplier Roster Beton Bekasi

| Field | Value |
|---|---|
| Slug | `supplier-roster-beton-bekasi` |
| Page Type | `location` |
| Primary Keyword | `supplier roster beton Bekasi` |
| Location | Bekasi |
| Unique Purpose | Bekasi = zona industri & perumahan besar, banyak proyek skala menengah-besar |
| ⚠️ Shipping | Narasi aman, tanpa data fiktif |
| CTA | Minta info pengiriman ke Bekasi |

---

### 21. Supplier Roster Beton Bandung

| Field | Value |
|---|---|
| Slug | `supplier-roster-beton-bandung` |
| Page Type | `location` |
| Primary Keyword | `supplier roster beton Bandung` |
| Location | Bandung |
| Unique Purpose | Bandung = pasar arsitektur kuat + dekat Plered (produksi) |
| ⚠️ Shipping | Narasi aman, tanpa data fiktif |
| CTA | Minta info pengiriman ke Bandung |

---

## Seeder Data Batch 1

Jalankan perintah berikut untuk seed keyword universe Batch 1:

```bash
php artisan db:seed --class=SeoKeywordBatch1Seeder
```

> Seeder akan diimplementasikan di Phase berikutnya.

---

## Checklist Sebelum Publish

Setiap halaman **WAJIB** melewati checklist ini:

- [ ] Quality Score ≥ 60
- [ ] Semua kriteria kritis ≥ 4 (search_intent_match, buyer_relevance, unique_information, factual_accuracy, conversion_clarity)
- [ ] Tidak ada harga nominal yang tidak terverifikasi
- [ ] Tidak ada klaim kapasitas produksi dengan satuan waktu yang belum dikonfirmasi
- [ ] Tidak ada klaim pengiriman (waktu/biaya) tanpa data verified
- [ ] Slug tidak konflik dengan existing pages
- [ ] Duplication check passed (similarity < 75%)
- [ ] Human review oleh owner

---

## Definisi Keberhasilan Batch 1

### SEO Metrics (ukur 60 hari setelah publish)
- Pages indexed: ≥ 80%
- Impressions dari halaman baru: ada peningkatan
- CTR dari query yang ditargetkan: baseline

### Business Metrics
- WhatsApp clicks dari halaman SEO
- Quotation requests
- Qualified leads dari kontraktor/developer

### Quality Metrics
- Duplicate rate: 0%
- Pages needing immediate merge: 0
- Pages with zero value: 0
