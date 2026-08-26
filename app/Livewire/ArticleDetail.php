<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleDetail extends Component
{
    public Article $article;

    public function mount(string $slug)
    {
        $this->article = Article::with('category')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment view count safely once per session per article
        $sessionKey = 'viewed_article_'.$this->article->id;
        if (! session()->has($sessionKey)) {
            $this->article->increment('views_count');
            session()->put($sessionKey, true);
        }
    }

    public function render()
    {
        $relatedArticles = Article::published()
            ->where('id', '!=', $this->article->id)
            ->when($this->article->article_category_id, function ($q) {
                $q->where('article_category_id', $this->article->article_category_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // If not enough related in same category, fill with latest articles
        if ($relatedArticles->count() < 3) {
            $fillers = Article::published()
                ->where('id', '!=', $this->article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->concat($fillers);
        }

        $metaTitle = $this->article->meta_title ?: ($this->article->title.' - IndoRoster');
        $metaDescription = $this->article->meta_description ?: ($this->article->excerpt ?: Str::limit(strip_tags($this->article->content), 155));
        $metaKeywords = $this->article->meta_keywords ?: 'roster beton, fasad minimalis, roster plered, arsitektur tropis, indoroster';

        return view('livewire.article-detail', [
            'relatedArticles' => $relatedArticles,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'ogImage' => $this->article->thumbnail_url,
            'ogType' => 'article',
            'keywords' => $metaKeywords,
            'canonicalOverride' => route('article.detail', $this->article->slug),
        ]);
    }
}
