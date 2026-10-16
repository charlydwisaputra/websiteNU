import Alpine from 'alpinejs';

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
        // 1. Theme-independent state for hero using IntersectionObserver
        navbar.setAttribute('data-nav-state', 'scrolled');
        
        if (heroSection && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        navbar.setAttribute('data-nav-state', 'over-hero');
                    } else {
                        navbar.setAttribute('data-nav-state', 'scrolled');
                    }
                });
            }, { 
                rootMargin: "-60px 0px 0px 0px", 
                threshold: 0 
            });
            
            observer.observe(heroSection);
            
            if (window.scrollY > (heroSection.offsetHeight - 60)) {
                navbar.setAttribute('data-nav-state', 'scrolled');
            } else {
                navbar.setAttribute('data-nav-state', 'over-hero');
            }
        }

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
    const btn       = document.getElementById('mobile-menu-btn');
    const menu      = document.getElementById('mobile-menu');
    const iconOpen  = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');
    if (!btn || !menu) return;

    let isOpen = false;

    function toggleMenu(force) {
        isOpen = typeof force === 'boolean' ? force : !isOpen;
        btn.setAttribute('aria-expanded', isOpen);

        if (isOpen) {
            menu.classList.remove('hidden');
            menu.classList.add('menu-open');
        } else {
            menu.classList.add('hidden');
            menu.classList.remove('menu-open');
        }

        iconOpen.classList.toggle('hidden', isOpen);
        iconClose.classList.toggle('hidden', !isOpen);
    }
        if (isOpen) {
            const firstLink = menu.querySelector('a');
            if (firstLink) setTimeout(() => firstLink.focus(), 50);
        } else {
            if (document.activeElement && menu.contains(document.activeElement)) {
                btn.focus();
            }
        }


    btn.addEventListener('click', () => toggleMenu());

    // Close menu when a link inside is clicked
    // Close on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && isOpen) {
            toggleMenu(false);
            btn.focus();
        }
    });


    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => toggleMenu(false));
    });

    // Close on outside click
    document.addEventListener('click', e => {
        if (isOpen && !btn.contains(e.target) && !menu.contains(e.target)) {
            toggleMenu(false);
        }
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
