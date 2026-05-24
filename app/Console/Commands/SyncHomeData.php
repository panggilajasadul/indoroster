<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;
use App\Models\Banner;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncHomeData extends Command
{
    protected $signature = 'home:sync';
    protected $description = 'Synchronize homepage data from models and hardcoded content to Page Builder';

    public function handle()
    {
        $this->info('Starting synchronization...');

        // 1. Fetch Banners from Banner Model
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $heroBanners = [];

        foreach ($banners as $banner) {
            $localPath = $this->downloadImage($banner->image_url, 'pages/hero');
            $heroBanners[] = [
                'image' => $localPath ?: $banner->image_url,
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'button_text' => $banner->button_text,
                'button_url' => $banner->button_url,
            ];
        }

        // 2. Define Blocks with actual content from original home.blade.php
        // We will also try to download images for blocks to make them work in Filament Editor
        
        $showcaseImages = [
            'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765259930/47_dmjh8d.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765259848/sg-11134201-7ra3x-mbga48q8qh9x40_resize_w450_nl_f9jbbk.webp',
            'https://res.cloudinary.com/indoroster/image/upload/v1765259830/36_vaxh6k.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765260059/477127145_935487138780264_8156628137020905763_n_koes6o.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765259896/87_pikio2.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765259870/146480918_962561287611643_2630009701372432663_n_gugfhr.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765260071/19_aaa6uf.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765260029/17_ifv8eh.jpg',
            'https://res.cloudinary.com/indoroster/image/upload/v1765260857/162301330_988931014974670_4453781190506425580_n_iu9gd2.jpg'
        ];
        
        $localShowcase = [];
        foreach ($showcaseImages as $url) {
            $localShowcase[] = $this->downloadImage($url, 'pages/showcase') ?: $url;
        }

        $galleryItems = [
            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg', 'title' => 'Minimalist Facade'],
            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg', 'title' => 'Industrial Interior'],
            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/q_auto,f_auto,w_600/v1765260049/40_kt08ee.jpg', 'title' => 'Modern Paving'],
        ];

        foreach ($galleryItems as &$item) {
            $item['image'] = $this->downloadImage($item['image'], 'pages/gallery') ?: $item['image'];
        }

        $content = [
            [
                'type' => 'hero',
                'data' => [
                    'banners' => $heroBanners,
                    'slider_duration' => 5000,
                ]
            ],
            [
                'type' => 'ticker',
                'data' => [
                    'items' => [
                        ['text' => '5000+ Proyek Selesai'],
                        ['text' => 'Pabrik Tangan Pertama'],
                        ['text' => 'Garansi Pecah Ganti Baru'],
                        ['text' => 'Pengiriman Seluruh Indonesia'],
                        ['text' => 'Kualitas Beton K-200'],
                    ]
                ]
            ],
            [
                'type' => 'visual_showcase',
                'data' => [
                    'title' => 'Tampilan rumah jadi <span class="text-accent">3x lebih mewah</span><br> hanya dengan sentuhan Roster Minimalis.',
                    'images' => $localShowcase
                ]
            ],
            [
                'type' => 'strength_test',
                'data' => [
                    'title' => 'Seberapa Kuat <br><span class="text-accent text-6xl">Roster Kami?</span>',
                    'description' => 'Dibuat dengan beton kualitas <span class="font-bold text-black">K-200</span> dan teknik pengepresan maksimal. Roster kami dirancang untuk tahan banting, tahan cuaca, dan tetap kokoh hingga puluhan tahun.',
                    'video_url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765639289/1213_h2d5wy.mp4',
                    'features' => [
                        ['title' => 'Anti Pecah', 'desc' => 'Beton padat tanpa rongga udara.'],
                        ['title' => 'Kuat Tekan', 'desc' => 'Lulus uji beban berat konstruksi.'],
                    ]
                ]
            ],
            [
                'type' => 'featured_products',
                'data' => [
                    'title' => 'Motif <span class="text-accent">Best Seller</span> <br>Bulan Ini',
                    'limit' => 4
                ]
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'title' => 'Kenapa Memilih Roster Beton Minimalis Indoroster?',
                    'description' => 'Sebagai produsen tangan pertama pabrik roster beton Plered Purwakarta, kami memproduksi loster dengan standar tinggi K-200. Kami telah melayani ribuan proyek mulai dari rumah minimalis hingga komersial di seluruh Indonesia.',
                    'items' => [
                        ['title' => 'Kualitas Premium', 'content' => 'Campuran semen dan pasir pilihan, diproses dengan mesin press hidrolik tinggi menghasilkan roster yang sangat kuat dan presisi.'],
                        ['title' => 'Langsung dari Pabrik', 'content' => 'Harga tangan pertama yang jauh lebih murah dibandingkan toko material, tanpa mengorbankan kualitas.'],
                        ['title' => 'Garansi Pengiriman', 'content' => 'Pecah di jalan? Kami ganti! Tim ekspedisi kami sangat berpengalaman menangani material pecah belah.'],
                    ],
                    'videos' => [
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765640938/1213_5_frvqcr.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765642314/432_nej3an.mp4'],
                    ]
                ]
            ],
            [
                'type' => 'shipping_info',
                'data' => [
                    'badge' => 'Pengiriman Seluruh Indonesia',
                    'title' => 'Pusat Jual Roster Beton Murah Jabodetabek & Nasional',
                    'content' => 'Sebagai pusat produksi tangan pertama di <strong>Plered, Purwakarta</strong>, armada truk kami siap mengirimkan pesanan partai kecil maupun besar langsung ke lokasi proyek Anda di <strong>Jakarta, Bogor, Depok, Tangerang, Bekasi (Jabodetabek)</strong>, Bandung, Cirebon, hingga pengiriman via ekspedisi khusus ke seluruh wilayah Indonesia dengan garansi aman sampai tujuan.',
                    'video_url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765263080/1_beaclb.mp4',
                    'button_text' => 'Cek Ongkir ke Lokasi Saya',
                    'button_url' => ''
                ]
            ],
            [
                'type' => 'social_review',
                'data' => [
                    'badge' => 'Viral on TikTok',
                    'title' => 'Lihat Langsung <br><span class="text-accent italic text-5xl md:text-7xl">Review Kreator</span>',
                    'description' => 'Dengarkan pengalaman langsung dari para ahli dekorasi dan kreator rumah tentang kualitas roster beton kami. Real testimony, real quality.',
                    'video_url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765259110/review_ttddr5.mp4',
                    'creators_count' => '100+'
                ]
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'title' => 'Kata Pelanggan Kami',
                    'mode' => 'latest'
                ]
            ],
            [
                'type' => 'gallery_grid',
                'data' => [
                    'badge' => 'Transformation Stories',
                    'title' => 'Proyek yang <span class="text-accent italic">Berbicara</span>',
                    'description' => 'Inspirasi pemasangan roster dari proyek nyata pelanggan kami di seluruh Indonesia.',
                    'items' => $galleryItems
                ]
            ],
            [
                'type' => 'ugc_videos',
                'data' => [
                    'badge' => 'Visual Experience',
                    'title' => 'Lihat <span class="text-accent italic">Detailnya</span> <br>Lebih Dekat',
                    'description' => 'Kami percaya bahwa melihat adalah percaya. Koleksi video inspirasi kami menunjukkan bagaimana cahaya dan udara mengalir melalui setiap celah roster kami.',
                    'videos' => [
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765259348/15_lhowif.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765259277/7_upqkhz.mp4'],
                    ]
                ]
            ],
            [
                'type' => 'cta',
                'data' => [
                    'badge' => 'Ready to start?',
                    'title' => 'Wujudkan Hunian <br>Impian Anda <span class="italic">Sekarang</span>',
                    'button_text' => 'Hubungi WhatsApp Sekarang',
                    'button_url' => ''
                ]
            ],
        ];

        // 3. Save to database
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content' => $content,
                'is_active' => true,
            ]
        );

        $this->info('Homepage data synchronized successfully with real original content and local images!');
    }

    private function downloadImage($url, $directory)
    {
        if (empty($url) || !str_starts_with($url, 'http')) {
            return $url;
        }

        try {
            $contents = file_get_contents($url);
            if ($contents === false) return null;

            $filename = basename(parse_url($url, PHP_URL_PATH));
            if (empty($filename)) $filename = Str::random(10) . '.jpg';

            $path = $directory . '/' . $filename;
            Storage::disk('public')->put($path, $contents);

            return $path;
        } catch (\Exception $e) {
            $this->error("Failed to download image: $url - " . $e->getMessage());
            return null;
        }
    }
}
