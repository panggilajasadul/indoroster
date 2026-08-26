# SEO Growth Engine Implementation Log

Dokumen ini mencatat detail teknis dari implementasi **SEO Growth Engine** pada IndoRoster.com.

---

## 1. Perubahan Teknis Laravel (Phase 1A & 1B)

### 1.1 Optimasi Meta Tag & Canonical URL
- Mengubah canonical URL di [app.blade.php](file:///c:/xampp/htdocs/indoroster/resources/views/components/layouts/app.blade.php) agar otomatis memotong (strip) query parameters berbahaya seperti `?variant`, `?search`, dan `?sortBy` yang memicu isu duplikasi konten.
- Menambahkan rute clean URL `/katalog/{categorySlug}` pada [web.php](file:///c:/xampp/htdocs/indoroster/routes/web.php) sebagai pengganti `/katalog?category={slug}`.
- Memperbarui [SitemapController.php](file:///c:/xampp/htdocs/indoroster/app/Http/Controllers/SitemapController.php) agar menggunakan rute katalog bersih `/katalog/{categorySlug}` daripada parameter query.
- Menambahkan parameter `keywords`, `ogTitle`, `ogDescription`, dan `canonicalOverride` di file tata letak global untuk kustomisasi per-produk dan per-kategori.

### 1.2 Skema Google Shopping & Google Images
- Memperbarui skema JSON-LD [product-schema.blade.php](file:///c:/xampp/htdocs/indoroster/resources/views/components/product-schema.blade.php):
  - Mengubah properti `image` menjadi array dari seluruh gambar produk untuk memenuhi standar Google Images dan Google Merchant Center.
  - Menambahkan properti `mpn` (Manufacturer Part Number) setara dengan SKU produk.
  - Menambahkan detail pengiriman otomatis (`shippingDetails`) dan masa aktif penawaran (`priceValidUntil`).
  - Menambahkan properti spesifikasi teknis (`additionalProperty`) seperti Material, Dimensi, dan Berat.
- Menambahkan structured data `ImageObject` pada galeri proyek [gallery.blade.php](file:///c:/xampp/htdocs/indoroster/resources/views/livewire/gallery.blade.php) untuk menghubungkan gambar proyek dengan produk yang dijual agar dapat terindeks dengan baik di Google Images.
- Menambahkan structured data `VideoObject` pada halaman video inspirasi [video-inspiration.blade.php](file:///c:/xampp/htdocs/indoroster/resources/views/livewire/video-inspiration.blade.php) agar konten video terdeteksi oleh bot perayap video Google.

### 1.3 Database Migration & Form Model
- Menambahkan 3 file migrasi database baru:
  - Kolom SEO produk pada tabel `products` (focus_keyword, secondary_keywords, seo_h1, og_title, og_description, seo_score, opportunity_score, seo_issues, seo_last_analyzed).
  - Kolom `alt_text` pada tabel `gallery_media` untuk SEO Gambar galeri.
  - Kolom `meta_title` & `meta_description` pada tabel `categories` untuk landing page katalog kategori.
- Memperbarui `$fillable` dan `$casts` pada model Eloquent: [Product.php](file:///c:/xampp/htdocs/indoroster/app/Models/Product.php), [Category.php](file:///c:/xampp/htdocs/indoroster/app/Models/Category.php), dan [GalleryMedia.php](file:///c:/xampp/htdocs/indoroster/app/Models/GalleryMedia.php).
- Memperluas antarmuka admin Filament:
  - Menambahkan isian lengkap pada form edit produk [ProductResource.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/ProductResource.php), termasuk display badge SEO Score dan daftar isu SEO.
  - Menambahkan tab pengisian SEO Kategori pada [CategoryResource.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/CategoryResource.php).
  - Memisahkan field `caption` dan `alt_text` pada media galeri di [GalleryResource.php](file:///c:/xampp/htdocs/indoroster/app/Filament/Resources/GalleryResource.php).

---

## 2. API Integrasi & CLI Engine (Phase 2 & 3)

### 2.1 Rute REST API Terproteksi
- Menambahkan endpoint REST API pada [api.php](file:///c:/xampp/htdocs/indoroster/routes/api.php):
  - `GET /api/seo/products/{id}/data` untuk mengambil data produk lengkap.
  - `POST /api/seo/products/{id}/save-results` untuk menyimpan hasil analisis dan update batch Alt Text gambar.
  - `POST /api/seo/images/save-alts` untuk batch update Alt Text manual.
- Mengamankan endpoint tersebut menggunakan Middleware [SeoApiToken.php](file:///c:/xampp/htdocs/indoroster/app/Http/Middleware/SeoApiToken.php) yang memeriksa header `X-SEO-Token` dengan token di `.env`.

### 2.2 CLI Python SEO Engine
- Engine terpisah ditulis di folder `seo-engine/` dengan struktur:
  - `config.py`: Konfigurasi token, weights scoring, sinonim, target lokasi, dan proteksi perayap (SSRF private network blocklist).
  - `crawlers/image_checker.py`: Pengecekan status HTTP URL gambar dengan validasi keamanan.
  - `generators/title_generator.py` & `description_generator.py`: Pembuat Meta Title & Meta Description teroptimasi SEO berbasis spesifikasi produk dan focus keyword.
  - `generators/alt_text_generator.py`: Pembuat Alt Text unik dan variatif secara otomatis berdasarkan index gambar.
  - `scorers/seo_scorer.py`: Menghitung SEO Health Score (0-100) dan Opportunity Score.
  - `api/client.py`: API Client untuk berkomunikasi dengan REST API Laravel.
  - `main.py`: Command Line Interface berbasis Click dan Rich.

---

## 3. GitHub Actions Workflows (Phase 4)

- `.github/workflows/generate_product_seo.yml` disiapkan untuk memicu jalannya perayap dan kalkulasi Python SEO secara gratis melalui GitHub Actions Runner dan mem-push hasilnya langsung ke server shared hosting.

---

*Proses audit dan penulisan kode awal selesai dilakukan.*
