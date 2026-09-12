<!DOCTYPE html>
@php
    $themeDefaultMode = \App\Models\SiteSetting::getValue('theme_default_mode', 'light');
    $themeAllowUserToggle = filter_var(\App\Models\SiteSetting::getValue('theme_allow_user_toggle', true), FILTER_VALIDATE_BOOLEAN);
    $themeVersion = \App\Models\SiteSetting::getValue('theme_version', '1');
    $cookieTheme = request()->cookie('indoroster_theme');

    if (! $themeAllowUserToggle) {
        // Mode dikunci oleh Admin: tidak boleh ada cookie override pengunjung
        $isDarkInitial = ($themeDefaultMode === 'dark');
    } else {
        // Mode toggle diizinkan: ikuti cookie jika ada, atau default admin
        $isDarkInitial = ($cookieTheme === 'dark') || (! $cookieTheme && $themeDefaultMode === 'dark');
    }
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth {{ $isDarkInitial ? 'dark' : '' }}">
<head>
    <!-- Mobile & iOS Safari Theme Color & Color Scheme (Mencegah iPhone otomatis invert jadi dark) -->
    <meta name="theme-color" content="{{ $isDarkInitial ? '#020617' : '#ffffff' }}">
    <meta name="color-scheme" content="{{ $isDarkInitial ? 'dark' : 'light' }}">

    {{-- GA4 ID & Meta Pixel ID --}}
    @php
        $gaId = \App\Models\SiteSetting::getValue('google_analytics_id', 'G-GZQXJ03B4C');
        if (empty($gaId) || $gaId === 'G-XXXXXXXXXX') {
            $gaId = 'G-GZQXJ03B4C';
        }
        $metaPixelId = config('services.meta.pixel_id') ?: \App\Models\SiteSetting::getValue('meta_pixel_id', '947593387751313');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $pageTitle      = $title ?? \App\Models\SiteSetting::getValue('meta_title_default', 'IndoRoster — Pabrik Roster Beton Minimalis | Suplier Proyek Jabodetabek & Indonesia');
        $pageDesc       = $description ?? \App\Models\SiteSetting::getValue('meta_description_default', 'Pusat produsen tangan pertama roster beton minimalis, bata expose, dan loster arsitektural modern harga pabrik. Melayani pengiriman cepat partai kecil & proyek ribuan pcs ke Jabodetabek, Bandung, Karawang, Cirebon & seluruh Indonesia.');
        $pageImage      = $ogImage ?? (\App\Models\SiteSetting::getValue('og_image_default') ?: asset('assets/logo_indoroster_no_text.PNG'));
        $robotsMeta     = $robots ?? 'index, follow';

        // Canonical: strip SEMUA query params — clean URL /katalog/{slug} sudah
        // diset sebagai canonicalOverride oleh masing-masing Livewire component.
        // Tidak ada parameter yang "aman" — ?category pun menyebabkan duplicate content
        // karena sudah ada clean URL /katalog/{categorySlug}.
        $canonicalUrl = $canonicalOverride ?? url()->current();

        // OG: bisa override title/description secara terpisah dari meta
        $ogTitleFinal = $ogTitle ?? $pageTitle;
        $ogDescFinal  = $ogDescription ?? $pageDesc;

        // Keywords: per-halaman jika tersedia, fallback ke keyword bisnis global dari database
        $keywordsMeta = $keywords ?? \App\Models\SiteSetting::getValue('seo_keywords_default', 'roster beton minimalis, loster beton minimalis, jual roster beton, pabrik roster beton, harga roster beton, roster dinding minimalis, ventilasi beton, jual roster jakarta, roster beton jabodetabek, roster beton bandung, supplier roster proyek');

        $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        // Theme & Appearance Settings
        $themeAccentColor = \App\Models\SiteSetting::getValue('theme_accent_color', '#f75c20');
        $themeNavbarStyle = \App\Models\SiteSetting::getValue('theme_navbar_style', 'glassmorphism');
        $isOrderModeMidtrans = \App\Models\SiteSetting::getValue('order_mode', 'midtrans') === 'midtrans';
    @endphp

    <title>{{ $pageTitle }}</title>

    <!-- Theme Initialization Script (Zero-FOUC, iOS Safari / Android Sync & Livewire SPA Sync) -->
    <script>
        function updateMetaTheme(isDark) {
            try {
                let metaThemeColor = document.querySelector('meta[name="theme-color"]');
                if (!metaThemeColor) {
                    metaThemeColor = document.createElement('meta');
                    metaThemeColor.setAttribute('name', 'theme-color');
                    document.head.appendChild(metaThemeColor);
                }
                let metaColorScheme = document.querySelector('meta[name="color-scheme"]');
                if (!metaColorScheme) {
                    metaColorScheme = document.createElement('meta');
                    metaColorScheme.setAttribute('name', 'color-scheme');
                    document.head.appendChild(metaColorScheme);
                }
                if (isDark) {
                    metaThemeColor.setAttribute('content', '#020617');
                    metaColorScheme.setAttribute('content', 'dark');
                } else {
                    metaThemeColor.setAttribute('content', '#ffffff');
                    metaColorScheme.setAttribute('content', 'light');
                }
            } catch (e) {}
        }

        function syncIndorosterTheme() {
            try {
                const defaultMode = '{{ $themeDefaultMode }}';
                const allowToggle = {{ $themeAllowUserToggle ? 'true' : 'false' }};
                const currentVersion = '{{ $themeVersion }}';
                let isDark = false;

                if (!allowToggle) {
                    // Admin mengunci mode tampilan: hapus sisa cookie/storage lama agar HP/iPhone tidak tersangkut
                    localStorage.removeItem('indoroster_theme');
                    localStorage.removeItem('indoroster_theme_version');
                    document.cookie = 'indoroster_theme=; path=/; max-age=0; SameSite=Lax';

                    if (defaultMode === 'dark') {
                        isDark = true;
                    } else if (defaultMode === 'system') {
                        isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    } else {
                        isDark = false;
                    }
                } else {
                    // Jika admin baru saja menyimpan/mengubah setting tema di admin panel,
                    // sinkronkan versi dan reset cookie lama agar browser mengadopsi default baru admin
                    const savedVersion = localStorage.getItem('indoroster_theme_version');
                    if (savedVersion !== currentVersion) {
                        localStorage.removeItem('indoroster_theme');
                        localStorage.setItem('indoroster_theme_version', currentVersion);
                        document.cookie = 'indoroster_theme=; path=/; max-age=0; SameSite=Lax';
                    }

                    const cookieMatch = document.cookie.match(/(^|;\s*)indoroster_theme=([^;]+)/);
                    const cookieVal = cookieMatch ? cookieMatch[2] : null;
                    const storedTheme = localStorage.getItem('indoroster_theme') || cookieVal;

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
                }

                if (isDark) {
                    document.documentElement.classList.add('dark');
                    updateMetaTheme(true);
                } else {
                    document.documentElement.classList.remove('dark');
                    updateMetaTheme(false);
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

    <!-- Favicon & Brand Icons (Google, Bing, Yandex, Apple & Modern Browsers) -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/logo_indoroster_no_text.PNG') }}">

    <!-- DNS Prefetch & Preconnect -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Preload LCP (Page-specific Hero Banner/Product Image) -->
    @stack('preload-lcp')

    <!-- Google Fonts: Inter & Outfit with display=swap -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@700;800&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@700;800&display=swap">
    </noscript>

    <!-- Primary SEO Meta -->
    <meta name="google-site-verification" content="{{ \App\Models\SiteSetting::getValue('google_site_verification', '5T-7RFSLMEwCNdq2lx93GU5S5BckFBgjFPf5B-HlT1Y') }}" />
    <meta name="yandex-verification" content="{{ \App\Models\SiteSetting::getValue('yandex_verification', 'd74c403698ee3929') }}" />
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="keywords" content="{{ $keywordsMeta }}">
    <meta name="author" content="Indoroster">
    <meta name="robots" content="{{ $robotsMeta }}">
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Jabodetabek, Bandung, Jawa Barat, Indonesia">

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

    <!-- Meta Pixel Code -->
    @if(!empty($metaPixelId))
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $metaPixelId }}');
    </script>
    @endif
    <!-- End Meta Pixel Code -->

    <!-- Global Structured Data (Organization, LocalBusiness, WebSite) -->
    <x-seo-schemas />

    <!-- Per-Page SEO: Schema JSON-LD, etc. -->
    @stack('seo')
</head>

<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 selection:bg-terra-500 selection:text-white flex flex-col min-h-screen relative" x-data="{ mobileMenuOpen: false }">
    @if(!empty($metaPixelId))
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1"
    /></noscript>
    @endif
    
    @php
        $navigationMenus = \App\Models\NavigationMenu::where('is_active', true)->orderBy('order', 'asc')->get();
        $navbarAlignment = \App\Models\SiteSetting::getValue('navbar_alignment', 'left');
        $alignmentClass = match($navbarAlignment) {
            'center' => 'justify-center',
            'right' => 'justify-end',
            default => 'justify-start',
        };
        $showNavbarLayout = filter_var($showNavbar ?? false, FILTER_VALIDATE_BOOLEAN);
        $isPromoPage = (request()->routeIs('promo.*') || request()->is('promo*') || request()->is('penawaran-proyek*')) && ! $showNavbarLayout;
    @endphp

    <!-- Sticky Announcement / Trust Strip -->
    @php
        $topBarActive = filter_var(\App\Models\SiteSetting::getValue('top_bar_is_active', true), FILTER_VALIDATE_BOOLEAN);
        $topBarTagline = \App\Models\SiteSetting::getValue('top_bar_tagline', 'Pabrik Tangan Pertama — Suplier Jabodetabek, Bandung & Indonesia');
        $topBarSubtext = \App\Models\SiteSetting::getValue('top_bar_subtext', 'Cetak Tumbuk Padat & Presisi');
        $topBarTrackingText = \App\Models\SiteSetting::getValue('top_bar_tracking_text', 'Lacak Pengiriman');
    @endphp

    @if($topBarActive && !$isPromoPage)
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

    @if($isPromoPage)
    <!-- Minimalist Distraction-Free Header for Ads (Zero Menu Bounce) -->
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 py-3.5 shadow-xs transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="IndoRoster" class="h-8 sm:h-9 w-auto">
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-black tracking-widest text-slate-900 dark:text-white uppercase font-display leading-tight">INDOROSTER</span>
                    <span class="text-[9px] font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider">Pusat Roster Beton Minimalis</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-[10px] font-bold uppercase text-slate-400">Layanan Konsultasi & RAB</span>
                    <span class="text-xs font-black text-slate-800 dark:text-slate-100">{{ $rawWa }}</span>
                </div>
                <a href="https://wa.me/{{ $waNumber }}" target="_blank" data-meta-event="Contact" data-content-name="Promo Header Fast WA" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black shadow-xs hover:scale-105 active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                    <span>Chat Sales Pabrik</span>
                </a>
            </div>
        </div>
    </header>
    @else
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
                            localStorage.setItem('indoroster_theme_version', '{{ $themeVersion }}');
                            document.cookie = 'indoroster_theme=' + this.currentTheme + '; path=/; max-age=31536000; SameSite=Lax';
                            const isDark = (this.currentTheme === 'dark');
                            if (isDark) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                            if (typeof updateMetaTheme === 'function') {
                                updateMetaTheme(isDark);
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
                                    <a href="{{ route('member.profile') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        Profil & Kemitraan
                                    </a>
                                    <a href="{{ route('member.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('order.tracking') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Lacak Pesanan
                                    </a>
                                    <a href="{{ route('member.addresses') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-terra-500 transition-colors">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Alamat Saya
                                    </a>
                                    @if(!$isOrderModeMidtrans)
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
    @endif

    @if(!$isPromoPage)
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
                                            localStorage.setItem('indoroster_theme_version', '{{ $themeVersion }}');
                                            document.cookie = 'indoroster_theme=' + this.currentTheme + '; path=/; max-age=31536000; SameSite=Lax';
                                            const isDark = (this.currentTheme === 'dark');
                                            if (isDark) {
                                                document.documentElement.classList.add('dark');
                                            } else {
                                                document.documentElement.classList.remove('dark');
                                            }
                                            if (typeof updateMetaTheme === 'function') {
                                                updateMetaTheme(isDark);
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
    @endif

    @if(!$isPromoPage)
    <!-- Trust Top Announcement Bar with Hover Tooltips (seperti referensi Toco) -->
    <x-trust-top-bar />
    @endif

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

    @if(!$isPromoPage)
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
        <a href="{{ auth()->check() ? route('member.orders') : route('login') }}" class="flex flex-col items-center gap-1 text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition {{ request()->routeIs('member.*') || request()->routeIs('login') ? 'text-terra-600 dark:text-terra-400 font-bold' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="text-[10px]">{{ auth()->check() ? 'Pesanan' : 'Masuk' }}</span>
        </a>
    </nav>
    @endif

    @if($isPromoPage)
    <!-- Minimalist High-Trust Footer for Promo Page -->
    <footer class="bg-slate-950 text-slate-400 pt-8 pb-24 md:pb-10 border-t border-slate-800/80 text-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="space-y-1">
                <p class="font-bold text-slate-200">INDOROSTER — Pusat Roster Beton Minimalis</p>
                <p class="text-[11px] text-slate-500">Garansi 100% Pecah Ganti Baru di Tempat • Pengiriman Armada Langsung Pabrik • Layanan WA: {{ $rawWa }}</p>
            </div>
            <div class="text-[11px] text-slate-500">
                &copy; {{ date('Y') }} IndoRoster Indonesia. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
    @else
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
                        Pabrik Roster Beton Premium langsung dari sentra produksi Plered, Purwakarta. Memproduksi roster presisi cetak tumbuk padat dengan alat khusus pengrajin berpengalaman, menghasilkan roster yang keras, rapi, dan kokoh untuk arsitektur tropis modern.
                    </p>
                    <div class="inline-flex items-center gap-3 bg-slate-900/90 border border-slate-800 px-4 py-2.5 rounded-xl text-xs text-slate-300">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span>Garansi Pengiriman Aman & Penggantian Pecah 100%</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-200 tracking-widest uppercase mb-4">Layanan B2B & Wilayah</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('b2b.contractor') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Khusus Kontraktor Proyek</a></li>
                        <li><a href="{{ route('b2b.developer') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Pengadaan Developer</a></li>
                        <li><a href="{{ route('b2b.architect') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Katalog Teknis Arsitek</a></li>
                        <li><a href="{{ route('b2b.supplier') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Grosir Toko Bangunan</a></li>
                        <li><a href="{{ route('b2b.project') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Proyek Fasad & Gedung</a></li>
                        <li><a href="{{ route('tools.calculator') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors text-terra-400/90 font-medium">🧮 Kalkulator Dinding</a></li>
                        <li><a href="{{ route('location.index') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">📍 Area Layanan Kirim</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-200 tracking-widest uppercase mb-4">Eksplorasi & Info</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('catalog') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Katalog Roster Lengkap</a></li>
                        <li><a href="{{ route('article.index') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Artikel & Edukasi</a></li>
                        <li><a href="{{ route('gallery') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Galeri Proyek Arsitektur</a></li>
                        <li><a href="{{ route('video-inspiration') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Video Dokumentasi</a></li>
                        <li><a href="{{ route('production') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Standar Mutu Produksi</a></li>
                        <li><a href="{{ route('order.tracking') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Lacak Status Pesanan</a></li>
                        <li><a href="{{ route('about-us') }}" class="text-sm text-slate-400 hover:text-terra-400 transition-colors">Tentang Pabrik Kami</a></li>
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
                            <span>{{ \App\Models\SiteSetting::getValue('contact_email', 'abdulhamid66266@gmail.com') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-6 text-xs text-slate-500 text-center sm:text-left">
                    <p>&copy; {{ date('Y') }} INDOROSTER Indonesia. Hak Cipta Dilindungi.</p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-3 gap-y-1 text-slate-400 font-medium">
                        <a href="{{ route('policy.return') }}" class="hover:text-terra-400 transition-colors">Garansi & Pengembalian</a>
                        <span class="text-slate-700">•</span>
                        <a href="{{ route('policy.shipping') }}" class="hover:text-terra-400 transition-colors">Kebijakan Pengiriman</a>
                        <span class="text-slate-700">•</span>
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
    @endif

    @if(!$isPromoPage)
    <!-- Floating Interactive WhatsApp Widget (Pulse + Badge + Chat Modal) -->
    <x-whatsapp-widget />

    <!-- Live Recent Purchase Social Proof Popup -->
    <x-live-sales-popup />
    @endif

    @livewireScripts
    @stack('scripts')

    {{-- Google Analytics 4 — Lazy load setelah window.load + delay 3 dtk
         Tidak diletakkan di <head> agar tidak memblokir render (FCP/LCP/TBT).
         Data konversi & sesi panjang tetap ter-track 100%; hanya bounce < 3 dtk yang terlewat. --}}
    @if(!empty($gaId))
    <script>
      (function() {
        function loadGA4() {
          // Muat script GA4 secara dinamis
          var s = document.createElement('script');
          s.async = true;
          s.src = 'https://www.googletagmanager.com/gtag/js?id={{ $gaId }}';
          document.head.appendChild(s);

          window.dataLayer = window.dataLayer || [];
          function gtag(){ dataLayer.push(arguments); }
          window.gtag = gtag;
          gtag('js', new Date());
          gtag('config', '{{ $gaId }}');
          @if($gaId !== 'G-E7GQNHMDCZ')
          gtag('config', 'G-E7GQNHMDCZ');
          @endif

          // Livewire SPA Navigation Pageview Tracking
          document.addEventListener('livewire:navigated', function() {
            if (typeof gtag === 'function') {
              gtag('event', 'page_view', {
                page_title: document.title,
                page_location: window.location.href,
                page_path: window.location.pathname + window.location.search
              });
            }
          });
        }

        // Tunggu halaman selesai render, lalu delay 3 detik sebelum muat GA4
        if (document.readyState === 'complete') {
          setTimeout(loadGA4, 3000);
        } else {
          window.addEventListener('load', function() {
            setTimeout(loadGA4, 3000);
          });
        }
      })();
    </script>
    @endif

    @php
        $authUserData = [];
        if (auth()->check()) {
            $u = auth()->user();
            $authUserData = array_filter([
                'em' => $u->email,
                'ph' => $u->phone,
                'name' => $u->name,
                'external_id' => (string) $u->id,
            ]);
        }
    @endphp

    <!-- Global Fast WhatsApp Lead Modal (EMQ Booster 8.5 - 9.5) -->
    <div x-data="{
            isOpen: false,
            targetWaUrl: '',
            contentName: 'WhatsApp Consultation',
            leadName: '',
            leadPhone: '',
            leadCity: '',
            leadQty: '',

            init() {
                try {
                    const saved = JSON.parse(localStorage.getItem('indoroster_lead_user') || '{}');
                    if (saved.name) this.leadName = saved.name;
                    if (saved.phone) this.leadPhone = saved.phone;
                    if (saved.city) this.leadCity = saved.city;
                    if (saved.qty) this.leadQty = saved.qty;
                } catch(e) {}
            },

            openModal(url, name, defaultCity) {
                this.targetWaUrl = url || 'https://wa.me/6281389709847';
                this.contentName = name || 'WhatsApp Consultation';

                try {
                    const saved = JSON.parse(localStorage.getItem('indoroster_lead_user') || '{}');
                    if (saved.name) this.leadName = saved.name;
                    if (saved.phone) this.leadPhone = saved.phone;
                    if (saved.city) this.leadCity = saved.city;
                    if (saved.qty) this.leadQty = saved.qty;
                } catch(e) {}

                if (!this.leadCity && defaultCity) {
                    this.leadCity = defaultCity;
                }

                this.isOpen = true;
            },

            closeModal() {
                this.isOpen = false;
            },

            submitLead() {
                if (!this.leadName || !this.leadPhone) return;

                try {
                    localStorage.setItem('indoroster_lead_user', JSON.stringify({
                        name: this.leadName,
                        phone: this.leadPhone,
                        city: this.leadCity,
                        qty: this.leadQty
                    }));
                } catch(e) {}

                const userData = {
                    ph: this.leadPhone,
                    name: this.leadName,
                    ct: this.leadCity
                };

                if (typeof window.trackMetaEvent === 'function') {
                    window.trackMetaEvent('Contact', {
                        content_name: this.contentName,
                        currency: 'IDR'
                    }, userData);

                    window.trackMetaEvent('Lead', {
                        content_name: this.contentName,
                        currency: 'IDR'
                    }, userData);
                }

                let waPhone = '6281389709847';
                if (this.targetWaUrl) {
                    const phoneMatch = this.targetWaUrl.match(/wa\.me\/([0-9]+)/);
                    if (phoneMatch && phoneMatch[1]) {
                        waPhone = phoneMatch[1];
                    }
                }

                // Extract existing message text or product info if any
                let originalText = '';
                try {
                    if (this.targetWaUrl && this.targetWaUrl.includes('text=')) {
                        const urlObj = new URL(this.targetWaUrl);
                        originalText = urlObj.searchParams.get('text') || '';
                    }
                } catch(e) {
                    const textMatch = this.targetWaUrl.match(/text=([^&]+)/);
                    if (textMatch && textMatch[1]) {
                        originalText = decodeURIComponent(textMatch[1]);
                    }
                }

                const cityName = this.leadCity ? this.leadCity : 'Jabodetabek & Jawa Barat';
                const qtyText = this.leadQty ? this.leadQty : '100+ pcs / Sesuai Rekomendasi';

                // If originalText contains specific motif or detail, extract motif if possible
                let motifDetail = '';
                if (originalText) {
                    const motifMatch = originalText.match(/Pilihan Motif:\s*\*?([^\n\*]+)\*?/i);
                    if (motifMatch && motifMatch[1]) {
                        motifDetail = `\n• *Pilihan Motif:* ${motifMatch[1].trim()}`;
                    }
                }

                const messageText = `Halo Tim Sales Pabrik IndoRoster, saya *${this.leadName}* ingin klaim Promo Harga Pabrik & Cek Ongkir:\n\n` +
                    `📋 *DATA KONSULTASI / PENAWARAN:*\n` +
                    `• *Nama:* ${this.leadName}\n` +
                    `• *WhatsApp:* ${this.leadPhone}\n` +
                    `• *Lokasi Kirim:* ${cityName}\n` +
                    `• *Jumlah Kebutuhan:* ${qtyText}` +
                    motifDetail + `\n` +
                    `• *Halaman:* ${this.contentName}\n\n` +
                    `Mohon info ketersediaan stok, total penawaran harga promo pabrik, dan estimasi jadwal kirim armada ke lokasi saya. Terima kasih!`;

                const finalUrl = `https://wa.me/${waPhone}?text=${encodeURIComponent(messageText)}`;

                this.closeModal();
                const win = window.open(finalUrl, '_blank');
                if (!win || win.closed || typeof win.closed === 'undefined') {
                    window.location.href = finalUrl;
                }
            },

            skipAndOpen() {
                if (typeof window.trackMetaEvent === 'function') {
                    window.trackMetaEvent('Contact', {
                        content_name: this.contentName,
                        currency: 'IDR'
                    });
                }
                this.closeModal();
                const targetUrl = this.targetWaUrl || 'https://wa.me/6281389709847';
                const win = window.open(targetUrl, '_blank');
                if (!win || win.closed || typeof win.closed === 'undefined') {
                    window.location.href = targetUrl;
                }
            }
         }" 
         @open-fast-wa-modal.window="openModal($event.detail.url, $event.detail.name, $event.detail.city)"
         x-show="isOpen" 
         x-cloak 
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs transition-opacity"
         @keydown.escape.window="closeModal()">
        
        <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden"
             @click.outside="closeModal()"
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Header with Badge -->
            <div class="bg-gradient-to-r from-terra-600 via-terra-500 to-amber-500 p-5 sm:p-6 text-white relative">
                <button type="button" @click="closeModal()" class="absolute top-4 right-4 text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <span class="inline-flex items-center gap-1 bg-white/20 text-white text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full mb-2 tracking-wide">
                    💬 Respon Cepat Sales Pabrik
                </span>
                <h3 class="text-lg sm:text-xl font-black font-display text-white leading-tight">
                    Konsultasi & Cek Ongkir Resmi
                </h3>
                <p class="text-xs text-white/90 mt-1 leading-relaxed">
                    Dapatkan penawaran harga pabrik tangan pertama & konfirmasi jadwal armada kirim ke lokasi Anda.
                </p>
            </div>

            <!-- Form Body -->
            <form @submit.prevent="submitLead()" class="p-5 sm:p-6 space-y-3.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Nama Lengkap / Panggilan <span class="text-terra-500">*</span></label>
                    <input type="text" x-model="leadName" required placeholder="Contoh: Pak Bambang / Ibu Maya" class="w-full h-11 px-3.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Nomor WhatsApp Aktif <span class="text-terra-500">*</span></label>
                    <input type="tel" x-model="leadPhone" required placeholder="Contoh: 081234567890" class="w-full h-11 px-3.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Kota / Lokasi Kirim</label>
                        <input type="text" x-model="leadCity" placeholder="Contoh: Jakarta / Bekasi / Bandung" class="w-full h-11 px-3.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Jumlah Pesanan (Pcs)</label>
                        <input type="text" x-model="leadQty" placeholder="Contoh: 200 pcs / Luas 15 m²" class="w-full h-11 px-3.5 text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                    </div>
                </div>

                <div class="pt-2 space-y-2">
                    <button type="submit" class="w-full h-12 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white font-black text-sm shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                        <span>Lanjut Chat WhatsApp Resmi ➔</span>
                    </button>

                    <button type="button" @click="skipAndOpen()" class="w-full text-center text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 py-1 transition-colors cursor-pointer">
                        Langsung ke WhatsApp tanpa isi data
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $currentUser = auth()->user();
        $authUserData = $currentUser ? [
            'em' => $currentUser->email,
            'ph' => $currentUser->phone ?? '',
            'name' => $currentUser->name ?? '',
            'external_id' => (string) $currentUser->id,
        ] : null;
    @endphp

    <!-- Meta Hybrid Tracker (Pixel + Conversions API Deduplication & High-EMQ Matching) -->
    <script>
    (function() {
        // 1. Capture fbclid to _fbc cookie automatically (90 days expiry)
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const fbclid = urlParams.get('fbclid');
            if (fbclid) {
                document.cookie = "_fbc=fb.1." + Date.now() + "." + encodeURIComponent(fbclid) + "; path=/; max-age=7776000; SameSite=Lax";
            }
        } catch(e) {}

        window.indorosterAuthUser = @json($authUserData);

        function getCookie(name) {
            try {
                const value = "; " + document.cookie;
                const parts = value.split("; " + name + "=");
                if (parts.length === 2) return parts.pop().split(";").shift();
            } catch(e) {}
            return '';
        }

        function generateEventId() {
            return 'evt_' + Date.now() + '_' + Math.random().toString(36).substring(2, 11);
        }

        function getMergedUserData(explicitUserData = {}) {
            let savedLead = {};
            try {
                savedLead = JSON.parse(localStorage.getItem('indoroster_lead_user') || '{}');
            } catch(e) {}

            const authUser = window.indorosterAuthUser || {};

            return Object.assign({}, {
                em: (explicitUserData.em || authUser.em || savedLead.em || '').trim(),
                ph: (explicitUserData.ph || authUser.ph || savedLead.phone || '').trim(),
                name: (explicitUserData.name || authUser.name || savedLead.name || '').trim(),
                ct: (explicitUserData.ct || savedLead.city || '').trim(),
                country: 'id',
                fbp: getCookie('_fbp') || '',
                fbc: getCookie('_fbc') || ''
            }, explicitUserData);
        }

        window.trackMetaEvent = function(eventName, customData = {}, userData = {}, givenEventId = null) {
            const eventId = givenEventId || generateEventId();
            const finalUserData = getMergedUserData(userData);

            // 1. Browser Pixel Track with eventID for Deduplication
            if (typeof fbq === 'function') {
                fbq('track', eventName, customData, { eventID: eventId });
            }

            // 2. Server-side CAPI Track with identical event_id
            const payload = JSON.stringify({
                event_name: eventName,
                event_id: eventId,
                event_source_url: window.location.href,
                custom_data: customData,
                user_data: finalUserData
            });

            // Try navigator.sendBeacon first for non-blocking, reliable delivery across page transitions
            let beaconSent = false;
            if (navigator.sendBeacon) {
                try {
                    const blob = new Blob([payload], { type: 'application/json' });
                    beaconSent = navigator.sendBeacon('/api/meta-events', blob);
                } catch(e) {
                    beaconSent = false;
                }
            }

            // Fallback to fetch with keepalive if beacon was not sent
            if (!beaconSent) {
                try {
                    fetch('/api/meta-events', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: payload,
                        keepalive: true
                    }).catch(function() {});
                } catch(e) {}
            }

            return eventId;
        };

        // Dual-Track PageView (Browser Pixel + Server CAPI) on initial page load
        if (typeof window.trackMetaEvent === 'function') {
            window.trackMetaEvent('PageView');
        }

        // Dual-Track PageView on Livewire SPA navigation
        document.addEventListener('livewire:navigated', function() {
            if (typeof window.trackMetaEvent === 'function') {
                window.trackMetaEvent('PageView');
            }
        });

        // Intercept click on WhatsApp links
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a[href*="wa.me"], a[href*="whatsapp.com"], [data-meta-event="Contact"]');
            if (target) {
                const isSkipModal = target.getAttribute('data-skip-modal') === 'true';
                const contentName = target.getAttribute('data-content-name') || 'WhatsApp Consultation';
                const defaultCity = target.getAttribute('data-city') || '';
                const href = target.getAttribute('href');

                if (!isSkipModal && href && (href.includes('wa.me') || href.includes('whatsapp.com'))) {
                    e.preventDefault();
                    window.dispatchEvent(new CustomEvent('open-fast-wa-modal', {
                        detail: { url: href, name: contentName, city: defaultCity }
                    }));
                } else {
                    if (typeof window.trackMetaEvent === 'function') {
                        window.trackMetaEvent('Contact', {
                            content_name: contentName,
                            currency: 'IDR'
                        });
                    }
                }
            }
        }, { capture: true });
    })();
    </script>
</body>
</html>
