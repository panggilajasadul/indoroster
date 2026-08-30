# IndoRoster — SEO Page Factory: Master Plan

> **Dokumen induk. Baca ini dulu sebelum membuka file lain.**
> Versi: 1.0 | Dibuat: 31 Agustus 2026

---

## Filosofi Dasar

> "Jangan mengejar sebanyak mungkin halaman. Kejar sebanyak mungkin **kebutuhan pembeli** yang bisa dijawab IndoRoster dengan informasi nyata, produk nyata, kemampuan nyata, dan proses pembelian yang jelas."

Setiap halaman harus membuat pengguna berpikir:

> **"Saya menemukan supplier yang memahami kebutuhan saya."**

Bukan:

> ~~"Saya menemukan halaman SEO yang dibuat untuk keyword saya."~~

---

## Mengapa 100+ Halaman Bisa Dibenarkan?

100+ halaman **bukan** berarti 100 variasi kata-kata yang sama.

100+ halaman berarti **100 alasan berbeda** seseorang membuka Google:

| Orang | Query | Kebutuhan |
|---|---|---|
| Kontraktor Andi, proyek perumahan Bekasi | `supplier roster beton grosir` | Volume + deliveri terjadwal |
| Developer PT Griya, cluster Bogor | `roster beton untuk proyek perumahan` | Konsistensi motif multi-unit |
| Procurement Bu Sari, gedung kantor Jakarta | `vendor roster beton proyek` | Dokumen formal + quotation |
| Arsitek Budi, fasad gedung Bandung | `roster beton untuk fasad gedung` | Spesifikasi teknis + estetika |
| Pemborong Pak Heri, renovasi rumah | `jual roster beton ventilasi` | Harga + stok + minimal order |

Lima orang ini punya kebutuhan yang **berbeda secara substantif**. Mereka butuh halaman yang berbeda.

---

## Peta Sistem (High-Level Architecture)

```
                           GOOGLE SEARCH
                                │
         ┌──────────────────────┼──────────────────────┐
         │                      │                      │
    PRODUK INTENT         BUYER INTENT          PROJECT INTENT
         │                      │                      │
    roster minimalis       kontraktor             perumahan
    roster ventilasi       developer              gedung
    roster dekoratif       pemborong              komersial
    roster fasad           arsitek                renovasi
    harga roster           procurement            fasad
         │                      │                      │
         └──────────────────────┼──────────────────────┘
                                │
                         LOKASI INTENT
                                │
                  Jakarta / Bekasi / Bogor / Bandung
                  Depok / Tangerang / Cianjur / dst.
                                │
                                ▼
                    INDOROSTER PAGE ECOSYSTEM
                                │
                   ┌────────────┼────────────┐
                   │            │            │
               PRODUK      QUOTATION      KONTAK
               KATALOG      (WA)          SALES
```

---

## Daftar File Planning

| File | Isi | Status |
|---|---|---|
| [`00-master-plan.md`](./00-master-plan.md) | Dokumen ini — induk semua planning | ✅ |
| [`01-keyword-universe.md`](./01-keyword-universe.md) | 200+ keywords dalam 13 cluster | ✅ |
| [`02-buyer-personas.md`](./02-buyer-personas.md) | 5 buyer persona detail + search behavior | ✅ |
| [`03-page-matrix-batch1.md`](./03-page-matrix-batch1.md) | 21 halaman Batch 1 + business rules | ✅ |
| [`04-content-framework.md`](./04-content-framework.md) | Template konten per page type | ✅ |
| [`05-internal-linking.md`](./05-internal-linking.md) | Arsitektur internal linking | ✅ |
| [`06-location-strategy.md`](./06-location-strategy.md) | Aturan location pages | ✅ |
| [`07-quality-gate.md`](./07-quality-gate.md) | 12 kriteria + threshold publish | ✅ |
| [`08-workflow.md`](./08-workflow.md) | 28-step page generation workflow | ✅ |
| [`09-batch-plan.md`](./09-batch-plan.md) | Batch 1-5 roadmap + 103 halaman | ✅ |
| [`10-page-matrix-100.md`](./10-page-matrix-100.md) | Master matrix 103 halaman | ✅ |

---

## Batasan Data yang Sudah Dikonfirmasi (31 Agustus 2026)

| Data | Nilai | Catatan |
|---|---|---|
| Kapasitas produksi | **10.000 pcs/bulan** | VERIFIED |
| MOQ Retail | **1.000 pcs** | VERIFIED — semua motif |
| MOQ Grosir | **5.000 pcs** | VERIFIED — semua motif |
| Harga per pcs | ❌ TIDAK DIPUBLIKASIKAN | Arahkan ke quotation |
| Waktu pengiriman | ❌ BELUM TERVERIFIKASI | Gunakan narasi aman |
| Biaya pengiriman | ❌ BELUM TERVERIFIKASI | Gunakan narasi aman |
| Stok produk | ❓ Per request | Jangan klaim "stok tersedia" |

> **Prinsip ketat**: Semakin kuat klaim, semakin kuat evidence yang dibutuhkan. Jangan mengarang.

---

## Target Batch

| Batch | Jumlah | Fokus | Trigger |
|---|---|---|---|
| Batch 1 | 25 halaman | Pillar + Buyer + Project + Use Case | Sekarang |
| Batch 2 | 20 halaman | Location Jabodetabek + Hybrid | Setelah data GSC 60 hari |
| Batch 3 | 20 halaman | Location Jabar + Buyer+Project hybrid | Setelah data GSC 120 hari |
| Batch 4 | 20 halaman | Long-tail + Informational | Berdasarkan data Ads |
| Batch 5 | 18 halaman | Procurement + Product deep-dive | Berdasarkan kebutuhan sales |
| **Total** | **103 halaman** | | |

---

## Prinsip Anti-Spam (Wajib Dibaca)

### ❌ Yang Dilarang

1. **Doorway Abuse**: Membuat halaman kota yang isinya sama, hanya nama kota diganti
2. **Scaled Content Abuse**: Halaman AI massal dengan variasi kecil, nilai pengguna minimal
3. **Keyword Stuffing**: Memasukkan blok kota `Jakarta Bekasi Bandung Bogor...` di footer
4. **Fake Evidence**: Klaim harga, testimoni, case study, kapasitas yang tidak terverifikasi
5. **Auto-publish massal**: Publish langsung tanpa quality gate

### ✅ Yang Diperbolehkan

1. **Purpose-driven pages**: Setiap halaman punya alasan eksistensi yang jelas
2. **Buyer-specific content**: Konten berbeda karena kebutuhan pembelinya berbeda
3. **Project-specific content**: Konten berbeda karena konteks proyeknya berbeda
4. **Location pages dengan nilai lokal nyata**: Hanya jika ada informasi substantif
5. **Evidence-first**: Semua klaim didukung data yang terverifikasi

---

## Unique Page Purpose Checklist

Sebelum membuat halaman baru, jawab pertanyaan ini:

- [ ] Siapa yang akan mencari halaman ini?
- [ ] Apa pertanyaan spesifik yang mereka punya?
- [ ] Apa yang membuat halaman ini berbeda dari halaman lain yang sudah ada?
- [ ] Informasi apa yang HANYA ada di halaman ini?
- [ ] Produk apa yang relevan secara spesifik?
- [ ] CTA apa yang paling sesuai dengan kebutuhan mereka?
- [ ] Apakah halaman ini berguna meski Google tidak ada?

Jika tidak bisa menjawab semua dengan jelas → **JANGAN BUAT**.

---

## Cara Menggunakan Sistem Ini

### Admin Workflow

1. Buka `/admin` → **SEO Keywords** → cari keyword dari `01-keyword-universe.md`
2. Pastikan keyword belum memiliki target page (kolom `target_page_id`)
3. Buka `/admin` → **SEO Page Factory** → **Buat Baru**
4. Isi form 7 tab dengan mengacu ke brief di `10-page-matrix-100.md`
5. Klik **Hitung Quality Score** — pastikan ≥ 60
6. Klik **Cek Duplikasi** — pastikan UNIQUE
7. Ubah status ke `human_review`
8. Owner review → jika approve → **Publish**

### Quality Gate (Minimum Publish)

```
Quality Score ≥ 60
DAN
Search Intent Match ≥ 4/5
DAN
Buyer Relevance ≥ 4/5
DAN
Unique Information ≥ 4/5
DAN
Factual Accuracy ≥ 4/5
DAN
Conversion Clarity ≥ 4/5
```

---

## Ekosistem Halaman Final (103 Halaman)

```
PILLAR (5)
├── supplier-roster-beton
├── pabrik-roster-beton
├── roster-beton-untuk-bangunan
├── pengadaan-roster-beton
└── grosir-roster-beton

BUYER (8)
├── supplier-roster-untuk-kontraktor
├── supplier-roster-untuk-developer
├── supplier-roster-untuk-pemborong
├── roster-beton-untuk-arsitek
├── vendor-roster-beton-procurement
├── supplier-roster-untuk-owner-proyek
├── roster-beton-untuk-pengusaha-properti
└── material-roster-untuk-toko-bangunan

PROJECT (10)
├── roster-beton-proyek-perumahan
├── roster-beton-proyek-gedung
├── roster-beton-proyek-komersial
├── roster-beton-proyek-renovasi
├── roster-beton-untuk-cluster-perumahan
├── roster-beton-untuk-apartemen
├── roster-beton-untuk-hotel
├── roster-beton-untuk-restoran-cafe
├── roster-beton-untuk-sekolah
└── roster-beton-untuk-tempat-ibadah

USE CASE (12)
├── roster-beton-untuk-fasad
├── roster-beton-untuk-ventilasi
├── roster-beton-untuk-pagar
├── roster-beton-untuk-carport
├── roster-beton-untuk-dinding-dekoratif
├── roster-beton-untuk-dinding-eksterior
├── roster-beton-untuk-taman
├── roster-beton-untuk-kolam-renang
├── roster-beton-untuk-area-servis
├── roster-beton-untuk-balkon
├── roster-beton-untuk-foyer
└── roster-beton-untuk-lobby

PRODUCT (8)
├── roster-beton-minimalis
├── roster-beton-dekoratif
├── roster-beton-modern
├── roster-beton-geometris
├── roster-beton-motif-bunga
├── roster-beton-ukuran-20x20
├── roster-beton-tebal-8cm
└── roster-beton-putih

VOLUME/GROSIR (6)
├── grosir-roster-beton-minimalis
├── roster-beton-volume-besar
├── roster-beton-untuk-proyek-besar
├── harga-roster-beton-proyek
├── harga-roster-beton-grosir
└── harga-roster-beton

BUYER+PROJECT HYBRID (10)
├── supplier-roster-kontraktor-proyek-perumahan
├── supplier-roster-developer-cluster
├── roster-beton-kontraktor-gedung
├── roster-beton-pemborong-renovasi
├── pengadaan-roster-proyek-developer
├── roster-beton-arsitek-fasad
├── roster-untuk-kontraktor-bangunan-komersial
├── vendor-roster-proyek-infrastruktur
├── supplier-roster-untuk-pemilik-proyek
└── roster-beton-untuk-kontraktor-renovasi

LOCATION JABODETABEK (10)
├── supplier-roster-beton-jakarta
├── supplier-roster-beton-bekasi
├── supplier-roster-beton-bogor
├── supplier-roster-beton-depok
├── supplier-roster-beton-tangerang
├── supplier-roster-beton-jakarta-selatan
├── supplier-roster-beton-jakarta-barat
├── supplier-roster-beton-jakarta-timur
├── supplier-roster-beton-jakarta-utara
└── supplier-roster-beton-tangerang-selatan

LOCATION JABAR (8)
├── supplier-roster-beton-bandung
├── supplier-roster-beton-cianjur
├── supplier-roster-beton-sukabumi
├── supplier-roster-beton-karawang
├── supplier-roster-beton-cirebon
├── supplier-roster-beton-purwakarta
├── supplier-roster-beton-subang
└── supplier-roster-beton-cimahi

LOCATION+BUYER HYBRID (8)
├── supplier-roster-kontraktor-jakarta
├── supplier-roster-developer-bekasi
├── supplier-roster-proyek-perumahan-bekasi
├── supplier-roster-proyek-perumahan-bogor
├── roster-beton-proyek-perumahan-bandung
├── grosir-roster-beton-jakarta
├── vendor-roster-pengadaan-jakarta
└── roster-beton-fasad-bandung

INFORMATIONAL (8)
├── cara-menghitung-kebutuhan-roster-beton
├── panduan-memilih-roster-beton
├── spesifikasi-teknis-roster-beton
├── perbedaan-roster-beton-dan-bata-biasa
├── cara-memasang-roster-beton
├── roster-beton-untuk-rumah-tropis
├── tips-pemilihan-motif-roster-beton
└── roster-beton-untuk-desain-minimalis

PROCUREMENT (5)
├── quotation-roster-beton
├── request-penawaran-roster-beton
├── profil-vendor-roster-beton
├── dokumen-pengadaan-roster-beton
└── roster-beton-untuk-tender-proyek

LONG-TAIL HYBRID (5)
├── supplier-roster-beton-proyek-perumahan-bekasi
├── roster-beton-fasad-gedung-komersial
├── grosir-roster-beton-untuk-developer-perumahan
├── supplier-roster-beton-untuk-proyek-besar-jakarta
└── roster-beton-minimalis-untuk-fasad-perumahan

TOTAL: 103 HALAMAN
```

---

## Referensi Sistem

- **Infrastruktur**: Lihat `app/Models/SeoPage.php`, `app/Services/SeoQualityScorer.php`
- **Admin**: `/admin/seo-page-factories`, `/admin/seo-keywords`
- **Config business rules**: `config/indoroster_business.php`
- **Template**: `resources/views/livewire/seo/seo-page-detail.blade.php`
- **Route**: `/{slug}` → `SeoPageDetailFallback`
