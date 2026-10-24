<?php

namespace App\Livewire\Export;

use App\Models\GalleryMedia;
use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;

/**
 * ExportContent — Halaman konten export bertema keyword untuk SEO/AEO/GEO internasional.
 *
 * Setiap slug menghasilkan landing page dedikasi dengan:
 * - H1, meta title, meta description, canonical unik per topik
 * - Konten tebal: definisi, keunggulan, FAQ (AEO), use-case
 * - FAQPage JSON-LD untuk AEO (Google AI Overviews, Bing Copilot, ChatGPT web)
 * - HowTo JSON-LD untuk GEO (step-by-step proses pemesanan ekspor)
 * - Internal link ke halaman negara terkait
 */
class ExportContent extends Component
{
    public string $contentSlug;

    /**
     * Konfigurasi semua halaman konten export.
     * Key = slug URL, value = array konfigurasi halaman.
     */
    public static function getContentConfig(string $slug): ?array
    {
        $configs = [

            // ─────────────────────────────────────────
            //  PRODUCT TYPE KEYWORD CLUSTERS
            // ─────────────────────────────────────────

            'breeze-blocks' => [
                'title' => 'Breeze Blocks Manufacturer & Exporter — Direct Factory Supply | IndoRoster',
                'description' => 'IndoRoster is a direct factory manufacturer and global exporter of 90° precision steel-mould concrete breeze blocks. Wholesale FOB pricing, certified ocean freight to 110 countries.',
                'canonical' => '/export/breeze-blocks',
                'h1' => 'Concrete Breeze Blocks — Direct Factory Manufacturer & Global Exporter',
                'intro' => 'IndoRoster manufactures high-density architectural concrete breeze blocks using a 90° steel-precision mould process in Plered, Purwakarta, Java, Indonesia. Our breeze blocks are exported globally via certified ocean freight, FOB basis, to contractors, architects, and developers worldwide.',
                'keywords' => ['breeze blocks', 'concrete breeze blocks', 'breeze block supplier', 'breeze block manufacturer', 'breeze block exporter', 'breeze block wholesale', 'decorative breeze blocks', 'architectural breeze blocks', 'precast breeze blocks', 'buy breeze blocks'],
                'related_countries' => ['singapore', 'australia', 'uk', 'usa', 'malaysia'],
                'faqs' => [
                    ['q' => 'What are breeze blocks?', 'a' => 'Breeze blocks (also called screen blocks, ventilation blocks, or concrete blocks with openings) are precast concrete masonry units with decorative perforated patterns. They allow airflow and light while providing privacy and solar shading for facades, garden walls, and architectural screens.'],
                    ['q' => 'How are IndoRoster breeze blocks different from standard breeze blocks?', 'a' => 'IndoRoster uses 90° precision steel moulds — not wooden hand-moulds — resulting in factory-perfect right angles, consistent 1mm-tolerance dimensions, and high-density compaction using pure mountain stone ash aggregate. This produces blocks that withstand ocean freight transit without crumbling.'],
                    ['q' => 'What sizes of breeze blocks do you export?', 'a' => 'Standard export sizes: 30×30cm, 40×20cm, 20×20cm, 15×15cm, and custom dimensions on request. Block thickness: 8cm–12cm. All sizes available in raw natural concrete, white dolomite, or terracotta red.'],
                    ['q' => 'What is the minimum order quantity (MOQ) for breeze block export?', 'a' => 'Standard MOQ for export orders is 1,000 pieces per design/colour. Container minimum is approximately 5,000–10,000 pieces (20ft FCL). Mixed-design LCL orders are available from 500 pieces.'],
                    ['q' => 'How do you ship breeze blocks internationally?', 'a' => 'We ship via certified ocean freight (FCL/LCL), FOB Tanjung Priok (Jakarta) or Tanjung Perak (Surabaya). Transit times: 2 days (Singapore) to 28+ days (Europe/Americas).'],
                    ['q' => 'Can I order custom breeze block patterns?', 'a' => 'Yes. Custom mould fabrication is available for orders of 2,000 pieces and above. Lead time for new mould: 3–4 weeks. We support DXF/DWG architectural drawings and provide samples before full production.'],
                ],
                'how_to' => [
                    'name' => 'How to Order Breeze Blocks Export from IndoRoster',
                    'steps' => [
                        ['name' => 'Browse the Export Catalog', 'text' => 'Visit indoroster.com/export/catalog to view all available breeze block patterns, sizes, and finishes.'],
                        ['name' => 'Send an Export Inquiry via WhatsApp', 'text' => 'Contact our Export Desk via WhatsApp (+62 813-8970-9847) with product code, quantity, and destination port.'],
                        ['name' => 'Receive FOB Quotation', 'text' => 'We will send a detailed quotation including unit price (FOB), packing list, and estimated transit time within 24 hours.'],
                        ['name' => 'Confirm & Pay Deposit', 'text' => 'Confirm with 30–50% down payment via T/T bank transfer or LC at sight.'],
                        ['name' => 'Production & Quality Control', 'text' => 'Each batch undergoes dimensional tolerance inspection and impact load testing before packing.'],
                        ['name' => 'Shipping & Delivery', 'text' => 'Products loaded into 20ft/40ft containers with wooden pallets and foam separators. Bill of lading, certificate of origin, and packing list provided.'],
                    ],
                ],
                'related_slugs' => ['concrete-screen-blocks', 'ventilation-blocks', 'decorative-blocks', 'mashrabiya'],
            ],

            'concrete-screen-blocks' => [
                'title' => 'Concrete Screen Blocks Manufacturer & Exporter — Wholesale FOB | IndoRoster',
                'description' => 'Factory direct concrete screen blocks for architectural facades, privacy walls, and solar shading. Export to 110 countries. Request FOB wholesale quotation.',
                'canonical' => '/export/concrete-screen-blocks',
                'h1' => 'Concrete Screen Blocks — Factory Direct Export to 110 Countries',
                'intro' => 'Concrete screen blocks are IndoRoster\'s flagship export product. Manufactured with 90° precision steel moulds, these blocks are engineered for long-distance ocean freight durability and architectural precision.',
                'keywords' => ['concrete screen blocks', 'screen block supplier', 'concrete screen wall', 'architectural screen blocks', 'decorative concrete screen', 'pierced concrete blocks', 'lattice blocks', 'perforated concrete blocks', 'concrete privacy screen', 'screen wall exporter'],
                'related_countries' => ['uae', 'singapore', 'australia', 'uk', 'spain'],
                'faqs' => [
                    ['q' => 'What is a concrete screen block?', 'a' => 'A concrete screen block is a precast masonry unit with a geometric open pattern that allows airflow, light diffusion, and visual privacy simultaneously. Used in architectural facades, garden screens, boundary walls, and solar shading panels.'],
                    ['q' => 'How do concrete screen blocks differ from regular bricks?', 'a' => 'Unlike solid bricks, concrete screen blocks have decorative perforations that reduce solar heat gain by up to 40%, promote natural cross-ventilation, and create dynamic shadow patterns through the day.'],
                    ['q' => 'What finishes are available for export concrete screen blocks?', 'a' => 'Available finishes: (1) Raw Natural Concrete — grey stone ash, no paint; (2) White Dolomite — natural white mineral composite; (3) Terracotta Red — iron oxide mineral pigment. All finishes are through-body and UV-resistant.'],
                    ['q' => 'Can concrete screen blocks be used for exterior facades in hot climates?', 'a' => 'Yes. IndoRoster blocks are manufactured in tropical Java and tested for UV resistance, tropical moisture, and thermal cycling — specifically suited for hot-humid climates in the Middle East, Southeast Asia, and tropical resorts.'],
                    ['q' => 'What patterns of concrete screen blocks are available?', 'a' => 'We produce 45+ standard patterns including geometric, star, cross, diamond, arabesque, circular, linear, and custom motifs.'],
                    ['q' => 'How are concrete screen blocks typically installed?', 'a' => 'Using standard mortar joints (6–10mm recommended for IndoRoster precision blocks). Can be laid in running bond, stack bond, or rotated 45° for diamond effect. Installation instructions PDF available on request.'],
                ],
                'how_to' => [
                    'name' => 'How to Export Concrete Screen Blocks from Indonesia',
                    'steps' => [
                        ['name' => 'Select Your Pattern & Quantity', 'text' => 'Choose from 45+ patterns at indoroster.com/export/catalog. Specify quantity in pieces or m².'],
                        ['name' => 'Get a Free Sample Pack', 'text' => 'Request physical sample blocks (3–5 pieces) shipped via DHL/FedEx for architectural specification approval.'],
                        ['name' => 'Receive Container Quotation', 'text' => 'Full quotation: unit price FOB Jakarta, container capacity, freight estimate, and delivery timeline.'],
                        ['name' => 'Place Order & Production', 'text' => 'Confirm order with 30% DP. Production lead time: 2–4 weeks standard, 6–8 weeks custom moulds.'],
                        ['name' => 'Export Documentation', 'text' => 'We provide: Commercial Invoice, Bill of Lading, Packing List, Certificate of Origin, and Phytosanitary Certificate if required.'],
                    ],
                ],
                'related_slugs' => ['breeze-blocks', 'ventilation-blocks', 'mashrabiya', 'decorative-blocks'],
            ],

            'ventilation-blocks' => [
                'title' => 'Ventilation Blocks Exporter — Concrete Airflow Blocks Wholesale | IndoRoster',
                'description' => 'Export-grade concrete ventilation blocks for passive cooling in hot-climate architecture. Wholesale FOB from factory in Java, Indonesia. Ships globally.',
                'canonical' => '/export/ventilation-blocks',
                'h1' => 'Concrete Ventilation Blocks — Passive Cooling Architecture Export',
                'intro' => 'IndoRoster concrete ventilation blocks are precision-engineered for passive airflow management in tropical and desert architecture. Used as air bricks, wind catchers, and bioclimatic facade elements, these blocks reduce interior temperatures through natural convection.',
                'keywords' => ['ventilation blocks', 'concrete ventilation blocks', 'air bricks', 'ventilation wall blocks', 'perforated ventilation blocks', 'airflow blocks', 'passive ventilation concrete', 'bioclimatic blocks', 'wind catcher blocks', 'natural ventilation blocks exporter'],
                'related_countries' => ['uae', 'singapore', 'australia', 'india', 'malaysia'],
                'faqs' => [
                    ['q' => 'What are ventilation blocks?', 'a' => 'Ventilation blocks are precast concrete units with designed openings that facilitate passive natural airflow through walls. Also known as air bricks, wind blocks, or breeze blocks, they promote cross-ventilation without sacrificing structural integrity.'],
                    ['q' => 'How do ventilation blocks improve indoor air quality?', 'a' => 'By creating cross-ventilation pathways, ventilation blocks allow hot air to escape and cooler air to enter naturally. In tropical climates, this can reduce indoor temperatures by 3–7°C compared to solid concrete walls.'],
                    ['q' => 'Are ventilation blocks structurally strong?', 'a' => 'Yes. IndoRoster uses high-density semi-dry compaction with steel mould pressing. The open pattern design maintains over 60% solid concrete by volume, ensuring structural adequacy for non-load-bearing walls.'],
                    ['q' => 'What opening percentage do your ventilation blocks have?', 'a' => 'Standard ventilation opening percentage ranges from 20% (high privacy) to 50% (maximum airflow). Custom opening ratios can be specified for bioclimatic performance requirements.'],
                    ['q' => 'Can ventilation blocks be used in rainy climates?', 'a' => 'Yes, with proper overhang design. For heavy rainfall regions, we recommend angled-channel or recessed opening designs that prevent water ingress while maintaining airflow.'],
                ],
                'how_to' => null,
                'related_slugs' => ['breeze-blocks', 'concrete-screen-blocks', 'screen-walls'],
            ],

            'mashrabiya' => [
                'title' => 'Concrete Mashrabiya Panels Supplier & Exporter — Middle East & Global | IndoRoster',
                'description' => 'Precast concrete Mashrabiya panels inspired by Islamic architecture. Factory direct export for luxury villas, hotels, and commercial facades in the Middle East, UK, and worldwide.',
                'canonical' => '/export/mashrabiya',
                'h1' => 'Concrete Mashrabiya — Precast Islamic Screen Panels for Global Architecture',
                'intro' => 'The Mashrabiya (مشربية) is a traditional Islamic architectural element — a latticed screen that controls solar light, ensures privacy, and promotes natural ventilation. IndoRoster produces modern precast concrete Mashrabiya panels that reimagine this centuries-old tradition in precision-manufactured, export-grade concrete.',
                'keywords' => ['concrete Mashrabiya', 'Mashrabiya panels', 'Islamic screen blocks', 'Arabic screen blocks', 'Mashrabiya exporter', 'precast Mashrabiya', 'solar shading panels', 'Islamic architecture blocks', 'ornamental concrete screen', 'Arabic facade panels'],
                'related_countries' => ['uae', 'saudi-arabia', 'qatar', 'kuwait', 'oman', 'bahrain'],
                'faqs' => [
                    ['q' => 'What is a Mashrabiya?', 'a' => 'A Mashrabiya (مشربية) is a traditional Islamic architectural screen element originating in medieval Arab architecture. Modern Mashrabiya panels are produced in concrete, metal, or composite materials. They serve four functions: solar shading, natural ventilation, privacy screening, and decorative expression.'],
                    ['q' => 'How are IndoRoster concrete Mashrabiya different from traditional wooden Mashrabiya?', 'a' => 'Concrete Mashrabiya from IndoRoster are weatherproof, termite-proof, and maintenance-free — ideal for exterior facades in harsh desert climates. They match the geometric intricacy of traditional carved wood patterns but are far more durable and cost-effective at architectural scale.'],
                    ['q' => 'What Mashrabiya patterns do you produce?', 'a' => 'We produce geometric Arabic patterns including: Classic Eight-Point Star, Muqarnas-inspired Grid, Hexagonal Lattice, Diamond Arabesque, and Custom Pattern by DXF drawing. Minimum custom pattern order: 2,000 panels.'],
                    ['q' => 'Can your Mashrabiya panels be used for large hotel facades?', 'a' => 'Yes. Our panels are engineered for commercial-scale facade cladding. We supply full container loads (5,000–20,000 panels per project) with consistent dimensional tolerance (±1mm).'],
                    ['q' => 'Do you have experience exporting Mashrabiya to the Middle East?', 'a' => 'Yes. We export to UAE, Saudi Arabia, Qatar, Kuwait, Oman, and Bahrain via Jebel Ali Port (Dubai) and Jeddah Islamic Port. Transit time from Tanjung Priok Jakarta: 12–16 days direct sea freight.'],
                ],
                'how_to' => null,
                'related_slugs' => ['concrete-screen-blocks', 'decorative-blocks', 'claustra-blocks'],
            ],

            'decorative-blocks' => [
                'title' => 'Decorative Concrete Blocks Supplier — Architectural Facade Export | IndoRoster',
                'description' => 'Factory direct decorative concrete blocks for luxury facades, garden walls, and landscape screens. 45+ patterns. Wholesale FOB export to 110 countries.',
                'canonical' => '/export/decorative-blocks',
                'h1' => 'Decorative Concrete Blocks — 45+ Architectural Patterns for Export',
                'intro' => 'IndoRoster produces 45+ decorative concrete block patterns for architectural facades, landscaping, and interior accent walls. Available in raw concrete, white dolomite, and terracotta finishes, these export-grade blocks are favoured by architects designing mid-century modern, tropical minimalist, and contemporary Mediterranean projects.',
                'keywords' => ['decorative concrete blocks', 'decorative blocks exporter', 'architectural decorative blocks', 'concrete pattern blocks', 'ornamental blocks', 'facade concrete blocks', 'landscape decorative blocks', 'decorative screen blocks', 'aesthetic concrete blocks', 'designer concrete blocks'],
                'related_countries' => ['australia', 'spain', 'portugal', 'greece', 'france', 'usa'],
                'faqs' => [
                    ['q' => 'What are decorative concrete blocks used for?', 'a' => 'Decorative concrete blocks are used for: garden privacy screens, pool enclosures, boundary walls, carport facades, architectural accent walls, patio screens, and pergola side panels.'],
                    ['q' => 'What architectural styles work with decorative concrete blocks?', 'a' => 'Popular design styles: Mid-Century Modern (USA/Australia), Tropical Minimalist (Southeast Asia), Mediterranean Modern (Spain/Greece/Portugal), Biophilic Design (global), and Contemporary Islamic (Middle East).'],
                    ['q' => 'Can I mix multiple decorative block patterns in one project?', 'a' => 'Yes. Many architects combine 2–3 pattern types for visual rhythm. We can ship mixed pattern orders in one container.'],
                    ['q' => 'Do decorative blocks require special maintenance?', 'a' => 'Minimal maintenance. Once-yearly cleaning with mild detergent and a soft brush is sufficient. Our natural stone ash composition resists algae and mould better than cement-only mixes. No painting or sealing required.'],
                ],
                'how_to' => null,
                'related_slugs' => ['breeze-blocks', 'concrete-screen-blocks', 'screen-walls'],
            ],

            'screen-walls' => [
                'title' => 'Concrete Screen Walls Supplier — Precast Privacy Screen Exporter | IndoRoster',
                'description' => 'Precast concrete screen walls and privacy screens for residential and commercial projects. Factory export from Indonesia. FOB wholesale pricing available.',
                'canonical' => '/export/screen-walls',
                'h1' => 'Concrete Screen Walls — Precast Privacy & Facade Screen Export',
                'intro' => 'A concrete screen wall is a non-load-bearing architectural boundary element composed of interlocking decorative concrete blocks. IndoRoster supplies prefabricated screen wall systems complete with block patterns, border blocks, and coping blocks, designed for fast on-site assembly.',
                'keywords' => ['concrete screen walls', 'precast screen walls', 'privacy screen walls', 'garden screen walls', 'concrete privacy wall', 'decorative screen wall exporter', 'prefabricated screen wall', 'fence screen wall', 'boundary screen wall', 'architectural privacy screen'],
                'related_countries' => ['australia', 'new-zealand', 'uk', 'usa', 'canada', 'singapore'],
                'faqs' => [
                    ['q' => 'What is a concrete screen wall?', 'a' => 'A concrete screen wall is a boundary or facade element made from decorative perforated concrete blocks. Unlike solid walls, screen walls allow air and filtered light to pass through while providing visual privacy. Common uses: pool fences, garden dividers, carport sides, and outdoor dining screens.'],
                    ['q' => 'How much does a concrete screen wall cost to import?', 'a' => 'Standard geometric patterns for a 20ft container (approx. 5,000 blocks, covering ±200m² of wall) start from USD 8,000–15,000 FOB Jakarta, excluding freight. Contact our export desk for your specific project quotation.'],
                    ['q' => 'Can I order complete screen wall kits with installation guidance?', 'a' => 'Yes. We provide: (1) Pattern blocks, (2) Border/frame blocks, (3) Coping blocks, (4) CAD installation drawing, (5) Mortar joint specification, and (6) Optional remote video installation consultation via WhatsApp.'],
                    ['q' => 'How tall can a decorative concrete screen wall be?', 'a' => 'For non-load-bearing garden screens, recommended maximum height: 2.4m without additional columns. With reinforced concrete columns every 1.8–2.4m, screen walls can reach 3.6m. Taller screens require structural engineering review.'],
                ],
                'how_to' => null,
                'related_slugs' => ['breeze-blocks', 'concrete-screen-blocks', 'decorative-blocks'],
            ],

            'cobogo-blocks' => [
                'title' => 'Cobogó Blocks Exporter — Brazilian Architectural Screen Blocks | IndoRoster',
                'description' => 'Concrete Cobogó blocks inspired by Brazilian modernist architecture. Wholesale export for architects and developers. Factory direct from Java, Indonesia.',
                'canonical' => '/export/cobogo-blocks',
                'h1' => 'Cobogó Concrete Blocks — Brazilian Modernist Screen Blocks for Export',
                'intro' => 'Cobogó (ko-bo-GOH) is a Portuguese-Brazilian term for decorative perforated blocks used in modernist architecture. Invented in Recife, Brazil in 1929, the cobogó concept has spread globally as architects adopt passive solar design. IndoRoster produces cobogó-inspired precision concrete blocks for the global architectural market.',
                'keywords' => ['cobogo blocks', 'cobogó', 'cobogo exporter', 'Brazilian screen blocks', 'modernist concrete blocks', 'perforated concrete facade', 'cobogo supplier', 'Brazilian architecture blocks', 'tropical screen blocks', 'passive solar blocks'],
                'related_countries' => ['brazil', 'portugal', 'spain', 'usa', 'france'],
                'faqs' => [
                    ['q' => 'What is a Cobogó block?', 'a' => 'A Cobogó is a decorative perforated architectural element used for passive ventilation and solar shading. The term is Portuguese-Brazilian, originating in 1929. Today, cobogó-style blocks are used globally as a sustainable, low-tech cooling strategy for tropical and subtropical climates.'],
                    ['q' => 'How is IndoRoster\'s cobogó block different from Brazilian originals?', 'a' => 'While Brazilian original cobogós were hand-made from clay or artisanal concrete, IndoRoster produces cobogó-inspired blocks via 90° steel-mould semi-dry compaction, achieving factory-grade consistency and ocean freight durability.'],
                    ['q' => 'What styles of cobogó blocks are available for export?', 'a' => 'Available cobogó-inspired patterns: Classic Hexagon, Rounded Lozenge, Pinwheel, Sunburst, and custom geometric designs. Sizes: 30×30cm, 20×20cm, and 40×20cm. Finishes: raw grey, white dolomite, terracotta.'],
                ],
                'how_to' => null,
                'related_slugs' => ['breeze-blocks', 'decorative-blocks', 'concrete-screen-blocks'],
            ],

            'claustra-blocks' => [
                'title' => 'Claustra Béton Exporter — Architectural Concrete Claustra Blocks | IndoRoster',
                'description' => 'Concrete claustra blocks for Mediterranean and French architectural facades. Factory export from Java, Indonesia. Wholesale FOB pricing for contractors and developers.',
                'canonical' => '/export/claustra-blocks',
                'h1' => 'Claustra Béton — Concrete Screen Blocks for Mediterranean Architecture',
                'intro' => '"Claustra" is the French term for decorative concrete screen panels used as sun-shading, privacy screens, and architectural facade elements in Mediterranean and French-influenced architecture.',
                'keywords' => ['claustra béton', 'claustra blocks', 'claustra concrete', 'brise soleil concrete', 'French screen blocks', 'Mediterranean screen blocks', 'claustra exporter', 'concrete claustra supplier', 'solar shading concrete panels', 'architectural claustra'],
                'related_countries' => ['france', 'spain', 'portugal', 'morocco', 'algeria', 'greece'],
                'faqs' => [
                    ['q' => 'What is a claustra?', 'a' => 'A "claustra" (plural: claustras) is the French architectural term for a perforated concrete or masonry element used to partition spaces with filtered light and air. Common in French colonial architecture throughout North Africa and Southeast Asia.'],
                    ['q' => 'Are IndoRoster claustra blocks CE-certified?', 'a' => 'Our blocks are manufactured to ISO-compatible dimensional tolerances. We provide full material test reports (compressive strength, water absorption, dimensional tolerance) to support your local certification process.'],
                    ['q' => 'Do you have distributors in France or Spain?', 'a' => 'We currently export directly to architects, developers, and importers in France, Spain, and Portugal via Port of Marseille Fos and Port of Valencia. We are open to exclusive distributor partnerships — contact our export desk.'],
                ],
                'how_to' => null,
                'related_slugs' => ['breeze-blocks', 'mashrabiya', 'concrete-screen-blocks'],
            ],

            // ─────────────────────────────────────────
            //  APPLICATION / USE-CASE PAGES
            // ─────────────────────────────────────────

            'for-villas' => [
                'title' => 'Breeze Blocks for Luxury Villas — Export Supply | IndoRoster',
                'description' => 'Direct factory supply of architectural breeze blocks and screen walls for luxury villa projects worldwide. Pool screens, facade cladding, privacy screens. FOB export.',
                'canonical' => '/export/for-villas',
                'h1' => 'Breeze Blocks & Screen Walls for Luxury Villa Projects Worldwide',
                'intro' => 'Luxury villas worldwide increasingly specify architectural concrete screen blocks for pool privacy screens, garden boundary walls, carport facades, and interior accent walls. IndoRoster supplies export-grade precision breeze blocks designed for premium residential architecture.',
                'keywords' => ['breeze blocks for villas', 'screen blocks luxury villa', 'villa breeze block supplier', 'pool privacy screen blocks', 'garden screen blocks villa', 'concrete facade villa', 'architectural blocks villa project', 'villa screen wall export'],
                'related_countries' => ['australia', 'uae', 'singapore', 'spain', 'greece', 'maldives', 'mauritius'],
                'faqs' => [
                    ['q' => 'What types of breeze blocks are most popular for luxury villa projects?', 'a' => 'For luxury villas, the most specified patterns are: (1) Classic geometric grid for clean minimalist facades, (2) Star or diamond lattice for dramatic shadow play around pools, (3) Circular dot pattern for tropical-modern garden screens, (4) Custom arabesque for Middle Eastern-inspired villas.'],
                    ['q' => 'How many breeze blocks do I need for a villa pool screen?', 'a' => 'A standard pool privacy screen (1.8m × 6m = 10.8m²) requires approximately 120 blocks (30×30cm) or 270 blocks (20×20cm). Add 20% for cutting waste and breakage.'],
                    ['q' => 'Do you supply to individual villa owners or only to developers?', 'a' => 'Both. For individual villa owners, we recommend our export partner importers in your country. For villa developers building multiple units, direct container import from IndoRoster provides the best FOB pricing.'],
                ],
                'how_to' => null,
                'related_slugs' => ['breeze-blocks', 'screen-walls', 'concrete-screen-blocks', 'for-resorts'],
            ],

            'for-hotels' => [
                'title' => 'Breeze Blocks for Hotels — Commercial Facade Blocks Export | IndoRoster',
                'description' => 'Export-grade architectural concrete breeze blocks for hotel facades, pool screens, and commercial architectural projects. Full container supply from factory Indonesia.',
                'canonical' => '/export/for-hotels',
                'h1' => 'Architectural Breeze Blocks for Hotels & Commercial Hospitality Projects',
                'intro' => 'Hotels, beach resorts, and hospitality venues worldwide use architectural concrete screen blocks to create iconic facades, breezy lobby walls, rooftop screens, and poolside privacy elements. IndoRoster supplies full container loads of commercial-grade precision blocks.',
                'keywords' => ['breeze blocks for hotels', 'hotel facade concrete blocks', 'commercial screen blocks hotel', 'hospitality architecture blocks', 'hotel breeze block supplier', 'resort screen block exporter', 'hotel pool screen blocks', 'commercial decorative blocks'],
                'related_countries' => ['maldives', 'uae', 'australia', 'spain', 'thailand', 'mauritius'],
                'faqs' => [
                    ['q' => 'How many breeze blocks does a typical hotel facade project require?', 'a' => 'A boutique hotel facade project (500m² of screen wall area) requires approximately 5,500–8,000 blocks (30×30cm). A large resort project (2,000m²+) can require 20,000–40,000 blocks delivered in multiple containers with stage delivery.'],
                    ['q' => 'Can you supply consistent quality across multiple container deliveries?', 'a' => 'Yes. All IndoRoster blocks are manufactured from the same steel mould for the entire project run. Calibrated batch mixing ensures consistent colour and dimensional tolerance (±1mm) across every container.'],
                    ['q' => 'Do you have experience supplying to hotel chains?', 'a' => 'We have supplied to boutique resort and hotel projects across Southeast Asia, Indian Ocean, and the Middle East. Project references available on request under NDA.'],
                ],
                'how_to' => null,
                'related_slugs' => ['for-resorts', 'for-commercial', 'breeze-blocks', 'concrete-screen-blocks'],
            ],

            'for-resorts' => [
                'title' => 'Breeze Blocks for Tropical Resorts — Direct Factory Export | IndoRoster',
                'description' => 'Precision concrete breeze blocks for overwater bungalows, beach villas, and tropical resort architecture. FOB ocean freight to global resort destinations.',
                'canonical' => '/export/for-resorts',
                'h1' => 'Architectural Breeze Blocks for Tropical Resorts & Island Projects',
                'intro' => 'Tropical resorts demand building materials that are visually striking, durable against salt air and tropical humidity, and deliverable to remote island locations. IndoRoster concrete breeze blocks meet all these requirements.',
                'keywords' => ['breeze blocks for resorts', 'tropical resort screen blocks', 'island resort blocks', 'beach resort facade blocks', 'overwater bungalow blocks', 'resort privacy screen', 'tropical architecture blocks', 'eco resort screen blocks'],
                'related_countries' => ['maldives', 'fiji', 'mauritius', 'french-polynesia', 'philippines', 'thailand'],
                'faqs' => [
                    ['q' => 'How do you ship breeze blocks to remote island resort locations?', 'a' => 'We ship via LCL (Less than Container Load) to any port worldwide, including secondary island ports. For very small island destinations, we consolidate via Singapore or Surabaya transshipment hubs.'],
                    ['q' => 'Are your breeze blocks suitable for salt air coastal environments?', 'a' => 'Yes. Our natural stone ash aggregate provides excellent resistance to salt spray and coastal humidity. We do not use surface paint — the colour is through-body mineral, preventing salt-induced peeling.'],
                    ['q' => 'Can your blocks withstand typhoon-zone wind loading?', 'a' => 'IndoRoster blocks for typhoon/cyclone-prone regions are specified for non-load-bearing screen walls only. A structural engineer should specify the mortar mix, column spacing, and reinforcement for local wind code compliance.'],
                ],
                'how_to' => null,
                'related_slugs' => ['for-hotels', 'for-villas', 'breeze-blocks', 'ventilation-blocks'],
            ],

            'for-commercial' => [
                'title' => 'Concrete Screen Blocks for Commercial Buildings — Facade Export | IndoRoster',
                'description' => 'Architectural concrete screen blocks for office towers, shopping malls, and commercial facade projects. Full container export from Java, Indonesia. Request quotation.',
                'canonical' => '/export/for-commercial',
                'h1' => 'Concrete Screen Blocks for Commercial Facade & Urban Architecture',
                'intro' => 'Commercial buildings — offices, retail centres, mixed-use developments — increasingly specify concrete screen blocks for shading louvres, balcony balustrades, parking garage screens, and rooftop plant screening.',
                'keywords' => ['screen blocks commercial building', 'concrete facade commercial', 'office building screen blocks', 'commercial breeze blocks', 'parking garage screen blocks', 'retail facade concrete screen', 'urban architecture screen blocks', 'building facade concrete export'],
                'related_countries' => ['singapore', 'uae', 'usa', 'australia', 'uk', 'canada'],
                'faqs' => [
                    ['q' => 'Are IndoRoster blocks suitable for high-rise commercial facades?', 'a' => 'IndoRoster blocks are specified for non-load-bearing facade applications, parking garage screens, and balcony privacy screens. We provide material test certificates (compressive strength, water absorption) for engineering specification review.'],
                    ['q' => 'Can you supply custom commercial facade blocks with company logo or pattern?', 'a' => 'Yes. Custom mould production for branded patterns or bespoke architectural patterns is available for orders of 5,000 pieces and above. Lead time for custom mould: 4–6 weeks.'],
                ],
                'how_to' => null,
                'related_slugs' => ['for-hotels', 'concrete-screen-blocks', 'mashrabiya', 'breeze-blocks'],
            ],

            // ─────────────────────────────────────────
            //  B2B EXPORT PAGES
            // ─────────────────────────────────────────

            'wholesale' => [
                'title' => 'Breeze Blocks Wholesale Export — MOQ & Container Pricing | IndoRoster',
                'description' => 'Wholesale breeze blocks and concrete screen blocks for importers, distributors, and project developers. FOB container pricing from Java, Indonesia. MOQ 1,000 pcs.',
                'canonical' => '/export/wholesale',
                'h1' => 'Wholesale Breeze Blocks & Concrete Screen Blocks — Container Export Pricing',
                'intro' => 'IndoRoster is a direct factory exporter offering wholesale FOB pricing for importers, construction material distributors, and large-scale project developers. No middlemen — factory gate pricing with full export documentation.',
                'keywords' => ['breeze blocks wholesale', 'concrete blocks wholesale export', 'screen blocks bulk order', 'breeze block container price', 'wholesale concrete screen blocks', 'bulk breeze blocks importer', 'block exporter wholesale price', 'FOB breeze blocks Indonesia'],
                'related_countries' => ['australia', 'uk', 'usa', 'singapore', 'uae', 'canada'],
                'faqs' => [
                    ['q' => 'What is the minimum order quantity (MOQ) for wholesale export?', 'a' => 'Minimum Order Quantity: 1,000 pieces per pattern/colour. Recommended wholesale quantity: 1× 20ft FCL (approx. 5,000–7,000 pieces). 1× 40ft FCL: 10,000–14,000 pieces. Mixed pattern orders available.'],
                    ['q' => 'What is your FOB price for breeze blocks?', 'a' => 'FOB price depends on pattern complexity, quantity, and finish. Price range: USD 1.20–3.50 per piece FOB Tanjung Priok Jakarta. Request a formal quotation for your specific pattern and quantity.'],
                    ['q' => 'What payment terms do you offer for wholesale orders?', 'a' => 'Standard: 30% T/T deposit before production, 70% T/T balance before shipment. LC at sight accepted for orders above USD 20,000.'],
                    ['q' => 'Can I be an exclusive distributor for IndoRoster in my country?', 'a' => 'We are open to exclusive distribution agreements in markets without an existing partner. Requirements: minimum annual volume commitment and local market coverage plan. Contact our export desk.'],
                    ['q' => 'What documentation do you provide with wholesale shipments?', 'a' => 'Standard export documents: (1) Commercial Invoice, (2) Packing List, (3) Bill of Lading (B/L), (4) Certificate of Origin (FORM D for ASEAN or General SKA), (5) Phytosanitary Certificate if required.'],
                ],
                'how_to' => [
                    'name' => 'How to Place a Wholesale Breeze Block Export Order',
                    'steps' => [
                        ['name' => 'Send Inquiry with Quantity & Destination', 'text' => 'WhatsApp our export desk with: product pattern code(s), quantity in pieces, destination port, and required delivery date.'],
                        ['name' => 'Receive Formal FOB Quotation', 'text' => 'Within 24 hours, we send a formal quotation with unit price, container loading quantity, packing specification, and freight estimate.'],
                        ['name' => 'Negotiate & Confirm Order', 'text' => 'Confirm your order with a signed Purchase Order (PO). We issue a Proforma Invoice (PI) as the official order document.'],
                        ['name' => 'Pay Production Deposit', 'text' => 'Transfer 30% deposit by T/T. Production starts immediately upon deposit receipt.'],
                        ['name' => 'Production, QC & Loading', 'text' => 'Production: 2–4 weeks. QC inspection before packing. Loading into container with wooden pallet and foam separators.'],
                        ['name' => 'Shipment & Document Release', 'text' => 'Upon balance payment, we release: Bill of Lading, Commercial Invoice, Packing List, and Certificate of Origin.'],
                    ],
                ],
                'related_slugs' => ['fob-shipping', 'request-quote', 'breeze-blocks', 'concrete-screen-blocks'],
            ],

            'request-quote' => [
                'title' => 'Request Export Quotation — Breeze Blocks & Screen Blocks | IndoRoster',
                'description' => 'Request a free FOB export quotation for concrete breeze blocks, screen blocks, and architectural facade elements. Response within 24 hours.',
                'canonical' => '/export/request-quote',
                'h1' => 'Request Export Quotation — Factory Direct Breeze Blocks',
                'intro' => 'Ready to source architectural concrete breeze blocks for your project? Contact our Export Desk via WhatsApp. We respond to all export inquiries within 24 business hours with a formal Proforma Invoice including FOB unit price and estimated transit time to your port.',
                'keywords' => ['request breeze block quotation', 'breeze blocks export price', 'screen blocks FOB quote', 'concrete blocks import quotation', 'get breeze block price', 'architectural blocks export inquiry', 'IndoRoster quotation form', 'concrete export RFQ'],
                'related_countries' => [],
                'faqs' => [
                    ['q' => 'How quickly will I receive a quotation?', 'a' => 'All export inquiries via WhatsApp or contact form are responded to within 24 business hours (Mon–Sat, WIB = UTC+7).'],
                    ['q' => 'What information do I need to provide for a quotation?', 'a' => 'To receive an accurate quotation, please provide: (1) Product pattern code or description, (2) Approximate quantity in pieces or m², (3) Destination port and country, (4) Required delivery date, (5) Your project type (villa, hotel, commercial, distributor stock).'],
                    ['q' => 'Is there a fee for receiving a quotation?', 'a' => 'No. All export quotations are free and without obligation. We also offer free physical sample blocks (3–5 pieces) shipped via DHL/FedEx — ask about our sample program.'],
                ],
                'how_to' => null,
                'related_slugs' => ['wholesale', 'fob-shipping', 'breeze-blocks'],
            ],

            'fob-shipping' => [
                'title' => 'Breeze Blocks FOB Shipping from Indonesia — Export Logistics Guide | IndoRoster',
                'description' => 'Complete guide to importing architectural breeze blocks FOB from Java, Indonesia. Container sizes, transit times, ports, and documentation explained.',
                'canonical' => '/export/fob-shipping',
                'h1' => 'FOB Export Shipping — Importing Breeze Blocks from Indonesia',
                'intro' => 'This page explains how IndoRoster\'s export logistics work — from factory in Plered, Purwakarta to your destination port worldwide. Understanding FOB shipping, container types, and documentation will help you plan your import smoothly.',
                'keywords' => ['FOB breeze blocks Indonesia', 'import concrete blocks Indonesia', 'breeze block shipping container', 'FOB Tanjung Priok', 'concrete blocks sea freight', 'block exporter Indonesia logistics', 'import screen blocks', 'breeze block container Indonesia'],
                'related_countries' => ['singapore', 'australia', 'uk', 'usa', 'uae', 'malaysia'],
                'faqs' => [
                    ['q' => 'What does FOB mean for breeze block orders?', 'a' => 'FOB (Free On Board) means the price includes delivery to the ship at the origin port (Tanjung Priok Jakarta or Tanjung Perak Surabaya). The buyer pays for ocean freight, insurance, import duties, and destination port handling.'],
                    ['q' => 'What ports does IndoRoster use for export?', 'a' => 'Primary export port: Tanjung Priok (Jakarta) — IDJKT. Secondary: Tanjung Perak (Surabaya) — IDSURAB. FCL (full container load) and LCL (groupage) both available.'],
                    ['q' => 'How many breeze blocks fit in a 20ft container?', 'a' => 'A 20ft container holds approximately: 5,000–6,500 pieces (30×30×8cm blocks), or 7,000–9,000 pieces (20×20×8cm blocks). Weight limit: ~25 tonnes payload — concrete weight is the binding constraint for large format blocks.'],
                    ['q' => 'What are typical transit times from Indonesia?', 'a' => 'Singapore: 2–3 days. Malaysia: 2–4 days. Thailand/Philippines: 3–5 days. Australia: 7–14 days. UAE/Gulf: 12–16 days. UK/Europe: 22–28 days. USA West Coast: 18–22 days. USA East Coast: 24–30 days.'],
                    ['q' => 'What is the HS Code for breeze blocks?', 'a' => 'Standard HS Code for concrete screen/breeze blocks: 6810.19.90 (Articles of cement, concrete or artificial stone — Other). Some countries classify under 6810.91.00. Verify with your local customs broker.'],
                ],
                'how_to' => [
                    'name' => 'How to Import Breeze Blocks FOB from Indonesia',
                    'steps' => [
                        ['name' => 'Get Your FOB Quotation', 'text' => 'Contact IndoRoster Export Desk for FOB price, container capacity, and lead time.'],
                        ['name' => 'Hire a Licensed Freight Forwarder', 'text' => 'Engage a freight forwarder in your country to handle ocean freight booking, customs clearance, and delivery to your warehouse.'],
                        ['name' => 'Confirm Order & Pay Deposit', 'text' => 'Place your purchase order and pay the production deposit (30–50% T/T). We issue a Proforma Invoice (PI).'],
                        ['name' => 'Production at IndoRoster Factory', 'text' => 'Production lead time: 2–4 weeks. We send production progress photos via WhatsApp.'],
                        ['name' => 'Factory Loading & Export Clearance', 'text' => 'Goods loaded into container at Plered factory. Container trucks to Tanjung Priok Jakarta. Export clearance handled by our licensed export agent.'],
                        ['name' => 'Receive Documents & Shipment Tracking', 'text' => 'Upon vessel departure, we send B/L, Commercial Invoice, Packing List, and COO. Your freight forwarder handles import clearance at destination.'],
                    ],
                ],
                'related_slugs' => ['wholesale', 'request-quote', 'breeze-blocks', 'concrete-screen-blocks'],
            ],

        ]; // end configs

        return $configs[strtolower(trim($slug))] ?? null;
    }

    public function mount(string $contentSlug = '')
    {
        // Jika slug tidak dikirim sebagai parameter (karena route hardcoded tanpa {slug}),
        // ambil dari segment terakhir URL path secara otomatis
        if (empty($contentSlug)) {
            $contentSlug = last(explode('/', trim(request()->path(), '/')));
        }

        $this->contentSlug = strtolower(trim($contentSlug));

        if (static::getContentConfig($this->contentSlug) === null) {
            abort(404);
        }
    }

    public function render()
    {
        $config = static::getContentConfig($this->contentSlug);

        $featuredProducts = Product::where('is_active', true)
            ->with(['media', 'variants', 'category'])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $randomGallery = GalleryMedia::with('gallery.product')
            ->where('media_type', 'image')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        return view('livewire.export.export-content', [
            'config' => $config,
            'contentSlug' => $this->contentSlug,
            'featuredProducts' => $featuredProducts,
            'randomGallery' => $randomGallery,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $config['title'],
            'description' => $config['description'],
            'canonicalOverride' => url($config['canonical']),
        ]);
    }
}
