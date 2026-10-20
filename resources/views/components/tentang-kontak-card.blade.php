@php
    $fb = config('organisasi.sosial.facebook');
    $showFb = !empty($fb) && !str_ends_with($fb, '#') && $fb !== '#';
    $ig = config('organisasi.sosial.instagram');
    $showIg = !empty($ig) && !str_ends_with($ig, '#') && $ig !== '#';
    $yt = config('organisasi.sosial.youtube');
    $showYt = !empty($yt) && !str_ends_with($yt, '#') && $yt !== '#';
    $hasSosial = $showFb || $showIg || $showYt;

    $lat = config('organisasi.peta.lat', config('organisasi.lokasi_lat', -7.4218));
    $lng = config('organisasi.peta.lng', config('organisasi.lokasi_lng', 109.1608));
    $hasCoordinates = !empty($lat) && !empty($lng) && is_numeric($lat) && is_numeric($lng);

    $mapLink = $hasCoordinates 
        ? "https://www.google.com/maps?q={$lat},{$lng}"
        : "https://maps.google.com/?q=" . urlencode(config('organisasi.alamat'));
@endphp

<div class="p-5 sm:p-6 rounded-2xl bg-white dark:bg-[#070D09] border border-black/5 dark:border-white/10 shadow-sm space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/5">
        <div>
            <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Sekretariat</span>
            <h3 class="font-serif text-2xl font-medium mt-0.5" style="color:var(--text-primary)">Kontak & Lokasi</h3>
        </div>
    </div>

    <!-- Alamat -->
    <div>
        <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Alamat</p>
        <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300 font-light">{{ config('organisasi.alamat') }}</p>
    </div>

    <!-- Email -->
    <div>
        <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Email</p>
        <a href="mailto:{{ config('organisasi.email') }}" class="inline-flex items-center min-h-[44px] text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
            {{ config('organisasi.email') }}
        </a>
    </div>

    @if($hasCoordinates)
    <!-- Peta Tertanam (OpenStreetMap dengan Leaflet) berdasarkan koordinat resmi -->
    <div>
        <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Peta Lokasi</p>
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            .dark .leaflet-tile-pane {
                filter: brightness(0.75) contrast(1.05) saturate(0.85);
            }
            .leaflet-control-zoom {
                border: 0 !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
                border-radius: 8px !important;
                overflow: hidden !important;
            }
        </style>

        @once
        <script>
            (function() {
                function registerMapEmbed() {
                    if (window.Alpine && !window.Alpine._mapEmbedRegistered) {
                        window.Alpine._mapEmbedRegistered = true;
                        window.Alpine.data('mapEmbed', (config = {}) => ({
                            active: false,
                            map: null,
                            observer: null,
                            lat: config.lat || -7.4218,
                            lng: config.lng || 109.1608,
                            zoom: config.zoom || 14,
                            pinUrl: config.pinUrl || '/images/pin-hijau.svg',

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
                    }
                }

                if (window.Alpine) {
                    registerMapEmbed();
                } else {
                    document.addEventListener('alpine:init', registerMapEmbed);
                }
            })();
        </script>
        @endonce

        <!-- Container Peta Interaktif dengan Pelindung Scroll & Auto-Lock -->
        <div x-data="mapEmbed({
                lat: {{ (float)$lat }},
                lng: {{ (float)$lng }},
                zoom: 14,
                pinUrl: @js(asset('images/pin-hijau.svg'))
             })"
             @click.outside="toggleActive(false)"
             class="relative rounded-xl overflow-hidden border border-black/5 dark:border-white/10 shadow-xs bg-slate-100 dark:bg-slate-900 group">
            
            <!-- Canvas Peta OSM Leaflet (Tinggi 200-240px) -->
            <div x-ref="mapContainer" class="w-full h-[200px] sm:h-[220px] md:h-[240px] z-0"></div>

            <!-- Overlay Pelindung Scroll: Mencegah scroll trap di layar sentuh HP -->
            <div x-show="!active" 
                 @click="toggleActive(true)"
                 class="absolute inset-0 bg-black/15 dark:bg-black/35 backdrop-blur-[1px] flex items-center justify-center cursor-pointer transition-opacity z-10 p-3"
                 role="button"
                 tabindex="0"
                 @keydown.enter="toggleActive(true)"
                 @keydown.space.prevent="toggleActive(true)"
                 aria-label="Ketuk untuk mengaktifkan interaksi peta">
                <div class="px-3.5 py-2 rounded-xl bg-white/95 dark:bg-[#070D09]/95 text-slate-800 dark:text-slate-100 text-xs font-medium shadow-md border border-black/10 dark:border-white/10 flex items-center gap-2 select-none group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    <span>Ketuk untuk membuka interaksi peta</span>
                </div>
            </div>

            <!-- Tombol Kunci Kembali (muncul saat peta aktif) -->
            <button x-show="active" 
                    x-cloak
                    @click.stop="toggleActive(false)"
                    type="button"
                    class="absolute top-2 right-2 z-20 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-[#070D09]/95 text-[11px] font-medium text-slate-700 dark:text-slate-200 border border-black/10 dark:border-white/10 shadow-sm hover:bg-white dark:hover:bg-black transition-colors"
                    aria-label="Kunci peta">
                Kunci peta
            </button>
        </div>

        <!-- Atribusi OSM ringkas satu baris kecil di sudut -->
        <div class="mt-1 text-[10px] text-slate-400 dark:text-slate-500 text-right">
            <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer" class="hover:underline">© OpenStreetMap kontributor</a>
        </div>
    </div>
    @endif

    <!-- Media Sosial (Hanya yang memiliki tautan valid, sembunyikan jika '#' atau kosong) -->
    @if($hasSosial)
    <div>
        <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Media Sosial</p>
        <div class="flex flex-wrap gap-2">
            @if($showFb)
            <a href="{{ $fb }}" target="_blank" rel="noopener noreferrer" 
               class="inline-flex items-center gap-2 px-3 py-2 min-h-[44px] rounded-xl text-xs font-medium bg-black/5 dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 border border-black/5 dark:border-white/5 hover:border-emerald-500/30 transition-all duration-200 active:scale-95 group"
               aria-label="Kunjungi Facebook NU Kalisari">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 dark:text-slate-400 dark:group-hover:text-emerald-400 transition-colors shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <span>Facebook</span>
            </a>
            @endif

            @if($showIg)
            <a href="{{ $ig }}" target="_blank" rel="noopener noreferrer" 
               class="inline-flex items-center gap-2 px-3 py-2 min-h-[44px] rounded-xl text-xs font-medium bg-black/5 dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 border border-black/5 dark:border-white/5 hover:border-emerald-500/30 transition-all duration-200 active:scale-95 group"
               aria-label="Kunjungi Instagram NU Kalisari">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 dark:text-slate-400 dark:group-hover:text-emerald-400 transition-colors shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                <span>Instagram</span>
            </a>
            @endif

            @if($showYt)
            <a href="{{ $yt }}" target="_blank" rel="noopener noreferrer" 
               class="inline-flex items-center gap-2 px-3 py-2 min-h-[44px] rounded-xl text-xs font-medium bg-black/5 dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 border border-black/5 dark:border-white/5 hover:border-emerald-500/30 transition-all duration-200 active:scale-95 group"
               aria-label="Kunjungi YouTube NU Kalisari">
                <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 dark:text-slate-400 dark:group-hover:text-emerald-400 transition-colors shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                <span>YouTube</span>
            </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Tombol Aksi Utama: Buka di Peta -->
    <div class="pt-3 border-t border-black/5 dark:border-white/5">
        <a href="{{ $mapLink }}" target="_blank" rel="noopener noreferrer" 
           class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 min-h-[44px] bg-emerald-600 hover:bg-emerald-700 active:scale-95 rounded-xl text-sm font-semibold transition-all text-white shadow-sm shadow-emerald-950/10 group">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Buka di Peta</span>
            <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
        </a>
    </div>
</div>
