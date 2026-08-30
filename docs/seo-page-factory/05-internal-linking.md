# Internal Linking Architecture IndoRoster

> Strategi distribusi PageRank/Link Juice dan navigasi terstruktur nasional.
> Menghubungkan halaman Pillar $\to$ Cluster $\to$ Product $\to$ Location secara natural tanpa manipulasi.

---

## 1. Topologi Internal Linking (Silo Structure)

```
                            [ HOMEPAGE ]
                                  │
      ┌───────────────────────────┼───────────────────────────┐
      ▼                           ▼                           ▼
[ PILLAR PAGES ]            [ KATALOG ]              [ TOOLS / KALKULATOR ]
(Supplier / Pabrik / Grosir) (Semua Motif)          (/kalkulator-roster)
      │                           ▲                           ▲
      ├───────────────┐           │                           │
      ▼               ▼           │                           │
[ BUYER PAGES ] [ PROJECT PAGES ] ├───────────────────────────┤
(Kontraktor)    (Perumahan)       │                           │
(Developer)     (Gedung)          │                           │
      │               │           │                           │
      ├───────────────┘           │                           │
      ▼                           ▼                           │
[ USE CASE PAGES ] ─────────► [ DETAIL PRODUK ] ──────────────┘
(Fasad / Ventilasi)          (/produk/{slug})
      │
      ▼
[ LOCATION PAGES ]
(Jakarta / Bekasi / Bandung)
```

---

## 2. Aturan Relasi Antar Halaman (Link Rules)

### A. Dari Halaman Pillar ke Bawah:
- **Setiap Halaman Pillar** wajib memiliki link ke:
  - 3-4 Halaman Buyer Spesifik (`/supplier-roster-untuk-kontraktor`, `/roster-beton-untuk-developer-perumahan`)
  - 3-4 Halaman Proyek Spesifik (`/roster-beton-proyek-perumahan`)
  - Link langsung ke Katalog & Kalkulator Kebutuhan.

### B. Dari Halaman Buyer / Proyek ke Produk:
- **Setiap Halaman Buyer/Proyek** wajib menampilkan widget produk terkurasi (4-8 item) yang langsung mengarah ke `/produk/{slug}`.
- Wajib memiliki link kembali ke Parent Pillar Page via Breadcrumb.

### C. Dari Halaman Use Case ke Kalkulator & Produk:
- Halaman Fasad & Ventilasi wajib memberikan callout link ke `/kalkulator-roster` untuk mempermudah perhitungan luas dinding.
- Memberikan link kontekstual ke jenis motif tertentu di katalog.

### D. Dari Halaman Lokasi ke Halaman Proyek & Hub:
- Halaman Lokasi (`/supplier-roster-beton-bekasi`) wajib memberikan link ke:
  - Halaman Proyek Perumahan (`/roster-beton-proyek-perumahan`)
  - Halaman Grosir (`/grosir-roster-beton`)
  - Halaman Hub Lokasi Nasional (`/lokasi`)

---

## 3. Anchor Text Best Practices

| Jenis Link | Anchor Text yang Dianjurkan | ❌ Anchor Text Terlarang |
|---|---|---|
| Menuju Pillar Supplier | "supplier roster beton proyek", "pabrik roster beton IndoRoster" | "klik di sini", "supplier roster beton supplier roster beton" |
| Menuju Halaman Buyer | "kebutuhan roster untuk rekan kontraktor", "pengadaan material perumahan" | "link 1", "kontraktor kontraktor" |
| Menuju Use Case | "roster beton untuk aplikasi fasad", "ventilasi sirkulasi udara" | "baca ini", "halaman fasad" |
| Menuju Produk | "[Nama Motif] ukuran 20x20", "katalog roster minimalis modern" | "beli sekarang murah", "produk roster terbaik termurah" |
| Menuju Kalkulator | "hitung estimasi kebutuhan dinding roster", "kalkulator kebutuhan roster" | "alat hitung", "klik kalkulator" |

---

## 4. Cross-Linking Antar Halaman Selevel (Sibling Linking)

Untuk mencegah halaman menjadi "dead-end" (halaman buntu), bagian bawah setiap halaman SEO wajib memuat bagian **Halaman Terkait (Related Pages)** yang berisi 3 kartu halaman dengan cluster relevan:

- Halaman Kontraktor $\to$ Rekomendasi: *Proyek Perumahan*, *Grosir Roster*, *Fasad Bangunan*.
- Halaman Fasad $\to$ Rekomendasi: *Roster Minimalis*, *Fasad Gedung Komersial*, *Kalkulator Roster*.
- Halaman Bekasi $\to$ Rekomendasi: *Supplier Jakarta*, *Proyek Perumahan Bekasi*, *Grosir Roster*.
