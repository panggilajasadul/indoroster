# Quality Gate & Scoring Engine IndoRoster

> Standar evaluasi kualitas 12 kriteria sebelum sebuah halaman SEO diizinkan untuk dipublikasikan (`status = published`).

---

## 1. Ambang Batas Kelayakan Publish (Publish Threshold)

Sebuah halaman **HANYA BISA DIPUBLIKASIKAN** jika memenuhi **KEDUA SYARAT** berikut:

1. **Total Quality Score $\ge 60$ dari 100** (Dihitung otomatis oleh `SeoQualityScorer`).
2. **Semua Kriteria Kritis Bernilai Minimal 4 dari 5**:
   - `search_intent_match` $\ge 4$
   - `buyer_relevance` $\ge 4$
   - `unique_information` $\ge 4$
   - `factual_accuracy` $\ge 4$
   - `conversion_clarity` $\ge 4$

> Jika salah satu kriteria kritis di bawah 4, halaman **DITAHAN** pada status `needs_review` atau `draft`.

---

## 2. Rincian 12 Kriteria Penilaian

| # | Kriteria | Bobot | Kategori Kritis | Indikator Penilaian |
|---|---|---|---|---|
| 1 | **Search Intent Match** | 10% | 🚨 KRITIS | H1 dan paragraf pembuka langsung menjawab maksud pencarian utama (bukan basa-basi). |
| 2 | **Buyer Relevance** | 10% | 🚨 KRITIS | Kosakata, pain point, dan sudut pandang relevan dengan target persona (misal: kontraktor/developer/arsitek). |
| 3 | **Product Relevance** | 8% | Standar | Produk yang ditampilkan cocok dengan konteks halaman (bukan asal menaruh seluruh produk). |
| 4 | **Unique Information (UVP)** | 10% | 🚨 KRITIS | Memuat nilai pembeda yang nyata (misal: cetak padat hidrolik, siku presisi 90°). |
| 5 | **Evidence / Data Pendukung** | 8% | Standar | Mencantumkan data teknis nyata (dimensi, berat, spesifikasi mutu beton K-200). |
| 6 | **Local Relevance** | 6% | Standar | Untuk halaman lokasi: memuat ulasan logistik/tipe proyek lokal yang akurat. |
| 7 | **Commercial Value** | 8% | Standar | Menghubungkan kebutuhan pembaca dengan solusi transaksi pengadaan IndoRoster. |
| 8 | **Conversion Clarity** | 10% | 🚨 KRITIS | Prosedur cara pemesanan / request quotation dijelaskan bertahap dengan tombol CTA yang jelas. |
| 9 | **UX & Readability** | 6% | Standar | Struktur heading H2/H3 rapi, paragraf tidak terlalu panjang, mudah dipindai di HP. |
| 10 | **Internal Linking** | 6% | Standar | Memiliki link ke breadcrumb, parent page, related cluster, atau kalkulator kebutuhan. |
| 11 | **Originality (Anti-Duplication)** | 10% | Standar | Nilai kemiripan teks dengan halaman lain $< 75\%$ (diverifikasi `SeoDuplicationChecker`). |
| 12 | **Factual Accuracy** | 8% | 🚨 KRITIS | Tidak ada data fiktif (kapasitas terverifikasi 10.000 pcs/bln, MOQ 1.000 retail / 5.000 grosir). |

---

## 3. Sistem Duplication Checker (Anti-Content Cloning)

Layanan `SeoDuplicationChecker` menghitung derajat kemiripan teks terhadap seluruh basis data halaman (`seo_pages` dan `pages` CMS):

- **Similarity $< 60\%$** $\to$ `UNIQUE` (Aman untuk lanjut ke review).
- **Similarity $60\% - 75\%$** $\to$ `NEEDS_REVIEW` (Periksa apakah ada paragraf yang terlalu mirip).
- **Similarity $> 75\%$** $\to$ `DUPLICATE` (Ditolak, wajib dirombak atau digabungkan ke halaman induk).
