<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\ArticleCategory;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleList extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $categorySlug = '';

    #[Url(as: 'tag')]
    public string $tag = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategorySlug()
    {
        $this->resetPage();
    }

    public function updatedTag()
    {
        $this->resetPage();
    }

    public function setCategory(string $slug = '')
    {
        $this->categorySlug = $slug;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categorySlug = '';
        $this->tag = '';
        $this->resetPage();
    }

    public function render()
    {
        $categories = ArticleCategory::where('is_active', true)
            ->withCount(['articles' => function ($query) {
                $query->published();
            }])
            ->get();

        $activeCategory = null;
        if (! empty($this->categorySlug)) {
            $activeCategory = $categories->firstWhere('slug', $this->categorySlug);
        }

        $query = Article::query()
            ->with('category')
            ->published()
            ->search($this->search);

        if ($activeCategory) {
            $query->where('article_category_id', $activeCategory->id);
        }

        if (! empty($this->tag)) {
            $query->whereJsonContains('tags', $this->tag);
        }

        // Featured article for hero banner (only shown on page 1 without active filters)
        $featuredArticle = null;
        if ($this->getPage() === 1 && empty($this->search) && empty($this->categorySlug) && empty($this->tag)) {
            $featuredArticle = (clone $query)->where('is_featured', true)->latest('published_at')->first();
            if (! $featuredArticle) {
                $featuredArticle = (clone $query)->latest('published_at')->first();
            }

            if ($featuredArticle) {
                $query->where('id', '!=', $featuredArticle->id);
            }
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(9);

        $pageTitle = 'Artikel, Tips Bangunan & Inspirasi Roster Beton';
        $pageDesc = 'Kumpulan artikel arsitektural, panduan teknis pemasangan dinding roster beton minimalis, inspirasi fasad tropis, dan edukasi mutu material dari IndoRoster.';

        if ($activeCategory) {
            $pageTitle = 'Kategori: '.$activeCategory->name.' - IndoRoster';
            $pageDesc = $activeCategory->description ?: $pageDesc;
        }

        return view('livewire.article-list', [
            'articles' => $articles,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'featuredArticle' => $featuredArticle,
        ])->layout('components.layouts.app', [
            'title' => $pageTitle.' - IndoRoster',
            'description' => $pageDesc,
            'keywords' => 'artikel roster beton, inspirasi fasad minimalis, tips pasang roster, panduan arsitektur, cara hitung roster, indoroster purwakarta',
            'ogType' => 'website',
        ]);
    }
}
