<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Page;

class DynamicPage extends Component
{
    public Page $page;

    public function mount($slug = null)
    {
        if (!$slug && request()->routeIs('gallery')) {
            $slug = 'gallery';
        }
        $this->page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.dynamic-page')->layout('components.layouts.app', [
            'title' => $this->page->meta_title ?: ($this->page->title . ' - Indoroster'),
        ]);
    }
}
