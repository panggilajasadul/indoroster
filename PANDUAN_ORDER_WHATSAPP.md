# Panduan Sistem Pemesanan WhatsApp Sementara (INDOROSTER)

Dokumen ini menjelaskan modifikasi yang dilakukan untuk mengalihkan pemesanan dari sistem checkout otomatis (Midtrans) ke WhatsApp, serta langkah-langkah detail untuk mengembalikannya ke sistem normal saat payment gateway sudah disetujui.

---

## 📌 Informasi Umum
* **Nomor WhatsApp Admin:** `081389709847` (Format link: `https://wa.me/6281389709847`)
* **Tujuan Perubahan:** Memblokir halaman keranjang dan checkout karena Midtrans belum disetujui, lalu menampilkan formulir pemesanan langsung via WhatsApp di halaman detail produk.

---

## 🛠️ File yang Dimodifikasi

1. **`app/Livewire/Cart.php`** (Memblokir akses halaman keranjang)
2. **`app/Livewire/Checkout.php`** (Memblokir akses halaman checkout)
3. **`resources/views/components/layouts/app.blade.php`** (Banner notifikasi global)
4. **`resources/views/livewire/product-detail.blade.php`** (Tombol beli & popup modal WhatsApp)

---

## 🔄 Cara Mengembalikan ke Sistem Normal (Midtrans)

Ketika akun Midtrans Anda sudah aktif dan disetujui, Anda dapat mengembalikan sistem belanja online normal dengan salah satu dari dua cara berikut:

### Opsi A: Menggunakan Git (Sangat Cepat & Mudah)
Jika Anda menggunakan Git di Windows 11 (Git Bash / VS Code Terminal), Anda hanya perlu menjalankan satu baris perintah berikut untuk membatalkan semua modifikasi:

```bash
git checkout app/Livewire/Cart.php app/Livewire/Checkout.php resources/views/components/layouts/app.blade.php resources/views/livewire/product-detail.blade.php
```
*Catatan: Pastikan Anda belum melakukan commit pada perubahan ini jika ingin menggunakan cara di atas.*

---

### Opsi B: Mengedit File Secara Manual
Buka editor kode pilihan Anda (seperti VS Code) di Windows 11 dan ubah baris kode berikut ke kode aslinya:

#### 1. File: [Cart.php](file:///Applications/XAMPP/xamppfiles/htdocs/indoroster/app/Livewire/Cart.php)
Cari method `mount()` di bagian atas file:
```php
// Hapus kode pengalihan WhatsApp ini:
public function mount()
{
    return redirect()->route('home')->with('error', 'Transaksi online sedang dinonaktifkan sementara. Silakan lakukan pemesanan langsung melalui WhatsApp pada halaman detail produk.');
}
```
Ganti kembali ke kode aslinya:
```php
public function mount()
{
    $this->loadCart();
}
```

#### 2. File: [Checkout.php](file:///Applications/XAMPP/xamppfiles/htdocs/indoroster/app/Livewire/Checkout.php)
Cari method `mount()` di bagian atas file:
```php
// Hapus kode pengalihan WhatsApp ini:
public function mount()
{
    return redirect()->route('home')->with('error', 'Transaksi online sedang dinonaktifkan sementara. Silakan lakukan pemesanan langsung melalui WhatsApp pada halaman detail produk.');
}
```
Ganti kembali ke kode aslinya:
```php
public function mount()
{
    $this->mode = request()->query('mode', '');
    
    $this->loadCart();
    
    if (count($this->cartItems) === 0) {
        return redirect('/keranjang');
    }

    $this->provinces = \Laravolt\Indonesia\Models\Province::orderBy('name')->get();

    if (auth()->check()) {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        
        $this->savedAddresses = $user->addresses()->orderByDesc('is_default')->get();
        $defaultAddress = $this->savedAddresses->where('is_default', true)->first() ?? $this->savedAddresses->first();
        if ($defaultAddress) {
            $this->selectedAddressId = $defaultAddress->id;
            $this->selectAddress($defaultAddress->id);
        }
    } else {
        $this->savedAddresses = collect([]);
    }
}
```

#### 3. File: [app.blade.php](file:///Applications/XAMPP/xamppfiles/htdocs/indoroster/resources/views/components/layouts/app.blade.php)
Cari baris notifikasi banner di bawah tag `<main>` (sekitar baris 380):
```html
{{-- Hapus blok pemberitahuan session success/error ini jika tidak diperlukan lagi --}}
@if (session()->has('success') || session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-transition class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        ...
    </div>
@endif
```

#### 4. File: [product-detail.blade.php](file:///Applications/XAMPP/xamppfiles/htdocs/indoroster/resources/views/livewire/product-detail.blade.php)
Ada tiga bagian yang perlu diubah pada halaman detail produk:

* **Bagian Tombol Aksi (sekitar baris 312):**
  Ubah `@click="openOrderWa($wire.quantity)"` kembali ke fungsi Livewire asli:
  
  *Ganti tombol Keranjang menjadi:*
  ```html
  <button wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" class="w-full sm:flex-1 h-10 bg-white border-2 border-terra-500 text-terra-600 hover:bg-terra-50 text-sm font-bold rounded-md transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
      <svg wire:loading wire:target="addToCart" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
      <svg wire:loading.remove wire:target="addToCart" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
      <span wire:loading.remove wire:target="addToCart">Keranjang</span>
      <span wire:loading wire:target="addToCart">Memproses...</span>
  </button>
  ```
  
  *Ganti tombol Beli Sekarang menjadi:*
  ```html
  <button wire:click="buyNow" wire:loading.attr="disabled" wire:target="buyNow" class="w-full sm:flex-1 h-10 bg-terra-500 hover:bg-terra-600 text-white text-sm font-bold rounded-md shadow-md shadow-terra-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
      <svg wire:loading wire:target="buyNow" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
      <span wire:loading.remove wire:target="buyNow">Beli Sekarang</span>
      <span wire:loading wire:target="buyNow">Memproses...</span>
  </button>
  ```

* **Bagian Tag Script (sekitar baris 830):**
  Hapus seluruh blok fungsi `openOrderWa(qty)` dari dalam tag `<script>`:
  ```javascript
  // HAPUS FUNGSI INI:
  function openOrderWa(qty) {
      ...
  }
  ```

* **Bagian Modal WhatsApp (Paling bawah file):**
  Hapus seluruh blok HTML modal WhatsApp beserta `x-data` nya:
  ```html
  <!-- HAPUS SEBELUM PENUTUP DIV AKHIR: -->
  <!-- WhatsApp Order Modal component -->
  <div x-data="{ ... }" ...>
      ...
  </div>
  ```

---

## 💻 Tips Saat Migrasi ke Windows 11
Jika Anda memindahkan proyek ini ke Windows 11:
1. Pastikan Anda telah menginstal **Git for Windows** jika ingin menggunakan Opsi A (Git command).
2. Anda bisa menjalankan server lokal di Windows 11 menggunakan XAMPP untuk Windows atau dengan command:
   ```cmd
   php artisan serve
   ```
3. Konfigurasi database di `.env` (seperti `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`) harus disesuaikan dengan setelan database MySQL baru Anda di Windows 11.
