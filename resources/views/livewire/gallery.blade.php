<div class="py-12 bg-gray-50 min-h-screen" x-data="{ 
    fullSizeModal: {{ $initialActiveIndex !== null ? 'true' : 'false' }}, 
    activeIndex: {{ $initialActiveIndex !== null ? $initialActiveIndex : 0 }}, 
    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }}, 
    commentDrawerOpen: false, 
    activePhotoId: @entangle('activePhotoId'), 
    photos: @entangle('photos'),
    showToast: false,
    toastMessage: ''
}" x-init="
    $watch('activePhotoId', id => {
        if (id && photos) {
            const idx = photos.findIndex(p => p.id === id);
            if (idx !== -1) { activeIndex = idx; }
        }
    });
    $watch('photos', list => {
        if (activePhotoId && list) {
            const idx = list.findIndex(p => p.id === activePhotoId);
            if (idx !== -1) { activeIndex = idx; }
        }
    });
">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" wire:loading.class="opacity-50 transition-opacity">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="font-display text-fluid-h1 font-black text-slate-900 mb-4 tracking-tight">
                {!! $title !!}
            </h1>
            <div class="w-24 h-1.5 bg-terra-500 mx-auto rounded-full mb-6"></div>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                {{ $description }}
            </p>
        </div>

        <!-- Filters & Sorting -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <!-- Categories -->
            <div class="flex flex-wrap gap-2.5 md:gap-3">
                <button 
                    type="button"
                    wire:click="setTab('all')" 
                    class="group relative px-6 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 overflow-hidden
                    {{ $activeTab === 'all' 
                        ? 'bg-terra-500 text-white shadow-[0_10px_20px_-5px_rgba(247,92,32,0.4)] translate-y-[-2px]' 
                        : 'bg-white text-slate-500 border border-slate-200 hover:border-terra-300 hover:text-terra-600 hover:shadow-md hover:translate-y-[-2px]' 
                    }}">
                    <span class="relative z-10">SEMUA</span>
                    @if($activeTab !== 'all')
                        <div class="absolute inset-0 bg-terra-50 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    @endif
                </button>

                @foreach($availableCategories as $category)
                    <button 
                        type="button"
                        wire:click="setTab('{{ $category }}')" 
                        class="group relative px-6 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 overflow-hidden
                        {{ $activeTab === $category 
                            ? 'bg-terra-500 text-white shadow-[0_10px_20px_-5px_rgba(247,92,32,0.4)] translate-y-[-2px]' 
                            : 'bg-white text-slate-500 border border-slate-200 hover:border-terra-300 hover:text-terra-600 hover:shadow-md hover:translate-y-[-2px]' 
                        }}">
                        <span class="relative z-10">{{ strtoupper($category) }}</span>
                        @if($activeTab !== $category)
                            <div class="absolute inset-0 bg-terra-50 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Photo Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-container">
            @foreach($photos as $index => $image)
                <div 
                    @click="activeIndex = {{ $index }}; activePhotoId = '{{ $image['id'] }}'; fullSizeModal = true"
                    wire:key="img-{{ $activeTab }}-{{ $loop->index }}"
                    class="group relative aspect-square overflow-hidden rounded-2xl bg-white cursor-pointer shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500 ease-out">
                    
                    {{-- Link Badge if associated with product --}}
                    @if(!empty($image['product']))
                        <div class="absolute top-4 left-4 z-10 pointer-events-auto">
                            <div 
                                @click.stop="window.location.href = '{{ url('/produk/' . $image['product']['slug']) }}'"
                                class="product-badge-btn inline-flex items-center gap-2 px-3 py-1.5 bg-white/95 backdrop-blur-md rounded-xl text-slate-800 text-[10px] md:text-xs font-bold border border-slate-200/50 shadow-md hover:bg-terra-500 hover:text-white hover:border-terra-500 hover:scale-105 transition-all duration-300 group/badge cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-terra-500 flex-shrink-0 group-hover/badge:text-white transition-colors">
                                    <path fill-rule="evenodd" d="M6 5v1h8V5a4 4 0 0 0-8 0ZM4 8.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 .75.75v6.75a2.25 2.25 0 0 1-2.25 2.25H6.25a2.25 2.25 0 0 1-2.25-2.25V8.5Zm3 1.5a.75.75 0 1 0-1.5 0v1.5a.75.75 0 1 0 1.5 0v-1.5Zm6.5-.75a.75.75 0 0 1 .75.75v1.5a.75.75 0 1 1-1.5 0v-1.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="truncate max-w-[110px] text-[9px] text-slate-500 group-hover/badge:text-white/80 font-medium transition-colors">{{ $image['product']['name'] }}</span>
                                    <span class="text-[10px] font-black text-terra-600 group-hover/badge:text-white transition-colors">Beli Sekarang →</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <img 
                        src="{{ $image['url'] }}" 
                        alt="{{ $image['title'] }}" 
                        loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-110">
                    
                    {{-- Overlay with better gradient and animation --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6 translate-y-4 group-hover:translate-y-0">
                        <span class="text-terra-400 text-xs font-bold uppercase tracking-widest mb-1">{{ $image['category'] }}</span>
                        <h3 class="font-display text-white text-sm font-bold leading-tight drop-shadow-sm">{{ $image['title'] }}</h3>
                        @if(!empty($image['location']))
                            <p class="text-white/70 text-[10px] mt-1 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $image['location'] }}
                            </p>
                        @endif
                        {{-- Hover Stats Overlay --}}
                        <div class="flex items-center gap-3 mt-3 text-white/90 text-xs font-medium">
                            <span class="flex items-center gap-1.5 bg-black/30 backdrop-blur-sm px-2 py-0.5 rounded-full text-[10px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-3 h-3 text-rose-500">
                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                </svg>
                                <span x-text="photos[{{ $index }}]?.likes_count || 0"></span>
                            </span>
                            <span class="flex items-center gap-1.5 bg-black/30 backdrop-blur-sm px-2 py-0.5 rounded-full text-[10px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-3 h-3 text-blue-400">
                                    <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 0 0 6 21.75a6.721 6.721 0 0 0 3.583-1.022 7.478 7.478 0 0 0 2.417.422c4.142 0 7.5-3.134 7.5-7s-3.358-7-7.5-7-7.5 3.134-7.5 7c0 1.86.787 3.55 2.054 4.804-.15.42-.393.812-.716 1.156a.75.75 0 0 0 .584 1.254c.783-.02 1.543-.2 2.254-.53a6.719 6.719 0 0 0 .633-.298ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm6.75.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                <span x-text="photos[{{ $index }}]?.comments_count || 0"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Floating Icon Indicator --}}
                    <div class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-500 scale-50 group-hover:scale-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($photos) === 0)
            <div class="text-center py-20">
                <p class="text-slate-500 italic text-lg">Belum ada foto untuk kategori ini.</p>
            </div>
        @endif
    </div>

    <!-- Custom Alpine Lightbox Modal (TikTok/Reels style for Photos) -->
    <div 
        x-show="fullSizeModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="fullSizeModal = false"
        @keydown.arrow-left.window="if (fullSizeModal) { activeIndex = (activeIndex - 1 + photos.length) % photos.length; activePhotoId = photos[activeIndex].id }"
        @keydown.arrow-right.window="if (fullSizeModal) { activeIndex = (activeIndex + 1) % photos.length; activePhotoId = photos[activeIndex].id }">
        
        <!-- Close Button (Global) -->
        <button 
            @click.stop="fullSizeModal = false" 
            class="absolute top-4 right-4 z-[120] p-3 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Main Modal Container: Split Layout on Desktop, Full Image on Mobile -->
        <div class="w-full h-full flex flex-col md:flex-row relative">
            
            <!-- Left Side: Image Container & Navigation (75% width on desktop) -->
            <div class="flex-1 h-full flex items-center justify-center relative bg-black/20 p-4">
                
                <!-- Navigation: Previous -->
                <button 
                    @click.stop="activeIndex = (activeIndex - 1 + photos.length) % photos.length; activePhotoId = photos[activeIndex].id" 
                    class="absolute left-4 z-[110] p-3 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <!-- Photo Element -->
                <img 
                    :src="photos[activeIndex]?.url" 
                    :alt="photos[activeIndex]?.title" 
                    class="max-h-[85vh] max-w-full object-contain rounded-xl shadow-2xl transition-all duration-350"
                />

                <!-- Navigation: Next -->
                <button 
                    @click.stop="activeIndex = (activeIndex + 1) % photos.length; activePhotoId = photos[activeIndex].id" 
                    class="absolute right-4 z-[110] p-3 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <!-- Mobile Floating Controls & Brand Overlay (Only visible on mobile) -->
                <div class="absolute bottom-6 left-4 right-16 z-[110] text-left md:hidden pointer-events-none">
                    <div class="flex items-center gap-2 mb-2 pointer-events-auto">
                        <div class="w-8 h-8 rounded-full border border-white/40 flex items-center justify-center shadow-lg bg-terra-500">
                            <span class="text-[8px] font-black text-white" x-text="photos[activeIndex]?.type === 'gallery' ? 'INDO' : (photos[activeIndex]?.reviewer_name?.charAt(0) || 'U')"></span>
                        </div>
                        <span class="text-white text-xs font-bold tracking-widest uppercase drop-shadow-md" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.6);" x-text="photos[activeIndex]?.reviewer_name || 'INDOROSTER OFFICIAL'"></span>
                    </div>
                    <h2 class="font-display text-white text-sm font-normal drop-shadow-lg line-clamp-2" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);" x-text="photos[activeIndex]?.title"></h2>
                    <p class="text-white/70 text-[10px] mt-1 flex items-center gap-1 drop-shadow" x-show="photos[activeIndex]?.location">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-terra-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span x-text="photos[activeIndex]?.location"></span>
                    </p>

                    <!-- Mobile Product Badge (Shoppable) -->
                    <template x-if="photos[activeIndex]?.product">
                        <a 
                            :href="'/produk/' + photos[activeIndex]?.product?.slug" 
                            class="flex items-center gap-2 bg-white/95 backdrop-blur-md p-2 rounded-xl shadow-xl mt-3 border border-white/20 pointer-events-auto max-w-[85vw]"
                        >
                            <img :src="photos[activeIndex]?.product?.image" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                            <div class="flex-grow min-w-0">
                                <p class="text-[10px] font-bold text-slate-800 truncate" x-text="photos[activeIndex]?.product?.name"></p>
                                <p class="text-[9px] font-black text-terra-600 mt-0.5" x-text="photos[activeIndex]?.product?.formatted_price + ' • Beli'"></p>
                            </div>
                        </a>
                    </template>
                </div>

                <!-- Mobile Floating Actions Sidebar (Like, Comment, Share) -->
                <div class="absolute right-4 bottom-24 z-[115] flex flex-col items-center gap-4 text-white md:hidden">

                    <!-- Like Button -->
                    <button 
                        @click.stop="if (!isLoggedIn) { window.location.href = '{{ route('login') }}'; } else { $wire.toggleLike(photos[activeIndex].id) }" 
                        class="flex flex-col items-center justify-center group focus:outline-none cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all duration-200 border border-white/10 active:scale-95 text-white"
                            :class="photos[activeIndex]?.is_liked ? 'text-rose-500' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" :fill="photos[activeIndex]?.is_liked ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold mt-1 drop-shadow-md" x-text="photos[activeIndex]?.likes_count || 0"></span>
                    </button>

                    <!-- Comment Button -->
                    <button 
                        @click.stop="if (!isLoggedIn) { window.location.href = '{{ route('login') }}'; } else { activePhotoId = photos[activeIndex].id; commentDrawerOpen = true; }" 
                        class="flex flex-col items-center justify-center group focus:outline-none cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all duration-200 border border-white/10 active:scale-95 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold mt-1 drop-shadow-md" x-text="photos[activeIndex]?.comments_count || 0"></span>
                    </button>

                    <!-- Share Button -->
                    <button 
                        @click.stop="
                            const shareUrl = `${window.location.origin}${window.location.pathname}?photo=${photos[activeIndex].id}`;
                            if (navigator.share) {
                                navigator.share({
                                    title: photos[activeIndex].title,
                                    text: 'Lihat foto inspirasi roster beton menarik ini di Indoroster!',
                                    url: shareUrl
                                }).catch(() => {});
                            } else {
                                navigator.clipboard.writeText(shareUrl).then(() => {
                                    toastMessage = 'Tautan foto berhasil disalin!';
                                    showToast = true;
                                    setTimeout(() => showToast = false, 3000);
                                });
                            }
                        " 
                        class="flex flex-col items-center justify-center group focus:outline-none cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all duration-200 border border-white/10 active:scale-95 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold mt-1 drop-shadow-md">Bagikan</span>
                    </button>
                </div>
            </div>

            <!-- Right Side: Details & Comments Panel (380px width, hidden on mobile) -->
            <div class="hidden md:flex w-[380px] h-full bg-slate-900 border-l border-white/10 flex-col relative z-20">
                <!-- Header / Author Info -->
                <div class="p-6 border-b border-white/10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full border border-white/40 flex items-center justify-center shadow-lg relative overflow-hidden"
                             :class="photos[activeIndex]?.type === 'gallery' ? 'bg-terra-500' : 'bg-slate-700'">
                            <span class="text-xs font-black text-white" x-text="photos[activeIndex]?.type === 'gallery' ? 'INDO' : (photos[activeIndex]?.reviewer_name?.charAt(0) || 'U')"></span>
                        </div>
                        <div class="text-left">
                            <p class="text-white text-xs font-bold uppercase tracking-widest" x-text="photos[activeIndex]?.reviewer_name || 'INDOROSTER OFFICIAL'"></p>
                            <p class="text-white/60 text-[10px] mt-0.5 flex items-center gap-1" x-show="photos[activeIndex]?.location">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-terra-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-text="photos[activeIndex]?.location"></span>
                            </p>
                        </div>
                    </div>
                    <h3 class="font-display text-white text-sm font-normal leading-relaxed text-left" x-text="photos[activeIndex]?.title"></h3>
                </div>

                <!-- Shoppable Product Link (Desktop) -->
                <template x-if="photos[activeIndex]?.product">
                    <div class="px-6 py-4 border-b border-white/10 bg-slate-950/40">
                        <a 
                            :href="'/produk/' + photos[activeIndex]?.product?.slug" 
                            class="flex items-center gap-3 bg-white/5 backdrop-blur-md p-3 rounded-2xl border border-white/10 hover:bg-white/10 hover:scale-[1.01] transition-all duration-300 group max-w-full">
                            <!-- Product Image -->
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 flex-shrink-0 border border-white/10">
                                <img :src="photos[activeIndex]?.product?.image" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex-grow min-w-0 text-left">
                                <p class="text-xs font-bold text-white line-clamp-1 group-hover:text-terra-400 transition-colors" x-text="photos[activeIndex]?.product?.name"></p>
                                <p class="text-[11px] font-black text-terra-500 mt-0.5" x-text="photos[activeIndex]?.product?.formatted_price"></p>
                            </div>

                            <!-- Bag Icon -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-500 group-hover:bg-terra-600 flex items-center justify-center text-white shadow-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.262-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a.75.75 0 1 0-1.5 0v-.75a.75.75 0 0 0 1.5 0v.75Zm7.5-.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </a>
                    </div>
                </template>

                <!-- Likes & Comments Counts + Interaction Buttons (Desktop) -->
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between text-white bg-slate-900/80">
                    <div class="flex flex-wrap items-center gap-4">

                        <!-- Likes Count Button -->
                        <button 
                            @click.stop="if (!isLoggedIn) { window.location.href = '{{ route('login') }}'; } else { $wire.toggleLike(photos[activeIndex].id) }" 
                            class="flex items-center gap-2 hover:text-rose-500 transition-colors focus:outline-none"
                            :class="photos[activeIndex]?.is_liked ? 'text-rose-500' : 'text-white/80'">
                            <svg xmlns="http://www.w3.org/2000/svg" :fill="photos[activeIndex]?.is_liked ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span class="text-xs font-bold" x-text="(photos[activeIndex]?.likes_count || 0) + ' Suka'"></span>
                        </button>

                        <!-- Comments count indicator -->
                        <div class="flex items-center gap-2 text-white/80">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                            </svg>
                            <span class="text-xs font-bold" x-text="(photos[activeIndex]?.comments_count || 0) + ' Komentar'"></span>
                        </div>
                    </div>

                    <!-- Share Button (Desktop) -->
                    <button 
                        @click.stop="
                            const shareUrl = `${window.location.origin}${window.location.pathname}?photo=${photos[activeIndex].id}`;
                            navigator.clipboard.writeText(shareUrl).then(() => {
                                toastMessage = 'Tautan foto berhasil disalin!';
                                showToast = true;
                                setTimeout(() => showToast = false, 3000);
                            });
                        "
                        class="flex items-center gap-1.5 hover:text-terra-400 text-white/80 transition-colors focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                        </svg>
                        <span class="text-xs font-bold">Bagikan</span>
                    </button>
                </div>

                <!-- Comments List (Desktop) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar">
                    <template x-if="!(photos[activeIndex]?.comments && photos[activeIndex]?.comments.length)">
                        <div class="h-full flex flex-col items-center justify-center text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-500 mb-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                            </svg>
                            <p class="text-sm text-slate-400 font-medium">Belum ada komentar</p>
                            <p class="text-xs text-slate-500 mt-1">Jadilah yang pertama berkomentar!</p>
                        </div>
                    </template>

                    <template x-for="comment in photos[activeIndex]?.comments || []" :key="comment.id">
                        <div class="flex gap-3 text-left">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-600 flex items-center justify-center border border-white/10 shadow">
                                <span class="text-xs font-bold text-white uppercase" x-text="comment.user_name.charAt(0)"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="bg-white/5 rounded-2xl px-4 py-2.5 border border-white/5">
                                    <p class="text-xs font-black text-terra-400" x-text="comment.user_name"></p>
                                    <p class="text-sm text-slate-200 mt-1 leading-relaxed break-words text-left" x-text="comment.body"></p>
                                </div>
                                <div class="flex items-center gap-3 ml-2 mt-1">
                                    <span class="text-[10px] text-slate-400" x-text="comment.created_at_human"></span>
                                    <button 
                                        @click.prevent="$wire.setReplyTo(comment.id, comment.user_name)"
                                        class="text-[10px] font-bold text-terra-400 hover:text-terra-300 transition-colors">
                                        Balas
                                    </button>
                                </div>

                                <!-- Nested Replies -->
                                <div x-show="comment.replies && comment.replies.length > 0" class="mt-3 ml-2 space-y-3 border-l border-white/10 pl-3">
                                    <template x-for="reply in comment.replies || []" :key="reply.id">
                                        <div class="flex gap-2.5 text-left">
                                            <!-- Reply User Avatar -->
                                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center border border-white/10 shadow">
                                                <span class="text-[10px] font-bold text-white uppercase" x-text="reply.user_name.charAt(0)"></span>
                                            </div>
                                            <!-- Reply Details -->
                                            <div class="flex-1 min-w-0">
                                                <div class="bg-white/5 rounded-2xl px-3.5 py-2 border border-white/5">
                                                    <p class="text-[11px] font-black text-terra-400" x-text="reply.user_name"></p>
                                                    <p class="text-xs text-slate-200 mt-0.5 leading-relaxed break-words text-left" x-text="reply.body"></p>
                                                </div>
                                                <span class="text-[9px] text-slate-400 ml-2 mt-0.5 inline-block" x-text="reply.created_at_human"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Desktop Comment Input Form -->
                <div class="p-4 border-t border-white/10 bg-slate-950/40">
                    @if($replyToCommentId)
                        <div class="flex items-center justify-between bg-terra-500/10 border border-terra-500/20 rounded-xl px-3 py-1.5 mb-2 text-xs">
                            <span class="text-slate-300">
                                Membalas <span class="font-bold text-terra-400">@<span>{{ $replyToUserName }}</span></span>
                            </span>
                            <button 
                                type="button"
                                wire:click="cancelReply"
                                class="text-slate-400 hover:text-white transition-colors p-0.5 rounded-full hover:bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <form wire:submit.prevent="submitComment" class="flex gap-2">
                        <input 
                            type="text" 
                            wire:model="newCommentText"
                            placeholder="{{ $replyToCommentId ? 'Tulis balasan...' : 'Tulis komentar...' }}"
                            class="flex-1 bg-white/5 text-white placeholder-slate-400 rounded-full py-2 px-4 text-sm border border-white/10 focus:outline-none focus:border-terra-500 focus:ring-1 focus:ring-terra-500 transition-all">
                        <button 
                            type="submit" 
                            class="bg-terra-500 hover:bg-terra-600 text-white rounded-full p-2.5 shadow-lg active:scale-95 transition-all flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Comment Drawer/Bottom Sheet (Mobile Only) -->
    <div 
        x-show="commentDrawerOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-full opacity-0"
        class="fixed bottom-0 left-0 right-0 h-[65vh] bg-slate-900/95 backdrop-blur-xl border-t border-white/10 rounded-t-3xl z-[130] flex flex-col shadow-2xl overflow-hidden md:hidden"
        style="display: none;"
        @click.away="commentDrawerOpen = false">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between bg-slate-900/50">
            <h3 class="font-display font-bold text-white text-base">
                Komentar (<span x-text="photos[activeIndex]?.comments_count || 0"></span>)
            </h3>
            <button 
                @click="commentDrawerOpen = false"
                class="p-1.5 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Comments List (Mobile Drawer) -->
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 no-scrollbar">
            <template x-if="!(photos[activeIndex]?.comments && photos[activeIndex]?.comments.length)">
                <div class="h-full flex flex-col items-center justify-center text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-500 mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                    </svg>
                    <p class="text-sm text-slate-400 font-medium">Belum ada komentar</p>
                    <p class="text-xs text-slate-500 mt-1">Jadilah yang pertama berkomentar!</p>
                </div>
            </template>

            <template x-for="comment in photos[activeIndex]?.comments || []" :key="comment.id">
                <div class="flex gap-3 text-left">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-600 flex items-center justify-center border border-white/10 shadow">
                        <span class="text-xs font-bold text-white uppercase" x-text="comment.user_name.charAt(0)"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="bg-white/5 rounded-2xl px-4 py-2.5 border border-white/5">
                            <p class="text-xs font-black text-terra-400" x-text="comment.user_name"></p>
                            <p class="text-sm text-slate-200 mt-1 leading-relaxed break-words text-left" x-text="comment.body"></p>
                        </div>
                        <div class="flex items-center gap-3 ml-2 mt-1">
                            <span class="text-[10px] text-slate-400" x-text="comment.created_at_human"></span>
                            <button 
                                @click.prevent="$wire.setReplyTo(comment.id, comment.user_name)"
                                class="text-[10px] font-bold text-terra-400 hover:text-terra-300 transition-colors">
                                Balas
                            </button>
                        </div>

                        <!-- Nested Replies -->
                        <div x-show="comment.replies && comment.replies.length > 0" class="mt-3 ml-2 space-y-3 border-l border-white/10 pl-3">
                            <template x-for="reply in comment.replies || []" :key="reply.id">
                                <div class="flex gap-2.5 text-left">
                                    <!-- Reply User Avatar -->
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center border border-white/10 shadow">
                                        <span class="text-[10px] font-bold text-white uppercase" x-text="reply.user_name.charAt(0)"></span>
                                    </div>
                                    <!-- Reply Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-white/5 rounded-2xl px-3.5 py-2 border border-white/5">
                                            <p class="text-[11px] font-black text-terra-400" x-text="reply.user_name"></p>
                                            <p class="text-xs text-slate-200 mt-0.5 leading-relaxed break-words text-left" x-text="reply.body"></p>
                                        </div>
                                        <span class="text-[9px] text-slate-400 ml-2 mt-0.5 inline-block" x-text="reply.created_at_human"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Mobile Comment Form (Footer) -->
        <div class="p-4 border-t border-white/10 bg-slate-950/40">
            @if($replyToCommentId)
                <div class="flex items-center justify-between bg-terra-500/10 border border-terra-500/20 rounded-xl px-3 py-1.5 mb-2 text-xs">
                    <span class="text-slate-300">
                        Membalas <span class="font-bold text-terra-400">@<span>{{ $replyToUserName }}</span></span>
                    </span>
                    <button 
                        type="button"
                        wire:click="cancelReply"
                        class="text-slate-400 hover:text-white transition-colors p-0.5 rounded-full hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                    </button>
                </div>
            @endif

            <form wire:submit.prevent="submitComment" class="flex gap-2">
                <input 
                    type="text" 
                    wire:model="newCommentText"
                    placeholder="{{ $replyToCommentId ? 'Tulis balasan...' : 'Tulis komentar...' }}"
                    class="flex-1 bg-white/5 text-white placeholder-slate-400 rounded-full py-2 px-4 text-sm border border-white/10 focus:outline-none focus:border-terra-500 focus:ring-1 focus:ring-terra-500 transition-all">
                <button 
                    type="submit" 
                    class="bg-terra-500 hover:bg-terra-600 text-white rounded-full p-2.5 shadow-lg active:scale-95 transition-all flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .animate-hover {
            transition: all 0.2s ease-in-out;
        }
        .animate-hover:hover {
            transform: scale(1.1);
        }
    </style>
</div>
