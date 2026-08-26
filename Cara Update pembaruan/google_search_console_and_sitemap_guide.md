# Panduan Verifikasi Google Search Console & Otomatisasi Sitemap.xml

Dokumen ini berisi rangkuman perbaikan struktur folder di Hostinger, cara verifikasi Search Console, serta cara kerja sistem otomatisasi Sitemap untuk website **Indoroster**.

---

## 1. Rangkuman Struktur Folder di Hostinger

Agar Laravel dapat berjalan dengan benar di Hostinger, folder **`public_html`** telah diatur sebagai tautan pintasan (*symbolic link* / *symlink*) yang mengarah ke folder publik Laravel.

*   **Lokasi Proyek Utama**: `/home/u202379832/domains/indoroster.com/laravel-proyek`
*   **Lokasi Folder Publik Web**: `/home/u202379832/domains/indoroster.com/public_html` (Symlink mengarah ke `laravel-proyek/public`)

> [!IMPORTANT]
> **Jangan mengaktifkan "Deploy Otomatis" dari Git Panel Hostinger** jika direktori root-nya masih mengarah ke `public_html`. Hal tersebut akan merusak susunan folder publik Laravel. Gunakan Git pull manual lewat SSH untuk memperbarui website.

---

## 2. Cara Melakukan Update Website (lewat SSH)

Setiap kali Anda selesai melakukan perubahan kode di komputer lokal dan telah melakukan `git push` ke GitHub, ikuti perintah berikut untuk memperbarui server produksi:

1.  **Masuk ke SSH**:
    ```bash
    ssh -p 65002 u202379832@153.92.10.252
    ```
2.  **Masuk ke folder proyek**:
    ```bash
    cd domains/indoroster.com/laravel-proyek
    ```
3.  **Tarik kode terbaru dari GitHub**:
    ```bash
    git pull origin main
    ```
4.  **Bersihkan cache Laravel**:
    ```bash
    php artisan optimize:clear
    ```

---

## 3. Sistem Otomatisasi Sitemap (`sitemap.xml`)

Untuk membantu Google mengindeks semua produk dan halaman Anda secara instan, sistem sitemap saat ini telah dibuat **100% otomatis** di latar belakang.

### Bagaimana Cara Kerjanya?
*   **Pembaruan Otomatis**: Setiap kali Anda mengunggah, mengedit, atau menghapus data berikut di halaman admin:
    *   **Produk** (`Product`)
    *   **Kategori** (`Category`)
    *   **Foto Galeri** (`Gallery`)
    *   **Halaman Dinamis** (`Page`)
    Sistem Laravel akan langsung memperbarui file fisik `sitemap.xml` Anda di server secara otomatis.
*   **Pemicu Manual (Backup)**: Jika Anda ingin memperbarui sitemap secara manual tanpa mengubah database, kunjungi link ini di browser Anda:
    👉 [https://indoroster.com/generate_sitemap.php](https://indoroster.com/generate_sitemap.php)

---

## 4. Cara Mendaftarkan Sitemap ke Google Search Console

1.  Buka **[Google Search Console](https://search.google.com/search-console)**.
2.  Pilih properti domain **`https://indoroster.com/`**.
3.  Pilih menu **Sitemaps** (Peta Situs) di navigasi sebelah kiri.
4.  Di bagian **Add a new sitemap**, ketik:
    ```text
    sitemap.xml
    ```
5.  Klik tombol **Submit** (Kirim).

Google akan membaca file `sitemap.xml` tersebut secara berkala. Setiap ada produk baru yang Anda masukkan di halaman admin, Google akan mengetahuinya secara otomatis tanpa Anda perlu melakukan apa-apa lagi.
