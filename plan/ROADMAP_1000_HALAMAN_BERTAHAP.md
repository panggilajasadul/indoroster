# MASTER ROADMAP: EKSEKUSI 1.000 HALAMAN BERTAHAP INDOROSTER

Dokumen ini adalah **buku panduan master dan catatan progres (batch tracker)** untuk membangun arsitektur 1.000 halaman yang saling mengunci (*silo interlinking*) dan mengalirkan kekuatan peringkat (*link juice*) kembali ke **Halaman Utama (`https://indoroster.com/`)** serta **3 Pilar Nasional**.

---

## 🏛️ 1. STRUKTUR HIERARKI SILO 1.000 HALAMAN & DISTRIBUSI LINK JUICE

Semua halaman dibangun dengan struktur piramida terbalik yang mengalirkan link equity ke atas:

```
                      ┌───────────────────────────────────────────────┐
                      │             HOMEPAGE UTAMA                    │
                      │        (https://indoroster.com/)              │
                      │  "Produsen & Supplier Roster No. 1 Indonesia" │
                      └───────────────────────▲───────────────────────┘
                                              │
                    ┌─────────────────────────┴─────────────────────────┐
                    │                                                   │
        ┌───────────┴───────────┐                           ┌───────────┴───────────┐
        │  3 CORE PILLAR HUBS   │                           │    3 CORE B2B HUBS    │
        │  - /roster-beton      │                           │  - /untuk-kontraktor  │
        │  - /roster-minimalis  │                           │  - /untuk-developer   │
        │  - /roster-beton-min. │                           │  - /untuk-arsitek     │
        └───────────▲───────────┘                           └───────────▲───────────┘
                    │                                                   │
     ┌──────────────┴──────────────┬────────────────────┬───────────────┴──────────────┐
     │                             │                    │                              │
┌────┴────────────┐       ┌────────┴─────────┐ ┌────────┴──────────┐          ┌────────┴──────────┐
│ CLUSTER PROYEK  │       │ CLUSTER KAWASAN  │ │ CLUSTER USE-CASE  │          │ CLUSTER EDUKASI   │
│ (Villa, Kafe,   │       │ (BSD, Bandung,   │ │ (Pagar, Fasad,    │          │ (Tips Pasang,     │
│ Ruko, Hotel)    │       │ Bekasi, Bogor)   │ │ Partisi, Sekat)   │          │ Perhitungan m²)   │
└─────────────────┘       └──────────────────┘ └───────────────────┘          └───────────────────┘
```

Setiap halaman di tingkat terbawah wajib memiliki:
1. **Inbound Link ke Homepage** (dengan anchor text variasi: *IndoRoster*, *pabrik roster beton*, *produsen roster minimalis*).
2. **Inbound Link ke 1 dari 3 Pilar Utama** (`/roster-beton`, `/roster-minimalis`, atau `/roster-beton-minimalis`).
3. **Inbound Link ke Gateway B2B** (`/untuk-kontraktor`, `/untuk-developer`, dll.).
4. **Silo Cross-link ke 2–3 Halaman Sejenis** (agar tidak ada halaman yang terisolasi).

---

## 🚫 2. ATURAN AUTENTISITAS PRODUK (WAJIB PATUH)

* **Bahan Abu Natural:** Wajib **Pasir Abu Batu (Abu Batu) murni**, DILARANG menyebut "pasir silika".
* **DILARANG menyebut "K-200" / "K200":** Ganti dengan *"Material cetak padat kokoh (bobot mantap 3.8–4.2 kg per keping)"*.
* **DILARANG mengklaim "Mesin Hidrolik":** Roster IndoRoster adalah *cetak tumbuk padat semi-kering oleh pengrajin ahli Plered Purwakarta*.
* **DILARANG menyebut "Produk Cor":** Roster IndoRoster *BUKAN cor basah*, melainkan cetak tumbuk padat plat baja presisi siku 90°.
* **Format Layout:** Wajib adaptif, simetris, dan lapang sesuai `indoroster-content/PANDUAN_KONTEN_DAN_DESAIN_PREMIUM.md`.

---

## 📊 3. TRACKER & ROADMAP EKSEKUSI BATCH (10 HALAMAN PER BATCH)

| Batch | Fokus Cluster | Target Jumlah | Status |
| :--- | :--- | :--- | :--- |
| **Batch 1** | **High-Intent Commercial & Proyek Populer** | 10 Halaman | 🟡 **SEDANG BERJALAN** |
| **Batch 2** | **Kawasan Properti & Klaster Residensial Jabodetabek** | 10 Halaman | ⚪ Terencana |
| **Batch 3** | **Spesifikasi Teknis, Dimensi & Solusi Fasad** | 10 Halaman | ⚪ Terencana |
| **Batch 4** | **Jawa Barat Focus (Bandung, Sukabumi, Cirebon)** | 10 Halaman | ⚪ Terencana |
| **Batch 5** | **B2B Tender & Grosir Pengadaan Volume Besar** | 10 Halaman | ⚪ Terencana |
| ... | *Batch 6 s/d Batch 100* | Total 1.000 Halaman | ⚪ Terencana |

---

## 🎯 4. RINCIAN BATCH 1: 10 HALAMAN PILIHAN BERBOBOT TINGGI

Berikut adalah 10 halaman prioritas pertama yang dirancang untuk langsung menangkap *search volume* komersial bernilai tinggi:

### 1. `/roster-beton-untuk-villa-resort`
* **Cluster:** Proyek Komersial
* **Target Keyword:** `roster beton untuk villa`, `desain dinding roster villa resort`
* **Fokus Narasi:** Konsep arsitektur tropis terbuka yang sejuk, menyatu dengan alam, meredam panas tanpa menghalangi pemandangan asri di area pegunungan dan pantai.
* **Hub Silo:** &rarr; `/roster-minimalis`, `/untuk-arsitek`, Homepage.

### 2. `/roster-beton-untuk-cafe-industrial`
* **Cluster:** Proyek Komersial
* **Target Keyword:** `roster beton cafe`, `fasad roster cafe industrial aesthetic`
* **Fokus Narasi:** Tampilan unfinish abu batu natural dan terakota yang instagramable, permainan bayangan cahaya matahari (shadow play) untuk spot foto kafe kekinian.
* **Hub Silo:** &rarr; `/roster-beton`, `/katalog`, Homepage.

### 3. `/roster-beton-untuk-fasad-ruko`
* **Cluster:** Proyek Komersial
* **Target Keyword:** `fasad roster ruko modern`, `dinding roster bangunan komersial`
* **Fokus Narasi:** Modernisasi tampak depan ruko agar tidak terlihat monoton, sirkulasi udara lantai atas, dan privasi ruang usaha.
* **Hub Silo:** &rarr; `/roster-beton-minimalis`, `/untuk-kontraktor`, Homepage.

### 4. `/roster-pagar-rumah-minimalis`
* **Cluster:** Use-Case Residensial
* **Target Keyword:** `pagar roster minimalis`, `desain pagar roster beton modern`
* **Fokus Narasi:** Pagar keliling rumah yang kokoh, anti-sumpek, memberikan sirkulasi angin segar ke taman depan sekaligus menjaga privasi dari jalan raya.
* **Hub Silo:** &rarr; `/roster-minimalis`, `/kalkulator-roster`, Homepage.

### 5. `/roster-dinding-secondary-skin`
* **Cluster:** Use-Case Residensial & Fasad
* **Target Keyword:** `secondary skin roster beton`, `penangkal panas matahari dinding roster`
* **Fokus Narasi:** Mengurangi radiasi panas matahari siang hingga 40%, menjaga suhu interior tetap sejuk alami dan menghemat biaya tagihan listrik AC.
* **Hub Silo:** &rarr; `/roster-beton`, `/untuk-arsitek`, Homepage.

### 6. `/roster-nako-anti-tampias`
* **Cluster:** Fitur Produk Spesifik
* **Target Keyword:** `roster nako anti tampias`, `loster jalusi nako penahan hujan`
* **Fokus Narasi:** Desain sirip miring aerodinamis yang menghalau percikan air hujan agar tidak masuk ke area void dapur, ruang cuci jemur, dan balkon terbuka.
* **Hub Silo:** &rarr; `/roster-minimalis`, `/katalog`, Homepage.

### 7. `/roster-beton-bsd-city`
* **Cluster:** Kawasan Properti Prioritas
* **Target Keyword:** `supplier roster beton bsd city`, `jual roster bsd serpong tangerang`
* **Fokus Narasi:** Pengiriman cepat armada pabrik langsung ke kawasan hunian cluster BSD City, Gading Serpong, dan Alam Sutera dengan jaminan garansi bebas pecah.
* **Hub Silo:** &rarr; `/lokasi/tangerang-selatan`, `/roster-beton`, Homepage.

### 8. `/roster-beton-bandung-utara`
* **Cluster:** Kawasan Properti Prioritas
* **Target Keyword:** `supplier roster beton bandung utara`, `jual roster lembang dago`
* **Fokus Narasi:** Pengadaan roster beton untuk villa dan hunian asri wilayah Dago, Setiabudi, dan Lembang dengan material tahan cuaca lembab dan bebas lumut.
* **Hub Silo:** &rarr; `/lokasi/kota-bandung`, `/roster-minimalis`, Homepage.

### 9. `/supplier-roster-proyek-perumahan`
* **Cluster:** B2B Developer & Kontraktor
* **Target Keyword:** `supplier roster proyek perumahan`, `pengadaan roster cluster developer`
* **Fokus Narasi:** Suplai volume ribuan pcs untuk rumah contoh dan ratusan unit klaster, keseragaman motif presisi, kestabilan harga kontrak, dan kesiapan SPK/Faktur Pajak.
* **Hub Silo:** &rarr; `/untuk-developer`, `/roster-beton-minimalis`, Homepage.

### 10. `/harga-roster-beton-partai-besar`
* **Cluster:** Wholesale / Grosir
* **Target Keyword:** `harga roster beton partai besar`, `harga grosir roster beton pabrik`
* **Fokus Narasi:** Transparansi tier harga grosir tangan pertama dari sentra Plered Purwakarta, skema ritase truk colt diesel/fuso, dan diskon volume khusus pemborong.
* **Hub Silo:** &rarr; `/supplier-roster-beton`, `/roster-beton`, Homepage.

---

## 📌 CARA MELANJUTKAN BATCH BERIKUTNYA:
Setiap kali Anda ingin melanjutkan batch baru, cukup beri perintah:
*"Lanjutkan pengerjaan Batch [Nomor Batch]"* — sistem akan membaca daftar keyword, struktur silo, dan mengeksekusinya sesuai standar kualitas premium tanpa lompat-lompat.
