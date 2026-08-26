# AGENTS.md — Indoroster

Aplikasi e-commerce Indonesia: Laravel 12 + Filament 3 + Livewire 3, MySQL via XAMPP di Windows.
Locale `id`, timezone `Asia/Jakarta` — sesuaikan bahasa teks UI/komentar dengan bahasa Indonesia
seperti kode di sekitarnya.

## Perintah
- Environment dev lengkap: `composer dev` (serve + queue:listen + pail + vite via concurrently)
- Test: `composer test` (= `config:clear` + `artisan test`); berjalan di SQLite :memory:
  sesuai phpunit.xml meskipun aplikasi memakai MySQL. Satu test: `php artisan test --filter=InvoicePrintTest`
- Format: `vendor/bin/pint` (preset Laravel, tanpa pint.json)
- Build: `npm run build` — `public/build/` ikut di-commit (deploy shared-hosting), jadi rebuild
  dan stage aset hasil build setelah mengubah frontend
- Tidak ada CI. Verifikasi dengan pint + test sebelum selesai.

## Arsitektur
- Storefront: komponen full-page Livewire yang langsung di-wire di `routes/web.php`
- Admin `/admin` (`app/Filament/Resources`) dan kurir `/courier` (`app/Filament/Courier/**`,
  CSS custom mobile-first di dalam CourierPanelProvider). Akses dibatasi oleh
  `User::canAccessPanel()`: role admin/courier + `is_active`.
- Pembayaran Midtrans (kunci sandbox di .env); webhook `POST /api/payments/midtrans-callback`
  dikecualikan dari CSRF di bootstrap/app.php
- `OrderObserver` otomatis membuat Invoice+Payment saat payment_status→paid dan ShippingLabel
  saat status→processing — jangan buat record tersebut secara manual
- Sitemap diregenerasi otomatis (menulis file fisik) saat Product/Category/Page/Gallery
  disimpan/dihapus; hook ada di AppServiceProvider::boot(), kegagalan bersifat senyap
- SMTP: nilai mail di .env ditimpa saat runtime oleh baris `site_settings` (group=`mail`)
  bila ada (AppServiceProvider.php:38)
- Tabel province/city/district/village laravolt/indonesia berisi data seed dalam jumlah besar

## Catatan Penting (Gotchas)
- Mode pemesanan WhatsApp sementara: `mount()` Cart/Checkout redirect ke pemesanan WhatsApp
  karena Midtrans belum disetujui. Langkah pemulihan persisnya ada di PANDUAN_ORDER_WHATSAPP.md —
  jangan "memperbaiki" redirect ini
- `.env.production` untuk shared hosting indoroster.com (DB `u202379832_db_indoroster`);
  produksi memaksa HTTPS di AppServiceProvider, dan public/.htaccess me-redirect HTTP→HTTPS
  non-localhost
- Script level root (`check_*.php`, `extract_images*.php`, `test_videos.php`, `scratch/*`)
  adalah utilitas maintenance/debug sekali pakai, bukan kode aplikasi; `database_indoroster.sql`
  adalah dump DB yang di-commit sebagai referensi
- Git: conventional commits (`feat:`, `fix:`…) di `main`. Jangan pernah push ke GitHub — owner
  menangani deploy/push secara manual. Hanya commit jika diminta secara eksplisit
