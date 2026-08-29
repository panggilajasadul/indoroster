<?php

namespace App\Livewire\Tools;

use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;

class RosterCalculator extends Component
{
    public $wall_width = 3.0;

    public $wall_height = 2.5;

    public $roster_dimension = '20x20x8'; // 20x20x8 cm, 20x20x10 cm, 15x30x10 cm

    public $include_waste = true;

    public function render()
    {
        $netArea = max(0, (float) $this->wall_width * (float) $this->wall_height);

        // Kebutuhan keping per m2 & estimasi berat per pcs
        if ($this->roster_dimension === '15x30x10') {
            $pcsPerM2 = 22.22; // 1 / (0.15 * 0.30)
            $weightPerPcs = 5.2;
            $dimensionLabel = '15 x 30 x 10 cm';
        } elseif ($this->roster_dimension === '20x20x10') {
            $pcsPerM2 = 25.0; // 1 / (0.20 * 0.20)
            $weightPerPcs = 3.8;
            $dimensionLabel = '20 x 20 x 10 cm';
        } else { // 20x20x8
            $pcsPerM2 = 25.0; // 1 / (0.20 * 0.20)
            $weightPerPcs = 3.2;
            $dimensionLabel = '20 x 20 x 8 cm';
        }

        $rawPcs = (int) ceil($netArea * $pcsPerM2);
        $wastePcs = $this->include_waste ? (int) ceil($rawPcs * 0.05) : 0;
        $totalPcs = $rawPcs + $wastePcs;

        // Estimasi berat total
        $estimatedWeightKg = round($totalPcs * $weightPerPcs, 1);

        $featuredProducts = Product::where('is_active', true)->with(['media', 'variants', 'category'])->take(6)->get();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $waText = "Halo Admin IndoRoster, saya baru saja menghitung kebutuhan roster di website:\n"
            ."- Ukuran Dinding: {$this->wall_width}m x {$this->wall_height}m (Luas: ".round($netArea, 2)." m2)\n"
            ."- Ukuran Roster: {$dimensionLabel}\n"
            ."- Estimasi Kebutuhan: {$totalPcs} pcs (termasuk cadangan pasang 5%)\n"
            ."- Estimasi Total Bobot: ~{$estimatedWeightKg} kg\n"
            .'Mohon info motif roster yang ready stock, total harga pabrik, dan estimasi ongkir armada ke lokasi saya. Terima kasih.';

        $waUrl = "https://wa.me/{$waNumber}?text=".urlencode($waText);

        $metaTitle = 'Kalkulator Kebutuhan Roster Beton Dinding | Hitung Akurat — IndoRoster';
        $metaDescription = 'Hitung estimasi kebutuhan jumlah keping roster beton per meter persegi (m2) secara akurat untuk dinding fasad, pagar, dan sekat partisi. Dilengkapi perhitungan safety waste.';
        $keywords = 'kalkulator roster, cara hitung kebutuhan roster, berapa roster per m2, hitung roster pagar, kebutuhan roster dinding, ukuran roster beton 20x20';

        return view('livewire.tools.roster-calculator', [
            'netArea' => $netArea,
            'rawPcs' => $rawPcs,
            'wastePcs' => $wastePcs,
            'totalPcs' => $totalPcs,
            'estimatedWeightKg' => $estimatedWeightKg,
            'featuredProducts' => $featuredProducts,
            'waUrl' => $waUrl,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('tools.calculator'),
        ]);
    }
}
