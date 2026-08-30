# Page Generation Workflow (Alur Kerja 28 Tahap)

> Siklus hidup pembuatan halaman dari tahap ideasi hingga pemantauan pasca-publikasi (Google Search Console & Conversions).

---

## 1. Diagram Alur Kerja (Lifecycle Status)

```
[ IDEA ] ──────────► [ RESEARCHED ] ───────► [ CONTENT_BRIEF ]
                           │                        │
                           ▼                        ▼
                   [ TARGET_MAPPED ]            [ DRAFT ]
                                                    │
                                                    ▼
                                            [ AUTOMATED_QA ]
                                            (Scorer & Duplication)
                                                    │
                           ┌────────────────────────┴────────────────────────┐
                           ▼                                                 ▼
                     (Lolos: Score ≥ 60)                            (Gagal: Score < 60)
                           │                                                 │
                           ▼                                                 ▼
                   [ HUMAN_REVIEW ]                                  [ NEEDS_REVIEW ]
                           │                                                 │
                           ▼                                                 └──► (Revisi Draft)
                      [ READY ]
                           │
                           ▼
                    [ PUBLISHED ]
                           │
                           ▼
                    [ MONITORING ] ──► (Evaluasi GSC 60 Hari)
```

---

## 2. Rincian 28 Tahap Operasional

### Fase 1: Riset & Penyelarasan Intent (Tahap 1–7)
1. **Identifikasi Query**: Menemukan search term potensial (dari Google Ads, GSC, atau `01-keyword-universe.md`).
2. **Pengecekan Slug Registry**: Memverifikasi slug belum terpakai di sistem `SeoSlugRegistry`.
3. **Analisis Search Intent**: Mengklasifikasikan apakah query bernilai TOFU, MOFU, atau BOFU.
4. **Penentuan Target Persona**: Menentukan target (Kontraktor, Developer, Pemborong, Arsitek, Procurement, Owner).
5. **Penentuan Project Type**: Menyesuaikan konteks (Perumahan, Gedung, Komersial, Fasad, Ventilasi).
6. **Penentuan Lokasi**: Menentukan apakah bersifat Nasional atau Kota spesifik.
7. **Perumusan Unique Page Purpose (UPP)**: Menjawab: *"Mengapa halaman ini wajib dibuat dan apa nilai uniknya?"*

### Fase 2: Struktur & Penyusunan Konten (Tahap 8–15)
8. **Pengumpulan Bukti/Evidence**: Mengambil spesifikasi teknis, data MOQ terverifikasi, dan kapasitas pabrik.
9. **Pemilihan Rule Produk**: Menentukan filter produk (`featured`, `category:slug`, `best_for:value`).
10. **Penyusunan SEO Title & Meta Description**: Memastikan CTR-friendly dan tidak duplikat.
11. **Penulisan H1 & Opening**: Menjawab langsung masalah pembaca di 2 kalimat pertama.
12. **Penyusunan Sections Block**: Menambahkan section spesifik (UVP, Cara Pesan, FAQ, Produk).
13. **Konfigurasi WhatsApp Message**: Menyusun pesan WhatsApp dinamis sesuai persona pembeli.
14. **Pemasangan Internal Links**: Memilih parent page dan 3 sibling pages terkait.
15. **Penyusunan Skema JSON-LD**: Memasang skema terstruktur (FAQPage / ProductList).

### Fase 3: Quality Gate & Evaluasi Sistem (Tahap 16–21)
16. **Auto Quality Scoring**: Menjalankan fungsi `SeoQualityScorer::calculate()` via Filament Admin.
17. **Cek Kriteria Kritis**: Memastikan 5 kriteria kritis bernilai minimal 4 dari 5.
18. **Duplication Audit**: Memeriksa nilai kemiripan teks dengan halaman lain via `SeoDuplicationChecker`.
19. **Verifikasi Faktual**: Memastikan tidak ada klaim kapasitas $> 10.000\text{ pcs/bln}$ atau harga tanpa dasar.
20. **Audit Mobile & UX**: Meninjau keterbacaan paragraf di perangkat smartphone.
21. **Review Manual oleh Owner/Admin**: Pemeriksaan akhir sebelum status dialihkan ke `ready`.

### Fase 4: Publikasi & Distribusi (Tahap 22–24)
22. **Status Published**: Mengubah status menjadi `published` dan mencatat `published_at`.
23. **Sitemap Generation**: Sistem otomatis menyertakan URL baru ke dalam `sitemap.xml`.
24. **Indexing Request**: Submit URL ke Google Search Console untuk perayapan cepat.

### Fase 5: Evaluasi & Optimasi Berkelanjutan (Tahap 25–28)
25. **Monitoring Impresi (Hari ke-30)**: Memantau apakah kata kunci mulai muncul di GSC.
26. **Audit CTR & Posisi (Hari ke-60)**: Mengevaluasi rasio klik-tayang dan kata kunci baru yang masuk.
27. **Conversion Tracking**: Menghitung jumlah klik WhatsApp dan permintaan penawaran (quotation).
28. **Refinement / Merge**: Mengoptimalkan isi halaman atau menggabungkan halaman jika intent ternyata kanibal.
