@if(is_array($page->content) && count($page->content) > 0)
    <x-block-renderer :blocks="$page->content" />
@else
<div class="bg-white min-h-screen py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight mb-4">{{ $page->title }}</h1>
            <div class="w-24 h-1 bg-terra-500 mx-auto rounded-full"></div>
        </div>

        <!-- Content -->
        <div class="prose prose-lg prose-slate max-w-none prose-img:rounded-xl prose-a:text-terra-600 hover:prose-a:text-terra-700">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endif
