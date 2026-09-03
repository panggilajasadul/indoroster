<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Product;
use App\Models\SeoLocation;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $page = Page::where('slug', 'home')->first();

        $metaTitle = $page?->meta_title
            ?: SiteSetting::getValue('meta_title_default', 'Pabrik Roster Beton Minimalis | Suplier Proyek Jabodetabek & Indonesia');
        $metaDescription = $page?->meta_description
            ?: SiteSetting::getValue('meta_description_default', 'Pusat produsen tangan pertama roster beton minimalis, bata expose, dan loster arsitektural modern harga pabrik Plered Purwakarta.');

        $topLocations = class_exists(SeoLocation::class)
            ? SeoLocation::where('seo_enabled', true)->orderBy('priority', 'asc')->take(16)->get()
            : collect();

        return view('livewire.home', [
            'page' => $page,
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'featuredProducts' => Product::where('is_active', true)->with('media', 'variants', 'category')->latest()->take(8)->get(),
            'viralProducts' => Product::viral()->take(6)->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(),
            'topLocations' => $topLocations,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'canonicalOverride' => route('home'),
        ]);
    }
}
