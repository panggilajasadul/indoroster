---
name: meta-pixel-capi-integration
description: Panduan lengkap untuk AI agent dalam mengimplementasikan integrasi Meta Pixel (client-side) dan Meta Conversions API/CAPI (server-side) secara bersamaan dengan deduplikasi event, dengan target akhir mencapai skor Event Match Quality (EMQ) 10/10 di Meta Events Manager. Gunakan skill ini setiap kali user meminta pemasangan Facebook/Meta Pixel, Conversion API, tracking iklan Meta/Instagram, perbaikan Event Match Quality, atau troubleshooting data event iklan Meta — baik untuk website WordPress/WooCommerce, toko online (Shopify, custom), maupun landing page sederhana yang funnel-nya berujung ke WhatsApp/form leads.
---

# Integrasi Meta Pixel + Conversions API (Target EMQ 10/10)

## 1. Ringkasan & Tujuan

Meta memberi skor **Event Match Quality (EMQ)** dari 0–10 untuk tiap event yang dikirim ke Pixel/CAPI. Skor ini murni ditentukan oleh **seberapa lengkap parameter identitas pelanggan** (`user_data`) yang dikirim, bukan oleh jumlah traffic. Skill ini menuntun AI agent untuk:

1. Memasang **Pixel** (browser) dan **CAPI** (server) secara paralel untuk event yang sama.
2. Melakukan **deduplikasi** agar event yang sama tidak dihitung dobel.
3. Mengirim **parameter identitas selengkap mungkin** di setiap event agar EMQ mendekati/mencapai 10.
4. Memverifikasi hasil lewat Meta Events Manager sebelum menyatakan tugas selesai.

Jangan anggap tugas selesai hanya karena kode sudah terpasang — **skor EMQ harus dicek di Events Manager** sebagai bukti keberhasilan (lihat bagian 8).

## 2. Prasyarat yang Harus Dikumpulkan dari User

Tanyakan/kumpulkan sebelum mulai coding:

- **Pixel ID** (Events Manager → Data Sources)
- **Access Token** sistem/App (Conversions API → Generate Access Token, atau via System User di Business Settings)
- **Dataset/Domain** website yang diverifikasi di Meta Business Manager (Brand Safety → Domains)
- Tumpukan teknologi backend (Node.js, PHP/WordPress, atau lainnya) — menentukan contoh kode server yang dipakai
- Titik-titik konversi bisnis: apakah checkout online (Purchase), atau funnel non-ecommerce (klik WhatsApp, submit form leads) — umum untuk bisnis UMKM/produk fisik seperti roster/precast

**Keamanan**: Access token CAPI TIDAK PERNAH ditaruh di kode client-side/browser. Selalu simpan di environment variable server (`.env`), jangan hardcode, jangan commit ke repo publik.

## 3. Arsitektur

```
Browser (Pixel/fbq.js)  ──┐
                          ├──> Meta (dedup by event_id + event_name)
Server (CAPI POST)  ──────┘
```

- Pixel mengirim event dari browser user (dipengaruhi ad blocker, ITP Safari, iOS14+).
- CAPI mengirim event yang sama dari server (tidak terpengaruh blocker), idealnya dengan data pelanggan yang lebih lengkap (hasil dari form, database order, dsb).
- Kedua event HARUS punya `event_id` yang sama persis untuk event yang sama agar Meta men-dedup, bukan menghitung 2x.

## 4. Pemetaan Event (Contoh Bisnis Produk Fisik / Non-ecommerce Penuh)

| Tahap Funnel | Event Standar Meta | Trigger |
|---|---|---|
| Buka halaman | `PageView` | Semua halaman |
| Lihat produk | `ViewContent` | Halaman detail produk |
| Klik tombol WhatsApp | `Contact` | onClick tombol WA/telepon |
| Submit form tanya harga | `Lead` | Submit form |
| Tambah ke keranjang (jika ada) | `AddToCart` | Klik "tambah ke keranjang" |
| Checkout online | `InitiateCheckout` / `Purchase` | Mulai checkout / pembayaran sukses |

Sesuaikan tabel ini dengan funnel nyata user — jangan asal pasang semua event standar jika tidak relevan.

## 5. Implementasi Client-Side (Pixel)

Base code (taruh di `<head>`, sebelum `</head>` menutup, di semua halaman):

```html
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', 'PIXEL_ID');
fbq('track', 'PageView');
</script>
```

Contoh event dengan `eventID` untuk dedup (WAJIB sama dengan `event_id` yang dikirim server untuk event yang sama):

```javascript
const eventId = crypto.randomUUID(); // generate sekali, kirim ke server juga

fbq('track', 'Lead', {
  content_name: 'Form Tanya Harga Roster',
}, { eventID: eventId });
```

## 6. Implementasi Server-Side (Conversions API)

Endpoint: `POST https://graph.facebook.com/v20.0/{PIXEL_ID}/events?access_token={ACCESS_TOKEN}`

Contoh Node.js/Express:

```javascript
const crypto = require('crypto');

function sha256(value) {
  return crypto.createHash('sha256')
    .update(value.trim().toLowerCase())
    .digest('hex');
}

async function sendCapiEvent({ eventName, eventId, eventSourceUrl, req, userData }) {
  const payload = {
    data: [{
      event_name: eventName,
      event_time: Math.floor(Date.now() / 1000),
      event_id: eventId, // HARUS sama dengan eventID di Pixel browser
      action_source: 'website',
      event_source_url: eventSourceUrl,
      user_data: {
        em: userData.email ? [sha256(userData.email)] : undefined,
        ph: userData.phone ? [sha256(userData.phone.replace(/\D/g,''))] : undefined,
        fn: userData.firstName ? [sha256(userData.firstName)] : undefined,
        ln: userData.lastName ? [sha256(userData.lastName)] : undefined,
        ct: userData.city ? [sha256(userData.city)] : undefined,
        st: userData.state ? [sha256(userData.state)] : undefined,
        zp: userData.zip ? [sha256(userData.zip)] : undefined,
        country: userData.country ? [sha256(userData.country)] : undefined,
        external_id: userData.externalId ? [sha256(userData.externalId)] : undefined,
        client_ip_address: req.ip,
        client_user_agent: req.headers['user-agent'],
        fbc: req.cookies?._fbc,
        fbp: req.cookies?._fbp,
      },
    }],
    // test_event_code: 'TEST12345', // aktifkan hanya saat testing
  };

  const res = await fetch(
    `https://graph.facebook.com/v20.0/${process.env.META_PIXEL_ID}/events?access_token=${process.env.META_CAPI_TOKEN}`,
    { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }
  );
  return res.json();
}
```

Untuk WordPress/PHP, logika sama: hash PII dengan `hash('sha256', strtolower(trim($value)))`, kirim via `wp_remote_post()` ke endpoint yang sama.

## 7. Strategi Mencapai Skor EMQ 10/10

EMQ naik seiring makin banyak parameter `user_data` yang **cocok dengan data internal Meta**. Prioritas parameter (dari paling berpengaruh):

1. `em` (email, hashed) — paling berpengaruh
2. `ph` (nomor telepon, hashed, format E.164 tanpa simbol, contoh: `6281234567890`)
3. `fbc` (Facebook click ID dari cookie `_fbc`, ambil dari parameter URL `fbclid`)
4. `fbp` (Facebook browser ID dari cookie `_fbp`, otomatis dibuat oleh Pixel)
5. `client_ip_address` + `client_user_agent` — WAJIB selalu dikirim, ambil langsung dari request server
6. `fn`, `ln` (nama depan/belakang, hashed)
7. `ct`, `st`, `zp`, `country` (kota, provinsi, kode pos, negara — hashed)
8. `external_id` (ID unik internal, misal ID user/order di sistem, hashed)

**Aturan hashing**: semua field PII (`em`, `ph`, `fn`, `ln`, `ct`, `st`, `zp`, `country`, `external_id`) wajib di-hash SHA256 setelah di-lowercase dan di-trim. `fbc`, `fbp`, `client_ip_address`, `client_user_agent` TIDAK di-hash — kirim apa adanya.

**Isi selengkap yang tersedia di tiap tahap funnel**:
- Event awal (PageView/ViewContent): minimal `fbp`, `fbc`, IP, user agent — data pelanggan belum ada.
- Event setelah user isi form/checkout (Lead/Purchase): tambahkan email, telepon, nama, alamat dari data form — di titik inilah skor EMQ paling mudah didorong ke 10.

## 8. Deduplikasi

- `event_id` harus identik antara Pixel dan CAPI untuk event yang mewakili kejadian yang sama.
- `event_name` juga harus identik.
- Kirim CAPI dalam rentang waktu wajar (idealnya real-time, maksimal beberapa menit setelah event browser) agar Meta bisa mencocokkan.

## 9. Testing & Validasi (Wajib Sebelum Klaim Selesai)

1. Buka **Meta Events Manager → Pixel → Test Events**.
2. Masukkan `test_event_code` dari halaman itu ke payload CAPI (sementara, untuk testing saja).
3. Trigger tiap event dari browser (harus muncul dengan sumber "Browser") dan pastikan event server juga muncul (sumber "Server") dengan `event_id` sama → status harus "Deduplicated".
4. Buka tab **Diagnostics** dan **Aggregated Event Measurement** → cek kolom **Event Match Quality** per event. Jika belum 10, cek parameter mana yang kosong ("Missing" atau abu-abu) dan lengkapi sesuai bagian 7.
5. Setelah lolos test, **hapus** `test_event_code` dari payload produksi.

## 10. Checklist Verifikasi Akhir

- [ ] Pixel base code terpasang di semua halaman
- [ ] Event standar sesuai funnel bisnis terpasang di client & server
- [ ] `event_id` sama persis antara Pixel & CAPI untuk event yang sama
- [ ] Access token CAPI disimpan di server (bukan di kode client)
- [ ] Semua field PII di-hash SHA256 (lowercase + trim)
- [ ] `client_ip_address`, `client_user_agent`, `fbp`, `fbc` selalu dikirim
- [ ] Event lanjut funnel (Lead/Purchase) menyertakan email/telepon/nama/alamat
- [ ] Diverifikasi di Test Events: status "Deduplicated" muncul
- [ ] Skor Event Match Quality dicek di Events Manager mendekati/mencapai 10
- [ ] `test_event_code` sudah dihapus dari kode produksi

## 11. Troubleshooting Umum

- **EMQ rendah meski sudah kirim email/telepon**: cek format hashing (harus lowercase+trim sebelum SHA256), cek nomor telepon pakai format internasional tanpa "+" atau spasi.
- **Event tidak ter-dedup (muncul 2x)**: `event_id` beda antara browser & server, atau `event_name` tidak identik.
- **`fbc` selalu kosong**: pastikan cookie `_fbc` dibaca dari parameter URL `fbclid` saat user datang dari iklan, dan Pixel base code sudah jalan sebelum event dikirim.
- **CAPI return error 400**: cek format `user_data` — field array harus berisi array string (`["hash"]`), bukan string biasa.