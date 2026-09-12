<?php

namespace App\Livewire\Promo;

use App\Models\Gallery;
use App\Models\Product;
use App\Models\PromoPage;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
class PromoRosterPabrik extends Component
{
    #[Url(as: 'kota')]
    public string $city = '';

    #[Url(as: 'utm_source')]
    public string $utmSource = 'facebook';

    #[Url(as: 'utm_campaign')]
    public string $utmCampaign = 'fb_ads_roster_pabrik';

    // Interactive Calculator properties
    public float $wallLength = 4.0;

    public float $wallHeight = 2.5;

    public int $wasteMargin = 0; // 0% cadangan potongan (Pas Sesuai Luas)

    public string $selectedSize = '20x20x10'; // default 20x20x10 cm

    public string $selectedMotif = '';

    // Customer Identity for frictionless consultation
    public string $customerName = '';

    public string $customerAddress = '';

    public ?string $pageSlug = null;

    public function mount(?string $city = null, ?string $kota = null, ?string $slug = null): void
    {
        $this->pageSlug = $slug;

        if (! empty($city)) {
            $this->city = $city;
        } elseif (! empty($kota)) {
            $this->city = $kota;
        } elseif (empty($this->city)) {
            $this->city = request()->query('kota', request()->query('city', 'Jabodetabek & Jawa Barat'));
        }
    }

    /**
     * Total luas dinding dalam meter persegi.
     */
    public function getWallAreaProperty(): float
    {
        $len = max(0.1, (float) $this->wallLength);
        $height = max(0.1, (float) $this->wallHeight);

        return round($len * $height, 2);
    }

    /**
     * Kebutuhan keping per m2 berdasarkan ukuran roster.
     */
    public function getPcsPerM2Property(): float
    {
        return match ($this->selectedSize) {
            '30x15x10' => 10000 / (30 * 15), // ~22.22 pcs/m2
            '25x15x10' => 10000 / (25 * 15), // ~26.67 pcs/m2
            '20x10x10' => 10000 / (20 * 10), // 50 pcs/m2
            default => 25.0, // 20x20x10 cm (10000 / 400 = 25 pcs/m2)
        };
    }

    /**
     * Label ukuran roster yang dipilih.
     */
    public function getSizeLabelProperty(): string
    {
        return match ($this->selectedSize) {
            '30x15x10' => '30 x 15 x 10 cm',
            '25x15x10' => '25 x 15 x 10 cm',
            '20x10x10' => '20 x 10 x 10 cm',
            default => '20 x 20 x 10 cm',
        };
    }

    /**
     * Estimasi kebutuhan keping roster akurat sesuai luas dan ukuran + cadangan potongan.
     */
    public function getEstimatedPcsProperty(): int
    {
        $area = $this->wallArea;
        $basePcs = $area * $this->pcsPerM2;
        $withMargin = $basePcs * (1 + ($this->wasteMargin / 100));

        return (int) max(1, ceil($withMargin));
    }

    /**
     * Ambil data model PromoPage aktif.
     */
    public function getPromoPageProperty(): ?PromoPage
    {
        $path = trim(request()->path(), '/');

        $slug = $this->pageSlug ?: match ($path) {
            'penawaran-proyek' => 'penawaran-proyek',
            'promo/roster-minimalis' => 'roster-minimalis',
            'promo/roster-pabrik' => 'roster-pabrik',
            'promo' => 'promo',
            default => 'roster-pabrik',
        };

        return PromoPage::query()->where('is_active', true)->where('slug', $slug)->first()
            ?? PromoPage::query()->where('is_active', true)->first();
    }

    /**
     * Generate dynamic WhatsApp Click-to-Chat URL.
     */
    public function getWhatsAppUrl(?string $motifName = null, ?int $customPcs = null, bool $fromCalculator = false): string
    {
        $promoPage = $this->promoPage;
        $rawWa = ! empty($promoPage?->whatsapp_number)
            ? $promoPage->whatsapp_number
            : SiteSetting::getValue('whatsapp_number', '0813-8970-9847');

        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        // Jika user mengisi lokasi di kalkulator, pakai inputan user.
        // Jika tidak diisi, berikan petunjuk titik-titik agar user mengetik lokasi/pemesan di WA.
        $locationStr = ! empty(trim($this->customerAddress))
            ? trim($this->customerAddress)
            : '..... (tulis kota / lokasi pengiriman Anda) .....';

        $nameGreeting = ! empty(trim($this->customerName))
            ? 'saya *'.trim($this->customerName).'*'
            : 'saya';

        $motif = $motifName ?: (! empty($this->selectedMotif) ? $this->selectedMotif : 'Motif Pilihan Pabrik');

        if ($fromCalculator) {
            $message = "Halo Tim IndoRoster, {$nameGreeting} sudah hitung di kalkulator website untuk ukuran dinding {$this->wallLength}m x {$this->wallHeight}m (Luas: {$this->wallArea} m²).\n\n".
                "• Pilihan Ukuran: *{$this->sizeLabel}*\n".
                "• Estimasi Kebutuhan: *{$this->estimatedPcs} pcs* (termasuk cadangan {$this->wasteMargin}%)\n".
                "• Lokasi / Alamat Kirim: {$locationStr}\n\n".
                'Mohon info total harga pabrik dan estimasi jadwal pengiriman armadanya. Terima kasih.';
        } else {
            $pcsStr = $customPcs ? "*{$customPcs} pcs*" : '..... (tulis jumlah pcs / luas dinding) .....';

            $message = "Halo Tim IndoRoster, {$nameGreeting} ingin minta penawaran harga pabrik & pricelist roster (min. 100 pcs):\n\n".
                "• Estimasi Kebutuhan: {$pcsStr}\n".
                "• Pilihan Motif: *{$motif}*\n".
                "• Lokasi / Alamat Kirim: {$locationStr}\n\n".
                'Mohon info katalog lengkap dan promo gratis ongkirnya. Terima kasih.';
        }

        return 'https://wa.me/'.$waNumber.'?text='.rawurlencode($message);
    }

    public function render()
    {
        $promoPage = $this->promoPage;
        $sections = $promoPage?->sections ?? [];

        // 1. Ambil produk motif roster terpopuler / rekomendasi admin
        $productIds = $sections['catalog']['product_ids'] ?? [];
        $productLimit = (int) ($sections['catalog']['product_limit'] ?? 8);

        if (! empty($productIds)) {
            $featuredProducts = Product::query()
                ->with(['media'])
                ->whereIn('id', $productIds)
                ->where('is_active', true)
                ->get();
        } else {
            $featuredProducts = Product::query()
                ->with(['media'])
                ->where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('total_sold', 'desc')
                ->take($productLimit)
                ->get();
        }

        // Hero visual image dari section / fallback featured product / gallery
        $heroImageUpload = $sections['hero']['image_upload'] ?? null;
        $heroImageUrl = $heroImageUpload ? asset('storage/'.$heroImageUpload) : ($sections['hero']['image_url'] ?? null);

        if (! $heroImageUrl) {
            $heroProduct = $featuredProducts->first(function ($p) {
                return ! empty($p->primary_image) || ($p->media && $p->media->isNotEmpty());
            });
            $heroImageUrl = $heroProduct ? ($heroProduct->primary_image ?: $heroProduct->media->first()?->media_url) : null;
        }

        // Hero visual video
        $heroVideoUpload = $sections['hero']['video_upload'] ?? null;
        $heroVideoUrl = $heroVideoUpload ? asset('storage/'.$heroVideoUpload) : ($sections['hero']['video_url'] ?? null);

        // 2. Ambil galeri dokumentasi pemasangan real (pilihan admin atau otomatis)
        $galleryIds = $sections['gallery']['gallery_ids'] ?? [];
        if (! empty($galleryIds)) {
            $installationGalleries = Gallery::query()
                ->with(['media', 'product.media'])
                ->whereIn('id', $galleryIds)
                ->where('is_active', true)
                ->get();
        } else {
            $installationGalleries = Gallery::query()
                ->with(['media', 'product.media'])
                ->where('is_active', true)
                ->whereHas('media')
                ->inRandomOrder()
                ->take(6)
                ->get();

            if ($installationGalleries->isEmpty()) {
                $installationGalleries = Gallery::query()
                    ->with(['media', 'product.media'])
                    ->where('is_active', true)
                    ->take(6)
                    ->get();
            }
        }

        if (! $heroImageUrl && $installationGalleries->isNotEmpty()) {
            $heroImageUrl = $installationGalleries->first()->primary_image;
        }

        $defaultCityName = $promoPage?->default_city ?: 'Jabodetabek & Jawa Barat';
        $cityName = ! empty($this->city) ? $this->city : $defaultCityName;

        $showNavbar = filter_var($promoPage?->getSection('show_navbar', false), FILTER_VALIDATE_BOOLEAN);

        $metaTitle = $promoPage?->meta_title ?: "IndoRoster — Pusat Roster Beton Minimalis — Penawaran Khusus Kirim ke {$cityName}";
        $metaDescription = $promoPage?->meta_description ?: "IndoRoster — Pusat Roster Beton Minimalis kualitas padat dan presisi. Melayani pesanan mulai 100 pcs hingga proyek besar ke {$cityName}.";
        $robots = $promoPage?->robots ?: 'noindex, follow';

        return view('livewire.promo.promo-roster-pabrik', [
            'promoPage' => $promoPage,
            'sections' => $sections,
            'cityName' => $cityName,
            'showNavbar' => $showNavbar,
            'featuredProducts' => $featuredProducts,
            'heroImageUrl' => $heroImageUrl,
            'heroVideoUrl' => $heroVideoUrl,
            'installationGalleries' => $installationGalleries,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'robots' => $robots,
            'showNavbar' => $showNavbar,
        ]);
    }
}
