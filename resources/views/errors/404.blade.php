<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | Indoroster</title>
    <meta name="description" content="Halaman yang Anda cari tidak ditemukan. Kembali ke halaman utama Indoroster untuk melihat katalog roster beton minimalis kami.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logo_indoroster_no_text.PNG') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-900 text-white min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <div class="text-[120px] font-black leading-none text-terra-500 mb-4" style="font-family: 'Outfit', sans-serif;">404</div>
        <h1 class="text-2xl md:text-3xl font-black text-white mb-4">Halaman Tidak Ditemukan</h1>
        <p class="text-slate-400 text-base md:text-lg mb-8 max-w-md mx-auto">
            Halaman yang Anda cari mungkin sudah dipindahkan, dihapus, atau tidak pernah ada. Jangan khawatir, kami punya banyak produk roster keren untuk Anda!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center gap-2 bg-terra-500 hover:bg-terra-600 text-white px-8 py-4 rounded-xl font-bold text-base transition-all shadow-lg shadow-terra-500/30">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="{{ url('/katalog') }}" 
               class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-xl font-bold text-base border border-white/20 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Lihat Katalog Produk
            </a>
        </div>
        <p class="text-slate-600 text-sm mt-12">
            Butuh bantuan? 
            <a href="{{ url('/kontak') }}" class="text-terra-400 hover:text-terra-300 underline">Hubungi kami</a>
        </p>
    </div>
    @livewireScripts
</body>
</html>
