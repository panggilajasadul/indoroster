<?php

namespace App\Services;

use App\Models\ExportPage;
use Illuminate\Support\Facades\File;

class ExportCountryService
{
    protected static ?array $cachedDefaults = null;

    /**
     * Get all 110 default country configurations from JSON.
     */
    public static function getDefaultConfigs(): array
    {
        if (static::$cachedDefaults !== null) {
            return static::$cachedDefaults;
        }

        $jsonPath = database_path('data/export_defaults.json');
        if (! File::exists($jsonPath)) {
            $jsonPath = base_path('scratch/export_defaults.json');
        }

        if (File::exists($jsonPath)) {
            static::$cachedDefaults = json_decode(File::get($jsonPath), true) ?? [];
        } else {
            static::$cachedDefaults = [];
        }

        return static::$cachedDefaults;
    }

    /**
     * Get default config for a specific country slug.
     */
    public static function getDefaultConfig(string $slug): ?array
    {
        $configs = static::getDefaultConfigs();

        return $configs[strtolower(trim($slug))] ?? null;
    }

    /**
     * Generate standard dynamic builder blocks array for a given country.
     */
    public static function generateDefaultSectionsConfig(array $countryData): array
    {
        $countryName = $countryData['name'] ?? 'International';
        $portName = $countryData['port_name'] ?? 'Designated International Container Port';
        $transitTime = $countryData['transit_time'] ?? '14 – 28 Days Sea Freight';

        return [
            [
                'type' => 'hero_banner',
                'data' => [
                    'bg_theme' => 'dark_charcoal',
                    'badge' => '🌐 Direct Factory Exporter • Global Ocean Freight',
                    'headline' => $countryData['headline'] ?? ('Architectural Breeze Blocks & Ventilation Roster — Direct Factory Exporter for '.$countryName),
                    'subheadline' => $countryData['subheadline'] ?? ('High-density solid steel-mould architectural breeze blocks crafted from pure mountain stone ash in Plered, Purwakarta. Delivering passive solar shading, 90° razor-sharp alignment, and certified ocean freight to '.$portName.'.'),
                    'show_whatsapp_btn' => true,
                    'whatsapp_text' => 'WhatsApp Export Desk (+62 813-8970-9847)',
                    'show_gallery_btn' => true,
                    'show_pdf_btn' => true,
                ],
            ],
            [
                'type' => 'media_showcase',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'title' => 'Explore the Architectural Possibilities',
                    'subtitle' => 'Discover how precision decorative breeze blocks introduce natural texture, shadow play, and biophilic airflow into contemporary facades and luxury living spaces.',
                    'media_source' => 'gallery',
                    'show_spill_badge' => true,
                    'badge_text' => 'Global Architectural Projects',
                ],
            ],
            [
                'type' => 'problem_risks',
                'data' => [
                    'bg_theme' => 'alert_red',
                    'badge' => 'The Import Risks You Must Avoid',
                    'title' => 'Why Cheap Wet-Cast & Clay Blocks Fail in Global Architectural Projects',
                    'subtitle' => 'Specifying low-grade artisanal breeze blocks often leads to expensive structural defects, misaligned facade lines, and client complaints:',
                    'items' => [
                        [
                            'icon' => '❌',
                            'title' => 'Wavy Mortar Lines (<1mm vs 15mm Joints)',
                            'desc' => 'Inconsistent manual wooden moulds lack 90° right angles, forcing masons to use clumsy 10–15mm mortar joints that destroy modern minimalist facade symmetry.',
                        ],
                        [
                            'icon' => '💥',
                            'title' => 'Ocean Transit Breakage & Brittle Core',
                            'desc' => 'Weak wet-cast cement crumbles under container vibrations and ocean swell. IndoRoster semi-dry compaction delivers high structural density that withstands long-distance sea freight.',
                        ],
                        [
                            'icon' => '🦠',
                            'title' => 'Superficial Spray Paint & Tropical Mould',
                            'desc' => 'Synthetic surface paints peel and flake under intense UV radiation. IndoRoster uses 100% solid through-body natural stone minerals with zero surface paint, preventing peeling and black algae.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'architectural_concept',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Architectural Materiality & Biophilic Design',
                    'title' => 'Architectural Materials Designed to Sculpt Light, Airflow, and Privacy',
                    'subtitle' => 'Breeze blocks are more than ornamental partitions. Their geometric apertures shape how ambient daylight, cross-ventilation, and privacy interact within contemporary tropical and modern architecture.',
                ],
            ],
            [
                'type' => 'products_showcase',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Modular Precision Motifs',
                    'title' => 'Explore Our Modular 20×20×10 cm Architectural Motifs',
                    'subtitle' => '45+ distinct geometric, linear, and organic patterns pressed in razor-sharp steel dies for tight, seamless vertical and horizontal facade alignment.',
                    'show_filter' => true,
                    'per_page' => 8,
                ],
            ],
            [
                'type' => 'natural_materials',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => '100% Solid Natural Mineral Aggregates (Zero Spray Paint)',
                    'title' => '3 Authentic Through-Body Mineral Finishes Available',
                    'show_raw_grey' => true,
                    'raw_grey_title' => 'Natural Mountain Stone Ash (Raw Grey)',
                    'raw_grey_desc' => 'Pure volcanic mountain stone aggregate and dense Portland cement. Raw tactile concrete texture favored for Brutalist, Bauhaus, and modern tropical minimalist facades.',
                    'raw_grey_best_for' => 'Modern villas, brutalist feature walls, tropical perimeter partitions.',
                    'raw_grey_image' => null,
                    'raw_grey_video_url' => null,

                    'show_white_dolomite' => true,
                    'white_dolomite_title' => 'Natural White Dolomite Stone (Milky Cream White)',
                    'white_dolomite_desc' => 'Crafted from pure natural white dolomite mountain stone. Elegant warm cream mineral tone that naturally reflects solar heat, resists coastal algae, and requires zero surface paint maintenance.',
                    'white_dolomite_best_for' => 'Mediterranean villas, Palm Springs pool screens, luxury beach resorts.',
                    'white_dolomite_image' => null,
                    'white_dolomite_video_url' => null,

                    'show_terracotta' => true,
                    'terracotta_title' => 'Authentic Plered High-Fire Terracotta (Terracotta Red)',
                    'terracotta_desc' => 'Formed from selected Plered earthen clay and kiln-fired at high temperatures for optimal strength, natural porous breathability, and warm earthy architectural character.',
                    'terracotta_best_for' => 'Tropical resorts, rustic hospitality, Spanish hacienda garden walls.',
                    'terracotta_image' => null,
                    'terracotta_video_url' => null,
                ],
            ],
            [
                'type' => 'free_sample_request',
                'data' => [
                    'bg_theme' => 'warm_terracotta',
                    'badge' => 'Physical Quality Verification',
                    'title' => 'Request Free Physical Sample Box Before Placing Container Orders',
                    'subtitle' => 'We provide 100% free physical sample blocks (Raw Grey, White Dolomite, or Terracotta Red) so architects and developers can verify the 90° precision steel-mould sharpness, aggregate density, and surface tactile quality. Sample units are free of charge; courier express air freight (DHL/FedEx) or forwarder pickup is covered by the client.',
                    'sample_image' => null,
                    'sample_video_file' => null,
                    'sample_video_url' => null,
                    'feature_1_title' => '100% Free Sample Units',
                    'feature_1_desc' => 'Order 1–3 physical breeze block units with zero product cost.',
                    'feature_2_title' => 'Express Air Freight Collect',
                    'feature_2_desc' => 'Worldwide express dispatch via DHL, FedEx, or your forwarder corporate account.',
                    'feature_3_title' => '100% Freight Rebate on FCL Order',
                    'feature_3_desc' => 'Your express courier freight cost is 100% credited back as an invoice deduction when you place a 20ft/40ft FCL container order!',
                    'cta_button_text' => '🎁 Request Free Sample Kit via WhatsApp (+62 813-8970-9847)',
                    'sample_wa_message' => 'Hello IndoRoster, I would like to request a Free Architectural Sample Kit for our project in '.$countryName.'. We will cover the express courier freight.',
                ],
            ],
            [
                'type' => 'factory_heritage',
                'data' => [
                    'bg_theme' => 'dark_charcoal',
                    'badge' => 'Heritage of Indonesian Stonemasonry',
                    'title' => 'Centenary Indonesian Craftsmanship Meets Precision Industrial Steel Tooling',
                    'subtitle' => 'Behind every IndoRoster breeze block lies the deep-rooted heritage of Plered, Purwakarta — Indonesia’s world-renowned artisan pottery and stonemasonry hub active since the early 1900s. We combine time-honored semi-dry compaction techniques with razor-sharp laser-cut steel moulds, giving every piece an authentic human touch backed by industrial structural consistency.',
                    'factory_image' => null,
                    'factory_video_url' => null,
                    'stat_years' => '100+ Yrs',
                    'stat_years_label' => 'Plered Craft Heritage',
                    'stat_tolerance' => '< 1 mm',
                    'stat_tolerance_label' => 'Steel Mould Tolerance',
                    'stat_cooling' => '40%',
                    'stat_cooling_label' => 'Passive Solar Cooling',
                    'stat_reach' => '110',
                    'stat_reach_label' => 'Global Export Destinations',
                ],
            ],
            [
                'type' => 'production_process_spill',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Authentic Manufacturing Craftsmanship',
                    'title' => 'How We Manufacture High-Density Architectural Breeze Blocks',
                    'subtitle' => 'Step-by-step glimpse into our semi-dry compaction, precision steel moulding, and strict curing process at our Plered, Purwakarta production facility.',
                    'process_main_image' => null,
                    'process_video_url' => null,
                    'steps' => [
                        [
                            'step_num' => '01',
                            'title' => 'Pure Mountain Stone Aggregate Formulation',
                            'desc' => 'Selected volcanic mountain sand ash and premium Portland binders blended in a precise semi-dry ratio for high density and maximum weather resistance.',
                            'image' => null,
                        ],
                        [
                            'step_num' => '02',
                            'title' => 'Laser-Cut 90° Steel Mould Compaction',
                            'desc' => 'Master artisans compact the aggregate into heavy solid steel dies, guaranteeing razor-sharp 90° right angles and tight <1mm dimensional tolerance.',
                            'image' => null,
                        ],
                        [
                            'step_num' => '03',
                            'title' => 'Controlled Moisture Hydration & Curing',
                            'desc' => 'Blocks undergo uniform multi-day moisture curing to achieve peak compressive strength without shrinkage microcracks or thermal warping.',
                            'image' => null,
                        ],
                        [
                            'step_num' => '04',
                            'title' => 'Individual Caliper QC & Soundness Inspection',
                            'desc' => 'Every unit is physically inspected for edge crispness, structural soundness, and surface uniformity before crating.',
                            'image' => null,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'shipping_logistics_spill',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Export Packing & Ocean Logistics',
                    'title' => 'Container Stuffing & Export Dispatch Process',
                    'subtitle' => 'Watch how our breeze blocks are securely packed in heavy-duty ISPM 15 wooden crates, strapped, and loaded into ocean containers at our factory gate.',
                    'shipping_main_image' => null,
                    'shipping_video_url' => null,
                    'steps' => [
                        [
                            'step_num' => '01',
                            'title' => 'Heavy-Duty ISPM 15 Export Wooden Pallet Crating',
                            'desc' => 'Solid heat-treated timber pallets with dense foam and straw cushioning to absorb ocean transit swell and vibrations.',
                            'image' => null,
                        ],
                        [
                            'step_num' => '02',
                            'title' => 'Corner Protectors & Multi-Layer Waterproof Wrap',
                            'desc' => 'Reinforced corner guards, heavy-duty PET strapping bands, and thick stretch wrap protect against marine humidity and weather.',
                            'image' => null,
                        ],
                        [
                            'step_num' => '03',
                            'title' => 'Factory Forklift Container Stuffing (20ft / 40ft FCL)',
                            'desc' => 'Careful tight-fit container stuffing directly at our factory gate to eliminate cargo shifting during ocean voyages.',
                            'image' => null,
                        ],
                        [
                            'step_num' => '04',
                            'title' => 'Port Dispatch & Certificate of Origin Clearance',
                            'desc' => 'Direct bonded trucking to Port of Tanjung Priok (Jakarta) with full export customs documentation and Certificate of Origin (Form D / COO).',
                            'image' => null,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'logistics_specs',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Ocean Freight Logistics',
                    'title' => 'Container Capacity & Export Packaging Specifications',
                    'subtitle' => 'Direct container dispatch from Tanjung Priok Port (Jakarta, Indonesia) to '.$portName.' with heavy-duty export palletized crates.',
                    'port_name' => $portName,
                    'transit_time' => $transitTime,
                    'cap_20ft' => 'approx. 2,500 – 3,000 pcs (±12–14 metric tons)',
                    'cap_40ft' => 'approx. 4,500 – 5,500 pcs (±22–26 metric tons)',
                    'packing_desc' => $countryData['packing_desc'] ?? 'Fumigated ISPM 15 heat-treated wooden crates, foam cushioning, reinforced corner protectors, high-tensile PET strapping bands, and multi-layer waterproof shrink wrap.',
                    'form_d_text' => $countryData['form_d_text'] ?? 'Supported with Certificate of Origin (COO) / Form D for preferential tariff exemption and smooth customs clearance.',
                ],
            ],
            [
                'type' => 'trade_terms',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Trade Terms & Payment Security',
                    'title' => 'International Trade Terms (Incoterms 2020: FOB / CIF / EXW) & Payment Security',
                    'subtitle' => 'Transparent, secure, and internationally standardized manufacturing and procurement workflow.',
                    'trade_scope' => 'Incoterms 2020: FOB Tanjung Priok Port (Jakarta) / CIF '.$portName.' / EXW Factory Handover (Plered, West Java)',
                    'payment_methods' => 'International Telegraphic Transfer (T/T / SWIFT Wire) in USD / EUR / SGD / AUD & Local IDR',
                    'dp_milestone' => '50% Production Deposit (Locks Production Schedule & Materials)',
                    'balance_milestone' => '50% Balance Payment upon Pre-Shipment QC Inspection & Container Stuffing Report',
                ],
            ],
            [
                'type' => 'faqs_accordion',
                'data' => [
                    'bg_theme' => 'clean_light',
                    'badge' => 'Export FAQ',
                    'title' => 'Frequently Asked Questions for '.$countryName.' Projects',
                    'faqs' => $countryData['faqs'] ?? [
                        [
                            'q' => 'How are breeze blocks shipped internationally to '.$countryName.'?',
                            'a' => 'Shipments are dispatched from Tanjung Priok Port (Jakarta, Indonesia) directly to '.$portName.' via 20ft or 40ft Full Container Load (FCL).',
                        ],
                        [
                            'q' => 'What is the minimum order quantity (MOQ)?',
                            'a' => 'Standard MOQ is 1×20ft FCL container (approx. 2,500 – 3,000 pcs) for optimal ocean sea freight cost efficiency.',
                        ],
                        [
                            'q' => 'Can we request bespoke custom patterns or special dimensions?',
                            'a' => 'Yes. We provide CAD engineering consultation and precision steel mould fabrication for custom architectural patterns with MOQ of 2,000 pcs.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'rfq_lead_magnet',
                'data' => [
                    'bg_theme' => 'dark_charcoal',
                    'lead_magnet_title' => 'Looking for Full Product Specifications & Pattern Inspiration?',
                    'lead_magnet_desc' => 'Download our comprehensive Architectural Export Lookbook featuring 45+ precision modular motifs, exact dimensions, structural windload data, facade installation drawings, and shadow cast simulations in PDF format.',
                    'rfq_title' => 'Request Direct Factory Export Quotation & Container Delivery Schedule',
                    'rfq_subtitle' => 'FOB Jakarta / CIF '.$portName.' pricing for architects, developers, contractors, and building material distributors.',
                ],
            ],
        ];
    }

    /**
     * Resolve final country data by merging database record (if any) with defaults.
     */
    public static function resolveCountryData(string $slug): array
    {
        $slug = strtolower(trim($slug));
        $defaultConfig = static::getDefaultConfig($slug) ?? [
            'code' => strtoupper(substr($slug, 0, 2)),
            'name' => ucfirst($slug),
            'flag' => '🌐',
            'lang' => 'en',
            'region' => 'Global',
            'headline' => 'Architectural Breeze Blocks for '.ucfirst($slug).' Projects',
            'subheadline' => 'Discover decorative ventilation blocks crafted in Indonesia for architectural facades, feature walls, privacy screens and contemporary spaces.',
            'currency' => 'USD',
            'port_name' => 'Designated Container Port',
            'transit_time' => '14 – 28 Days Sea Freight',
            'packing_desc' => 'Heavy-duty export wooden pallet & crate packing.',
            'form_d_text' => 'Certificate of Origin documentation supported.',
            'key_cities' => [],
            'meta_title' => 'Breeze Blocks Supplier '.ucfirst($slug).' | IndoRoster Export',
            'meta_description' => 'Direct manufacturer & exporter of architectural concrete breeze blocks to '.ucfirst($slug).'.',
            'faqs' => [],
        ];

        // Check if database override exists
        $dbRecord = ExportPage::where('country_slug', $slug)->first();

        if ($dbRecord && $dbRecord->is_active) {
            $merged = array_merge($defaultConfig, [
                'name' => $dbRecord->country_name ?: $defaultConfig['name'],
                'flag' => $dbRecord->flag_emoji ?: $defaultConfig['flag'],
                'region' => $dbRecord->region ?: $defaultConfig['region'],
                'port_name' => $dbRecord->destination_port ?: $defaultConfig['port_name'],
                'transit_time' => $dbRecord->transit_time ?: $defaultConfig['transit_time'],
                'meta_title' => $dbRecord->meta_title ?: $defaultConfig['meta_title'],
                'meta_description' => $dbRecord->meta_description ?: $defaultConfig['meta_description'],
                'headline' => $dbRecord->hero_headline ?: $defaultConfig['headline'],
                'subheadline' => $dbRecord->hero_subheadline ?: $defaultConfig['subheadline'],
                'sections_config' => ! empty($dbRecord->sections_config)
                    ? $dbRecord->sections_config
                    : static::generateDefaultSectionsConfig($defaultConfig),
            ]);

            return $merged;
        }

        // Return default with generated sections_config
        $defaultConfig['sections_config'] = static::generateDefaultSectionsConfig($defaultConfig);

        return $defaultConfig;
    }

    /**
     * Synchronize all 110 default countries to database so admin can instantly view and edit.
     */
    public static function syncAllDefaultsToDatabase(): int
    {
        $configs = static::getDefaultConfigs();
        $count = 0;

        foreach ($configs as $slug => $c) {
            $sections = static::generateDefaultSectionsConfig($c);

            ExportPage::updateOrCreate(
                ['country_slug' => $slug],
                [
                    'country_name' => $c['name'],
                    'flag_emoji' => $c['flag'] ?? '🌐',
                    'region' => $c['region'] ?? 'Global',
                    'destination_port' => $c['port_name'] ?? null,
                    'transit_time' => $c['transit_time'] ?? null,
                    'is_active' => true,
                    'meta_title' => $c['meta_title'] ?? null,
                    'meta_description' => $c['meta_description'] ?? null,
                    'hero_headline' => $c['headline'] ?? null,
                    'hero_subheadline' => $c['subheadline'] ?? null,
                    'hero_badge' => '🌐 Direct Factory Exporter — Global Sea Freight',
                    'sections_config' => $sections,
                ]
            );
            $count++;
        }

        return $count;
    }
}
