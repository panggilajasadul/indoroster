import './bootstrap';
import { animate, inView, stagger } from 'motion';

// Global Smart Video Lazy-Player (Saves RAM & GPU on Mobile)
let videoObserver = null;

function initSmartVideoLazyPlayer() {
    if (typeof IntersectionObserver === 'undefined') return;

    if (videoObserver) {
        videoObserver.disconnect();
    }

    videoObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const video = entry.target;
            if (entry.isIntersecting && entry.intersectionRatio >= 0.25) {
                // Video in viewport: load and play quietly
                if (video.paused) {
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(() => {});
                    }
                }
            } else {
                // Video scrolled out of view: pause to free up GPU decoder & RAM
                if (!video.paused) {
                    video.pause();
                }
            }
        });
    }, {
        threshold: [0, 0.25, 0.75],
        rootMargin: '50px 0px 50px 0px'
    });

    const videos = document.querySelectorAll('video[autoplay], video[data-lazy-video], video[loop]');
    videos.forEach((video) => {
        // Ensure muted and playsinline for smooth hardware playback
        video.muted = true;
        video.setAttribute('playsinline', '');
        videoObserver.observe(video);
    });
}

// Initialize Motion System for IndoRoster (Optimized for Mobile)
function initIndoRosterMotion() {
    const isDesktopPointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const isMobile = window.innerWidth < 768;

    // Dynamic Header Glassmorphism blur intensifier
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

    // 1. Scroll-Triggered Staggered Elements (Cards, Grid items)
    document.querySelectorAll('[data-motion="stagger"]').forEach((container) => {
        const items = container.querySelectorAll('[data-motion-item]');
        if (items.length > 0) {
            inView(container, () => {
                animate(
                    items,
                    { opacity: [0, 1], y: [20, 0], scale: [0.98, 1] },
                    { delay: stagger(isMobile ? 0.04 : 0.08), duration: isMobile ? 0.45 : 0.65, easing: [0.16, 1, 0.3, 1] }
                );
            }, { margin: '-5% 0px -5% 0px' });
        }
    });

    // 2. Individual Fade Up & Reveal
    document.querySelectorAll('[data-motion="fade-up"]').forEach((el) => {
        inView(el, () => {
            animate(
                el,
                { opacity: [0, 1], y: [18, 0] },
                { duration: isMobile ? 0.5 : 0.7, easing: [0.16, 1, 0.3, 1] }
            );
        }, { margin: '-5% 0px -5% 0px' });
    });

    // 3. Scale In Elements
    document.querySelectorAll('[data-motion="scale"]').forEach((el) => {
        inView(el, () => {
            animate(
                el,
                { opacity: [0, 1], scale: [0.94, 1] },
                { duration: isMobile ? 0.45 : 0.65, easing: [0.16, 1, 0.3, 1] }
            );
        }, { margin: '-5% 0px -5% 0px' });
    });

    // 4. Kinetic Number Counters
    document.querySelectorAll('[data-counter]').forEach((el) => {
        const targetValue = parseInt(el.getAttribute('data-counter'), 10);
        const suffix = el.getAttribute('data-suffix') || '';
        const prefix = el.getAttribute('data-prefix') || '';
        
        if (!isNaN(targetValue)) {
            inView(el, () => {
                animate(0, targetValue, {
                    duration: 1.5,
                    easing: [0.16, 1, 0.3, 1],
                    onUpdate: (latest) => {
                        el.textContent = `${prefix}${Math.round(latest).toLocaleString('id-ID')}${suffix}`;
                    }
                });
            }, { margin: '-5% 0px -5% 0px' });
        }
    });

    // 5. 3D Tilt Effect on Hover (DESKTOP ONLY - Ignored on Touchscreens)
    if (isDesktopPointer) {
        document.querySelectorAll('[data-tilt]').forEach((card) => {
            card.style.transformStyle = 'preserve-3d';
            card.style.transition = 'transform 0.15s ease-out';

            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -8;
                const rotateY = ((x - centerX) / centerX) * 8;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            });
        });

        // 6. Magnetic Buttons (Tactile Floating Effect - DESKTOP ONLY)
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

    // Initialize Smart Video Lazy-Player
    initSmartVideoLazyPlayer();
}

// Run on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initIndoRosterMotion);
} else {
    initIndoRosterMotion();
}

// Re-initialize when Livewire navigates or updates
document.addEventListener('livewire:navigated', () => {
    initIndoRosterMotion();
});
document.addEventListener('livewire:initialized', () => {
    initIndoRosterMotion();
});
