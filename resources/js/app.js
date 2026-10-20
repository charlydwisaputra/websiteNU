import Alpine from 'alpinejs';

Alpine.data('mapEmbed', (config = {}) => ({
    active: false,
    map: null,
    observer: null,
    lat: config.lat ?? -7.4218,
    lng: config.lng ?? 109.1608,
    zoom: config.zoom ?? 14,
    pinUrl: config.pinUrl ?? '/images/pin-hijau.svg',

    init() {
        this.loadLeaflet();
        this.setupObserver();
    },

    loadLeaflet() {
        if (typeof L === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
            script.crossOrigin = '';
            script.onload = () => this.setupMap();
            document.head.appendChild(script);
        } else {
            this.setupMap();
        }
    },

    setupMap() {
        if (this.map) return;
        const container = this.$refs.mapContainer;
        if (!container) return;

        this.map = L.map(container, {
            attributionControl: false,
            zoomControl: true,
            scrollWheelZoom: false,
            dragging: false,
            touchZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
        }).setView([this.lat, this.lng], this.zoom);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(this.map);

        const pinIcon = L.icon({
            iconUrl: this.pinUrl,
            iconSize: [32, 40],
            iconAnchor: [16, 40],
            popupAnchor: [0, -40],
        });

        L.marker([this.lat, this.lng], { icon: pinIcon }).addTo(this.map);
    },

    setupObserver() {
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    this.toggleActive(false);
                }
            });
        }, { threshold: 0.1 });
        this.observer.observe(this.$el);
    },

    toggleActive(state) {
        this.active = state;
        if (!this.map) return;
        if (state) {
            this.map.dragging.enable();
            this.map.touchZoom.enable();
            this.map.scrollWheelZoom.enable();
        } else {
            this.map.dragging.disable();
            this.map.touchZoom.disable();
            this.map.scrollWheelZoom.disable();
        }
    },

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
        if (this.map) {
            this.map.remove();
            this.map = null;
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();

/* ============================================================
   PUBLIC PAGE INTERACTIONS
   ============================================================ */
function initializePublicInteractions() {

    /* ── 0. Theme toggle ── */
    initThemeToggle();

    /* ── 1. Mobile hamburger menu ── */
    initMobileMenu();

    /* ── 1b. Navbar scroll state (Headroom behavior) ── */
    const navbar = document.getElementById('glass-navbar');
    const heroSection = document.getElementById('hero-section');
    
    if (navbar) {
        if (heroSection) {
            const updateHeroState = () => {
                const rect = heroSection.getBoundingClientRect();
                // When hero bottom is at or above navbar height (~80px from top)
                if (rect.bottom <= 80) {
                    if (navbar.getAttribute('data-nav-state') !== 'scrolled') {
                        navbar.setAttribute('data-nav-state', 'scrolled');
                    }
                } else {
                    if (navbar.getAttribute('data-nav-state') !== 'over-hero') {
                        navbar.setAttribute('data-nav-state', 'over-hero');
                    }
                }
            };

            updateHeroState();
            window.addEventListener('scroll', updateHeroState, { passive: true });
        } else {
            navbar.setAttribute('data-nav-state', 'scrolled');
        }

        // 1c. Mobile navbar solid background threshold (> 24px)
        const updateScrollPast = () => {
            const isScrolledPast = window.scrollY > 24;
            if (navbar.getAttribute('data-scrolled-past') !== (isScrolledPast ? 'true' : 'false')) {
                navbar.setAttribute('data-scrolled-past', isScrolledPast ? 'true' : 'false');
            }
        };
        updateScrollPast();
        window.addEventListener('scroll', updateScrollPast, { passive: true });

        // 2. Headroom mobile behavior
        let lastScrollY = window.scrollY;
        let isScrolling = false;

        const updateNavbar = () => {
            isScrolling = false;
            const currentScrollY = window.scrollY;

            // Desktop: always visible
            if (window.innerWidth >= 1024) {
                navbar.classList.remove('navbar--hidden');
                lastScrollY = currentScrollY;
                return;
            }

            // Don't hide when mobile menu is open
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const isMenuOpen = (mobileMenu && !mobileMenu.classList.contains('hidden')) ||
                               (mobileBtn && mobileBtn.getAttribute('aria-expanded') === 'true');

            if (isMenuOpen) {
                navbar.classList.remove('navbar--hidden');
                lastScrollY = currentScrollY;
                return;
            }

            // Don't hide when near the top of the page
            if (currentScrollY <= 80) {
                navbar.classList.remove('navbar--hidden');
                lastScrollY = currentScrollY;
                return;
            }

            // Threshold check (~8px) to avoid flickering on minor scroll jitter
            const diff = currentScrollY - lastScrollY;
            if (Math.abs(diff) < 8) {
                return;
            }

            if (diff > 0) {
                // Scrolling down -> hide navbar
                navbar.classList.add('navbar--hidden');
            } else {
                // Scrolling up -> show navbar
                navbar.classList.remove('navbar--hidden');
            }

            lastScrollY = currentScrollY;
        };

        const onScroll = () => {
            if (!isScrolling) {
                window.requestAnimationFrame(updateNavbar);
                isScrolling = true;
            }
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                navbar.classList.remove('navbar--hidden');
            }
        }, { passive: true });
        updateNavbar();
    }

    /* ── 2. Smooth scroll for anchor links ── */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', event => {
            const targetId = anchor.getAttribute('href');
            if (!targetId || targetId === '#') return;
            const targetEl = document.querySelector(targetId);
            if (!targetEl) return;
            event.preventDefault();
            // Offset scroll sebesar margin top (top-5) + tinggi navbar (72px) + sedikit ruang (24px)
            window.scrollTo({
                top: targetEl.getBoundingClientRect().top + window.pageYOffset - 116,
                behavior: 'smooth',
            });
        });
    });

    /* ── 3. Reveal on scroll ── */
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealSelector = '.reveal, .reveal-up, .reveal-left, .reveal-fade';

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        document.querySelectorAll(revealSelector).forEach(el => el.classList.add('active'));
    } else {
        const revealObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.02, rootMargin: '0px 0px 50px 0px' });

        const observeElement = (el) => {
            if (el.classList.contains('active')) return;
            const rect = el.getBoundingClientRect();
            if (rect.top <= (window.innerHeight || document.documentElement.clientHeight)) {
                el.classList.add('active');
            } else {
                revealObserver.observe(el);
            }
        };

        document.querySelectorAll(revealSelector).forEach(observeElement);

        // Re-observe dynamic content (e.g. Alpine search/filter re-renders)
        const mutationObserver = new MutationObserver(mutations => {
            for (const mutation of mutations) {
                for (const node of mutation.addedNodes) {
                    if (node.nodeType === 1) {
                        if (node.matches && node.matches(revealSelector)) {
                            observeElement(node);
                        }
                        if (node.querySelectorAll) {
                            node.querySelectorAll(revealSelector).forEach(observeElement);
                        }
                    }
                }
            }
        });
        mutationObserver.observe(document.body, { childList: true, subtree: true });

        // Safeguard on scroll
        window.addEventListener('scroll', () => {
            document.querySelectorAll(revealSelector).forEach(el => {
                if (!el.classList.contains('active')) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= (window.innerHeight || document.documentElement.clientHeight)) {
                        el.classList.add('active');
                        revealObserver.unobserve(el);
                    }
                }
            });
        }, { passive: true });
    }

    /* ── 4. Lightbox ── */
    document.querySelectorAll('[data-lightbox]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modal = document.createElement('div');
            modal.className =
                'fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/90 p-4';
            modal.innerHTML = `
                <button type="button" aria-label="Tutup"
                        class="absolute right-5 top-5 text-3xl text-white leading-none">&times;</button>
                <img src="${trigger.dataset.lightbox}"
                     alt="${trigger.dataset.title || ''}"
                     class="max-h-[90vh] max-w-full rounded-2xl object-contain shadow-2xl">`;
            document.body.appendChild(modal);
            document.body.classList.add('lightbox-open');
            modal.addEventListener('click', e => {
                if (e.target === modal || e.target.tagName === 'BUTTON') {
                    modal.remove();
                    document.body.classList.remove('lightbox-open');
                }
            });
        });
    });
}

/* ============================================================
   MOBILE MENU — hamburger toggle
   ============================================================ */
function initMobileMenu() {
    const btn      = document.getElementById('mobile-menu-btn');
    const menu     = document.getElementById('mobile-menu');
    const backdrop = document.getElementById('mobile-menu-backdrop');
    const navbar   = document.getElementById('glass-navbar');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');
    if (!btn || !menu) return;

    let isOpen = false;

    /** Single source of truth: class 'menu-open' on <html> only. */
    function setScrollLock(lock) {
        document.documentElement.classList.toggle('menu-open', lock);
    }

    /** Position backdrop so it starts below the navbar, never dimming the header. */
    function positionBackdrop() {
        if (!backdrop || !navbar) return;
        const navBottom = navbar.getBoundingClientRect().bottom;
        backdrop.style.top = navBottom + 'px';
    }

    function openMenu() {
        if (isOpen) return;
        isOpen = true;
        menu.classList.remove('hidden');
        menu.classList.add('menu-open');
        if (backdrop) {
            positionBackdrop();
            backdrop.classList.add('active');
        }
        setScrollLock(true);
        btn.setAttribute('aria-expanded', 'true');
        btn.setAttribute('aria-label', 'Tutup menu');
        if (iconOpen) iconOpen.classList.add('hidden');
        if (iconClose) iconClose.classList.remove('hidden');
        // Focus first link after animation settles
        const firstLink = menu.querySelector('a');
        if (firstLink) setTimeout(() => firstLink.focus(), 50);
    }

    function closeMenu(returnFocusToBtn = false) {
        if (!isOpen) return;
        isOpen = false;
        menu.classList.add('hidden');
        menu.classList.remove('menu-open');
        if (backdrop) backdrop.classList.remove('active');
        setScrollLock(false);
        btn.setAttribute('aria-expanded', 'false');
        btn.setAttribute('aria-label', 'Buka menu');
        if (iconOpen) iconOpen.classList.remove('hidden');
        if (iconClose) iconClose.classList.add('hidden');
        if (returnFocusToBtn) btn.focus();
    }

    // Guarantee clean state on first render
    closeMenu();

    // Hamburger / X button in navbar
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen ? closeMenu(true) : openMenu();
    });

    // Backdrop tap closes menu
    if (backdrop) {
        backdrop.addEventListener('click', () => closeMenu(false));
    }

    // Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && isOpen) closeMenu(true);
    });

    // Close when any nav link or CTA inside panel is tapped
    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => closeMenu(false));
    });

    // Close and release lock when resized to desktop (≥ 1024 px)
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024 && isOpen) closeMenu(false);
        else if (isOpen) positionBackdrop(); // re-position if navbar height changes
    }, { passive: true });

    // Release lock on bfcache restore (browser back/forward)
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) closeMenu(false);
    });
}

/* ============================================================
   THEME TOGGLE — Dark/Light Mode
   ============================================================ */
function initThemeToggle() {
    function doToggle() {
        // Add a smooth transition class to body when toggling theme
        document.body.style.transition = 'background-color 0.2s cubic-bezier(0.22, 1, 0.36, 1), color 0.2s ease';
        
        if (typeof window.toggleTheme === 'function') {
            window.toggleTheme();
        } else {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: isDark ? 'dark' : 'light' } }));
        }
        
        // Remove the inline transition after it's done so it doesn't affect other changes
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    }

    window.toggleTheme = window.toggleTheme || doToggle;

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('#theme-toggle, #theme-toggle-mobile, [data-theme-toggle], .theme-toggle-btn');
        if (!btn) return;
        if (btn.hasAttribute('onclick')) return;
        e.preventDefault();
        doToggle();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePublicInteractions);
} else {
    initializePublicInteractions();
}
