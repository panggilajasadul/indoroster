<div>
<?php $__env->startPush('seo'); ?>
    <?php if (isset($component)) { $__componentOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo-schemas','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo-schemas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c)): ?>
<?php $attributes = $__attributesOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c; ?>
<?php unset($__attributesOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c)): ?>
<?php $component = $__componentOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c; ?>
<?php unset($__componentOriginal2f1dd3f12cb8a5034627a1bd41ab6f1c); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page && is_array($page->content) && count($page->content) > 0): ?>
        <?php if (isset($component)) { $__componentOriginal4e50e64370b7afa47767aee7986ebc80 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e50e64370b7afa47767aee7986ebc80 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.block-renderer','data' => ['blocks' => $page->content]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('block-renderer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['blocks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($page->content)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4e50e64370b7afa47767aee7986ebc80)): ?>
<?php $attributes = $__attributesOriginal4e50e64370b7afa47767aee7986ebc80; ?>
<?php unset($__attributesOriginal4e50e64370b7afa47767aee7986ebc80); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4e50e64370b7afa47767aee7986ebc80)): ?>
<?php $component = $__componentOriginal4e50e64370b7afa47767aee7986ebc80; ?>
<?php unset($__componentOriginal4e50e64370b7afa47767aee7986ebc80); ?>
<?php endif; ?>
    <?php else: ?>
    <!-- Hero Banner Slider -->
    <?php
        $sliderDuration = (int) \App\Models\SiteSetting::getValue('hero_slider_duration', 5000);
        $bannerCount = $banners->count();
    ?>
    <div id="heroSlider" class="relative bg-slate-900 overflow-hidden min-h-[620px] sm:min-h-[580px] md:min-h-[70vh] lg:min-h-[75vh]">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bannerCount > 0): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div 
                    class="hero-slide absolute inset-0 transition-opacity duration-1000 <?php echo e($index === 0 ? 'active' : ''); ?>"
                    style="<?php echo e($index === 0 ? 'opacity:1; z-index:1;' : 'opacity:0; z-index:0;'); ?>"
                    data-slide="<?php echo e($index); ?>"
                >
                    <img 
                        src="<?php echo e($banner->image_url); ?>" 
                        alt="<?php echo e($banner->title); ?> — Roster Beton Minimalis Indoroster Plered Purwakarta" 
                        class="w-full h-full object-cover"
                        style="opacity: 0.45;"
                        <?php echo e($index === 0 ? 'loading=eager fetchpriority=high' : 'loading=lazy'); ?>

                    >
                    <div class="absolute inset-0 bg-slate-950/75 sm:bg-transparent sm:bg-gradient-to-r sm:from-slate-950/95 sm:via-slate-950/70 sm:to-transparent"></div>
                    
                    <div class="absolute inset-0 flex items-center py-12 sm:py-16 md:py-0">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12 md:mt-0">
                            <div class="max-w-2xl">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($index === 0): ?>
                                <h1 class="text-sm font-bold text-terra-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <span class="w-5 h-px bg-terra-500"></span>
                                    Pabrik Roster Beton Minimalis Plered Purwakarta
                                    <span class="w-5 h-px bg-terra-500"></span>
                                </h1>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="inline-block bg-terra-500/15 border border-terra-500/40 text-orange-500 px-3 py-1 rounded-full text-xs sm:text-sm font-semibold mb-4 sm:mb-5 tracking-wider uppercase">
                                    Pabrik Tangan Pertama · Plered, Purwakarta
                                </div>
                                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-semibold text-white leading-tight mb-6 sm:mb-8 tracking-normal">
                                    <?php echo $banner->title; ?>

                                </h2>
                                <p class="text-sm sm:text-base text-slate-300 mb-6 sm:mb-8 max-w-lg leading-relaxed">
                                    <?php echo $banner->subtitle; ?>

                                </p>
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                                    <a href="<?php echo e($banner->button_url ?? route('catalog')); ?>" class="inline-flex items-center justify-center border border-white bg-black/20 hover:bg-white hover:text-slate-900 text-white px-6 sm:px-8 py-3 sm:py-3.5 rounded-full font-semibold text-sm sm:text-base transition-all text-center backdrop-blur-sm">
                                        <?php echo e($banner->button_text ?? 'Lihat Katalog Produk'); ?>

                                    </a>
                                    <a href="<?php echo e(route('contact')); ?>" class="inline-flex items-center justify-center border border-terra-500 bg-black/20 hover:bg-terra-500 hover:text-white text-terra-500 px-6 sm:px-8 py-3 sm:py-3.5 rounded-full font-semibold text-sm sm:text-base transition-all text-center backdrop-blur-sm">
                                        Konsultasi Gratis
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
            <!-- Fallback bila tidak ada banner -->
            <div class="absolute inset-0 flex items-center py-12 sm:py-16 md:py-0 bg-gradient-to-br from-slate-900 to-slate-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl">
                        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-semibold text-white leading-tight mb-6 sm:mb-8">
                            Pabrik Roster Beton Minimalis Plered Purwakarta.
                        </h1>
                        <p class="text-sm sm:text-base text-slate-400 mb-8 sm:mb-12 max-w-lg leading-relaxed">
                            Produksi langsung dari tangan pertama. Melayani pengiriman ke seluruh Indonesia.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                            <a href="<?php echo e(route('catalog')); ?>" class="inline-flex items-center justify-center border border-terra-500 bg-black/20 hover:bg-terra-500 hover:text-white text-terra-500 px-6 sm:px-8 py-3 sm:py-3.5 rounded-full font-semibold text-sm sm:text-base transition-all text-center backdrop-blur-sm">
                                Lihat Katalog
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bannerCount > 1): ?>
            <!-- Prev Arrow -->
            <button onclick="heroSliderPrev()" class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/30 hover:bg-black/60 border-none text-white cursor-pointer items-center justify-center transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="group-hover:-translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <!-- Next Arrow -->
            <button onclick="heroSliderNext()" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/30 hover:bg-black/60 border-none text-white cursor-pointer items-center justify-center transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <!-- Dots -->
            <div id="heroDots" class="absolute bottom-7 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button onclick="heroSliderGoto(<?php echo e($i); ?>)" data-dot="<?php echo e($i); ?>" class="w-3 h-3 rounded-full border transition-all duration-300 <?php echo e($i === 0 ? 'border-terra-500 bg-terra-500' : 'border-white/50 bg-transparent'); ?>"></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bannerCount > 1): ?>
    <script>
    (function() {
        var current = 0;
        var total = <?php echo e($bannerCount); ?>;
        var duration = <?php echo e($sliderDuration); ?>;
        var timer = null;

        function init() {
            startTimer();
        }

        function goto(n) {
            var slides = document.querySelectorAll('#heroSlider .hero-slide');
            var dots = document.querySelectorAll('#heroDots button');
            slides[current].classList.remove('active');
            slides[current].style.opacity = '0';
            slides[current].style.zIndex = '0';
            slides[current].style.pointerEvents = 'none';
            current = (n + total) % total;
            slides[current].classList.add('active');
            slides[current].style.opacity = '1';
            slides[current].style.zIndex = '1';
            slides[current].style.pointerEvents = '';
            dots.forEach(function(d, i) {
                if (i === current) {
                    d.className = "w-3 h-3 rounded-full border border-terra-500 bg-terra-500 cursor-pointer transition-all duration-300";
                } else {
                    d.className = "w-3 h-3 rounded-full border border-white/50 bg-transparent cursor-pointer transition-all duration-300";
                }
            });
        }

        window.heroSliderNext = function() { resetTimer(); goto(current + 1); };
        window.heroSliderPrev = function() { resetTimer(); goto(current - 1); };
        window.heroSliderGoto = function(n) { resetTimer(); goto(n); };

        function startTimer() {
            timer = setInterval(function() { goto(current + 1); }, duration);
        }
        function resetTimer() {
            clearInterval(timer);
            startTimer();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <!-- Section 2: Social Proof Ticker -->
    <div class="py-6 bg-black text-white overflow-hidden border-y border-white/10">
        <div class="flex whitespace-nowrap animate-marquee">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 4; $i++): ?>
            <div class="flex items-center gap-12 px-6">
                <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em]">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    5000+ Proyek Selesai
                </span>
                <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em]">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    Pabrik Tangan Pertama
                </span>
                <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em]">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    Garansi Pecah Ganti Baru
                </span>
                <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em]">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    Pengiriman Seluruh Indonesia
                </span>
                <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em]">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    Kualitas Beton K-200
                </span>
            </div>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Section: Visual Showcase (Auto-Slider) -->
    <section class="py-16 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <h2 class="text-center font-display text-fluid-h2 font-black text-slate-900">
                Tampilan rumah jadi <span class="text-accent font-display">3x lebih mewah</span><br>
                hanya dengan sentuhan Roster Minimalis.
            </h2>
        </div>

        <div class="relative flex overflow-x-hidden group">
            <div class="animate-marquee flex whitespace-nowrap gap-1">
                <?php
                    $showcaseImages = [
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765259930/47_dmjh8d.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765259848/sg-11134201-7ra3x-mbga48q8qh9x40_resize_w450_nl_f9jbbk.webp',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765259830/36_vaxh6k.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260059/477127145_935487138780264_8156628137020905763_n_koes6o.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765259896/87_pikio2.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765259870/146480918_962561287611643_2630009701372432663_n_gugfhr.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260086/23_max5ag.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260071/19_aaa6uf.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260029/17_ifv8eh.jpg',
                        'https://res.cloudinary.com/indoroster/image/upload/v1765260857/162301330_988931014974670_4453781190506425580_n_iu9gd2.jpg'
                    ];
                ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_merge($showcaseImages, $showcaseImages); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="w-[300px] md:w-[450px] aspect-[4/3] rounded-none overflow-hidden shrink-0 shadow-lg border border-slate-100">
                    <img src="<?php echo e($img); ?>" class="w-full h-full object-cover" loading="lazy">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Section: Strength Test -->
    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <!-- Background accents -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -ml-48 -mt-48"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <h2 class="font-display text-fluid-h2 font-black text-black mb-6 uppercase italic">
                        Seberapa Kuat <br><span class="text-accent font-display text-fluid-h1">Roster Kami?</span>
                    </h2>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        Dibuat dengan beton kualitas <span class="font-bold text-black">K-200</span> dan teknik pengepresan maksimal. Roster kami dirancang untuk tahan banting, tahan cuaca, dan tetap kokoh hingga puluhan tahun.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-black uppercase text-sm">Anti Pecah</h4>
                                <p class="text-xs text-slate-700 mt-1">Beton padat tanpa rongga udara.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-black uppercase text-sm">Kuat Tekan</h4>
                                <p class="text-xs text-slate-700 mt-1">Lulus uji beban berat konstruksi.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 w-full">
                    <div class="relative aspect-video rounded-3xl overflow-hidden shadow-2xl border-8 border-white group">
                        <video 
                            class="w-full h-full object-cover" 
                            autoplay 
                            loop 
                            muted 
                            playsinline
                        >
                            <source src="https://res.cloudinary.com/indoroster/video/upload/v1765639289/1213_h2d5wy.mp4" type="video/mp4">
                        </video>
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section (Catalog Card Style) -->
    <section class="py-24 bg-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -ml-48 -mb-48"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <h2 class="font-display text-fluid-h2 font-black text-black mb-8">
                    Motif <span class="text-accent font-display">Best Seller</span> <br>Bulan Ini
                </h2>
                <div class="flex justify-center">
                    <a href="<?php echo e(route('catalog')); ?>" class="group flex items-center gap-4 text-black font-black text-sm uppercase tracking-widest hover:text-accent transition-all">
                        <span>Lihat Semua Katalog</span>
                        <div class="w-12 h-12 rounded-full border border-black/10 flex items-center justify-center group-hover:bg-accent group-hover:border-accent transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Product Grid (Same style as catalog page) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3 lg:gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('product.detail', $product->slug)); ?>" class="bg-white rounded-md border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400">
                    
                    <!-- Media Section -->
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <?php
                            $displayMedia = $product->primary_media;
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia->media_type === 'video' && !str_contains($displayMedia->media_url, 'youtube.com') && !str_contains($displayMedia->media_url, 'youtu.be')): ?>
                                <video src="<?php echo e($displayMedia->formatted_url); ?>" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    autoplay muted loop playsinline></video>
                            <?php else: ?>
                                <img src="<?php echo e($displayMedia->media_type === 'image' ? $displayMedia->formatted_url : $product->primary_image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Discount Badge -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discount_percentage > 0): ?>
                            <div class="absolute top-0 right-0 bg-[#ffeee8] text-[#ee4d2d] border border-[#ffc9b8] text-[10px] font-bold px-1.5 py-0.5 rounded-bl z-10">
                                <?php echo e($product->discount_percentage); ?>% OFF
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Best Seller Badge -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loop->first): ?>
                            <div class="absolute top-0 left-0 bg-black text-accent text-[9px] font-black px-2 py-1 rounded-br z-10 tracking-wider uppercase">
                                #1 Best
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Video Indicator -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_video): ?>
                            <div class="absolute bottom-1 right-1 bg-black/40 text-white rounded-full p-1 backdrop-blur-sm z-10 shadow-sm">
                                <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Info Section -->
                    <div class="p-2 flex flex-col flex-grow">
                        <div class="text-xs text-slate-800 leading-snug mb-1 line-clamp-2 font-medium group-hover:text-terra-600 transition-colors">
                            <?php echo e($product->name); ?>

                        </div>
                        
                        <div class="mt-auto">
                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                <span class="text-sm font-bold text-[#ee4d2d] leading-none"><?php echo e($product->formatted_price_range); ?></span>
                            </div>

                            <div class="flex items-center justify-between">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_discount): ?>
                                    <span class="text-[9px] text-slate-400 line-through leading-none">Rp<?php echo e(number_format($product->original_price, 0, ',', '.')); ?></span>
                                <?php else: ?>
                                    <span></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-[9px] text-slate-500 whitespace-nowrap">
                                    <?php echo e($product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : ''); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Viral & Bestselling Products Section (Style Baru, 6 Kolom Otomatis) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($viralProducts) && $viralProducts->count() > 0): ?>
    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-terra-500/5 rounded-full blur-[100px] -ml-48 -mt-48"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-terra-500/5 rounded-full blur-[100px] -mr-48 -mb-48"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <span class="bg-terra-500/10 text-terra-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider inline-flex items-center gap-1.5 mb-4">
                    <span class="w-1.5 h-1.5 bg-terra-500 rounded-full animate-ping"></span>
                    Trending Hari Ini
                </span>
                <h2 class="font-display text-fluid-h2 font-black text-slate-900 mb-4">
                    Produk Viral & <span class="text-terra-500 font-display">Paling Banyak Dibeli</span> 🔥
                </h2>
                <p class="text-slate-500 text-sm max-w-xl mx-auto">
                    Koleksi roster beton minimalis terpopuler dengan volume penjualan tertinggi dan ulasan kepuasan terbaik dari pelanggan kami.
                </p>
            </div>

            <!-- Product Grid 6 Columns -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $viralProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('product.detail', $product->slug)); ?>" class="bg-white rounded-xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400">
                    
                    <!-- Media Section -->
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <?php
                            $displayMedia = $product->primary_media;
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia->media_type === 'video' && !str_contains($displayMedia->media_url, 'youtube.com') && !str_contains($displayMedia->media_url, 'youtu.be')): ?>
                                <video src="<?php echo e($displayMedia->formatted_url); ?>" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    autoplay muted loop playsinline></video>
                            <?php else: ?>
                                <img src="<?php echo e($displayMedia->media_type === 'image' ? $displayMedia->formatted_url : $product->primary_image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Badges -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discount_percentage > 0): ?>
                            <div class="absolute top-0 right-0 bg-red-50 text-red-600 border-l border-b border-red-100 text-[10px] font-bold px-2 py-0.5 rounded-bl-lg z-10 shadow-sm">
                                <?php echo e($product->discount_percentage); ?>% OFF
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Viral Badge / Rank -->
                        <div class="absolute top-2 left-2 z-10">
                            <span class="bg-terra-500 text-white text-[9px] font-black px-2 py-0.5 rounded-md tracking-wider uppercase flex items-center gap-0.5 shadow-sm">
                                #<?php echo e($loop->iteration); ?> Hot
                            </span>
                        </div>

                        <!-- Video Indicator -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_video): ?>
                            <div class="absolute bottom-2 right-2 bg-black/40 text-white rounded-full p-1 backdrop-blur-sm z-10 shadow-sm">
                                <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Info Section -->
                    <div class="p-3.5 flex flex-col flex-grow">
                        <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mb-1"><?php echo e($product->category->name ?? 'Roster'); ?></div>
                        <div class="text-xs text-slate-800 leading-snug mb-2 line-clamp-2 font-bold group-hover:text-terra-600 transition-colors">
                            <?php echo e($product->name); ?>

                        </div>
                        
                        <div class="mt-auto">
                            <!-- Ratings & Sales info -->
                            <div class="flex items-center gap-1 mb-2">
                                <div class="flex text-amber-400">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                </div>
                                <span class="text-[10px] font-bold text-slate-700"><?php echo e($product->average_rating); ?></span>
                                <span class="text-[10px] text-slate-300">|</span>
                                <span class="text-[10px] text-slate-500 font-medium"><?php echo e($product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : '0 terjual'); ?></span>
                            </div>

                            <div class="flex items-baseline justify-between gap-1 flex-wrap pt-2 border-t border-slate-100">
                                <span class="text-xs font-black text-terra-600 leading-none"><?php echo e($product->formatted_price_range); ?></span>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Kenapa Memilih Kami -->
    <section class="py-20 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="font-display text-fluid-h2 font-bold mb-6">Kenapa Memilih Roster Beton Minimalis Indoroster?</h2>
                    <p class="text-slate-400 mb-8 text-lg leading-relaxed">
                        Sebagai produsen tangan pertama pabrik roster beton Plered Purwakarta, kami memproduksi loster dengan standar tinggi K-200. Kami telah melayani ribuan proyek mulai dari rumah minimalis hingga komersial di seluruh Indonesia.
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 text-accent rounded-lg flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-2">Kualitas Premium</h3>
                                <p class="text-slate-400 leading-relaxed">Campuran semen dan pasir pilihan, diproses dengan mesin press hidrolik tinggi menghasilkan roster yang sangat kuat dan presisi.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 text-accent rounded-lg flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-2">Langsung dari Pabrik</h3>
                                <p class="text-slate-400 leading-relaxed">Harga tangan pertama yang jauh lebih murah dibandingkan toko material, tanpa mengorbankan kualitas.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent/20 text-accent rounded-lg flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-2">Garansi Pengiriman</h3>
                                <p class="text-slate-400 leading-relaxed">Pecah di jalan? Kami ganti! Tim ekspedisi kami sangat berpengalaman menangani material pecah belah.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative flex flex-col gap-6">
                    <div class="absolute -inset-4 bg-gradient-to-r from-accent/20 to-accent/40 rounded-2xl opacity-20 blur-2xl"></div>
                    <div class="relative rounded-2xl shadow-2xl border border-slate-700 overflow-hidden aspect-video bg-slate-800">
                        <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                            <source src="https://res.cloudinary.com/indoroster/video/upload/v1765640938/1213_5_frvqcr.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="relative rounded-2xl shadow-2xl border border-slate-700 overflow-hidden aspect-video bg-slate-800">
                        <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                            <source src="https://res.cloudinary.com/indoroster/video/upload/v1765642314/432_nej3an.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jangkauan Pengiriman -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-accent/5 rounded-3xl p-8 md:p-16 border border-accent/10 flex flex-col md:flex-row items-center gap-12">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 text-accent font-semibold text-sm mb-6">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                        Pengiriman Seluruh Indonesia
                    </div>
                    <h2 class="font-display text-fluid-h2 font-bold text-slate-900 mb-6">Pusat Jual Roster Beton Murah Jabodetabek & Nasional</h2>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        Sebagai pusat produksi tangan pertama di <strong>Plered, Purwakarta</strong>, armada truk kami siap mengirimkan pesanan partai kecil maupun besar langsung ke lokasi proyek Anda di <strong>Jakarta, Bogor, Depok, Tangerang, Bekasi (Jabodetabek)</strong>, Bandung, Cirebon, hingga pengiriman via ekspedisi khusus ke seluruh wilayah Indonesia dengan garansi aman sampai tujuan.
                    </p>
                    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::getValue('whatsapp_number', '081234567890'))); ?>" target="_blank" class="inline-flex items-center gap-2 bg-accent hover:bg-accent/90 text-black px-6 py-3 rounded-md font-bold transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        Cek Ongkir ke Lokasi Saya
                    </a>
                </div>
                <div class="flex-1 w-full relative">
                    <div class="rounded-2xl shadow-xl w-full overflow-hidden aspect-[4/3] bg-slate-100">
                        <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                            <source src="https://res.cloudinary.com/indoroster/video/upload/v1765263080/1_beaclb.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-lg border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">Garansi Aman</div>
                            <div class="text-sm text-slate-700">Pecah di jalan kami ganti!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: TikTok Review -->
    <section class="py-24 bg-slate-950 overflow-hidden relative">
        <!-- Glow Effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-accent/10 rounded-full blur-[120px]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-accent/20 border border-accent/30 text-accent mb-8">
                        <span class="flex h-2 w-2 rounded-full bg-accent animate-ping"></span>
                        <span class="text-xs font-black uppercase tracking-[0.2em]">Viral on TikTok</span>
                    </div>
                    <h2 class="font-display text-fluid-h1 font-black text-white mb-8">
                        Lihat Langsung <br><span class="text-accent font-display italic text-fluid-h1">Review Kreator</span>
                    </h2>
                    <p class="text-xl text-slate-400 mb-10 leading-relaxed">
                        Dengarkan pengalaman langsung dari para ahli dekorasi dan kreator rumah tentang kualitas roster beton kami. Real testimony, real quality.
                    </p>
                    <div class="flex items-center gap-6">
                        <div class="flex -space-x-4">
                            <img src="https://i.pravatar.cc/100?u=1" class="w-12 h-12 rounded-full border-2 border-slate-900 bg-slate-800 object-cover" alt="">
                            <img src="https://i.pravatar.cc/100?u=2" class="w-12 h-12 rounded-full border-2 border-slate-900 bg-slate-800 object-cover" alt="">
                            <img src="https://i.pravatar.cc/100?u=3" class="w-12 h-12 rounded-full border-2 border-slate-900 bg-slate-800 object-cover" alt="">
                        </div>
                        <div class="text-sm">
                            <div class="text-white font-bold text-lg">100+ Kreator</div>
                            <div class="text-slate-500 font-medium">Telah mereview produk kami</div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 flex justify-center w-full">
                    <!-- TikTok Mobile Mockup -->
                    <div class="relative w-full max-w-[320px] aspect-[9/19] bg-slate-900 rounded-[3rem] border-[8px] border-slate-800 shadow-[0_0_80px_rgba(255,102,0,0.25)] overflow-hidden">
                        <!-- Notch -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-slate-800 rounded-b-2xl z-20"></div>
                        
                        <video 
                            id="reviewVideo"
                            class="w-full h-full object-cover" 
                            loop 
                            playsinline
                            controls
                        >
                            <source src="https://res.cloudinary.com/indoroster/video/upload/v1765259110/review_ttddr5.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        
                        <!-- TikTok Overlay UI (Visual only) -->
                        <div class="absolute bottom-16 left-4 text-white z-10 pointer-events-none group">
                            <div class="font-bold text-sm mb-1">@indoroster_official</div>
                            <div class="text-xs text-white/80 line-clamp-2">Uji kekuatan roster beton K-200 🔥 #roster #minimalis #industrial</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimonials->count() > 0): ?>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-fluid-h2 font-bold text-slate-900 mb-4">Kata Pelanggan Kami</h2>
                <div class="w-24 h-1 bg-accent mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimoni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 shadow-sm">
                    <div class="flex text-terra-500 mb-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=0; $i<$testimoni->rating; $i++): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <p class="text-slate-600 mb-8 italic">"<?php echo e($testimoni->content); ?>"</p>
                    <div class="flex items-center gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimoni->photo_url): ?>
                            <img src="<?php echo e($testimoni->photo_url); ?>" alt="<?php echo e($testimoni->customer_name); ?>" class="w-12 h-12 rounded-full object-cover">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-terra-100 text-terra-600 font-bold text-xl rounded-full flex items-center justify-center"><?php echo e(substr($testimoni->customer_name, 0, 1)); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div>
                            <div class="font-bold text-slate-900"><?php echo e($testimoni->customer_name); ?></div>
                            <div class="text-sm text-slate-700"><?php echo e($testimoni->customer_role); ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Section: The Transformation (Gallery Grid) -->
    <section class="py-24 bg-black text-white relative overflow-hidden">
        <!-- Industrial Pattern Overlay -->
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20">
                <span class="text-accent font-black text-xs uppercase tracking-[0.3em] mb-4 block italic">Transformation Stories</span>
                <h2 class="font-display text-fluid-h2 font-black mb-6">Proyek yang <span class="text-accent font-display italic">Berbicara</span></h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-lg">
                    Inspirasi pemasangan roster dari proyek nyata pelanggan kami di seluruh Indonesia.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                    $transformationImages = [
                        ['url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg', 'title' => 'Minimalist Facade'],
                        ['url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg', 'title' => 'Industrial Interior'],
                        ['url' => 'https://res.cloudinary.com/indoroster/image/upload/q_auto,f_auto,w_600/v1765260049/40_kt08ee.jpg', 'title' => 'Modern Paving']
                    ];
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $transformationImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-900">
                    <img src="<?php echo e($item['url']); ?>" alt="<?php echo e($item['title']); ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="text-xl font-black mb-2"><?php echo e($item['title']); ?></h3>
                        <a href="<?php echo e(route('gallery')); ?>" class="text-accent text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                            Lihat Detail Proyek
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mt-16 text-center">
                <a href="<?php echo e(route('gallery')); ?>" class="inline-block px-12 py-5 border border-white/20 hover:border-accent hover:text-accent font-black text-xs uppercase tracking-[0.2em] transition-all">
                    Jelajahi Semua Inspirasi
                </a>
            </div>
        </div>
    </section>

    <!-- Section: UGC Video Experience -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div>
                    <span class="text-accent font-black text-xs uppercase tracking-[0.3em] mb-4 block">Visual Experience</span>
                    <h2 class="font-display text-fluid-h2 font-black text-black mb-8">
                        Lihat <span class="text-accent font-display italic">Detailnya</span> <br>Lebih Dekat
                    </h2>
                    <p class="text-slate-600 text-lg mb-10 leading-relaxed">
                        Kami percaya bahwa melihat adalah percaya. Koleksi video Indoroster kami menunjukkan bagaimana cahaya dan udara mengalir melalui setiap celah roster kami.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?php echo e(route('video-inspiration')); ?>" class="px-8 py-4 bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-accent hover:text-black transition-all">
                            Indoroster Video Lengkap
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <?php
                        $sampleVideos = [
                            'https://res.cloudinary.com/indoroster/video/upload/v1765259348/15_lhowif.mp4',
                            'https://res.cloudinary.com/indoroster/video/upload/v1765259277/7_upqkhz.mp4'
                        ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sampleVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative aspect-[9/16] rounded-3xl overflow-hidden bg-slate-100 shadow-2xl border-4 border-black/5">
                        <video src="<?php echo e($vid); ?>" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center">
                                    <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Live View</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Final CTA -->
    <section class="py-24 bg-accent relative overflow-hidden">
        <!-- Abstract Shapes -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-black/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/3 translate-y-1/3"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <span class="text-black font-black text-xs uppercase tracking-[0.3em] mb-6 block">Ready to start?</span>
            <h2 class="font-display text-fluid-h1 font-black text-black mb-10">
                Wujudkan Hunian <br>Impian Anda <span class="font-display italic">Sekarang</span>
            </h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::getValue('whatsapp_number', '081234567890'))); ?>" target="_blank" class="group relative px-12 py-6 bg-black text-white font-black text-sm uppercase tracking-[0.2em] rounded-full hover:scale-105 transition-all shadow-2xl">
                    <span class="relative z-10 flex items-center gap-3">
                        <svg class="w-6 h-6 fill-accent" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.224-3.82l.446.265c1.404.835 2.99 1.276 4.6 1.277 5.252 0 9.527-4.275 9.529-9.528.002-2.546-.988-4.941-2.79-6.742s-4.195-2.791-6.741-2.792c-5.253 0-9.527 4.275-9.529 9.528 0 1.685.442 3.325 1.279 4.766l.291.503-1.11 4.053 4.146-1.088zm10.732-6.52c-.3-.15-1.774-.875-2.048-.974-.275-.1-.475-.15-.675.15-.2.3-.775.974-.95 1.174-.175.2-.35.225-.65.075-.3-.15-1.265-.467-2.41-1.485-.89-.794-1.49-1.775-1.665-2.075-.175-.3-.019-.462.13-.611.134-.134.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.491-.51-.675-.519l-.575-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5 0 1.475 1.075 2.9 1.225 3.1.15.2 2.115 3.23 5.125 4.53.716.31 1.274.494 1.708.632.72.23 1.374.197 1.89.12.575-.085 1.774-.725 2.024-1.425.25-.7.25-1.3 0-1.425-.075-.125-.275-.2-.575-.35z"/></svg>
                        Hubungi WhatsApp Sekarang
                    </span>
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-full transition-opacity"></div>
                </a>
                <a href="<?php echo e(route('catalog')); ?>" class="font-black text-xs uppercase tracking-[0.2em] text-black border-b-2 border-black/20 hover:border-black transition-all py-2">
                    Lihat Katalog Dahulu
                </a>
            </div>
            <p class="mt-8 text-black/60 text-[10px] font-bold uppercase tracking-widest">Respons Cepat · Konsultasi Gratis · Kirim Seluruh Indonesia</p>
        </div>
    </section>

    <!-- FAQ -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($faqs->count() > 0): ?>
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-fluid-h2 font-bold text-slate-900 mb-4">FAQ Roster Beton Minimalis</h2>
                <div class="w-24 h-1 bg-terra-500 mx-auto rounded-full"></div>
            </div>
            
            <!-- SEO Schema Markup for FAQ -->
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "FAQPage",
              "mainEntity": [
                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                {
                  "@type": "Question",
                  "name": "<?php echo e(strip_tags($faq->question)); ?>",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "<?php echo e(strip_tags($faq->answer)); ?>"
                  }
                }<?php echo e(!$loop->last ? ',' : ''); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              ]
            }
            </script>
            
            <div class="space-y-4" x-data="{ activeAccordion: null }">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="activeAccordion === <?php echo e($index); ?> ? activeAccordion = null : activeAccordion = <?php echo e($index); ?>" class="w-full flex justify-between items-center p-6 bg-slate-50 hover:bg-slate-100 transition-colors text-left focus:outline-none">
                        <span class="font-bold text-slate-900"><?php echo e($faq->question); ?></span>
                        <svg class="w-5 h-5 text-terra-500 transform transition-transform" :class="{'rotate-180': activeAccordion === <?php echo e($index); ?>}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="activeAccordion === <?php echo e($index); ?>" x-collapse style="display: none;">
                        <div class="p-6 bg-white prose prose-slate max-w-none">
                            <?php echo $faq->answer; ?>

                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



    <script>
    // Force autoplay on all videos (some browsers block autoplay on dynamically loaded content)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('video[autoplay]').forEach(function(video) {
            video.muted = true;
            video.play().catch(function() {});
        });
    });
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/livewire/home.blade.php ENDPATH**/ ?>