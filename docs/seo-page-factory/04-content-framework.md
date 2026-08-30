# Content Framework IndoRoster (Struktur Halaman per Page Type)

> Panduan arsitektur section dan alur narasi (narrative flow) per jenis halaman.
> Mencegah duplikasi konten dan memastikan setiap jenis halaman memiliki *conversion flow* yang optimal.

---

## Prinsip Alur Konversi (Conversion-First Framework)

Setiap halaman disusun dengan formula:
$$\text{NEED} \longrightarrow \text{PROBLEM} \longrightarrow \text{PRODUCT FIT} \longrightarrow \text{PROOF} \longrightarrow \text{RISK REDUCTION} \longrightarrow \text{PROCESS} \longrightarrow \text{ACTION (CTA)}$$

---

## 1. Type A: Pillar Commercial Page

Contoh halaman: `/supplier-roster-beton`, `/pabrik-roster-beton`, `/pengadaan-roster-beton`

### Alur Struktur Halaman:
1. **Hero Section**:
   - Badge: Produsen Tangan Pertama Plered Purwakarta
   - H1: Menegaskan identitas supplier + cakupan pengadaan proyek
   - Subtitle: Kapasitas pabrik 10.000 pcs/bulan, siku 90° presisi, dokumen resmi pengadaan
   - CTA: Konsultasi Proyek (WhatsApp) & Link Kalkulator Kebutuhan
2. **Problem & Context Section**:
   - Tantangan pengadaan roster: material tidak rata, pengiriman pecah di jalan, supplier menghilang saat komplain.
3. **IndoRoster Solution (UVP)**:
   - Cetakan baja presisi hidrolik (menghemat adukan semen tukang).
   - Pengiriman bertahap (batch delivery) sesuai jadwal cor/pemasangan.
   - Dokumen lengkap: Surat Jalan, Invoice, Faktur Pajak.
4. **Selected Product Catalog (4-8 Motif Utama)**:
   - Menampilkan produk terlaris dengan spesifikasi dimensi (20x20x10 cm, 20x20x8 cm).
5. **Kapasitas & Batasan Minimum Order (MOQ Verified)**:
   - Penjelasan transparan: Retail min 1.000 pcs, Grosir proyek min 5.000 pcs (berlaku semua motif).
6. **5 Langkah Cara Pemesanan Proyek**:
   - Kirim Kebutuhan $\to$ Verifikasi Produk $\to$ Penawaran & Jadwal $\to$ Produksi/Packing $\to$ Pengiriman Aman.
7. **FAQ Proyek Terstruktur**:
   - 3-4 pertanyaan seputar toleransi pecah, armada kirim, faktur pajak.
8. **Bottom CTA**:
   - Form/Tombol request penawaran harga.

---

## 2. Type B: Buyer-Specific Page

Contoh halaman: `/supplier-roster-untuk-kontraktor`, `/roster-beton-untuk-developer-perumahan`, `/roster-beton-untuk-arsitek`

### Alur Struktur Halaman:
1. **Hero Persona**:
   - H1 disesuaikan bahasa pembeli (misal: "Suplai Roster Beton untuk Rekan Kontraktor & Pemborong").
   - Opening fokus pada timeline proyek, efisiensi tukang, atau fleksibilitas desain.
2. **Kebutuhan Spesifik Persona**:
   - *Untuk Kontraktor*: Kecepatan pasang, akurasi siku 90°, batch delivery.
   - *Untuk Developer*: Keseragaman tampilan perumahan 50-100 unit, jaminan suplai jangka panjang.
   - *Untuk Arsitek*: Variasi motif geometris, ketahanan cuaca, pencahayaan alami & bayangan fasad.
   - *Untuk Procurement*: Keabsahan legalitas vendor (NIB, NPWP), format quotation resmi, SLA pengiriman.
3. **Katalog Terkurasi Sesuai Kebutuhan Persona**:
   - Menampilkan motif-motif yang paling relevan (misal untuk cluster: motif minimalis kotak/garis modern).
4. **Detail Teknis & Pengujian**:
   - Mutu beton, toleransi dimensi, berat per keping untuk perhitungan beban dinding.
5. **Skema Pemesanan & Pembayaran Proyek**:
   - Prosedur PO, termin pembayaran pengadaan bertahap.
6. **WhatsApp Dynamic CTA**:
   - Pre-filled text sesuai identitas pembeli ("Halo, saya Kontraktor ingin konsultasi...").

---

## 3. Type C: Project-Specific Page

Contoh halaman: `/roster-beton-proyek-perumahan`, `/roster-beton-proyek-gedung`, `/roster-beton-untuk-bangunan-komersial`

### Alur Struktur Halaman:
1. **Hero Project Context**:
   - H1: Integrasi Roster Beton pada Proyek [Tipe Bangunan].
2. **Area Aplikasi Roster pada Proyek Tersebut**:
   - *Proyek Perumahan*: Fasad rumah, ventilasi dapur/toilet, pagar cluster, carport.
   - *Proyek Gedung/Komersial*: Dinding secondary skin, sekat lobi, dinding tangga darurat, pagar keliling.
   - *Proyek Cafe/Hotel*: Partisi ruang estetik, background foto, sirkulasi outdoor smoking area.
3. **Pertimbangan Teknis & Pemilihan Motif**:
   - Keseimbangan antara privasi visual, intensitas angin, dan perlindungan tampias hujan.
4. **Rekomendasi Produk**:
   - Grid produk dengan tag rekomendasi aplikasi.
5. **Estimasi Kebutuhan & Perhitungan Volume**:
   - Rumus perhitungan luas dinding ($1\text{ m}^2 \approx 25\text{ pcs}$ untuk ukuran 20x20 cm).
   - Saran waste margin ($3\text{--}5\%$).
6. **CTA Request Konsultasi Desain & Penawaran**.

---

## 4. Type D: Use Case Page (Fasad / Ventilasi / Pagar)

Contoh halaman: `/roster-beton-untuk-fasad`, `/roster-beton-untuk-ventilasi`, `/roster-beton-untuk-pagar`

### Alur Struktur Halaman:
1. **Hero Use Case**:
   - H1 terfokus pada fungsi arsitektural (misal: "Roster Beton untuk Fasad Rumah & Bangunan Modern").
2. **Manfaat Fungsional & Estetika**:
   - Mereduksi panas matahari langsung (solar shading).
   - Efisiensi energi pendingin ruangan (AC).
   - Nilai estetika bayangan dinamis di dalam ruangan.
3. **Panduan Pemasangan & Struktur Penguat**:
   - Pentingnya angkur besi / tulangan kolom praktis untuk dinding roster di atas ketinggian 2 meter.
4. **Katalog Khusus Fungsi Tersebut**:
   - Motif lubang padat vs lubang besar sesuai kebutuhan privasi/ventilasi.
5. **FAQ Spesifik**:
   - Apakah air hujan bisa masuk? Bagaimana cara finishing cat?
6. **CTA Hitung Kebutuhan Fasad / Ventilasi**.

---

## 5. Type E: Location Page (Berbasis Jangkauan Armada)

Contoh halaman: `/supplier-roster-beton-jakarta`, `/supplier-roster-beton-bekasi`, `/supplier-roster-beton-bandung`

### ⚠️ Aturan Wajib Anti-Doorway:
- Halaman lokasi **tidak boleh sekadar template kata yang diganti nama kotanya**.
- Harus menyajikan konteks proyek tipikal daerah tersebut dan penjelasan logistik yang realistis.

### Alur Struktur Halaman:
1. **Hero Wilayah Target**:
   - H1: Suplai Roster Beton untuk Proyek di [Kota].
   - Narasi pengiriman dari sentra pabrik Plered Purwakarta langsung ke gerbang proyek di wilayah target.
2. **Karakteristik Proyek di Wilayah Tersebut**:
   - *Bekasi/Karawang*: Dominasi cluster perumahan baru, kawasan industri & ruko komersial.
   - *Jakarta Selatan/Pusat*: Fasad renovasi arsitektural modern, cafe, gedung kantor low-rise.
   - *Bandung/Cianjur*: Desain villa tropis terbuka, perumahan dataran tinggi dengan ventilasi natural.
3. **Pertimbangan Logistik & Pengiriman Armada**:
   - Rencana pengiriman menggunakan armada truk colt diesel / fuso langsung dari pabrik.
   - Akses jalan proyek (lebar jalan lingkungan) untuk menentukan tipe truk yang digunakan.
4. **Katalog Produk Relevan untuk Tren Bangunan di Wilayah Tersebut**.
5. **Garansi Pengiriman Bebas Pecah**:
   - Prosedur penggantian material jika ada kerusakan saat transit.
6. **CTA Kontak Sales Wilayah**.

---

## 6. Type F: Wholesale / Pricing Page

Contoh halaman: `/grosir-roster-beton`, `/harga-roster-beton-proyek`

### ⚠️ Kebijakan Ketat Harga:
- **Jangan mencantumkan harga nominal spesifik** tanpa konfirmasi resmi karena harga material bergantung pada volume, ketebalan, dan titik pengiriman.
- Edukasi calon pembeli mengenai faktor-faktor penentu harga.

### Alur Struktur Halaman:
1. **Hero Skema Grosir**:
   - H1: Pengadaan Roster Beton Partai Besar & Harga Grosir Pabrik.
2. **Faktor Penentu Harga Pengadaan**:
   - Volume pesanan (Tiering MOQ: 1.000 vs 5.000+ pcs).
   - Tipe bahan & finishing (abu semen biasa vs putih/teraso).
   - Jarak titik drop pengiriman dari pabrik Purwakarta.
3. **Proses Request Quotation Resmi**:
   - Format data yang diperlukan untuk kalkulasi penawaran resmi (MOTIF + JUMLAH + LOKASI + JADWAL).
4. **Jaminan Kualitas Pabrik Tangan Pertama**:
   - Bebas markup perantara toko retail.
5. **CTA Request RAB & Simulasi Biaya Pengadaan**.
