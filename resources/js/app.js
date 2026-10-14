import './bootstrap';

// Global Smart Video Lazy-Player (Hemat RAM & GPU Mobile)
// HANYA menarget video[autoplay] dan video[data-lazy-video] — TIDAK video[loop] umum
let videoObserver = null;

function initSmartVideoLazyPlayer() {
    if (typeof IntersectionObserver === 'undefined') return;

    if (videoObserver) {
        videoObserver.disconnect();
    }

    videoObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const video = entry.target;
            if (entry.isIntersecting && entry.intersectionRatio >= 0.15) {
                // Jika video punya source[data-src], populate src sekarang
                const sources = video.querySelectorAll('source[data-src]');
                let needsLoad = false;
                sources.forEach((s) => {
                    if (!s.getAttribute('src')) {
                        s.src = s.dataset.src;
                        needsLoad = true;
                    }
                });
                if (video.dataset.src && !video.getAttribute('src')) {
                    video.src = video.dataset.src;
                    needsLoad = true;
                }
                if (needsLoad) {
                    video.load();
                }

                // Video masuk viewport: play diam-diam
                if (video.paused) {
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(() => {});
                    }
                }
            } else {
                // Video keluar viewport: pause untuk hemat GPU & RAM
                if (!video.paused) {
                    video.pause();
                }
            }
        });
    }, {
        threshold: [0, 0.15, 0.75],
        rootMargin: '150px 0px 150px 0px'
    });

    // HANYA video dengan autoplay atau data-lazy-video — BUKAN semua video[loop]
    const videos = document.querySelectorAll('video[autoplay], video[data-lazy-video]');
    videos.forEach((video) => {
        video.muted = true;
        video.setAttribute('playsinline', '');
        videoObserver.observe(video);
    });
}

// Scroll Reveal berbasis IntersectionObserver native (tanpa library motion eksternal)
let motionObserver = null;

function initIndoRosterMotion() {
    // Dynamic Header Glassmorphism
    const header = document.querySelector('header.glass-header');
    if (header) {
        const updateHeaderBlur = () => {
            if (window.scrollY > 20) {
                header.classList.add('bg-white/95', 'shadow-md', 'backdrop-blur-md');
                header.classList.remove('bg-white/80');
            } else {
                header.classList.remove('bg-white/95', 'shadow-md');
                header.classList.add('bg-white/80', 'backdrop-blur-md');
            }
        };
        window.addEventListener('scroll', updateHeaderBlur, { passive: true });
        updateHeaderBlur();
    }

    // Scroll-reveal untuk elemen [data-motion] via CSS class
    if (motionObserver) motionObserver.disconnect();

    motionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('motion-revealed');
                motionObserver.unobserve(entry.target);
            }
        });
    }, { rootMargin: '-5% 0px -5% 0px', threshold: 0.1 });

    document.querySelectorAll('[data-motion]').forEach((el) => {
        motionObserver.observe(el);
    });

    // Kinetic Number Counters (native requestAnimationFrame)
    document.querySelectorAll('[data-counter]').forEach((el) => {
        if (el.dataset.counterDone) return;
        const targetValue = parseInt(el.getAttribute('data-counter'), 10);
        const suffix = el.getAttribute('data-suffix') || '';
        const prefix = el.getAttribute('data-prefix') || '';
        if (isNaN(targetValue)) return;

        const counterObs = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                el.dataset.counterDone = '1';
                counterObs.disconnect();
                const duration = 1500;
                const start = performance.now();
                const tick = (now) => {
                    const elapsed = now - start;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = `${prefix}${Math.round(eased * targetValue).toLocaleString('id-ID')}${suffix}`;
                    if (progress < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            });
        }, { rootMargin: '-5% 0px -5% 0px' });
        counterObs.observe(el);
    });

    // 3D Tilt & Magnetic — Desktop only
    const isDesktopPointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (isDesktopPointer) {
        document.querySelectorAll('[data-tilt]').forEach((card) => {
            card.style.transformStyle = 'preserve-3d';
            card.style.transition = 'transform 0.15s ease-out';
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const rotateX = ((e.clientY - rect.top - rect.height / 2) / (rect.height / 2)) * -8;
                const rotateY = ((e.clientX - rect.left - rect.width / 2) / (rect.width / 2)) * 8;
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            });
        });

        document.querySelectorAll('[data-magnetic]').forEach((btn) => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                btn.style.transform = `translate(${x * 0.22}px, ${y * 0.22}px)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translate(0px, 0px)';
                btn.style.transition = 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
            });
            btn.addEventListener('mouseenter', () => {
                btn.style.transition = 'transform 0.1s ease-out';
            });
        });
    }

    // Inisialisasi Smart Video Lazy-Player
    initSmartVideoLazyPlayer();
}

// Jalankan saat halaman load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initIndoRosterMotion);
} else {
    initIndoRosterMotion();
}

// Re-inisialisasi saat Livewire navigasi atau update
document.addEventListener('livewire:navigated', () => {
    initIndoRosterMotion();
});
document.addEventListener('livewire:initialized', () => {
    initIndoRosterMotion();
});
