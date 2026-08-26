<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Http\Controllers\SitemapController;
use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Produk Baru'),

            Actions\ActionGroup::make([
                Actions\Action::make('export_csv')
                    ->label('Export Data Produk (CSV)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn () => $this->exportProductsCsv()),

                Actions\Action::make('download_template')
                    ->label('Download Template CSV')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->action(fn () => $this->downloadTemplateCsv()),

                Actions\Action::make('import_csv')
                    ->label('Import Produk dari CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('Pilih File CSV')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel', 'text/comma-separated-values'])
                            ->disk('local')
                            ->directory('temp-imports')
                            ->required()
                            ->helperText('Unggah file .csv dengan format kolom sesuai template. Kolom nama produk, ukuran, dan harga varian akan diproses otomatis.'),
                    ])
                    ->modalHeading('Import Data Produk & Varian (CSV)')
                    ->modalDescription('Unggah file CSV untuk menambahkan produk baru secara massal. Sistem akan otomatis membuat varian Abu-Abu, Dolomit (Putih), dan Terracota (Merah) beserta optimasi SEO.')
                    ->modalSubmitActionLabel('Proses Import Sekarang')
                    ->action(function (array $data) {
                        $this->importProductsCsv($data['csv_file']);
                    }),
            ])
                ->label('Opsi CSV')
                ->icon('heroicon-o-table-cells')
                ->color('info')
                ->button(),

            Actions\Action::make('regenerate_sitemap')
                ->label('Perbarui Sitemap XML')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Perbarui File Sitemap.xml')
                ->modalDescription('Apakah Anda ingin men-generate ulang file sitemap.xml dengan daftar produk dan gambar terkini?')
                ->modalSubmitActionLabel('Perbarui Sekarang')
                ->action(function () {
                    try {
                        SitemapController::generate();
                        Notification::make()
                            ->title('Sitemap Berhasil Diperbarui')
                            ->body('Seluruh URL produk, kategori, dan gambar telah disinkronisasi ke sitemap.xml.')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal Memperbarui Sitemap')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    /**
     * Download template CSV kosong dengan contoh isian.
     */
    protected function downloadTemplateCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_produk_indoroster.csv"',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM agar Excel membukanya dengan encoding yang benar
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'nama_produk',
                'kategori',
                'sku',
                'tipe_motif',
                'ukuran',
                'berat_kg',
                'min_order',
                'harga_abu',
                'stok_abu',
                'harga_putih_dolomit',
                'stok_putih_dolomit',
                'harga_merah_terakota',
                'stok_merah_terakota',
                'focus_keyword',
                'meta_title',
                'meta_description',
            ]);

            // Baris contoh 1
            fputcsv($handle, [
                'Roster Beton Motif Melati (Satu Sisi)',
                'Roster Beton',
                'IR-MOTIF-001',
                'Satu Sisi',
                '20 x 20 x 10 cm',
                '3.5',
                '1',
                '10000',
                '1000',
                '11500',
                '1000',
                '11500',
                '1000',
                'roster beton motif melati',
                'Jual Roster Beton Motif Melati 20x20 | IndoRoster',
                'Pabrik roster motif melati 20x20x10 cm. Varian abu, putih dolomit, dan terakota kualitas presisi.',
            ]);

            // Baris contoh 2
            fputcsv($handle, [
                'Roster Beton Motif Petir (Dua Sisi)',
                'Roster Beton',
                'IR-MOTIF-006',
                'Dua Sisi',
                '20 x 20 x 10 cm',
                '3.5',
                '1',
                '11000',
                '1000',
                '12000',
                '1000',
                '12000',
                '1000',
                'roster beton motif petir 2 muka',
                'Jual Roster Beton Motif Petir Dua Sisi 20x20 | IndoRoster',
                'Pabrik roster beton motif petir dua muka 20x20 cm. Cocok untuk fasad & pagar minimalis.',
            ]);

            fclose($handle);
        }, 'template_import_produk_indoroster.csv', $headers);
    }

    /**
     * Export seluruh data produk dan variannya ke CSV.
     */
    protected function exportProductsCsv(): StreamedResponse
    {
        $fileName = 'data_produk_indoroster_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'ID',
                'Nama Produk',
                'Kategori',
                'SKU',
                'Ukuran (Dimensi)',
                'Berat (kg)',
                'Min Order',
                'Harga Abu (Rp)',
                'Stok Abu',
                'Harga Dolomit (Rp)',
                'Stok Dolomit',
                'Harga Terracota (Rp)',
                'Stok Terracota',
                'Focus Keyword',
                'Meta Title',
                'Meta Description',
                'Status Aktif',
                'Featured',
            ]);

            $materialAbu = Material::where('slug', 'abu-abu')->first();
            $materialDolomit = Material::where('slug', 'dolamit')->orWhere('slug', 'dolomit')->first();
            $materialTerracota = Material::where('slug', 'terracota')->orWhere('slug', 'terakota')->first();

            $products = Product::with(['category', 'variants'])->orderBy('id', 'asc')->get();

            foreach ($products as $product) {
                $varAbu = $materialAbu ? $product->variants->firstWhere('material_id', $materialAbu->id) : null;
                $varDolomit = $materialDolomit ? $product->variants->firstWhere('material_id', $materialDolomit->id) : null;
                $varTerracota = $materialTerracota ? $product->variants->firstWhere('material_id', $materialTerracota->id) : null;

                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->category?->name ?? 'Roster Beton',
                    $product->sku,
                    $product->dimensions,
                    $product->weight,
                    $product->min_order,
                    $varAbu ? $varAbu->price_adjustment : ($product->price ?? 0),
                    $varAbu ? $varAbu->stock : ($product->stock ?? 0),
                    $varDolomit ? $varDolomit->price_adjustment : 0,
                    $varDolomit ? $varDolomit->stock : 0,
                    $varTerracota ? $varTerracota->price_adjustment : 0,
                    $varTerracota ? $varTerracota->stock : 0,
                    $product->focus_keyword,
                    $product->meta_title,
                    $product->meta_description,
                    $product->is_active ? 'Ya' : 'Tidak',
                    $product->is_featured ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($handle);
        }, $fileName, $headers);
    }

    /**
     * Import file CSV yang diunggah dan proses ke database.
     */
    protected function importProductsCsv(string $filePath): void
    {
        $fullPath = Storage::disk('local')->path($filePath);

        if (! file_exists($fullPath)) {
            Notification::make()
                ->title('File tidak ditemukan')
                ->body('Terjadi kesalahan saat mengunggah file CSV.')
                ->danger()
                ->send();

            return;
        }

        $content = file_get_contents($fullPath);
        // Hapus UTF-8 BOM jika ada
        $bom = pack('H*', 'EFBBBF');
        $content = preg_replace("/^{$bom}/", '', $content);

        // Deteksi delimiter (koma atau titik koma)
        $firstLine = strtok($content, "\r\n");
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        $lines = str_getcsv($content, "\n");
        if (empty($lines)) {
            Notification::make()->title('File CSV Kosong')->warning()->send();

            return;
        }

        $headerRow = str_getcsv(array_shift($lines), $delimiter);
        $headerMap = [];
        foreach ($headerRow as $idx => $headerName) {
            $cleanName = strtolower(trim(str_replace(['"', "'", ' '], ['', '', '_'], $headerName)));
            $headerMap[$cleanName] = $idx;
        }

        // Helper untuk mencari index kolom berdasarkan alias yang fleksibel
        $getColumnIndex = function (array $aliases) use ($headerMap): ?int {
            foreach ($aliases as $alias) {
                $cleanAlias = strtolower(str_replace([' ', '-'], '_', $alias));
                if (isset($headerMap[$cleanAlias])) {
                    return $headerMap[$cleanAlias];
                }
            }

            return null;
        };

        $idxName = $getColumnIndex(['nama_produk', 'nama_roster', 'nama', 'name', 'motif']);
        $idxType = $getColumnIndex(['tipe_motif', 'motif_(tipe)', 'tipe', 'type', 'sisi']);
        $idxDims = $getColumnIndex(['ukuran', 'ukuran_(cm)', 'dimensi', 'dimensions', 'dims', 'size']);
        $idxWhite = $getColumnIndex(['harga_putih', 'harga_putih_(rp)', 'harga_dolomit', 'harga_putih_dolomit', 'white', 'dolomit', 'dolamit']);
        $idxRed = $getColumnIndex(['harga_merah', 'harga_merah_(rp)', 'harga_terakota', 'harga_merah_terakota', 'harga_terracota', 'red', 'terracota', 'terakota']);
        $idxGrey = $getColumnIndex(['harga_abu', 'harga_abu_(rp)', 'harga_abu_abu', 'grey', 'gray', 'abu', 'abu_abu']);
        $idxSku = $getColumnIndex(['sku', 'kode', 'kode_produk']);
        $idxCategory = $getColumnIndex(['kategori', 'category']);
        $idxWeight = $getColumnIndex(['berat', 'berat_kg', 'weight']);
        $idxKeyword = $getColumnIndex(['focus_keyword', 'kata_kunci', 'keyword']);
        $idxMetaTitle = $getColumnIndex(['meta_title', 'seo_title']);
        $idxMetaDesc = $getColumnIndex(['meta_description', 'seo_desc']);

        if ($idxName === null) {
            Notification::make()
                ->title('Format CSV Tidak Sesuai')
                ->body('Kolom Nama Produk / Nama Roster tidak ditemukan dalam file CSV.')
                ->danger()
                ->send();

            return;
        }

        // Pastikan Kategori & Material siap
        $category = Category::firstOrCreate(
            ['slug' => 'roster-beton'],
            ['name' => 'Roster Beton', 'is_active' => true, 'sort_order' => 1]
        );

        $materialAbu = Material::firstOrCreate(
            ['slug' => 'abu-abu'],
            ['name' => 'Abu-Abu', 'description' => 'Beton warna abu-abu natural mutu K-200']
        );

        $materialDolomit = Material::firstOrCreate(
            ['slug' => 'dolamit'],
            ['name' => 'Dolomit', 'description' => 'Beton putih semen dolomit halus elegan']
        );

        $materialTerracota = Material::firstOrCreate(
            ['slug' => 'terracota'],
            ['name' => 'Terracota', 'description' => 'Beton merah terakota klasik hangat']
        );

        $defaultDescription = '<h3>📝 PANDUAN PEMESANAN & LAYANAN KONSUMEN</h3>'
            .'<p>Di Indoroster, belanja roster jadi jauh lebih praktis. <strong>Anda tidak perlu login atau daftar akun</strong> untuk melakukan pemesanan. Cukup pilih, bayar, dan tunggu barang sampai!</p>'
            .'<h4>1. Cara Pemesanan (Tanpa Login)</h4>'
            .'<ul>'
            .'<li><strong>Pilih & Hitung:</strong> Gunakan kalkulator di atas untuk tahu jumlah yang dibutuhkan.</li>'
            .'<li><strong>Beli Langsung:</strong> Masukkan jumlah pcs dan klik Beli Sekarang.</li>'
            .'<li><strong>Isi Data:</strong> Langsung isi nama dan alamat pengiriman tanpa harus daftar akun.</li>'
            .'<li><strong>Terima Invoice:</strong> Setelah pembayaran berhasil, Anda akan langsung menerima Invoice Resmi sebagai bukti transaksi yang sah.</li>'
            .'</ul>'
            .'<h4>2. Informasi yang Akan Kami Kirimkan ke Anda</h4>'
            .'<p>Setelah Anda melakukan pemesanan, tim Admin kami akan menghubungi Anda melalui <strong>WhatsApp</strong> untuk memberikan informasi verifikasi alamat, konfirmasi pengiriman, dan info driver.</p>'
            .'<p>🛡️ <strong>Jaminan Kami:</strong> Garansi penggantian barang rusak selama pengiriman aman 100%.</p>';

        $countSuccess = 0;

        foreach ($lines as $rowLine) {
            if (trim($rowLine) === '') {
                continue;
            }

            $cols = str_getcsv($rowLine, $delimiter);
            $rawName = trim($cols[$idxName] ?? '');
            if (empty($rawName)) {
                continue;
            }

            $rawType = $idxType !== null ? trim($cols[$idxType] ?? '') : '';
            $rawDims = $idxDims !== null ? trim($cols[$idxDims] ?? '') : '20 x 20 x 10';
            if (! str_contains(strtolower($rawDims), 'cm')) {
                $rawDims .= ' cm';
            }

            $priceGrey = $idxGrey !== null ? (float) preg_replace('/[^\d]/', '', $cols[$idxGrey] ?? '') : 10000;
            $priceWhite = $idxWhite !== null ? (float) preg_replace('/[^\d]/', '', $cols[$idxWhite] ?? '') : 11500;
            $priceRed = $idxRed !== null ? (float) preg_replace('/[^\d]/', '', $cols[$idxRed] ?? '') : 11500;

            if ($priceGrey <= 0) {
                $priceGrey = 10000;
            }
            if ($priceWhite <= 0) {
                $priceWhite = $priceGrey + 1000;
            }
            if ($priceRed <= 0) {
                $priceRed = $priceWhite;
            }

            $productTitle = str_starts_with(strtolower($rawName), 'roster')
                ? $rawName
                : 'Roster Beton Minimalis Motif '.$rawName;

            if (! empty($rawType) && ! str_contains(strtolower($productTitle), strtolower($rawType))) {
                $productTitle .= ' ('.$rawType.')';
            }

            if (! str_contains(strtolower($productTitle), strtolower($rawDims))) {
                $productTitle .= ' '.$rawDims;
            }

            $slug = Str::slug($productTitle);
            $sku = $idxSku !== null && ! empty(trim($cols[$idxSku] ?? ''))
                ? trim($cols[$idxSku])
                : 'IR-'.strtoupper(Str::random(6));

            $weight = $idxWeight !== null && ! empty($cols[$idxWeight])
                ? (float) $cols[$idxWeight]
                : (str_contains($rawDims, '30 x 15') ? 4.2 : 3.5);

            // SEO Metadata Otomatis
            $focusKw = $idxKeyword !== null && ! empty(trim($cols[$idxKeyword] ?? ''))
                ? trim($cols[$idxKeyword])
                : 'roster beton minimalis motif '.strtolower($rawName).' '.strtolower($rawType);

            $metaTitle = $idxMetaTitle !== null && ! empty(trim($cols[$idxMetaTitle] ?? ''))
                ? trim($cols[$idxMetaTitle])
                : 'Jual '.$productTitle.' Murah | IndoRoster';

            if (mb_strlen($metaTitle) > 65) {
                $metaTitle = $productTitle.' | IndoRoster';
            }

            $metaDesc = $idxMetaDesc !== null && ! empty(trim($cols[$idxMetaDesc] ?? ''))
                ? trim($cols[$idxMetaDesc])
                : 'Pabrik '.$productTitle.'. Tersedia varian Abu-Abu, Putih Dolomit, dan Terracota kualitas cetak padat presisi. Garansi kirim aman se-Jabodetabek.';

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $productTitle,
                    'sku' => $sku,
                    'description' => $defaultDescription,
                    'short_description' => 'Roster beton minimalis arsitektural motif '.$rawName.' ukuran '.$rawDims.'. Kokoh, padat, presisi, dan siap mempercantik fasad bangunan.',
                    'dimensions' => $rawDims,
                    'weight' => $weight,
                    'price' => 0,
                    'original_price' => $priceWhite + 2000,
                    'min_order' => 1,
                    'stock' => 85000,
                    'is_active' => true,
                    'best_for' => 'Fasad Rumah, Pagar Minimalis, Dinding Ventilasi, Sekat Partisi',
                    'focus_keyword' => $focusKw,
                    'secondary_keywords' => ['loster motif '.strtolower($rawName), 'roster minimalis modern', 'roster ventilasi rumah'],
                    'seo_h1' => 'Jual '.$productTitle.' Harga Pabrik Langsung',
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDesc,
                    'og_title' => $metaTitle,
                    'og_description' => $metaDesc,
                    'seo_score' => 98,
                    'opportunity_score' => 95,
                    'seo_issues' => [],
                    'seo_last_analyzed' => now(),
                ]
            );

            // Varian Abu-Abu
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'material_id' => $materialAbu->id],
                ['name' => 'Abu-Abu', 'price_adjustment' => $priceGrey, 'stock' => 85000, 'weight' => $weight, 'is_active' => true]
            );

            // Varian Dolomit
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'material_id' => $materialDolomit->id],
                ['name' => 'Dolomit', 'price_adjustment' => $priceWhite, 'stock' => 85000, 'weight' => $weight, 'is_active' => true]
            );

            // Varian Terracota
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'material_id' => $materialTerracota->id],
                ['name' => 'Terracota', 'price_adjustment' => $priceRed, 'stock' => 85000, 'weight' => $weight, 'is_active' => true]
            );

            $countSuccess++;
        }

        // Hapus file temporary
        Storage::disk('local')->delete($filePath);

        Notification::make()
            ->title('Import CSV Berhasil!')
            ->body("Sebanyak {$countSuccess} produk dan variannya berhasil diimpor ke dalam katalog.")
            ->success()
            ->send();
    }
}
