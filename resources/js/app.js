import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/* ============================================================
   PUBLIC PAGE INTERACTIONS
   ============================================================ */
function initializePublicInteractions() {

    /* ── 1. Mobile hamburger menu ── */
    initMobileMenu();

    /* ── 1b. Navbar scroll state ── */
    const navbar = document.getElementById('glass-navbar');
    if (navbar) {
        const onScroll = () => {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // set initial state
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
    const reveals = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) {
        reveals.forEach(el => el.classList.add('active'));
    } else {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('active');
            });
        }, { threshold: 0.05 });
        reveals.forEach(el => observer.observe(el));
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

    btn.addEventListener('click', () => toggleMenu());

    // Close menu when a link inside is clicked
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

document.addEventListener('DOMContentLoaded', initializePublicInteractions);