@php
if (! isset($scrollTo)) {
    $scrollTo = '#katalog-eksplorasi';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       const target = document.querySelector('{$scrollTo}') || \$el.closest('{$scrollTo}') || \$el.closest('[id]') || \$el;
       if (target) {
           target.scrollIntoView({ behavior: 'smooth', block: 'start' });
       }
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
            {{-- Mobile Pagination View --}}
            <div class="flex items-center justify-between w-full sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-400 dark:text-slate-600 bg-slate-100 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800 rounded-xl cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <button type="button" 
                            wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:border-terra-500 hover:text-terra-600 dark:hover:text-terra-400 transition-colors shadow-2xs active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        <span>Sebelumnya</span>
                    </button>
                @endif

                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 px-2">
                    {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>

                @if ($paginator->hasMorePages())
                    <button type="button" 
                            wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:border-terra-500 hover:text-terra-600 dark:hover:text-terra-400 transition-colors shadow-2xs active:scale-95 cursor-pointer">
                        <span>Berikutnya</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-400 dark:text-slate-600 bg-slate-100 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-800 rounded-xl cursor-not-allowed">
                        <span>Berikutnya</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                @endif
            </div>

            {{-- Desktop Pagination View --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between w-full">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Menampilkan
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
                        dari
                        <span class="font-bold text-slate-900 dark:text-white">{{ $paginator->total() }}</span>
                        motif
                    </p>
                </div>

                <div>
                    <div class="inline-flex items-center gap-1">
                        {{-- Previous Page Button --}}
                        @if ($paginator->onFirstPage())
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 dark:text-slate-700 border border-slate-200/70 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 cursor-not-allowed" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        @else
                            <button type="button" 
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-terra-50 dark:hover:bg-slate-700 hover:border-terra-400 hover:text-terra-600 dark:hover:text-terra-400 transition shadow-2xs cursor-pointer active:scale-95" 
                                    aria-label="Halaman Sebelumnya">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-slate-400 dark:text-slate-600">
                                    {{ $element }}
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-black text-white bg-terra-500 border border-terra-500 shadow-sm shadow-terra-500/30 ring-2 ring-terra-500/25 select-none">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <button type="button" 
                                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" 
                                                    x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-terra-50 dark:hover:bg-slate-700 hover:border-terra-400 hover:text-terra-600 dark:hover:text-terra-400 transition shadow-2xs cursor-pointer active:scale-95" 
                                                    aria-label="Ke halaman {{ $page }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Button --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" 
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-terra-50 dark:hover:bg-slate-700 hover:border-terra-400 hover:text-terra-600 dark:hover:text-terra-400 transition shadow-2xs cursor-pointer active:scale-95" 
                                    aria-label="Halaman Berikutnya">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                        @else
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 dark:text-slate-700 border border-slate-200/70 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 cursor-not-allowed" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    @endif
</div>
