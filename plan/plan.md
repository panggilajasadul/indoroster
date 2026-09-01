# PRD — IndoRoster SEO Topical Authority & 1.000 Page Architecture

## 1. Tujuan

Membangun arsitektur SEO IndoRoster.com yang mampu mengembangkan hingga ±1.000 halaman berkualitas yang **saling memperkuat**, bukan halaman yang berdiri sendiri.

Sistem harus membuat Google memahami IndoRoster sebagai:

> **Produsen dan supplier roster yang melayani kebutuhan retail, renovasi, arsitektur, kontraktor, developer, proyek besar, dan pengiriman ke berbagai wilayah Indonesia.**

Target utama:

1. Memperkuat short keyword:

   * roster
   * roster beton
   * roster minimalis
   * roster beton minimalis

2. Menangkap commercial keyword:

   * jual roster
   * beli roster
   * harga roster
   * supplier roster
   * produsen roster
   * katalog roster

3. Menangkap long-tail:

   * roster untuk pagar
   * roster untuk teras
   * roster untuk rumah
   * roster untuk cafe
   * roster untuk renovasi
   * dan variasinya

4. Menangkap B2B/project intent:

   * supplier roster proyek
   * roster jumlah besar
   * supplier roster kontraktor
   * supplier roster developer
   * roster untuk proyek

5. Menangkap local intent:

   * roster Jakarta
   * roster Bandung
   * roster Bekasi
   * roster Tangerang
   * jual roster Bandung
   * supplier roster Bekasi
   * beli roster terdekat
   * roster minimalis terdekat

6. Memaksimalkan katalog produk dan halaman detail produk yang saat ini sudah tersedia.

---

# 2. Prinsip Utama

JANGAN membuat 1.000 halaman hanya karena targetnya 1.000 halaman.

Setiap halaman wajib mempunyai:

* Search intent
* Primary keyword
* Secondary keywords
* Unique value
* Parent page
* Child/related pages
* Internal links
* Produk relevan
* CTA
* Schema yang sesuai
* Canonical
* Meta title
* Meta description
* H1
* Konten unik
* Status indexability

Tidak boleh membuat halaman yang hanya mengganti nama kota/keyword tetapi isi hampir sama.

---

# 3. Arsitektur Utama

Gunakan struktur:

```text
HOME
│
├── SHORT KEYWORD / PILLAR
│   ├── /roster/
│   ├── /roster-beton/
│   ├── /roster-minimalis/
│   └── /roster-beton-minimalis/
│
├── KATALOG
│   ├── /katalog/
│   ├── /katalog/roster/
│   ├── /katalog/roster-beton/
│   ├── /katalog/roster-minimalis/
│   ├── /katalog/berdasarkan-ukuran/
│   ├── /katalog/berdasarkan-motif/
│   └── ...
│
├── PRODUK
│   ├── /produk/ir-001/
│   ├── /produk/ir-002/
│   ├── ...
│   └── /produk/ir-060/
│
├── USE CASE
│   ├── /kebutuhan/rumah/
│   ├── /kebutuhan/pagar/
│   ├── /kebutuhan/teras/
│   ├── /kebutuhan/carport/
│   ├── /kebutuhan/balkon/
│   ├── /kebutuhan/sekat/
│   ├── /kebutuhan/cafe/
│   ├── /kebutuhan/masjid/
│   └── ...
│
├── PROJECT / B2B
│   ├── /proyek/
│   ├── /proyek/kontraktor/
│   ├── /proyek/developer/
│   ├── /proyek/arsitek/
│   ├── /proyek/toko-material/
│   └── /proyek/jumlah-besar/
│
├── LOCAL
│   ├── /lokasi/jakarta/
│   ├── /lokasi/bandung/
│   ├── /lokasi/bekasi/
│   ├── /lokasi/tangerang/
│   ├── /lokasi/bogor/
│   └── ...
│
└── BLOG / GUIDE
    ├── /blog/
    ├── /panduan/
    ├── /inspirasi/
    └── ...
```

---

# 4. Hierarki SEO

Gunakan model:

```text
                    HOME
                      │
                      ↓
                ROSTER
                      │
        ┌─────────────┼──────────────┐
        ↓             ↓              ↓
  ROSTER BETON   ROSTER MINIMALIS   KATALOG
        │             │              │
        └─────────────┼──────────────┘
                      ↓
                  PRODUK
                      │
        ┌─────────────┼──────────────┐
        ↓             ↓              ↓
     USE CASE       PROJECT         LOCAL
        │             │              │
        └─────────────┼──────────────┘
                      ↓
                  TRANSAKSI
```

Semua cluster harus mempunyai jalur internal menuju halaman komersial dan produk.

---

# 5. Page Type

Sistem harus mendukung minimal 8 tipe halaman.

## TYPE A — Pillar

Contoh:

```text
/roster/
/roster-beton/
/roster-minimalis/
/roster-beton-minimalis/
```

Tujuan:

Menargetkan short keyword.

Karakteristik:

* Konten komprehensif
* Banyak internal link
* Link menuju kategori
* Link menuju produk
* Link menuju use case
* Link menuju lokasi
* Link menuju project
* CTA utama

---

## TYPE B — Category

Contoh:

```text
/katalog/roster-beton/
/katalog/roster-minimalis/
/katalog/roster-20x20/
/katalog/motif-geometris/
```

Menampilkan produk yang relevan.

Wajib:

* Product grid
* Filter
* Breadcrumb
* Deskripsi kategori
* FAQ
* Internal link
* Product schema jika sesuai
* ItemList schema bila sesuai

---

## TYPE C — Product

Contoh:

```text
/produk/ir-001/
/produk/ir-002/
```

Halaman produk wajib SEO-friendly.

Minimal:

* Nama produk
* SKU
* Foto
* Material
* Ukuran
* Berat
* Motif
* Harga jika tersedia
* MOQ jika relevan
* Ketersediaan
* Deskripsi unik
* Penggunaan
* Produk terkait
* Kategori
* FAQ
* CTA
* Breadcrumb
* Product structured data

Jangan membuat halaman produk hanya berisi nama + gambar.

---

# 6. TYPE D — USE CASE

Contoh:

```text
/kebutuhan/roster-untuk-pagar/
/kebutuhan/roster-untuk-teras/
/kebutuhan/roster-untuk-rumah/
/kebutuhan/roster-untuk-cafe/
```

Setiap halaman menjawab:

* Kebutuhan pengguna
* Masalah yang ingin diselesaikan
* Mengapa roster cocok
* Material yang tersedia
* Motif yang cocok
* Ukuran yang relevan
* Contoh aplikasi
* Produk terkait
* Estimasi kebutuhan jika dapat dihitung
* CTA

---

# 7. TYPE E — PROJECT / B2B

Contoh:

```text
/proyek/
/proyek/kontraktor/
/proyek/developer/
/proyek/arsitek/
/proyek/jumlah-besar/
```

Target:

* Kontraktor
* Developer
* Arsitek
* Procurement
* Toko material
* Reseller
* Pemilik proyek

Isi:

* Kapasitas produksi
* Pemesanan volume besar
* MOQ
* Quotation
* PO
* Dokumen
* Pengiriman
* Lead time
* Prosedur pemesanan
* Konsultasi kebutuhan
* CTA quotation

---

# 8. TYPE F — LOCAL

Contoh:

```text
/lokasi/jakarta/
/lokasi/bandung/
/lokasi/bekasi/
/lokasi/tangerang/
/lokasi/bogor/
```

Target keyword:

```text
roster Jakarta
jual roster Jakarta
beli roster Jakarta
roster beton Jakarta
roster minimalis Jakarta
supplier roster Jakarta
harga roster Jakarta
katalog roster Jakarta
```

Setiap halaman lokal wajib memiliki konten yang benar-benar relevan dengan lokasi.

Wajib menjelaskan:

* IndoRoster sebagai produsen/supplier
* Lokasi produksi
* Pengiriman ke kota tersebut
* Cara pemesanan
* Produk yang relevan
* Kebutuhan lokal
* Proyek
* Area layanan bila relevan
* Estimasi pengiriman jika data tersedia
* CTA

JANGAN mengklaim memiliki toko/cabang di kota tersebut jika tidak benar.

Keyword "terdekat" harus diperlakukan sebagai search intent, bukan alasan membuat halaman palsu.

---

# 9. TYPE G — BLOG / GUIDE

Blog berfungsi sebagai supporting authority.

Contoh:

```text
/blog/harga-roster-beton/
/blog/cara-memilih-roster/
/blog/cara-menghitung-kebutuhan-roster/
/blog/roster-untuk-rumah-minimalis/
/blog/roster-vs-bata/
```

Artikel wajib mempunyai tujuan internal linking.

Setiap artikel harus mengarah ke:

```text
Artikel
   ↓
Pillar
   ↓
Kategori
   ↓
Produk
```

Blog tidak boleh menjadi silo terpisah.

---

# 10. TYPE H — LANDING PAGE KOMBINASI

Gunakan secara selektif.

Contoh:

```text
roster beton + kota
roster minimalis + kota
roster untuk kebutuhan + kota
```

Contoh:

```text
/lokasi/bekasi/roster-minimalis/
/lokasi/bandung/roster-beton/
/lokasi/jakarta/roster-untuk-proyek/
```

Hanya dibuat apabila kombinasi tersebut memiliki:

1. Search intent yang masuk akal
2. Nilai komersial
3. Konten unik
4. Produk relevan
5. Informasi lokal yang cukup
6. Tidak menjadi duplicate doorway page

---

# 11. Keyword Matrix

Buat database keyword dengan struktur:

```text
keyword
search_intent
page_type
primary_url
parent_url
cluster
location
product_ids
priority
status
indexable
canonical_url
```

Contoh:

```text
roster beton minimalis
commercial
pillar
/roster-beton-minimalis/
roster
-
high
published
yes
self
```

Contoh lokal:

```text
roster minimalis Bekasi
local-commercial
local
/lokasi/bekasi/
roster-minimalis
Bekasi
high
published
yes
self
```

---

# 12. Internal Linking Engine

Ini adalah bagian TERPENTING.

Setiap halaman harus otomatis mempunyai:

### Parent Link

Menuju halaman induk.

### Sibling Links

Menuju halaman sejenis.

### Child Links

Menuju halaman turunan.

### Product Links

Menuju produk relevan.

### Location Links

Menuju lokasi relevan.

### Use Case Links

Menuju kebutuhan relevan.

### Commercial Links

Menuju halaman pembelian/supplier.

---

# 13. Aturan Internal Linking

Contoh:

```text
Artikel
 ↓
Roster Beton
 ↓
Kategori Roster Beton
 ↓
Produk
```

Use case:

```text
Roster untuk Pagar
 ↓
Roster Beton
 ↓
Katalog Roster
 ↓
Produk IR-001
```

Local:

```text
Roster Bekasi
 ↓
Roster Minimalis
 ↓
Katalog
 ↓
Produk
 ↓
Cara Pesan
```

B2B:

```text
Supplier Roster Proyek
 ↓
Roster Beton
 ↓
Katalog
 ↓
Produk
 ↓
Quotation
```

---

# 14. Link Authority Direction

Prioritaskan aliran authority menuju halaman:

```text
HOME
 ↓
PILLAR
 ↓
CATEGORY
 ↓
PRODUCT
 ↓
CONVERSION
```

Namun link juga harus dua arah secara natural:

```text
PRODUCT
 ↓
CATEGORY
 ↓
PILLAR
```

Tujuannya agar halaman produk yang mendapatkan traffic dari Google juga memperkuat cluster induknya.

---

# 15. Jangan Membuat Semua Halaman Sama Kuat

Gunakan hierarchy:

```text
TIER 1
5–10 halaman
★★★★★
```

Short keyword / pillar.

```text
TIER 2
30–80 halaman
★★★★
```

Category, commercial, B2B, major use case.

```text
TIER 3
100–300 halaman
★★★
```

Produk, long-tail, lokal utama.

```text
TIER 4
300–600 halaman
★★
```

Supporting content dan kombinasi yang benar-benar bernilai.

Tidak semua halaman harus mendapat backlink eksternal.

---

# 16. Target 1.000 Halaman

Contoh distribusi:

```text
Pillar / Short Keyword             10
Category / Commercial              50
Product                            60
Use Case                           100
Project / B2B                      50
Local                              250
Local + Use Case                   150
Informational / Guide              250
Local + Commercial                 80
──────────────────────────────────────
TOTAL                              1.000
```

Angka tersebut adalah target maksimum, bukan kewajiban.

Jika sebuah cluster tidak mempunyai cukup keyword/intention/value, JANGAN dipaksakan sampai 1.000.

---

# 17. Product Catalog SEO

Katalog existing harus menjadi pusat commerce.

Pastikan:

```text
Homepage
 ↓
Katalog
 ↓
Kategori
 ↓
Produk
```

dan:

```text
Google
 ↓
Produk individual
 ↓
Kategori
 ↓
Katalog
 ↓
Pillar
```

Produk yang saat ini belum terindex harus diaudit.

Periksa:

* Apakah URL dapat di-crawl?
* Apakah halaman menghasilkan 200 status?
* Apakah canonical benar?
* Apakah noindex?
* Apakah robots.txt menghalangi?
* Apakah ada internal link?
* Apakah sitemap memasukkan URL?
* Apakah halaman memiliki konten unik?
* Apakah produk memiliki gambar?
* Apakah structured data valid?

---

# 18. Sitemap Architecture

Jangan menggunakan satu sitemap besar apabila jumlah URL berkembang.

Gunakan:

```text
/sitemap.xml
```

sebagai sitemap index.

Turunan:

```text
/sitemap-products.xml
/sitemap-categories.xml
/sitemap-locations.xml
/sitemap-use-cases.xml
/sitemap-projects.xml
/sitemap-blog.xml
```

Hanya URL canonical dan indexable yang boleh masuk sitemap.

---

# 19. Breadcrumb

Semua halaman harus mempunyai breadcrumb.

Contoh:

```text
Home
>
Roster
>
Roster Beton
>
Roster Beton Minimalis
>
IR-001
```

Lokal:

```text
Home
>
Lokasi
>
Bekasi
>
Roster Minimalis
```

Gunakan BreadcrumbList structured data.

---

# 20. Entity Relationship

Bangun hubungan:

```text
Brand
 ↓
IndoRoster
 ↓
Product
 ↓
Material
 ↓
Motif
 ↓
Size
 ↓
Use Case
 ↓
Location
 ↓
Project Type
```

Contoh:

```text
IR-001
│
├── Material: Concrete
├── Size: 20x20x10 cm
├── Motif: Geometric
├── Category: Roster Beton
├── Style: Minimalis
├── Use Case:
│   ├── Rumah
│   ├── Pagar
│   └── Teras
└── Available for:
    ├── Retail
    └── Project
```

---

# 21. SEO Metadata Generator

Setiap halaman harus mempunyai metadata unik.

Template hanya digunakan sebagai fallback.

Contoh:

```text
Title:
Roster Beton Minimalis | Katalog Motif & Harga | IndoRoster

H1:
Roster Beton Minimalis

Description:
Temukan roster beton minimalis berbagai motif dan ukuran untuk rumah,
pagar, teras, dan kebutuhan proyek. Lihat katalog produk IndoRoster.
```

Untuk lokal:

```text
Title:
Roster Minimalis Bekasi | Katalog & Pengiriman | IndoRoster

H1:
Roster Minimalis Bekasi

Description:
Cari roster minimalis untuk rumah, pagar, teras, atau proyek di Bekasi.
IndoRoster melayani pemesanan dan pengiriman roster dari produsen.
```

---

# 22. SEO Content Rules

AI content generator WAJIB:

* Tidak melakukan keyword stuffing
* Tidak mengulang paragraf antar kota
* Tidak mengganti nama kota secara otomatis
* Tidak membuat fakta pengiriman yang tidak tersedia
* Tidak membuat alamat/cabang palsu
* Tidak membuat testimonial palsu
* Tidak membuat proyek palsu
* Tidak membuat kapasitas palsu
* Tidak membuat harga palsu
* Tidak membuat keyword cannibalization

Konten harus berdasarkan data produk dan data bisnis nyata.

---

# 23. Page Quality Gate

Sebelum halaman dipublish, sistem harus memeriksa:

```text
[ ] Primary keyword
[ ] Search intent
[ ] Unique content
[ ] Unique title
[ ] Unique H1
[ ] Meta description
[ ] Canonical
[ ] Breadcrumb
[ ] Internal links
[ ] Related products
[ ] Related pages
[ ] CTA
[ ] Schema
[ ] Image alt
[ ] Indexable
[ ] Sitemap
[ ] Tidak duplicate
[ ] Tidak doorway
```

Jika gagal quality gate:

```text
DRAFT
```

bukan langsung publish.

---

# 24. Content Status

Gunakan workflow:

```text
KEYWORD DISCOVERY
        ↓
CLUSTERING
        ↓
PAGE PLANNING
        ↓
AI DRAFT
        ↓
QUALITY CHECK
        ↓
HUMAN REVIEW
        ↓
PUBLISH
        ↓
INDEX REQUEST
        ↓
GSC MONITORING
        ↓
OPTIMIZATION
```

---

# 25. Cannibalization Detection

Sistem harus mendeteksi apabila dua halaman memiliki target keyword terlalu mirip.

Contoh:

```text
/roster-beton/
/roster-beton-minimalis/
/katalog/roster-beton/
/blog/roster-beton/
```

Tidak boleh semua menargetkan:

> roster beton

secara identik.

Tetapkan:

```text
/roster-beton/
→ commercial pillar

/katalog/roster-beton/
→ product/category intent

/blog/roster-beton/
→ informational intent
```

Setiap URL mempunyai search intent berbeda.

---

# 26. Local SEO Rules

Untuk lokasi:

```text
Kota
 ↓
Local landing page
 ↓
Produk relevan
 ↓
Use case
 ↓
Pengiriman
 ↓
CTA
```

Contoh:

```text
/lokasi/bekasi/
```

Target:

```text
roster Bekasi
jual roster Bekasi
supplier roster Bekasi
roster beton Bekasi
roster minimalis Bekasi
```

Jangan membuat klaim:

> "Toko roster terdekat di Bekasi"

jika IndoRoster tidak mempunyai toko fisik di Bekasi.

Gunakan positioning:

> "Produsen roster yang melayani pengiriman ke Bekasi."

---

# 27. "Terdekat" Strategy

Keyword seperti:

```text
roster terdekat
beli roster terdekat
toko roster terdekat
roster minimalis terdekat
```

dipandang sebagai **location-sensitive intent**.

Jangan membuat halaman:

```text
/roster-terdekat/
```

yang hanya berisi keyword.

Gunakan:

```text
location + product + transaction intent
```

Contoh:

```text
/lokasi/bekasi/
```

dengan konten:

* beli roster
* katalog
* pengiriman
* produk
* cara pesan

Jika bisnis mempunyai lokasi fisik yang sah, optimalkan Google Business Profile untuk lokasi tersebut.

---

# 28. Conversion Architecture

Setiap halaman komersial harus mempunyai CTA.

Retail:

```text
Lihat Produk
Pesan Sekarang
Tanyakan Harga
```

B2B:

```text
Minta Penawaran Proyek
Konsultasi Kebutuhan
Minta Quotation
```

Local:

```text
Cek Pengiriman ke [Kota]
Lihat Katalog
Tanyakan Harga
```

CTA tidak boleh hanya:

> Hubungi kami.

CTA harus menjelaskan tindakan berikutnya.

---

# 29. Admin SEO Dashboard

Buat dashboard:

```text
Total Pages
Indexed Pages
Not Indexed
Errors
Orphan Pages
Cannibalization
Duplicate
Missing Metadata
Missing Internal Links
Products Not Indexed
Local Pages
Project Pages
Organic Clicks
Organic Impressions
Average Position
CTR
```

Filter:

```text
Page Type
Keyword Cluster
Location
Product
Status
Index Status
```

---

# 30. Orphan Page Detection

Sistem harus mencari halaman yang:

```text
Internal inbound links = 0
```

Halaman orphan harus diperbaiki dengan menambahkan link dari:

* Parent
* Sibling
* Related content
* Category
* Product
* Local page

Tidak boleh ada halaman penting yang berdiri sendiri.

---

# 31. Authority Score Internal

Buat skor internal sederhana:

```text
Authority Score =
Parent Links
+
Relevant Internal Links
+
Product Relevance
+
Cluster Relevance
+
Content Quality
+
Search Intent Match
```

Halaman Tier 1 harus menerima internal link lebih banyak dan lebih relevan.

---

# 32. Prioritas Implementasi

Jangan langsung membuat 1.000 halaman.

## Phase 1 — Foundation

Bangun:

```text
10 Pillar
30 Category
60 Product
20 Use Case
10 B2B
20 Local
```

±150 halaman.

Tujuan:

Membangun struktur dasar.

---

## Phase 2 — Expansion

Tambah:

```text
100 Use Case
100 Local
100 Commercial
100 Informational
```

±400 halaman tambahan.

---

## Phase 3 — Authority

Analisis data Google Search Console.

Cari:

* keyword yang mulai muncul
* query baru
* halaman dengan impression tinggi
* halaman posisi 5–20
* keyword lokal
* keyword produk
* keyword proyek

Kemudian buat halaman berdasarkan **data aktual**, bukan asumsi.

---

## Phase 4 — Scale

Target:

±1.000 halaman.

Namun hanya halaman yang lolos:

```text
Search Intent
+
Unique Value
+
Business Relevance
+
Content Quality
+
Internal Linking
```

yang boleh dibuat.

---

# 33. Google Search Console Loop

Setiap bulan:

```text
GSC
 ↓
Query Discovery
 ↓
Keyword Clustering
 ↓
Find Opportunities
 ↓
Create / Improve Page
 ↓
Internal Link
 ↓
Update Sitemap
 ↓
Monitor
```

Prioritaskan halaman dengan:

```text
Position 4–20
High Impression
Low CTR
High Commercial Intent
```

daripada terus membuat halaman baru.

---

# 34. Acceptance Criteria

Implementasi dianggap berhasil apabila:

### Architecture

* Semua page type mempunyai struktur parent-child.
* Tidak ada halaman penting yang orphan.
* Semua halaman memiliki canonical.
* Breadcrumb tersedia.
* Internal linking otomatis dan relevan.

### Product

* Semua produk yang layak index dapat ditemukan crawler.
* Produk memiliki URL permanen.
* Product schema tersedia.
* Produk terhubung ke kategori.
* Produk terhubung ke pillar.

### Local

* Halaman lokal mempunyai konten unik.
* Tidak ada lokasi palsu.
* Tidak ada klaim toko/cabang palsu.
* Produk relevan muncul di halaman lokal.
* Local page terhubung ke pillar dan katalog.

### SEO

* Tidak ada duplicate title/H1 yang tidak disengaja.
* Tidak ada keyword cannibalization yang signifikan.
* Sitemap hanya berisi canonical indexable URL.
* Robots tidak menghalangi halaman penting.
* Tidak ada orphan page kritis.

### Content

* Tidak ada AI-generated filler.
* Tidak ada keyword stuffing.
* Tidak ada fakta bisnis yang dibuat-buat.
* Setiap halaman mempunyai search intent yang jelas.

---

# 35. Target Akhir

IndoRoster harus berkembang menjadi struktur:

```text
                         INDO ROSTER
                              │
                         ROSTER
                              │
             ┌────────────────┼────────────────┐
             ↓                ↓                ↓
        PRODUCT           USE CASE           LOCAL
             │                │                │
             ↓                ↓                ↓
         KATALOG           PROJECT          KOTA
             │                │                │
             ↓                ↓                ↓
          PRODUK          KONTRAKTOR       AREA
             │                │                │
             └────────────────┼────────────────┘
                              ↓
                       COMMERCIAL INTENT
                              ↓
                       PESAN / QUOTE
```

Tujuan akhirnya bukan:

> **"IndoRoster mempunyai 1.000 halaman."**

Tujuan akhirnya:

> **"IndoRoster mempunyai 1.000 entry point yang berbeda untuk menemukan kebutuhan roster, tetapi semuanya terhubung secara logis dan memperkuat topical authority halaman utama, kategori, katalog, produk, serta halaman transaksi."**

Prioritas authority:

```text
ROSTER
   ↑
ROSTER BETON
   ↑
ROSTER MINIMALIS
   ↑
ROSTER BETON MINIMALIS
   ↑
KATALOG
   ↑
PRODUK
   ↑
USE CASE
   ↑
PROJECT
   ↑
LOCAL
   ↑
BLOG
```

Semua layer tersebut harus saling terhubung melalui internal linking yang relevan.

**Jangan mengejar jumlah halaman. Kejar kualitas cluster dan hubungan antarhalaman.**
