# Panduan Lengkap Konfigurasi Midtrans (IndoRoster)

Dokumen ini berisi panduan teknis langkah demi langkah untuk mengganti akun, kunci API, serta beralih antara mode **Sandbox (Testing)** dan **Production (Live)** pada sistem IndoRoster di server Hostinger.

---

## 📌 Ringkasan Parameter `.env`

Di file `.env` server Hostinger (`domains/indoroster.com/laravel-proyek/.env`), terdapat 6 baris konfigurasi Midtrans:

```env
# ==========================================
# KONFIGURASI MIDTRANS
# ==========================================
MIDTRANS_MERCHANT_ID="MXXXXXXXXX"
MIDTRANS_CLIENT_KEY="Mid-client-XXXXXXXXXXXX"
MIDTRANS_SERVER_KEY="Mid-server-XXXXXXXXXXXX"
MIDTRANS_IS_PRODUCTION=false      # Set 'true' untuk Live, 'false' untuk Sandbox (Testing)
MIDTRANS_IS_SANITIZED=true       # Wajib 'true'
MIDTRANS_IS_3DS=true             # Wajib 'true' untuk keamanan 3D Secure Kartu Kredit
```

---

## 🚀 Langkah 1: Pengaturan di Dashboard Midtrans (MAP)

> **Catatan Penting:** Pengaturan Dashboard untuk **Sandbox** dan **Production** terpisah. Pastikan Anda memilih mode yang sesuai pada dropdown di pojok kiri atas dashboard.

1. Buka dan login ke: **[https://dashboard.midtrans.com/](https://dashboard.midtrans.com/)**
2. Pilih environment di pojok kiri atas (**Sandbox** atau **Production**).
3. Ambil Kunci API di menu: **Settings** > **Access Keys**
   - Salin **Merchant ID**, **Client Key**, dan **Server Key**.
4. Atur URL Callback & Redirect di menu: **Settings** > **Configuration**

| Pengaturan di Midtrans | Nilai URL yang Harus Diisi | Keterangan |
| :--- | :--- | :--- |
| **Payment Notification URL** | `https://indoroster.com/api/payments/midtrans-callback` | **Wajib & Sangat Penting** (Webhook notifikasi pembayaran lunas otomatis) |
| **Finish Redirect URL** | `https://indoroster.com/checkout/success` | Mengarahkan pembeli kembali ke web setelah sukses bayar |
| **Unfinish Redirect URL** *(Opsional)* | `https://indoroster.com/keranjang` | Jika pembeli menutup popup sebelum bayar |
| **Error Redirect URL** *(Opsional)* | `https://indoroster.com/keranjang` | Jika terjadi kegagalan sistem |

5. Klik tombol **Simpan / Save Changes** di setiap halaman pengaturan.

---

## 💻 Langkah 2: Mengubah Konfigurasi di Hostinger

Ada 2 cara untuk mengubah `.env` di Hostinger:

### Cara A: Melalui Terminal / SSH Hostinger (Direkomendasikan)

1. Buka menu **Terminal** / **SSH** di hPanel Hostinger.
2. Masuk ke folder proyek website:
   ```bash
   cd domains/indoroster.com/laravel-proyek
   ```
3. Buka file `.env` menggunakan text editor nano:
   ```bash
   nano .env
   ```
4. Gulir ke bawah mencari baris `MIDTRANS_...` dan masukkan kunci baru Anda.
5. **Simpan perubahan:**
   - Tekan **`Ctrl + O`**, lalu tekan **`Enter`**.
   - Tekan **`Ctrl + X`** untuk keluar dari editor nano.
6. **Wajib:** Bersihkan cache konfigurasi Laravel:
   ```bash
   php artisan optimize:clear
   php artisan config:cache
   ```

---

### Cara B: Melalui File Manager Hostinger

1. Buka **hPanel** > **File Manager**.
2. Masuk ke folder: `domains` > `indoroster.com` > `laravel-proyek`.
3. Cari file `.env` *(Jika tersembunyi, aktifkan 'Tampilkan file tersembunyi' di pojok kanan atas)*.
4. Klik kanan file `.env` > pilih **Edit**.
5. Sesuaikan nilai `MIDTRANS_...`.
6. Klik tombol **Save / Simpan**.
7. Buka menu **Terminal** di Hostinger dan jalankan:
   ```bash
   cd domains/indoroster.com/laravel-proyek && php artisan optimize:clear
   ```

---

## 🧪 Alat Uji Coba Mode Sandbox (Testing)

Jika menggunakan mode **Sandbox**, Anda tidak perlu menggunakan uang sungguhan. Gunakan simulator resmi Midtrans untuk simulasi bayar:

* **Simulator Pembayaran Resmi:** [https://simulator.sandbox.midtrans.com/](https://simulator.sandbox.midtrans.com/)
* **Kartu Kredit Testing:**
  - Nomor Kartu: `4811111111111114`
  - CVV: `123`
  - Expired Date: Bulan/Tahun masa depan (misal `12/28`)
  - OTP Testing: `123456`

---

## ⚠️ Troubleshooting & Masalah Umum

1. **Status pesanan tidak otomatis berubah jadi "Lunas" setelah pembeli transfer:**
   - Cek kembali menu **Settings** > **Configuration** di Midtrans. Pastikan `Payment Notification URL` terisi: `https://indoroster.com/api/payments/midtrans-callback`.
   - Pastikan URL diawali dengan `https://` bukan `http://`.
2. **Popup pembayaran error / "Access Denied" / 401 Unauthorized:**
   - Pastikan tidak ada spasi atau tanda petik ganda ganda yang salah di file `.env`.
   - Pastikan nilai `MIDTRANS_IS_PRODUCTION` sesuai dengan environment akun yang sedang dipakai (`false` untuk Sandbox, `true` untuk Production).
   - Jalankan `php artisan optimize:clear` di terminal.
