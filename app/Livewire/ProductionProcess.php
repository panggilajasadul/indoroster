<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class ProductionProcess extends Component
{
    public $mainVideos = [
        [
            'title' => 'Uji Kekuatan Roster Beton di Lapangan',
            'video' => '',
            'description' => 'Simak bukti nyata kekuatan roster beton IndoRoster di lapangan. Roster kami teruji tahan retak, mampu menopang struktur dengan kuat, dan hasilnya tetap lurus presisi untuk pemasangan skala besar. Sangat cocok bagi Anda yang menginginkan fasad atau pagar rumah yang estetik sekaligus punya struktur yang sangat kokoh.',
            'subtitle' => 'DIBUAT DENGAN KETELITIAN TINGGI | 100% PRESISI',
            'features' => [
                [
                    'title' => 'Konstruksi Super Kokoh & Tahan Lama',
                    'desc' => 'Dirancang dengan komposisi beton padat berkualitas, roster kami tahan terhadap retak dan gupil (pecah sudut) bahkan pada konfigurasi dinding penahan beban sekalipun.',
                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                ],
                [
                    'title' => 'Ukuran Konsisten untuk Hasil Pasang yang Rapi',
                    'desc' => 'Berkat teknologi cetakan yang akurat, setiap keping roster memiliki ukuran yang seragam. Hasil pemasangan jadi lebih rapi, sejajar, dan estetis bahkan untuk dinding modul besar sekalipun.',
                    'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5z',
                ],
            ],
            'bottom_feature' => [
                'title' => 'Andalan untuk Penggunaan Jangka Panjang',
                'desc' => 'Roster kami tahan terhadap panas dan hujan tanpa risiko retak akibat pemuaian. Materialnya sangat solid sehingga tidak gampang retak saat proses instalasi, solusi terbaik untuk mempercantik dinding luar dan pagar Anda.',
                'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z',
            ],
        ],
        [
            'title' => 'Detail Produk Lebih Dekat',
            'video' => '',
            'description' => '"Melalui video detail produk ini, Anda dapat melihat lebih dekat permukaan roster, ketebalan dindingnya, kerapian sudut-sudutnya, hingga kualitas finishing di setiap sisi. Detail ini sangat penting untuk memastikan roster kami memenuhi standar proyek premium, baik untuk fasad, pagar, maupun kebutuhan interior."',
            'subtitle' => 'KUALITAS MATERIAL PREMIUM',
            'features' => [],
            'bottom_feature' => null,
        ],
    ];

    public $productionProcess = [
        [
            'title' => 'Cetakan Presisi untuk Motif Roster Rumit',
            'video' => '',
            'desc' => 'Intip proses cetak motif labirin kami yang detail. Video ini menunjukkan bagaimana motif rumit dibentuk dengan dimensi yang konsisten, menjamin hasil pemasangan yang rapi pada dinding Anda.',
        ],
        [
            'title' => 'Proses Cetak Manual Roster Minimalis',
            'video' => '',
            'desc' => 'Roster kami diproduksi melalui proses cetak manual yang terkontrol secara ketat. Setiap unit dipadatkan secara maksimal untuk menjamin kekuatan struktur dan hasil permukaan yang halus serta presisi.',
        ],
        [
            'title' => 'Pelepasan Cetakan & Finishing Awal',
            'video' => '',
            'desc' => 'Video ini menunjukkan tahap pelepasan cetakan, di mana roster yang baru dibentuk dikeluarkan dengan hati-hati untuk proses penguatan. Ketelitian pada tahap ini sangat penting untuk menjaga sudut roster tetap tajam dan simetris.',
        ],
        [
            'title' => 'Produksi Skala Besar',
            'video' => '',
            'desc' => 'Kami siap melayani kebutuhan proyek dalam skala besar dengan kapasitas produksi yang mumpuni tanpa mengurangi kualitas di setiap kepingnya.',
        ],
        [
            'title' => 'Pengiriman & Logistik',
            'video' => '',
            'desc' => 'Proses packing dan pengiriman yang aman untuk memastikan roster sampai di lokasi proyek Anda dalam kondisi sempurna.',
        ],
        [
            'title' => 'QC & Finishing Akhir',
            'video' => '',
            'desc' => 'Setiap roster melewati tahap Quality Control yang ketat untuk memastikan tidak ada cacat produksi sebelum dikirim ke pelanggan.',
        ],
    ];

    public function render()
    {
        $page = Page::where('slug', 'proses-produksi')->where('is_active', true)->first();

        $metaTitle = $page?->meta_title ?: 'Proses Produksi Roster Beton | INDOROSTER — Pabrik Plered Purwakarta';
        $metaDesc = $page?->meta_description ?: 'Transparansi proses produksi roster beton INDOROSTER dari cetakan hingga pengiriman. Cetak padat presisi tinggi dan quality control ketat untuk setiap keping roster.';

        return view('livewire.production-process', [
            'page' => $page,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => route('production'),
        ]);
    }
}
