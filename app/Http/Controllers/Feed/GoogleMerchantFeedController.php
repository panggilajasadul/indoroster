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
            $title = 'Roster Beton Minimalis '.$product->name.' 20x20 cm - IndoRoster';

            $rawDesc = $product->description ?: $product->short_description;
            if (empty($rawDesc)) {
                $rawDesc = "Roster beton minimalis motif {$product->name} ukuran 20x20x10 cm cetak tumbuk padat plat baja presisi pengrajin sentra Plered Purwakarta. Menggunakan bahan baku alami berkualitas tahan lumut. Garansi pengiriman aman ganti baru 100%.";
            } else {
                $rawDesc = strip_tags($rawDesc);
            }
            $cleanDesc = trim(preg_replace('/\s+/', ' ', $rawDesc));
            if (mb_strlen($cleanDesc) > 4900) {
                $cleanDesc = mb_substr($cleanDesc, 0, 4900).'...';
            }

            $productUrl = route('product.detail', $product->slug);
            $imageUrl = $product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG');

            // Format nominal harga (GMC mewajibkan format e.g. "13000.00 IDR")
            $priceNominal = $product->price && $product->price > 0 ? (float) $product->price : 13000.0;
            $formattedPrice = number_format($priceNominal, 2, '.', '').' IDR';

            $stockStatus = ($product->stock === null || $product->stock > 0) ? 'in_stock' : 'out_of_stock';

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
            $xml .= '      <g:shipping>'."\n";
            $xml .= '        <g:country>ID</g:country>'."\n";
            $xml .= '        <g:service>Armada Pabrik IndoRoster</g:service>'."\n";
            $xml .= '        <g:price>0.00 IDR</g:price>'."\n";
            $xml .= '      </g:shipping>'."\n";
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
