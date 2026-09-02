<?php

namespace Database\Seeders;

use App\Models\ExportPage;
use App\Services\ExportCountryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ExportPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/export_defaults.json');
        if (! File::exists($jsonPath)) {
            $jsonPath = base_path('scratch/export_defaults.json');
        }

        if (! File::exists($jsonPath)) {
            $this->command->error('File export_defaults.json tidak ditemukan.');

            return;
        }

        $countries = json_decode(File::get($jsonPath), true) ?? [];
        $total = count($countries);
        $this->command->info("Memulai Seeding {$total} Halaman Ekspor Negara Dinamis...");

        $seeded = 0;
        foreach ($countries as $slug => $data) {
            $countryName = $data['name'] ?? ucfirst($slug);
            $flagEmoji = $data['flag'] ?? '🌐';
            $region = $data['region'] ?? 'Asia';
            $destinationPort = $data['port_name'] ?? 'Designated International Port';
            $transitTime = $data['transit_time'] ?? '14 – 28 Days Sea Freight';

            // Generate dynamic page builder sections configuration
            $sectionsConfig = ExportCountryService::generateDefaultSectionsConfig($data);

            ExportPage::updateOrCreate(
                ['country_slug' => strtolower(trim($slug))],
                [
                    'country_name' => $countryName,
                    'flag_emoji' => $flagEmoji,
                    'region' => $region,
                    'destination_port' => $destinationPort,
                    'transit_time' => $transitTime,
                    'is_active' => true,
                    'meta_title' => $data['meta_title'] ?? ("Breeze Blocks & Architectural Screen Blocks Exporter to {$countryName} — IndoRoster"),
                    'meta_description' => $data['meta_description'] ?? ("Direct factory exporter of solid steel-mould architectural concrete breeze blocks to {$countryName}. Certified ocean freight to {$destinationPort}."),
                    'hero_headline' => $data['headline'] ?? ("Direct Factory Breeze Blocks Exporter for {$countryName}"),
                    'hero_subheadline' => $data['subheadline'] ?? null,
                    'hero_badge' => '🌐 Direct Factory Exporter • Global Ocean Freight',
                    'sections_config' => $sectionsConfig,
                ]
            );

            $seeded++;
        }

        $this->command->info("SELESAI! Sebanyak {$seeded} Halaman Ekspor Negara Dinamis telah tersimpan di database dengan Page Builder Sections lengkap.");
    }
}
