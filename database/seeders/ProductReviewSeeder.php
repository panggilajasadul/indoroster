<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        ProductReview::truncate();
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        $names = [
            'Hendra Saputra', 'Rina Maharani', 'Arief Budiman', 'Dewi Anggraeni', 'Fajar Hidayat',
            'Budi Santoso', 'Siti Nurhaliza', 'Agus Pramono', 'Lina Wijaya', 'Rudi Hartono',
            'Maya Indah', 'Putri Ayu', 'Ir. Bambang Wicaksono', 'Tommy Halim', 'Deni Kurniawan',
            'Yusuf Rahman', 'Ratna Sari', 'H. Ahmad Fauzi', 'Nadia Permata', 'Pak Joko',
            'Siska', 'Andi', 'Budi', 'Cici', 'Dedi', 'Euis', 'Fandi', 'Gita', 'Hadi', 'Ika',
            'Junaidi', 'Kiki', 'Lulu', 'Maman', 'Nana', 'Oki', 'Pipit', 'Qori', 'Rendy', 'Sari',
        ];

        $locations = [
            'Bekasi', 'Bandung', 'Tangerang', 'Surabaya', 'Depok', 'Semarang', 'Bogor', 'Cirebon',
            'Jakarta Selatan', 'Karawang', 'Tangerang Selatan', 'Jakarta Barat', 'BSD', 'Cilegon',
            'Malang', 'Purwakarta', 'Yogyakarta', 'Jakarta Timur', 'Solo', 'Medan',
        ];

        // Gaya TikTok/FB/Casual (Bintang 4-5)
        $casualReviews = [
            'Gak nyangka si pager jadi aestetik banget! Orang pada nanya belinya dimana 😂 Jadi berasa paling beda sendiri di komplek.',
            'Cakep parah hasilnya! Tadinya ragu tapi pas udah dipasang beneran bikin rumah keliatan mewah. Worth it parah sih ini 🔥',
            'Roster nya solid banget, betonnya kerasa premium. Pas bener buat gaya industrial rumah aku. Mantap bener dah pokoknya!',
            'Auto ganteng rumah gue pake ginian. Adminnya juga asik bangeettt dibantu bgt buat itung kebutuhannya. Thankyou ya!',
            'Gak nyesel beli disini, harga pabrik emang gak bohong. Kualitas bintang 5 harga kaki lima. Sukses terus ya buat tokonya!',
            'Barangnya sampe dengan selamat, roster nya tebal dan berat. Beneran kokoh bukan kaleng-kaleng 💪 Bakal langganan sih.',
            'Look-nya jadi mahal banget rumah gue. Gak sia-sia milih model ini, presisi bgt pas dipasang tukang. Seneng bangeeettt 😍',
            'Aestetik poll! Cocok bgt buat yang mau renovasi tipis-tipis tapi hasil maksimal. Tetangga ampe pada kepo nanya-nanya haha.',
            'Kualitas beton K-200 nya emang berasa beda. Solid dan gak gampang rompal. Rekomended bgt buat yang nyari kualitas.',
            'Baru kali ini beli roster online puas bgt. Admin fast respon, pengiriman juga sesuai jadwal. Top markotop!',
            'Mantul bgt sih ini, pager rumah jadi keliatan beda bgt dari yang lain. Banyak yg berhenti cuma buat liatin pager 😂',
            'Hasilnya sesuai ekspektasi, bahkan lebih bagus aslinya. Teksturnya cakep bgt buat finishing industrial.',
            'Puas bgt belanja disini, pelayanannya oke barangnya juga oke punya. Gak rugi pokoknya mah!',
            'Roster beton dolamitnya cakep bener, natural bgt warnanya. Bikin adem sirkulasi udara juga jadi lancar 🏠',
            'Sumpah ini bagus bgt, awalnya iseng coba eh malah ketagihan mau pasang di sisi lain juga. Racun bgt emang ginian wkwk.',
        ];

        // Gaya Storytelling/Problem Solver (Bintang 4-5)
        $storyReviews = [
            'Rumah saya tadinya gerah banget karena ventilasi minim. Setelah pasang roster di dinding samping, sirkulasi udara langsung berasa beda. Ruang tamu jadi adem tanpa AC, hemat listrik juga 🏠',
            'Saya developer perumahan, 30 unit semua pake roster dari Indoroster. Nilai jual rumah naik karena fasad-nya keliatan mewah. Buyer pada suka bgt sama look-nya.',
            'Cafe saya dulunya biasa aja, setelah renovasi pake roster di bagian depan, sekarang jadi spot foto favorit customer. Omzet naik bgt semenjak tampilannya jadi lebih aestetik 📈',
            'Memilih roster untuk pagar rumah minimalis saya adalah keputusan terbaik. Kesannya jadi modern tapi tetap homey. Beneran bikin betah liatin rumah sendiri.',
            'Udah bandingin harga ke banyak toko, disini emang paling miring tapi kualitasnya gak main-main. Betonnya beneran kokoh dan presisi.',
        ];

        // Gaya Review Kurang Puas (Bintang 1-3) - Masalah di pengiriman/respon, tapi kualitas barang tetap dipuji
        $disappointedReviews = [
            'Kualitas barangnya sih mantap pol, beton kokoh bgt. Cuma pengirimannya lama banget tolong ditingkatkan lagi ya. Sayang bgt barang bagus tapi sampenya telat.',
            'Barangnya bagus banget beneran gak nyesel beli. Tapi adminnya slow respon pas ditanya status kiriman. Jadi agak was-was nunggunya.',
            'Roster nya sih juara, tebal dan rapi finishingnya. Tapi kurirnya agak kurang ramah pas anter barang. Semoga kedepannya lebih oke lagi.',
            'Puas bgt sama barangnya, beneran kokoh. Cuma waktu pengiriman meleset 2 hari dari jadwal. Overall buat kualitas barang gak ada lawan deh.',
            'Barangnya sih bintang 5, cakep bgt dipasang di pager. Cuma respon admin pas mau order agak lama. Kualitas roster nya top bgt padahal.',
            'Sebenernya barangnya bagus bgt, puas sama fisiknya. Cuma rada kecewa sama estimasi sampenya yang lama. Tingkatkan lagi ya pelayanannya.',
            'Betonnya solid bgt, K-200 beneran berasa. Tapi pengiriman ke rumah saya agak ribet kemarin koordinasinya. Kalo barangnya sih oke bgt.',
            'Udah dipasang dan hasilnya bagus bgt, kualitas juara. Cuma pengirimannya aja sih yang perlu diperbaiki biar gak terlalu lama nunggunya.',
        ];

        foreach ($products as $product) {
            // Update total sold
            $product->update(['total_sold' => rand(10000, 15000)]);

            // Generate 250 reviews per product (total 1000 for 4 products)
            for ($i = 0; $i < 250; $i++) {
                $type = rand(1, 100);
                $rating = 5;
                $content = '';
                $rand = rand(1, 100);

                if ($rand <= 60) {
                    // Bintang 5
                    $rating = 5;
                    $content = $type <= 70 ? Arr::random($casualReviews) : Arr::random($storyReviews);
                } elseif ($rand <= 85) {
                    // Bintang 4
                    $rating = 4;
                    $content = $type <= 80 ? Arr::random($casualReviews) : Arr::random($storyReviews);
                } elseif ($rand <= 95) {
                    // Bintang 3
                    $rating = 3;
                    $content = Arr::random($disappointedReviews);
                } elseif ($rand <= 98) {
                    // Bintang 2
                    $rating = 2;
                    $content = Arr::random($disappointedReviews);
                } else {
                    // Bintang 1
                    $rating = 1;
                    $content = Arr::random($disappointedReviews);
                }

                ProductReview::create([
                    'product_id' => $product->id,
                    'reviewer_name' => Arr::random($names),
                    'reviewer_location' => Arr::random($locations),
                    'rating' => $rating,
                    'content' => $content,
                    'is_approved' => true,
                    'is_seeded' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 365))->subMinutes(rand(1, 1440)),
                ]);
            }
        }
    }
}
