<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\Faq;

class Home extends Component
{
    public function render()
    {
        $page = Page::where('slug', 'home')->first();

        return view('livewire.home', [
            'page' => $page,
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'featuredProducts' => Product::where('is_active', true)->with('media', 'variants', 'category')->latest()->take(8)->get(),
            'viralProducts' => Product::viral()->take(6)->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->take(6)->get(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(),
        ])->layout('components.layouts.app', [
            'title' => $page?->meta_title ?? 'Pabrik Roster Beton Minimalis Plered Purwakarta | Indoroster Jabodetabek & Indonesia',
            'description' => $page?->meta_description ?? 'Pusat pembuatan dan jual Roster Beton Minimalis Plered Purwakarta. Produsen tangan pertama, harga pabrik termurah, kualitas K-200. Siap kirim ke seluruh Jabodetabek dan Indonesia.',
        ]);
    }
}
