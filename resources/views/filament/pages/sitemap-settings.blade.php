<x-filament-panels::page>
    @php
        $stats = $this->getSitemapStats();
    @endphp

    <div class="space-y-6">
        {{-- Status Bar & Quick Actions --}}
        <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <div class="w-3.5 h-3.5 rounded-full {{ $stats['exists'] ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            Status File Sitemap XML: 
                            <span class="{{ $stats['exists'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600' }}">
                                {{ $stats['exists'] ? 'Aktif & Siap Diindeks' : 'Belum Dibuat' }}
                            </span>
                        </h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        File sitemap publik berada di <code class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-primary-600 dark:text-primary-400 rounded-md font-mono text-xs">{{ $stats['sitemap_url'] }}</code>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button 
                        type="button"
                        wire:click="generateSitemap"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 shadow-md shadow-orange-500/20 active:scale-95 transition-all duration-150 disabled:opacity-50 cursor-pointer">
                        <x-heroicon-o-arrow-path wire:loading.class="animate-spin" class="w-4 h-4" />
                        <span wire:loading.remove>Generate / Perbarui Sekarang</span>
                        <span wire:loading>Memproses Sitemap...</span>
                    </button>

                    <a 
                        href="{{ $stats['sitemap_url'] }}" 
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-500" />
                        Lihat XML
                    </a>
                </div>
            </div>

            {{-- Auto-Sync Feature Callout --}}
            <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-800 flex items-start gap-3 text-xs text-gray-600 dark:text-gray-400 bg-amber-50/50 dark:bg-amber-950/20 p-3.5 rounded-xl border-amber-200/50 dark:border-amber-900/40">
                <x-heroicon-m-bolt class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                <div>
                    <strong class="font-semibold text-gray-900 dark:text-gray-200">Sistem Otomatisasi (Real-time Live Sync):</strong>
                    Sistem IndoRoster secara otomatis men-generate ulang sitemap saat Anda <strong>menambah</strong>, <strong>mengubah</strong>, atau <strong>menghapus</strong> data Produk, Kategori, Galeri, dan Artikel. Tombol manual di atas dapat digunakan jika Anda ingin memaksa pembaruan seketika (*force rebuild*).
                </div>
            </div>
        </div>

        {{-- Metrics Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Card 1: Total URL --}}
            <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <x-heroicon-o-link class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stats['url_count']) }}
                    </div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Total Halaman (URL) di XML
                    </div>
                </div>
            </div>

            {{-- Card 2: Terakhir Diperbarui --}}
            <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-base font-bold text-gray-900 dark:text-white leading-tight">
                        {{ $stats['last_modified_relative'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" title="{{ $stats['last_modified_formatted'] }}">
                        {{ $stats['last_modified'] ? $stats['last_modified']->format('H:i') . ' WIB' : '-' }}
                    </div>
                </div>
            </div>

            {{-- Card 3: Google Images Media --}}
            <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <x-heroicon-o-photo class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($stats['image_count']) }}
                    </div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Foto Produk & Galeri (SEO Image)
                    </div>
                </div>
            </div>

            {{-- Card 4: Ukuran File --}}
            <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <x-heroicon-o-document-text class="w-6 h-6" />
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $stats['file_size'] }} <span class="text-xs font-normal text-gray-500">KB</span>
                    </div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Ukuran File sitemap.xml
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Breakdown & Search Engine Guidance --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Content Breakdown Table --}}
            <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5 text-primary-500" />
                    Struktur & Cakupan Konten Sitemap
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Berikut adalah data live yang otomatis disinkronisasi ke dalam skema sitemap Spatie dengan standar Google Webmaster:
                </p>

                <div class="divide-y divide-gray-100 dark:divide-gray-800 border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden text-sm">
                    <div class="flex items-center justify-between p-3.5 bg-gray-50/50 dark:bg-gray-800/30">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-o-cube class="w-4 h-4 text-orange-500" />
                            <span class="font-medium text-gray-700 dark:text-gray-300">Katalog Produk Aktif</span>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-700 dark:bg-orange-950/60 dark:text-orange-300">
                            {{ $stats['active_products'] }} Produk (Prioritas 0.85)
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-white dark:bg-gray-900">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-o-tag class="w-4 h-4 text-blue-500" />
                            <span class="font-medium text-gray-700 dark:text-gray-300">Kategori Produk</span>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                            {{ $stats['active_categories'] }} Kategori (Prioritas 0.80)
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-gray-50/50 dark:bg-gray-800/30">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-o-map-pin class="w-4 h-4 text-rose-500" />
                            <span class="font-medium text-gray-700 dark:text-gray-300">Halaman SEO Lokasi & Kawasan</span>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                            {{ $stats['active_seo_locations'] }} Halaman Lokasi (Prioritas 0.80)
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-white dark:bg-gray-900">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-o-newspaper class="w-4 h-4 text-emerald-500" />
                            <span class="font-medium text-gray-700 dark:text-gray-300">Artikel & Blog Edukasi</span>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                            {{ $stats['published_articles'] }} Artikel (Prioritas 0.75)
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-gray-50/50 dark:bg-gray-800/30">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-o-photo class="w-4 h-4 text-purple-500" />
                            <span class="font-medium text-gray-700 dark:text-gray-300">Galeri Proyek & Video Inspirasi</span>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300">
                            {{ $stats['active_galleries'] }} Proyek (Prioritas 0.80)
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-white dark:bg-gray-900">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-o-document-duplicate class="w-4 h-4 text-gray-500" />
                            <span class="font-medium text-gray-700 dark:text-gray-300">Halaman Statis & B2B Portal</span>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            Homepage, Katalog, B2B Kontraktor/Developer/Arsitek, Kontak
                        </span>
                    </div>

                    {{-- Total Summary Row --}}
                    <div class="flex items-center justify-between p-3.5 bg-primary-50/60 dark:bg-primary-950/20 border-t-2 border-primary-200 dark:border-primary-900">
                        <div class="flex items-center gap-2.5">
                            <x-heroicon-m-check-badge class="w-4 h-4 text-primary-600 dark:text-primary-400" />
                            <span class="font-bold text-primary-700 dark:text-primary-300">Total URL di Sitemap.xml (Aktual)</span>
                        </div>
                        <span class="px-3 py-1 text-xs font-black rounded-full bg-primary-600 text-white shadow">
                            {{ number_format($stats['url_count']) }} URL Terindeks
                        </span>
                    </div>
                </div>
            </div>

            {{-- Search Engine Submission Guide --}}
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-emerald-500" />
                    Indeks Mesin Pencari
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Pastikan URL sitemap berikut didaftarkan ke Webmaster Console agar bot mesin pencari segera mengindeks halaman baru:
                </p>

                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 font-mono text-xs text-gray-800 dark:text-gray-200 break-all select-all">
                    {{ $stats['sitemap_url'] }}
                </div>

                <div class="pt-2 space-y-2.5">
                    <a 
                        href="https://search.google.com/search-console" 
                        target="_blank"
                        class="flex items-center justify-between p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 hover:bg-orange-50/30 dark:hover:bg-orange-950/20 text-xs font-semibold text-gray-800 dark:text-gray-200 transition">
                        <span class="flex items-center gap-2">
                            <span>🔍 Google Search Console</span>
                        </span>
                        <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-400" />
                    </a>

                    <a 
                        href="https://www.bing.com/webmasters" 
                        target="_blank"
                        class="flex items-center justify-between p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50/30 dark:hover:bg-blue-950/20 text-xs font-semibold text-gray-800 dark:text-gray-200 transition">
                        <span class="flex items-center gap-2">
                            <span>🌐 Bing Webmaster Tools</span>
                        </span>
                        <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-400" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
