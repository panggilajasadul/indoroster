<x-filament-panels::page>
    <!-- Stats Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <!-- Card 1: Product Reviews -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-amber-500/10 rounded-full blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-500/10 text-amber-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.25.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 10.11c-.773-.56-.375-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ulasan Produk</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalReviews }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-50 dark:border-gray-800 pt-3">
                <span>Simulasi / Dummy:</span>
                <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $seededReviews }} ulasan</span>
            </div>
        </div>

        <!-- Card 2: Video Comments -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-blue-500/10 rounded-full blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-500/10 text-blue-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Komentar Video</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $videoComments }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-50 dark:border-gray-800 pt-3">
                <span>Total Komentar:</span>
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $totalComments }} total</span>
            </div>
        </div>

        <!-- Card 3: Photo Comments -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-purple-500/10 rounded-full blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-500/10 text-purple-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Komentar Foto</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $photoComments }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-50 dark:border-gray-800 pt-3">
                <span>Simulasi / Dummy:</span>
                <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $seededComments }} komentar</span>
            </div>
        </div>

        <!-- Card 4: Seeded Users -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-emerald-500/10 rounded-full blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">User Simulasi (Dummy)</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $seededUsers }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-50 dark:border-gray-800 pt-3">
                <span>Domain isolasi:</span>
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">@indoroster.com</span>
            </div>
        </div>

        <!-- Card 5: Seeded Likes -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02]">
            <div class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-rose-500/10 rounded-full blur-xl"></div>
            <div class="flex items-center gap-4">
                <div class="p-3 bg-rose-500/10 text-rose-500 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Like Media</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalLikes }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-50 dark:border-gray-800 pt-3">
                <span>Simulasi / Dummy:</span>
                <span class="font-semibold text-rose-600 dark:text-rose-400">{{ $seededLikes }} like</span>
            </div>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Left: Generation Tools -->
        <x-filament::section>
            <x-slot name="heading">
                Pembangkit Simulasi (Data Generator)
            </x-slot>
            <x-slot name="description">
                Gunakan tombol-tombol di bawah untuk membuat data simulasi secara massal di database.
            </x-slot>

            <div class="space-y-6 mt-4">
                <!-- Action 1: Product Reviews -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Simulasi Ulasan Produk</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md">Membuat ulasan produk fiktif secara acak dengan bintang 1-5, nama Indonesia asli, kota pembeli, serta isi ulasan yang realistis.</p>
                    </div>
                    <div class="flex items-center gap-3 self-start sm:self-center">
                        <div class="flex items-center rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-300 dark:border-gray-700 focus-within:ring-1 focus-within:ring-amber-500 focus-within:border-amber-500">
                            <span class="pl-3 pr-1 text-xs text-gray-500 dark:text-gray-400 select-none">Jumlah:</span>
                            <input 
                                type="number" 
                                wire:model="customReviewsCount" 
                                class="w-16 px-2 py-1.5 text-sm font-semibold rounded-r-lg border-0 bg-transparent text-gray-900 dark:text-gray-100 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                min="1"
                            />
                        </div>
                        <x-filament::button 
                            wire:click="generateReviews" 
                            wire:loading.attr="disabled"
                            icon="heroicon-m-sparkles" 
                            color="warning"
                        >
                            Generate Ulasan
                        </x-filament::button>
                    </div>
                </div>

                <!-- Action 2: Video Comments -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Simulasi Komentar Video (TikTok Style)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md">Membuat komentar bergaya TikTok di video inspirasi admin & review video. Berisi slang medsos, tanya ongkir, harga, dan testimoni.</p>
                    </div>
                    <div class="flex items-center gap-3 self-start sm:self-center">
                        <div class="flex items-center rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-300 dark:border-gray-700 focus-within:ring-1 focus-within:ring-primary-500 focus-within:border-primary-500">
                            <span class="pl-3 pr-1 text-xs text-gray-500 dark:text-gray-400 select-none">Jumlah:</span>
                            <input 
                                type="number" 
                                wire:model="customVideoCommentsCount" 
                                class="w-16 px-2 py-1.5 text-sm font-semibold rounded-r-lg border-0 bg-transparent text-gray-900 dark:text-gray-100 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                min="1"
                            />
                        </div>
                        <x-filament::button 
                            wire:click="generateVideoComments" 
                            wire:loading.attr="disabled"
                            icon="heroicon-m-play" 
                            color="primary"
                        >
                            Generate Video
                        </x-filament::button>
                    </div>
                </div>

                <!-- Action 3: Photo Comments -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Simulasi Komentar Foto (Instagram Style)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md">Membuat komentar bergaya Instagram di galeri foto admin & review foto. Berisi tanya spesifikasi, ukuran roster, dan pujian estetik.</p>
                    </div>
                    <div class="flex items-center gap-3 self-start sm:self-center">
                        <div class="flex items-center rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-300 dark:border-gray-700 focus-within:ring-1 focus-within:ring-info-500 focus-within:border-info-500">
                            <span class="pl-3 pr-1 text-xs text-gray-500 dark:text-gray-400 select-none">Jumlah:</span>
                            <input 
                                type="number" 
                                wire:model="customPhotoCommentsCount" 
                                class="w-16 px-2 py-1.5 text-sm font-semibold rounded-r-lg border-0 bg-transparent text-gray-900 dark:text-gray-100 focus:ring-0 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                min="1"
                            />
                        </div>
                        <x-filament::button 
                            wire:click="generatePhotoComments" 
                            wire:loading.attr="disabled"
                            icon="heroicon-m-camera" 
                            color="info"
                        >
                            Generate Foto
                        </x-filament::button>
                    </div>
                </div>

                <!-- Action 4: Random Likes -->
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 space-y-4">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Simulasi Kirim Like Acak (Massal)</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kirim like secara acak ke seluruh postingan video atau foto. Berguna untuk membuat interaksi sosial terlihat hidup.</p>
                    </div>
                    
                    <form wire:submit="submitLikeForm" class="flex flex-col sm:flex-row sm:items-end gap-4">
                        <div class="flex-grow">
                            {{ $this->likeForm }}
                        </div>
                        <x-filament::button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            icon="heroicon-m-heart" 
                            color="danger"
                            class="sm:h-9"
                        >
                            Kirim Like Acak
                        </x-filament::button>
                    </form>
                </div>
            </div>
        </x-filament::section>

        <!-- Right: Danger Zone & Instructions -->
        <div class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    Zona Bahaya (Danger Zone)
                </x-slot>
                <x-slot name="description">
                    Penghapusan data simulasi dari database.
                </x-slot>

                <div class="mt-4 p-4 rounded-lg bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/50 space-y-4">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Hapus Semua Data Simulasi</h4>
                        <p class="text-xs text-red-600 dark:text-red-400/80">Menghapus seluruh ulasan produk, komentar, dan user dummy yang bertanda 'is_seeded'. Aksi ini tidak akan mempengaruhi komentar atau ulasan riil dari pelanggan asli.</p>
                    </div>
                    <x-filament::button 
                        wire:click="clearSimulation" 
                        wire:loading.attr="disabled"
                        wire:confirm="Apakah Anda yakin ingin menghapus semua data simulasi (ulasan, komentar, user dummy)?"
                        icon="heroicon-m-trash" 
                        color="danger"
                    >
                        Bersihkan Semua Data Simulasi
                    </x-filament::button>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    Informasi & Petunjuk
                </x-slot>
                
                <div class="space-y-3 mt-2 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                    <p>💡 <strong>Isolasi Data Aman:</strong> Semua komentar dan ulasan yang digenerate oleh simulasi ini ditandai khusus dengan flag <code class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 rounded font-mono text-gray-800 dark:text-gray-200">is_seeded = true</code> di database. Sehingga tidak akan tercampur dengan data nyata.</p>
                    <p>👤 <strong>User Dummy:</strong> Akun-akun yang berkomentar dibuat secara otomatis menggunakan nama Indonesia dan email berakhiran <code class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 rounded font-mono text-gray-800 dark:text-gray-200">@indoroster.com</code> untuk mempermudah identifikasi.</p>
                    <p>💬 <strong>Gaya Komentar:</strong> Data komentar secara acak memilih template bahasa kasual, pertanyaan logistik (ongkir/lokasi), detail teknis (presisi/bahan), serta testimoni ("udah pake").</p>
                </div>
            </x-filament::section>
    </div>

    <!-- Targeted Generation Section -->
    <div class="mt-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Specific Product Reviews -->
            <x-filament::section>
                <x-slot name="heading">
                    Ulasan Produk Spesifik (Targeted)
                </x-slot>
                <x-slot name="description">
                    Tambahkan ulasan pada produk spesifik (termasuk yang baru diupload) dengan rating bintang dan jumlah ulasan tertentu.
                </x-slot>

                <form wire:submit="submitReviewForm" class="space-y-4 mt-4">
                    {{ $this->reviewForm }}
                    
                    <x-filament::button type="submit" color="warning" class="w-full mt-4" icon="heroicon-m-sparkles">
                        Kirim Ulasan Spesifik
                    </x-filament::button>
                </form>
            </x-filament::section>

            <!-- Card 2: Specific Video Comments -->
            <x-filament::section>
                <x-slot name="heading">
                    Komentar Video Spesifik (Targeted)
                </x-slot>
                <x-slot name="description">
                    Tambahkan komentar simulasi bergaya TikTok pada video galeri inspirasi tertentu.
                </x-slot>

                <form wire:submit="submitVideoForm" class="space-y-4 mt-4">
                    {{ $this->videoForm }}
                    
                    <x-filament::button type="submit" color="primary" class="w-full mt-4" icon="heroicon-m-play">
                        Kirim Komentar Video
                    </x-filament::button>
                </form>
            </x-filament::section>

            <!-- Card 3: Specific Photo Comments -->
            <x-filament::section>
                <x-slot name="heading">
                    Komentar Foto Spesifik (Targeted)
                </x-slot>
                <x-slot name="description">
                    Tambahkan komentar simulasi bergaya Instagram pada foto galeri tertentu.
                </x-slot>

                <form wire:submit="submitPhotoForm" class="space-y-4 mt-4">
                    {{ $this->photoForm }}
                    
                    <x-filament::button type="submit" color="info" class="w-full mt-4" icon="heroicon-m-camera">
                        Kirim Komentar Foto
                    </x-filament::button>
                </form>
            </x-filament::section>

            <!-- Card 4: Specific Media Likes -->
            <x-filament::section>
                <x-slot name="heading">
                    Like Media Spesifik (Targeted)
                </x-slot>
                <x-slot name="description">
                    Kirim like simulasi dari user dummy pada video atau foto galeri tertentu.
                </x-slot>

                <form wire:submit="submitSpecificLikeForm" class="space-y-4 mt-4">
                    {{ $this->specificLikeForm }}
                    
                    <x-filament::button type="submit" color="danger" class="w-full mt-4" icon="heroicon-m-heart">
                        Kirim Like Spesifik
                    </x-filament::button>
                </form>
            </x-filament::section>
        </div>
    </div>

    <!-- Table Section: Content Simulation (Views, Likes, Comments) -->
    <div class="mt-8">
        <x-filament::section>
            <x-slot name="heading">
                📊 Simulasi & Cek Konten (Video / Foto)
            </x-slot>
            <x-slot name="description">
                Pantau seluruh konten video dan foto galeri. Gunakan fitur filter untuk melihat tipe konten, menyaring tayangan di bawah 5.000, serta menyuntikkan jumlah tayangan, like, atau komentar secara langsung baik satuan maupun massal.
            </x-slot>

            <div class="mt-4">
                @livewire('content-simulation-table')
            </div>
        </x-filament::section>
    </div>

    <!-- Table Section: Recent Comments -->
    <div class="mt-8">
        <x-filament::section>
            <x-slot name="heading">
                Moderasi Komentar Terkini
            </x-slot>
            <x-slot name="description">
                Daftar semua komentar (asli maupun simulasi) diurutkan dari yang terbaru. Anda bisa menghapus langsung komentar yang tidak diinginkan.
            </x-slot>

            <div class="mt-4">
                {{ $this->table }}
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
