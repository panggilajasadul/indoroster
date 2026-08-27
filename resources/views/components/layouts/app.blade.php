<!DOCTYPE html>
@php
    $cookieTheme = request()->cookie('indoroster_theme');
    $themeDefaultMode = \App\Models\SiteSetting::getValue('theme_default_mode', 'light');
    $isDarkInitial = ($cookieTheme === 'dark') || (!$cookieTheme && $themeDefaultMode === 'dark');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth {{ $isDarkInitial ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $pageTitle      = $title ?? \App\Models\SiteSetting::getValue('meta_title_default', 'Indoroster - Pabrik Roster Beton Minimalis Plered Purwakarta');
        $pageDesc       = $description ?? \App\Models\SiteSetting::getValue('meta_description_default', 'Pusat Pabrik Roster Beton Minimalis Plered Purwakarta. Produsen tangan pertama, harga pabrik, cetak tumbuk padat, keras, dan rapi. Melayani pengiriman ke seluruh Jabodetabek dan Indonesia.');
        $pageImage      = $ogImage ?? asset('assets/logo_indoroster_no_text.PNG');
        $robotsMeta     = $robots ?? 'index, follow';

        // Canonical: strip query params yang berisiko duplicate content
        // Kecuali ?category yang memang digunakan untuk katalog kategori
        $currentUrl   = url()->current();
        $queryString  = request()->getQueryString();
        $safeParams   = [];
        if ($queryString) {
            parse_str($queryString, $queryParams);
            if (isset($queryParams['category']) && !empty($queryParams['category'])) {
                $safeParams['category'] = $queryParams['category'];
            }
        }
        $canonicalUrl = $canonicalOverride ?? ($safeParams ? $currentUrl . '?' . http_build_query($safeParams) : $currentUrl);

        // OG: bisa override title/description secara terpisah dari meta
        $ogTitleFinal = $ogTitle ?? $pageTitle;
        $ogDescFinal  = $ogDescription ?? $pageDesc;

        // Keywords: per-halaman jika tersedia, fallback ke keyword bisnis global dari database
        $keywordsMeta = $keywords ?? \App\Models\SiteSetting::getValue('seo_keywords_default', 'roster beton minimalis, loster beton minimalis, roster beton plered, jual roster beton, pabrik roster beton purwakarta, harga roster beton, roster dinding minimalis, ventilasi beton, jual roster jakarta, roster beton jabodetabek');

        $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        // Theme & Appearance Settings
        $themeAllowUserToggle = filter_var(\App\Models\SiteSetting::getValue('theme_allow_user_toggle', true), FILTER_VALIDATE_BOOLEAN);
        $themeAccentColor = \App\Models\SiteSetting::getValue('theme_accent_color', '#f75c20');
        $themeNavbarStyle = \App\Models\SiteSetting::getValue('theme_navbar_style', 'glassmorphism');
        $isOrderModeMidtrans = \App\Models\SiteSetting::getValue('order_mode', 'midtrans') === 'midtrans';
    @endphp

    <title>{{ $pageTitle }}</title>

    <!-- Theme Initialization Script (Zero-FOUC & Livewire SPA Navigation Sync) -->
    <script>
        function syncIndorosterTheme() {
            try {
                const defaultMode = '{{ $themeDefaultMode }}';
                const cookieMatch = document.cookie.match(/(^|;\s*)indoroster_theme=([^;]+)/);
                const cookieVal = cookieMatch ? cookieMatch[2] : null;
                const storedTheme = localStorage.getItem('indoroster_theme') || cookieVal;
                let isDark = false;

                if (storedTheme === 'dark') {
                    isDark = true;
                } else if (storedTheme === 'light') {
                    isDark = false;
                } else {
                    if (defaultMode === 'dark') {
                        isDark = true;
                    } else if (defaultMode === 'system') {
                        isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    } else {
                        isDark = false;
                    }
                }

                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        }

        syncIndorosterTheme();
        document.addEventListener('livewire:navigated', syncIndorosterTheme);
        document.addEventListener('livewire:init', syncIndorosterTheme);
    </script>

    <style>
        :root {
            --brand-accent: {{ $themeAccentColor }};
        }
        [x-cloak] { display: none !important; }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logo_indoroster_no_text.PNG') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo_indoroster_no_text.PNG') }}">

    <!-- DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="//res.cloudinary.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts: Inter, Outfit, Poppins, Jost, Urbanist, Plus Jakarta Sans -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300..800&family=Jost:ital,wght@0,300..900;1,300..900&family=Outfit:wght@300..900&family=Plus+Jakarta+Sans:wght@300..800&family=Poppins:ital,wght@0,300..900;1,300..900&family=Urbanist:ital,wght@0,300..900;1,300..900&display=swap">

    <!-- TomSelect CSS (non-blocking) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet" media="print" onload="this.media='all'">

    <!-- Leaflet Maps CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Primary SEO Meta -->
    <meta name="google-site-verification" content="{{ \App\Models\SiteSetting::getValue('google_site_verification', '5T-7RFSLMEwCNdq2lx93GU5S5BckFBgjFPf5B-HlT1Y') }}" />
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywordsMeta }}">
    <meta name="author" content="Indoroster">
    <meta name="robots" content="{{ $robotsMeta }}">
    <meta name="geo.region" content="ID-JB">
    <meta name="geo.placename" content="Plered, Purwakarta, Jawa Barat">

    <!-- Canonical -->
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:site_name" content="Indoroster">
    <meta property="og:title" content="{{ $ogTitleFinal }}">
    <meta property="og:description" content="{{ $ogDescFinal }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $ogTitleFinal }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@indoroster">
    <meta name="twitter:creator" content="@indoroster">
    <meta name="twitter:title" content="{{ $ogTitleFinal }}">
    <meta name="twitter:description" content="{{ $ogDescFinal }}">
    <meta name="twitter:image" content="{{ $pageImage }}">
    <meta name="twitter:image:alt" content="{{ $ogTitleFinal }}">

    <!-- Vite (Tailwind CSS + App JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head-scripts')

    <!-- Global Structured Data (Organization, LocalBusiness, WebSite) -->
    <x-seo-schemas />

    <!-- Per-Page SEO: Schema JSON-LD, etc. -->
    @stack('seo')
</head>

<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 selection:bg-terra-500 selection:text-white flex flex-col min-h-screen relative" x-data="{ mobileMenuOpen: false }">
    
    @php
        $navigationMenus = \App\Models\NavigationMenu::where('is_active', true)->orderBy('order', 'asc')->get();
        $navbarAlignment = \App\Models\SiteSetting::getValue('navbar_alignment', 'left');
        $alignmentClass = match($navbarAlignment) {
            'center' => 'justify-center',
            'right' => 'justify-end',
            default => 'justify-start',
        };
    @endphp

    <!-- Sticky Announcement / Trust Strip -->
    @php
        $topBarActive = filter_var(\App\Models\SiteSetting::getValue('top_bar_is_active', true), FILTER_VALIDATE_BOOLEAN);
        $topBarTagline = \App\Models\SiteSetting::getValue('top_bar_tagline', 'Pabrik Tangan Pertama Plered Purwakarta');
        $topBarSubtext = \App\Models\SiteSetting::getValue('top_bar_subtext', 'Cetak Tumbuk Padat & Presisi');
        $topBarTrackingText = \App\Models\SiteSetting::getValue('top_bar_tracking_text', 'Lacak Pengiriman');
    @endphp

    @if($topBarActive)
    <div class="bg-slate-900 dark:bg-slate-950 text-slate-300 text-xs py-2 px-4 border-b border-slate-800 hidden sm:block">
        <div class="max-w-screen-2xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($topBarTagline)
                <span class="flex items-center gap-1.5 text-terra-400 font-semibold tracking-wide uppercase text-[11px]">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ $topBarTagline }}
                </span>
                @endif
                @if($topBarTagline && $topBarSubtext)
                <span class="text-slate-600">|</span>
                @endif
                @if($topBarSubtext)
                <span class="text-slate-300">{{ $topBarSubtext }}</span>
                @endif
            </div>
            <div class="flex items-center gap-5 text-slate-300">
                @if($topBarTrackingText)
                <a href="{{ route('order.tracking') }}" class="hover:text-terra-400 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" /></svg>
                    {{ $topBarTrackingText }}
                </a>
                @endif
                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="hover:text-emerald-400 font-medium transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-400 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    WA: {{ $rawWa }}
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Master Header (Glassmorphic) -->
    <header class="glass-header sticky top-0 z-50 border-b border-slate-200/80 dark:border-slate-800/80 shadow-xs transition-all duration-300">
        <nav class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="w-full h-16 sm:h-18 flex items-center justify-between">
                
                <!-- Left: Hamburger (Mobile) & Logo -->
                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 shrink-0">
                    <!-- Hamburger Menu Button (Mobile Only) -->
                    <button @click="mobileMenuOpen = true" type="button" class="lg:hidden p-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none" aria-label="Buka Menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                        <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="Indoroster Logo" class="h-8 sm:h-9 md:h-10 w-auto transition-transform duration-300 group-hover:scale-105">
                        <div class="flex flex-col">
                            <span class="text-lg sm:text-xl font-black tracking-widest text-slate-900 dark:text-white uppercase font-display leading-tight transition-colors duration-300 group-hover:text-terra-500">INDOROSTER</span>
                            <span class="text-[9px] font-bold tracking-wider text-slate-400 dark:text-slate-400 uppercase hidden sm:block">Pabrik Roster Beton</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Navigation Links (Desktop Only) -->
                <div class="hidden lg:flex items-center justify-center flex-1 mx-2 xl:mx-4 space-x-0.5 xl:space-x-1 min-w-0 z-10">
                    @foreach($navigationMenus as $menu)
                        @php
                            $isActive = request()->is(ltrim($menu->url, '/') . '*') || (request()->is('/') && $menu->url === '/');
                        @endphp
                        <a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="px-2 xl:px-3 py-1.5 text-xs xl:text-sm font-semibold {{ $isActive ? 'text-terra-600 dark:text-terra-400 bg-terra-50/80 dark:bg-terra-500/10 font-bold' : 'text-slate-700 dark:text-slate-200 hover:text-terra-600 dark:hover:text-terra-400 hover:bg-terra-50/60 dark:hover:bg-slate-800/60' }} rounded-lg transition-all whitespace-nowrap">{{ $menu->label }}</a>
                    @endforeach
                </div>

                <!-- Right: Utilities & Theme Switcher & Profile -->
                <div class="flex items-center justify-end gap-1.5 sm:gap-3 shrink-0 z-20">
                    
                    <!-- Theme Switcher (Desktop & Mobile) -->
                    @if($themeAllowUserToggle)
                    <div x-data="{
                        currentTheme: localStorage.getItem('indoroster_theme') || (document.documentElement.classList.contains('dark') ? 'dark' : 'light'),
                        toggleTheme() {
                            this.currentTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
                            localStorage.setItem('indoroster_theme', this.currentTheme);
                            document.cookie = 'indoroster_theme=' + this.currentTheme + '; path=/; max-age=31536000; SameSite=Lax';
                            if (this.currentTheme === 'dark') {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                            window.dispatchEvent(new CustomEvent('indoroster-theme-updated', { detail: this.currentTheme }));
                        }
                    }" @indoroster-theme-updated.window="currentTheme = $event.detail" class="flex items-center">
                        <button @click="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-terra-600 dark:hover:text-terra-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none cursor-pointer" :title="currentTheme === 'dark' ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'" aria-label="Toggle Dark/Light Mode">
                            <!-- Moon Icon (Shown in Light Mode) -->
                            <svg x-show="currentTheme !== 'dark'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <!-- Sun Icon (Shown in Dark Mode) -->
                            <svg x-show="currentTheme === 'dark'" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </div>
                    @endif

                    @auth
                        @if(auth()->user()->hasVerifiedEmail())
                            @livewire('cart-count')
                            @livewire('notification-bell')
                            
                            <div class="relative ml-1" x-data="{ userMenuOpen: false, userTimer: null }"
                                 @mouseenter="clearTimeout(userTimer); userMenuOpen = true"
                                 @mouseleave="userTimer = setTimeout(() => { userMenuOpen = false }, 300)">
                                <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 focus:outline-none p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition group">
                                    <div class="w-8 h-8 rounded-full bg-terra-500 text-white flex items-center justify-center font-display font-bold text-sm shadow-sm transition-transform duration-200 group-hover:scale-105">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                </button>
                                <!-- Dropdown Menu -->
                                <div x-show="userMenuOpen" 
                                     x-transition:enter="transition ease-out duration-100" 
                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-75" 
                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                     x-transition:leave-end="transform opacity-0 scale-95" 
                                     class="absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xl py-2 z-50 origin-top-right" 
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-slate-50 dark:border-slate-800 mb-1">
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    @if($isOrderModeMidtrans)
                                    <a href="{{ route('member.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('member.addresses') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Buku Alamat
                                    </a>
                                    @else
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors">
                                        <svg class="w-4 h-4 text-emerald-500 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        Konsultasi WhatsApp
                                    </a>
                                    @endif
                                    <a href="{{ route('member.notifications') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                        Notifikasi
                                    </a>
                                    <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>
                                    <a href="{{ route('logout') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Keluar
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- User belum verifikasi email --}}
                            <div class="relative ml-1" x-data="{ userMenuOpen: false, userTimer: null }"
                                 @mouseenter="clearTimeout(userTimer); userMenuOpen = true"
                                 @mouseleave="userTimer = setTimeout(() => { userMenuOpen = false }, 300)">
                                <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 focus:outline-none p-1 rounded-full hover:bg-slate-50 dark:hover:bg-slate-800 transition group relative">
                                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-display font-bold text-sm shadow-sm transition-transform duration-200 group-hover:scale-105">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-red-500 border-2 border-white dark:border-slate-900 rounded-full animate-pulse"></span>
                                </button>
                                <!-- Dropdown: Verifikasi dulu -->
                                <div x-show="userMenuOpen" 
                                     x-transition:enter="transition ease-out duration-100" 
                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-75" 
                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                     x-transition:leave-end="transform opacity-0 scale-95" 
                                     class="absolute right-0 mt-2 w-72 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xl py-2 z-50 origin-top-right" 
                                     style="display: none;">
                                    <div class="px-4 py-3 border-b border-amber-100 dark:border-amber-900/40 mb-1 bg-amber-50/50 dark:bg-amber-950/30">
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-0.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Email belum terverifikasi
                                        </p>
                                    </div>
                                    <div class="px-4 py-3">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-3">Verifikasi email Anda terlebih dahulu untuk bisa berbelanja dan mengakses fitur member.</p>
                                        <a href="{{ route('verification.notice') }}" class="block w-full text-center bg-terra-500 hover:bg-terra-600 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition-colors">
                                            Verifikasi Sekarang
                                        </a>
                                    </div>
                                    <div class="border-t border-slate-100 dark:border-slate-800 mt-1"></div>
                                    <a href="{{ route('logout') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Keluar
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        @livewire('cart-count')
                        <div class="hidden sm:flex items-center gap-2.5 ml-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 dark:text-slate-200 hover:text-terra-600 dark:hover:text-terra-400 px-3 py-2 transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-slate-900 dark:bg-terra-500 hover:bg-terra-600 dark:hover:bg-terra-600 px-4 py-2 rounded-xl shadow-xs transition-all duration-200">Daftar</a>
                        </div>
                        <a href="{{ route('login') }}" class="sm:hidden p-2 text-slate-700 dark:text-slate-200 hover:text-terra-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition ml-1" aria-label="Masuk">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Side Drawer Menu (Mobile & Tablet) -->
    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100]" style="display: none;" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="ease-in-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in-out duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" 
             @click="mobileMenuOpen = false" aria-hidden="true"></div>

        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 left-0 flex max-w-full">
                    <!-- Sliding panel -->
                    <div x-show="mobileMenuOpen" 
                         x-transition:enter="transform transition ease-in-out duration-300" 
                         x-transition:enter-start="-translate-x-full" 
                         x-transition:enter-end="translate-x-0" 
                         x-transition:leave="transform transition ease-in-out duration-300" 
                         x-transition:leave-start="translate-x-0" 
                         x-transition:leave-end="-translate-x-full" 
                         class="pointer-events-auto w-screen max-w-xs sm:max-w-sm">
                        
                        <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-slate-900 shadow-2xl">
                            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                                    <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="Indoroster" class="h-8 w-auto">
                                    <span class="text-lg font-black tracking-widest text-slate-900 dark:text-white uppercase font-display">INDOROSTER</span>
                                </a>
                                <div class="flex items-center gap-1">
                                    @if($themeAllowUserToggle)
                                    <div x-data="{
                                        currentTheme: localStorage.getItem('indoroster_theme') || (document.documentElement.classList.contains('dark') ? 'dark' : 'light'),
                                        toggleTheme() {
                                            this.currentTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
                                            localStorage.setItem('indoroster_theme', this.currentTheme);
                                            document.cookie = 'indoroster_theme=' + this.currentTheme + '; path=/; max-age=31536000; SameSite=Lax';
                                            if (this.currentTheme === 'dark') {
                                                document.documentElement.classList.add('dark');
                                            } else {
                                                document.documentElement.classList.remove('dark');
                                            }
                                            window.dispatchEvent(new CustomEvent('indoroster-theme-updated', { detail: this.currentTheme }));
                                        }
                                    }" @indoroster-theme-updated.window="currentTheme = $event.detail">
                                        <button @click="toggleTheme()" type="button" class="rounded-xl p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer" aria-label="Toggle Dark/Light Mode">
                                            <svg x-show="currentTheme !== 'dark'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                            </svg>
                                            <svg x-show="currentTheme === 'dark'" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                    <button @click="mobileMenuOpen = false" type="button" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200 transition cursor-pointer">
                                        <span class="sr-only">Tutup menu</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="px-4 py-6 space-y-1">
                                <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Navigasi Utama</p>
                                <nav class="flex flex-col space-y-1">
                                    @foreach($navigationMenus as $menu)
                                        <a href="{{ url($menu->url) }}" target="{{ $menu->target }}" class="flex items-center justify-between px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-terra-50 dark:hover:bg-slate-800 hover:text-terra-600 dark:hover:text-terra-400 rounded-xl transition-colors group">
                                            <span>{{ $menu->label }}</span>
                                            <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-terra-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </nav>

                                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 mt-4">
                                    <p class="px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Menu Produk & Galeri</p>
                                    <a href="{{ route('catalog') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-600 dark:hover:text-terra-400 rounded-xl transition">
                                        <span class="w-2 h-2 rounded-full bg-terra-500"></span>
                                        Katalog Roster Lengkap
                                    </a>
                                    <a href="{{ route('gallery') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-600 dark:hover:text-terra-400 rounded-xl transition">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        Galeri Proyek & Realisasi
                                    </a>
                                    <a href="{{ route('video-inspiration') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-600 dark:hover:text-terra-400 rounded-xl transition">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        Video Inspirasi & Pemasangan
                                    </a>
                                    <a href="{{ route('article.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-600 dark:hover:text-terra-400 rounded-xl transition">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        Artikel & Edukasi
                                    </a>
                                </div>
                            </div>

                            <div class="mt-auto border-t border-slate-100 dark:border-slate-800 px-6 py-6 bg-slate-50 dark:bg-slate-950 space-y-3">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Layanan Pabrik</p>
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="flex items-center gap-3 text-sm font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 p-3 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Konsultasi Desain</p>
                                        <p class="text-sm font-bold text-emerald-800 dark:text-emerald-300">Chat WhatsApp</p>
                                    </div>
                                </a>
                                <a href="{{ route('order.tracking') }}" class="flex items-center gap-3 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-terra-300 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-terra-50 dark:bg-terra-500/10 text-terra-600 dark:text-terra-400 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" /></svg>
                                    </div>
                                    <span>Lacak Pesanan Pengiriman</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Top Announcement Bar with Hover Tooltips (seperti referensi Toco) -->
    <x-trust-top-bar />

    <!-- Main Content Shell -->
    <main class="flex-grow pb-24 lg:pb-0 relative">
        @auth
            @php
                $hasAddress = \App\Models\Address::where('user_id', auth()->id())->exists();
                $isVerified = auth()->user()->hasVerifiedEmail();
                $showOnboarding = $isOrderModeMidtrans && $isVerified && !$hasAddress && !request()->routeIs('member.addresses');
            @endphp
            @if($showOnboarding)
                <!-- Persistent Banner -->
                <div class="bg-amber-50 dark:bg-amber-950/40 border-b border-amber-200 dark:border-amber-900/40 px-4 py-3 sm:px-6 lg:px-8">
                    <div class="max-w-screen-2xl mx-auto flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                <strong>Profil Belum Lengkap:</strong> Anda belum menambahkan alamat pengiriman utama.
                            </p>
                        </div>
                        <a href="{{ route('member.addresses') }}" class="text-xs font-bold text-amber-900 dark:text-amber-100 bg-amber-200 dark:bg-amber-800/60 hover:bg-amber-300 px-3.5 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                            Tambahkan Alamat &rarr;
                        </a>
                    </div>
                </div>
            @endif
        @endauth

        @if (session()->has('success') || session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 rounded-2xl border shadow-soft-sm flex items-center justify-between {{ session()->has('success') ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200' : 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800 text-red-900 dark:text-red-200' }}">
                    <div class="flex items-center gap-3">
                        @if(session()->has('success'))
                            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-300 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @endif
                        <span class="text-sm font-medium">{{ session('success') ?? session('error') }}</span>
                    </div>
                    <button @click="show = false" class="p-1 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition" aria-label="Tutup pesan">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation Bar (App Experience) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 px-3 py-2 shadow-luxury dark:shadow-luxury-dark flex items-center justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition {{ request()->routeIs('home') ? 'text-terra-600 dark:text-terra-400 font-bold' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span class="text-[10px]">Beranda</span>
        </a>
        <a href="{{ route('catalog') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition {{ request()->routeIs('catalog*') ? 'text-terra-600 dark:text-terra-400 font-bold' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            <span class="text-[10px]">Katalog</span>
        </a>
        <a href="{{ route('video-inspiration') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition relative {{ request()->routeIs('video-inspiration*') ? 'text-terra-600 dark:text-terra-400 font-bold' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
            <span class="text-[10px]">Inspirasi</span>
        </a>
        <a href="{{ route('order.tracking') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition {{ request()->routeIs('order.tracking') ? 'text-terra-600 dark:text-terra-400 font-bold' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" /></svg>
            <span class="text-[10px]">Lacak</span>
        </a>
        <a href="{{ auth()->check() ? ($isOrderModeMidtrans ? route('member.orders') : route('member.notifications')) : route('login') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition {{ request()->routeIs('member.*') || request()->routeIs('login') ? 'text-terra-600 dark:text-terra-400 font-bold' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="text-[10px]">{{ auth()->check() ? 'Akun' : 'Masuk' }}</span>
        </a>
    </nav>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-300 border-t border-slate-900 pt-16 pb-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="Indoroster Logo" class="h-10 w-auto">
                        <div>
                            <span class="text-2xl font-black tracking-widest text-white uppercase font-display">INDOROSTER</span>
                            <p class="text-[10px] tracking-widest text-terra-400 font-bold uppercase">Pabrik Roster Beton Plered</p>
                        </div>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md mb-6">
                        Pabrik Roster Beton Premium tangan pertama dari Plered, Purwakarta. Memproduksi roster presisi cetak tumbuk padat dengan alat khusus pengrajin berpengalaman, menghasilkan roster yang keras, rapi, dan kokoh untuk arsitektur tropis modern.
                    </p>
                    <div class="inline-flex items-center gap-3 bg-slate-900/90 border border-slate-800 px-4 py-2.5 rounded-xl text-xs text-slate-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span>Garansi Pengiriman Aman & Penggantian Pecah 100%</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-200 tracking-widest uppercase mb-4">Eksplorasi & Legalitas</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('catalog') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Katalog Roster Lengkap</a></li>
                        <li><a href="{{ route('article.index') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors font-medium text-terra-400/90">Artikel & Edukasi</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Galeri Proyek Arsitektur</a></li>
                        <li><a href="{{ route('video-inspiration') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Video Dokumentasi & Pasang</a></li>
                        <li><a href="{{ route('production') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Standar Mutu Produksi</a></li>
                        <li><a href="{{ route('order.tracking') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Lacak Status Pesanan</a></li>
                        <li><a href="{{ route('about-us') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Tentang Pabrik Kami</a></li>
                        <li><a href="{{ route('dynamic.page', 'syarat-dan-ketentuan') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('dynamic.page', 'kebijakan-privasi') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Hubungi Sales & Pabrik</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-200 tracking-widest uppercase mb-4">Lokasi & Kontak</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-terra-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>{{ \App\Models\SiteSetting::getValue('factory_address', 'Plered, Purwakarta, Jawa Barat') }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-terra-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="hover:text-emerald-400 transition">{{ $rawWa }}</a>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-terra-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>{{ \App\Models\SiteSetting::getValue('contact_email', 'hello@indoroster.com') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-6 text-xs text-slate-500 text-center sm:text-left">
                    <p>&copy; {{ date('Y') }} INDOROSTER Indonesia. Hak Cipta Dilindungi.</p>
                    <div class="flex items-center gap-3 text-slate-400 font-medium">
                        <a href="{{ route('dynamic.page', 'syarat-dan-ketentuan') }}" class="hover:text-terra-400 transition-colors">Syarat & Ketentuan</a>
                        <span class="text-slate-700">•</span>
                        <a href="{{ route('dynamic.page', 'kebijakan-privasi') }}" class="hover:text-terra-400 transition-colors">Kebijakan Privasi</a>
                        <span class="text-slate-700">•</span>
                        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="hover:text-terra-400 transition-colors">Sitemap XML</a>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <!-- TikTok -->
                    <a href="{{ \App\Models\SiteSetting::getValue('tiktok_url', '#') }}" target="_blank" rel="noopener" class="text-slate-500 hover:text-white transition-colors" aria-label="TikTok">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V8.75a8.18 8.18 0 004.76 1.52V6.83a4.84 4.84 0 01-1-.14z"/></svg>
                    </a>
                    <!-- Instagram -->
                    <a href="{{ \App\Models\SiteSetting::getValue('instagram_url', '#') }}" target="_blank" rel="noopener" class="text-slate-500 hover:text-white transition-colors" aria-label="Instagram">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                    </a>
                    <!-- YouTube -->
                    <a href="{{ \App\Models\SiteSetting::getValue('youtube_url', '#') }}" target="_blank" rel="noopener" class="text-slate-500 hover:text-white transition-colors" aria-label="YouTube">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Interactive WhatsApp Widget (Pulse + Badge + Chat Modal) -->
    <x-whatsapp-widget />

    <!-- Live Recent Purchase Social Proof Popup -->
    <x-live-sales-popup />

    @livewireScripts

    <!-- TomSelect JS (deferred, non-blocking) -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js" defer></script>
</body>
</html>
