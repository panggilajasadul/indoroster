<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Product;
use App\Models\Testimonial;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $page = Page::where('slug', 'home')->first();

        $metaTitle = $page?->meta_title ?: 'Produsen & Supplier Roster Beton No. 1 di Indonesia — IndoRoster';
        $metaDescription = $page?->meta_description ?: 'Pabrik & produsen roster beton minimalis terbesar di Indonesia. Supplier loster cetak padat presisi harga pabrik Plered Purwakarta, siap kirim partai kecil & proyek ke seluruh Indonesia.';

        return view('livewire.home', [
            'page' => $page,
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'featuredProducts' => Product::where('is_active', true)->with('media', 'variants', 'category')->latest()->take(8)->get(),
            'viralProducts' => Product::viral()->take(6)->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(),
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'canonicalOverride' => route('home'),
        ]);
    }
}
