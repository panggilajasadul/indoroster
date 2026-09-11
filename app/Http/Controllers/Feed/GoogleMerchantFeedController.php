<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;

class GoogleMerchantFeedController extends Controller
{
    /**
     * Generate Google Merchant Center RSS 2.0 XML Product Feed
     * Category ID 5543: Hardware > Building Consumables > Masonry Materials
     */
    public function index(): Response
    {
        $products = Product::where('is_active', true)
            ->with(['category', 'media'])
            ->orderBy('id', 'asc')
            ->get();

        $siteUrl = config('app.url', 'https://indoroster.com');
        $siteTitle = 'IndoRoster — Katalog Produk Roster Beton Plered Purwakarta';
        $siteDescription = 'Feed produk resmi IndoRoster untuk Google Merchant Center & Google Shopping (Free Listings). Produsen roster beton cetak tumbuk plat baja presisi Plered Purwakarta.';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'."\n";
        $xml .= '  <channel>'."\n";
        $xml .= '    <title>'.htmlspecialchars($siteTitle, ENT_XML1, 'UTF-8').'</title>'."\n";
        $xml .= '    <link>'.htmlspecialchars($siteUrl, ENT_XML1, 'UTF-8').'</link>'."\n";
        $xml .= '    <description>'.htmlspecialchars($siteDescription, ENT_XML1, 'UTF-8').'</description>'."\n";

        foreach ($products as $product) {
            $id = 'IR-'.($product->sku ?: str_pad((string) $product->id, 4, '0', STR_PAD_LEFT));

            // Bersihkan nama produk dari duplikasi kata dan entitas HTML
            $rawName = html_entity_decode((string) $product->name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleanName = trim(preg_replace('/^(roster\s+|loster\s+)+/i', '', $rawName));
            $cleanName = preg_replace('/\s+/', ' ', $cleanName);

            $dim = $product->dimensions ? trim($product->dimensions) : '20x20x10 cm';
            if (! str_contains(strtolower($dim), 'cm')) {
                $dim .= ' cm';
            }

            // Title SEO Bersih & Profesional untuk Google Shopping
            $title = 'Roster Beton Minimalis '.$cleanName.' '.$dim.' - IndoRoster';
            if (mb_strlen($title) > 150) {
                $title = mb_substr($title, 0, 147).'...';
            }

            // Deskripsi Produk SEO Murni Tanpa Kode HTML Mentah (&nbsp;)
            $material = $product->material ?: 'Pasir abu batu murni / dolomit putih / terakota merah';
            $cleanDesc = "Roster beton minimalis motif {$cleanName} ukuran {$dim}. Diproduksi langsung oleh pabrik IndoRoster sentra Plered Purwakarta dengan metode cetak tumbuk padat plat baja presisi siku 90°. Bahan baku alami berkualitas tahan lumut ({$material}), bobot padat 3.8-4.2 kg, ideal untuk fasad secondary skin peredam panas 40%, pagar, dan partisi ventilasi anti-tampias. Jaminan garansi 100% ganti baru jika pecah di jalan.";

            $productUrl = route('product.detail', $product->slug);
            $imageUrl = $product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG');
            // Pastikan URL gambar selalu absolut HTTPS di produksi
            if (str_starts_with($imageUrl, 'http://') && ! str_contains($imageUrl, 'localhost')) {
                $imageUrl = 'https://'.substr($imageUrl, 7);
            }

            // Format nominal harga (GMC mewajibkan format e.g. "13000.00 IDR")
            $priceNominal = $product->price && $product->price > 0 ? (float) $product->price : 13000.0;
            $formattedPrice = number_format($priceNominal, 2, '.', '').' IDR';

            // Produk pabrik aktif selalu siap produksi (in_stock)
            $stockStatus = 'in_stock';

            $xml .= '    <item>'."\n";
            $xml .= '      <g:id>'.htmlspecialchars($id, ENT_XML1, 'UTF-8').'</g:id>'."\n";
            $xml .= '      <g:title>'.htmlspecialchars($title, ENT_XML1, 'UTF-8').'</g:title>'."\n";
            $xml .= '      <g:description>'.htmlspecialchars($cleanDesc, ENT_XML1, 'UTF-8').'</g:description>'."\n";
            $xml .= '      <g:link>'.htmlspecialchars($productUrl, ENT_XML1, 'UTF-8').'</g:link>'."\n";
            $xml .= '      <g:image_link>'.htmlspecialchars($imageUrl, ENT_XML1, 'UTF-8').'</g:image_link>'."\n";
            $xml .= '      <g:availability>'.$stockStatus.'</g:availability>'."\n";
            $xml .= '      <g:price>'.$formattedPrice.'</g:price>'."\n";
            $xml .= '      <g:brand>IndoRoster</g:brand>'."\n";
            $xml .= '      <g:condition>new</g:condition>'."\n";
            $xml .= '      <g:google_product_category>5543</g:google_product_category>'."\n";
            $xml .= '      <g:product_type>Bahan Bangunan &gt; Roster Beton Minimalis</g:product_type>'."\n";
            $xml .= '      <g:identifier_exists>no</g:identifier_exists>'."\n";
            $xml .= '    </item>'."\n";
        }

        $xml .= '  </channel>'."\n";
        $xml .= '</rss>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
