<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Main Description -->
        <x-filament::section>
            <x-slot name="heading">
                ⚙️ Kontrol Simulasi & Penjualan Produk
            </x-slot>
            <x-slot name="description">
                Gunakan halaman ini untuk memantau produk yang baru diunggah, memfilter produk dengan tingkat penjualan rendah (di bawah 5.000 terjual), dan menyuntikkan jumlah penjualan fiktif (baik setel ulang secara langsung atau menambahkan ke jumlah terjual saat ini).
            </x-slot>
            
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex flex-col gap-1 border-t border-gray-100 dark:border-gray-800 pt-3">
                <p>💡 <strong>Petunjuk Penggunaan:</strong></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Gunakan tombol toggle filter <strong>"Terjual < 5.000"</strong> untuk menyaring produk dengan penjualan rendah.</li>
                    <li>Gunakan filter <strong>"Produk Baru (30 Hari Terakhir)"</strong> untuk memantau produk yang baru saja diupload.</li>
                    <li>Klik aksi <span class="text-emerald-600 font-semibold">Suntik Terjual</span> pada baris produk untuk merubah atau menambah data unit terjual.</li>
                    <li>Klik aksi <span class="text-amber-600 font-semibold">Ulasan Baru</span> untuk mengisi ulasan simulasi acak / bertarget bintang untuk produk terkait.</li>
                    <li>Anda juga dapat melakukan suntik penjualan secara massal dengan mencentang beberapa produk sekaligus dan menekan tombol <span class="font-bold">Suntik Terjual Massal</span> di bawah tabel.</li>
                </ul>
            </div>
        </x-filament::section>

        <!-- Product Table -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl p-4 shadow-sm">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
