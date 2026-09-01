<?php

namespace App\Livewire\Application;

use App\Models\SiteSetting;
use Livewire\Component;

class ApplicationHub extends Component
{
    public function getApplicationsProperty(): array
    {
        return [
            [
                'slug' => 'pagar-rumah',
                'title' => 'Pagar Rumah Minimalis',
                'subtitle' => 'Kombinasi privasi, sirkulasi udara, dan estetika modern untuk batas hunian.',
                'icon' => '🏡',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                'recommended_motifs' => ['Nako Sipit', 'Nako LS', 'MMC', 'Petir'],
            ],
            [
                'slug' => 'fasad-rumah',
                'title' => 'Fasad Rumah Tropis Modern',
                'subtitle' => 'Secondary skin penangkal panas matahari langsung dengan pola bayangan arsitektural.',
                'icon' => '🏛️',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260049/40_kt08ee.jpg',
                'recommended_motifs' => ['Petir', 'Arrow', 'JaboL', 'MMC'],
            ],
            [
                'slug' => 'ventilasi-dinding',
                'title' => 'Ventilasi Dinding & Lubang Angin',
                'subtitle' => 'Sirkulasi udara alami bebas pengap untuk dapur, kamar mandi, dan ruang keluarga.',
                'icon' => '💨',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
                'recommended_motifs' => ['Nako Sipit', 'Kotak 4', 'Bintang', 'PCL'],
            ],
            [
                'slug' => 'partisi-ruangan',
                'title' => 'Sekat Partisi Ruangan Interior',
                'subtitle' => 'Pembatas ruang fleksibel dan estetik yang menjaga keterbukaan visual.',
                'icon' => '🚪',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg',
                'recommended_motifs' => ['Batman', 'MMC', 'Nako LS', 'Petir'],
            ],
            [
                'slug' => 'void-tangga',
                'title' => 'Dinding Void Tangga & Skylight',
                'subtitle' => 'Meneruskan cahaya alami ke area tangga tanpa membuat ruangan terasa sempit.',
                'icon' => '🪜',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259870/146480918_962561287611643_2630009701372432663_n_gugfhr.jpg',
                'recommended_motifs' => ['Nako Sipit', 'Petir', 'JaboL'],
            ],
            [
                'slug' => 'fasad-cafe',
                'title' => 'Fasad Cafe & Resto Industrial',
                'subtitle' => 'Spot foto instagramable dan daya tarik visual unik untuk bisnis kuliner.',
                'icon' => '☕',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
                'recommended_motifs' => ['Petir', 'MMC', 'Arrow', 'PCL'],
            ],
            [
                'slug' => 'ruko',
                'title' => 'Fasad Ruko & Commercial Building',
                'subtitle' => 'Transformasi visual fasad ruko menjadi bangunan komersial bernilai sewa tinggi.',
                'icon' => '🏢',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260059/477127145_935487138780264_8156628137020905763_n_koes6o.jpg',
                'recommended_motifs' => ['Nako LS', 'MMC', 'Kotak Kasar'],
            ],
            [
                'slug' => 'perumahan-cluster',
                'title' => 'Gerbang & Fasad Klaster Perumahan',
                'subtitle' => 'Keseragaman estetika mewah untuk gerbang utama dan fasad tipe rumah developer.',
                'icon' => '🏘️',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259830/36_vaxh6k.jpg',
                'recommended_motifs' => ['Nako Sipit', 'MMC', 'Petir', 'Bintang'],
            ],
            [
                'slug' => 'gedung-komersial',
                'title' => 'Fasad Gedung, Hotel & Kantor',
                'subtitle' => 'Dinding secondary skin berskala ribuan pcs dengan efisiensi pendinginan AC alami.',
                'icon' => '🏨',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                'recommended_motifs' => ['MMC', 'Petir', 'Arrow', 'Nako Sipit'],
            ],
            [
                'slug' => 'interior-cafe',
                'title' => 'Interior Bar & Backdrop Cafe',
                'subtitle' => 'Aksen meja kasir, bar counter, dan background photo booth bernuansa industrial.',
                'icon' => '🍸',
                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
                'recommended_motifs' => ['Batman', 'MMC', 'JaboL'],
            ],
        ];
    }

    public function render()
    {
        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $metaTitle = 'Inspirasi Aplikasi Desain Roster Beton Minimalis | IndoRoster';
        $metaDescription = 'Koleksi lengkap ide pengaplikasian roster beton minimalis untuk pagar, fasad rumah, ventilasi dinding, partisi ruangan, cafe, ruko, dan proyek komersial.';

        return view('livewire.application.application-hub', [
            'applications' => $this->applications,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'canonicalOverride' => route('application.index'),
            'keywords' => 'aplikasi roster beton, roster pagar, roster fasad, roster partisi ruangan, loster cafe, roster void tangga, roster beton minimalis',
        ]);
    }
}
