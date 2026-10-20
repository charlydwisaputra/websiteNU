<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Cetak Massal Kartu Anggota — NU Kalisari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.theme-script')

    <style>
        @media screen {
            .print-cards-grid {
                display: none !important;
            }
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .print-cards-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 85.6mm) !important;
                column-gap: 8mm !important;
                row-gap: 5mm !important;
                justify-content: center !important;
                margin: 0 auto !important;
                padding: 0 !important;
            }

            .print-card-item {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin: 0 !important;
                padding: 0 !important;
                border: 0.5px dashed #94a3b8 !important; /* Crop Marks */
                border-radius: 12px !important;
                overflow: hidden !important;
            }

            .print-card-item.\!hidden {
                display: none !important;
            }

            .member-card {
                width: 85.6mm !important;
                height: 54mm !important;
                max-width: 85.6mm !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased min-h-[100dvh] flex flex-col pb-28 md:pb-12"
      style="background:var(--bg-base);color:var(--text-primary)"
      x-data="{
          members: [
              @foreach($anggotas as $a)
              @php
                  $rawName = trim($a->name ?? '');
                  $words = array_values(array_filter(preg_split('/[\s\-_]+/', $rawName)));
                  if (count($words) >= 2) {
                      $inits = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
                  } elseif (count($words) === 1 && mb_strlen($words[0]) >= 2) {
                      $inits = strtoupper(mb_substr($words[0], 0, 2));
                  } elseif (count($words) === 1 && mb_strlen($words[0]) === 1) {
                      $inits = strtoupper($words[0] . 'N');
                  } else {
                      $inits = 'NU';
                  }
                  $banomName = $a->banom?->singkatan ?: ($a->banom?->nama ?: ($a->role ?: ''));
              @endphp
              {
                  id: {{ $a->id }},
                  name: {{ Js::from($a->name) }},
                  position: {{ Js::from($a->position ?: 'Anggota') }},
                  banom_id: {{ Js::from($a->banom_id ? (string)$a->banom_id : '') }},
                  banom: {{ Js::from($banomName) }},
                  status: {{ Js::from($a->status ?: 'Aktif') }},
                  id_pad: 'ID-{{ str_pad($a->id, 5, '0', STR_PAD_LEFT) }}',
                  photo_url: {{ Js::from($a->photo ? asset('storage/' . $a->photo) : null) }},
                  initials: {{ Js::from($inits) }},
                  wilayah: {{ Js::from($a->wilayah) }},
                  joined_at: {{ Js::from($a->joined_at ? $a->joined_at->translatedFormat('d F Y') : null) }},
                  single_print_url: {{ Js::from(route('admin.anggota.print-single', $a->id)) }}
              },
              @endforeach
          ],
          banomList: [
              @foreach($banoms as $b)
              { id: '{{ $b->id }}', singkatan: {{ Js::from($b->singkatan) }}, nama: {{ Js::from($b->nama) }} },
              @endforeach
          ],
          searchQuery: {{ Js::from(request('search', request('q', ''))) }},
          filterBanom: {{ Js::from(request('banom_id', '')) }},
          filterStatus: {{ Js::from(request('status', '')) }},
          selected: {{ json_encode($initialSelected ?? []) }},
          mobileFilterOpen: false,
          previewMember: null,
          showTips: false,

          get activeFilterCount() {
              let count = 0;
              if (this.filterBanom) count++;
              if (this.filterStatus) count++;
              return count;
          },

          get filteredMembers() {
              const q = (this.searchQuery || '').trim().toLowerCase();
              const banom = this.filterBanom;
              const status = (this.filterStatus || '').toLowerCase();

              return this.members.filter(m => {
                  if (banom && String(m.banom_id) !== String(banom)) {
                      return false;
                  }
                  if (status && (m.status || '').toLowerCase() !== status) {
                      return false;
                  }
                  if (q) {
                      const matchName = (m.name || '').toLowerCase().includes(q);
                      const matchPos = (m.position || '').toLowerCase().includes(q);
                      const matchBanom = (m.banom || '').toLowerCase().includes(q);
                      const matchId = (m.id_pad || '').toLowerCase().includes(q) || String(m.id).includes(q);
                      if (!matchName && !matchPos && !matchBanom && !matchId) {
                          return false;
                      }
                  }
                  return true;
              });
          },

          isSelected(id) {
              return this.selected.includes(id);
          },

          toggleSelect(id) {
              if (this.isSelected(id)) {
                  this.selected = this.selected.filter(i => i !== id);
              } else {
                  this.selected.push(id);
              }
          },

          isAllVisibleSelected() {
              const visibleIds = this.filteredMembers.map(m => m.id);
              return visibleIds.length > 0 && visibleIds.every(id => this.isSelected(id));
          },

          toggleSelectVisible() {
              const visibleIds = this.filteredMembers.map(m => m.id);
              if (visibleIds.length === 0) return;
              
              if (this.isAllVisibleSelected()) {
                  this.selected = this.selected.filter(id => !visibleIds.includes(id));
              } else {
                  const set = new Set([...this.selected, ...visibleIds]);
                  this.selected = Array.from(set);
              }
          },

          clearAllSelection() {
              this.selected = [];
          },

          getBanomName(id) {
              const b = this.banomList.find(item => String(item.id) === String(id));
              return b ? b.singkatan : id;
          },

          openPreview(m) {
              this.previewMember = m;
          },

          closePreview() {
              this.previewMember = null;
          },

          printSelected() {
              if (this.selected.length === 0) return;
              window.print();
          }
      }">

    {{-- Control Panel Header Desktop & Mobile (No Print) --}}
    <header class="no-print sticky top-0 z-40 border-b p-3 sm:p-4 glass-panel shadow-sm"
            style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light)">
        <div class="max-w-5xl mx-auto flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                @php
                    $backParams = array_filter([
                        'search' => request('search', request('q')),
                        'banom_id' => request('banom_id'),
                        'status' => request('status'),
                    ]);
                @endphp
                <a href="{{ route('admin.anggota.index', $backParams) }}"
                   :href="'{{ route('admin.anggota.index') }}' + (function() {
                       const p = new URLSearchParams();
                       if (searchQuery) p.set('search', searchQuery);
                       if (filterBanom) p.set('banom_id', filterBanom);
                       if (filterStatus) p.set('status', filterStatus);
                       const qs = p.toString();
                       return qs ? '?' + qs : '';
                   })()"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-xs font-semibold border transition-all shrink-0 min-h-[44px] min-w-[44px]"
                   style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
                   title="Kembali ke Data Anggota">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="min-w-0">
                    <h1 class="font-serif font-bold text-base sm:text-xl leading-tight truncate" style="color:var(--text-primary)">
                        Cetak Massal Kartu
                    </h1>
                    <p class="text-[11px] truncate" style="color:var(--text-muted)">
                        <span class="font-semibold" style="color:var(--accent)" x-text="selected.length + ' dipilih'">0 dipilih</span>
                        &middot; <span x-text="'Total ' + members.length + ' anggota'"></span>
                    </p>
                </div>
            </div>

            {{-- Desktop Action Button: "Cetak Terpilih (N)" --}}
            <div class="hidden md:flex items-center gap-2">
                <button type="button"
                        @click="printSelected()"
                        :disabled="selected.length === 0"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-sm transition-all min-h-[44px]"
                        :class="selected.length === 0 ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:opacity-95'"
                        style="background:var(--accent)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Terpilih (<span x-text="selected.length">0</span>)</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Container (No Print) --}}
    <main class="no-print flex-1 max-w-5xl w-full mx-auto p-3 sm:p-6 space-y-3">

        {{-- Collapsible Tips Cetak --}}
        <div class="rounded-xl border p-2.5 sm:p-3 text-xs"
             style="background:var(--accent-subtle);border-color:rgba(0,138,90,0.18)">
            <div class="flex items-center justify-between gap-2 cursor-pointer select-none"
                 @click="showTips = !showTips">
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-bold text-[11.5px] truncate" style="color:var(--accent)">Tips Cetak Kartu A4 (2&times;5 per Lembar)</span>
                </div>
                <div class="flex items-center gap-1 text-[11px] font-semibold" style="color:var(--text-muted)">
                    <span x-text="showTips ? 'Tutup' : 'Lihat'">Lihat</span>
                    <svg class="w-3.5 h-3.5 transition-transform" :class="showTips ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div x-show="showTips" x-collapse class="mt-2 pt-2 border-t text-[11.5px] leading-relaxed" style="border-color:rgba(0,138,90,0.15);color:var(--text-secondary)">
                Centang anggota yang ingin dicetak. Anggota yang tidak dicentang tidak ikut tercetak. Pada dialog cetak browser, gunakan kertas <strong>A4</strong> dan matikan <strong>"Headers and footers"</strong>.
            </div>
        </div>

        {{-- Sticky Search & Filter Toolbar --}}
        <div class="sticky top-[57px] sm:top-[65px] z-30 p-2.5 sm:p-3 rounded-2xl border shadow-sm space-y-2.5"
             style="background:var(--bg-surface-elevated);border-color:var(--border-light);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)">
            
            <div class="flex items-center gap-2">
                {{-- Kolom Cari dengan Debounce 250ms & Tombol Hapus (x) --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           x-model.debounce.250ms="searchQuery"
                           placeholder="Cari nama, jabatan, banom, atau nomor ID..."
                           class="w-full pl-9 pr-9 py-2 rounded-xl text-[16px] sm:text-xs font-medium outline-none border transition-all min-h-[44px]"
                           style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-primary)">
                    
                    {{-- Tombol Hapus (x) --}}
                    <button type="button"
                            x-show="searchQuery.length > 0"
                            @click="searchQuery = ''"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                            title="Hapus pencarian">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- HP: Tombol Filter (Buka Bottom Sheet) --}}
                <button type="button"
                        @click="mobileFilterOpen = true"
                        class="md:hidden inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border transition-all shrink-0 min-h-[44px]"
                        style="background:var(--bg-base);color:var(--text-secondary);border-color:var(--border-medium)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                    <span x-show="activeFilterCount > 0"
                          x-text="activeFilterCount"
                          class="w-4 h-4 rounded-full text-[10px] font-bold text-white flex items-center justify-center"
                          style="background:var(--accent)">1</span>
                </button>

                {{-- Desktop: Filter Banom & Status sebaris --}}
                <div class="hidden md:flex items-center gap-2">
                    <select x-model="filterBanom"
                            class="px-3 py-2 rounded-xl text-xs font-medium outline-none border min-h-[44px]"
                            style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Banom</option>
                        <template x-for="b in banomList" :key="b.id">
                            <option :value="b.id" x-text="b.singkatan"></option>
                        </template>
                    </select>

                    <select x-model="filterStatus"
                            class="px-3 py-2 rounded-xl text-xs font-medium outline-none border min-h-[44px]"
                            style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                        <option value="Alumni">Alumni</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>

            {{-- Summary Bar: Counter, Active Chips & Selection Controls --}}
            <div class="flex items-center justify-between gap-2 flex-wrap text-xs pt-1 border-t"
                 style="border-color:var(--border-light)">
                
                {{-- Counter & Active Chips --}}
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[11.5px] font-medium" style="color:var(--text-muted)"
                          x-text="'Menampilkan ' + filteredMembers.length + ' dari ' + members.length + ' anggota'">
                        Menampilkan 0 dari 0 anggota
                    </span>

                    {{-- Chip Banom --}}
                    <span x-show="filterBanom"
                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-medium border"
                          style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-secondary)">
                        <span x-text="'Banom: ' + getBanomName(filterBanom)"></span>
                        <button type="button" @click="filterBanom = ''" class="hover:text-red-500 font-bold">&times;</button>
                    </span>

                    {{-- Chip Status --}}
                    <span x-show="filterStatus"
                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-medium border"
                          style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-secondary)">
                        <span x-text="'Status: ' + filterStatus"></span>
                        <button type="button" @click="filterStatus = ''" class="hover:text-red-500 font-bold">&times;</button>
                    </span>

                    {{-- Chip Pencarian --}}
                    <span x-show="searchQuery.trim().length > 0"
                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-medium border"
                          style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-secondary)">
                        <span x-text="'Cari: \'' + searchQuery + '\''"></span>
                        <button type="button" @click="searchQuery = ''" class="hover:text-red-500 font-bold">&times;</button>
                    </span>
                </div>

                {{-- Persistent Selection Controls --}}
                <div class="flex items-center gap-2">
                    <span class="font-bold text-[11.5px]" style="color:var(--accent)"
                          x-text="selected.length + ' dipilih'">0 dipilih</span>
                    
                    <button type="button"
                            x-show="selected.length > 0"
                            @click="clearAllSelection()"
                            class="text-[11.5px] font-semibold text-rose-600 hover:underline min-h-[36px] flex items-center">
                        Hapus pilihan
                    </button>

                    <button type="button"
                            @click="toggleSelectVisible()"
                            class="px-2.5 py-1.5 rounded-xl text-xs font-semibold border transition-all min-h-[36px] inline-flex items-center gap-1"
                            style="background:var(--bg-base);color:var(--text-secondary);border-color:var(--border-medium)">
                        <span x-text="isAllVisibleSelected() ? 'Batal Semua Hasil' : 'Pilih Semua Hasil'">Pilih Semua</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Daftar Ringkas Anggota (Satu Baris Per Anggota) --}}
        <div class="space-y-2">
            <template x-for="m in filteredMembers" :key="m.id">
                <div class="group relative rounded-2xl border p-2.5 sm:p-3 flex items-center gap-3 transition-all cursor-pointer select-none"
                     :class="isSelected(m.id)
                         ? 'border-emerald-500/50 bg-emerald-50/40 dark:bg-emerald-950/20 shadow-sm'
                         : 'hover:border-slate-300 dark:hover:border-slate-700'"
                     style="background:var(--bg-surface)"
                     @click="openPreview(m)">

                    {{-- Checkbox Touch Target (Min 44px) --}}
                    <div class="p-2 -m-2 flex items-center justify-center min-w-[44px] min-h-[44px] shrink-0"
                         @click.stop="toggleSelect(m.id)">
                        <input type="checkbox"
                               :value="m.id"
                               :checked="isSelected(m.id)"
                               @change.stop="toggleSelect(m.id)"
                               class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer border-slate-300 dark:border-slate-600">
                    </div>

                    {{-- Avatar Thumbnail Kecil (3:4) --}}
                    <div class="w-9 h-12 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shrink-0 flex items-center justify-center">
                        <template x-if="m.photo_url">
                            <img :src="m.photo_url"
                                 :alt="m.name"
                                 class="w-full h-full object-cover">
                        </template>
                        <template x-if="!m.photo_url">
                            <div class="w-full h-full flex items-center justify-center text-white font-extrabold text-xs"
                                 style="background: #087A4B;"
                                 x-text="m.initials">
                            </div>
                        </template>
                    </div>

                    {{-- Info Anggota: Nama, Jabatan, Banom --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h3 class="font-bold text-sm leading-snug truncate" style="color:var(--text-primary)" x-text="m.name"></h3>
                            <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold"
                                  style="background: rgba(8, 122, 75, 0.1); color: #087A4B;"
                                  x-text="m.position"></span>
                            <template x-if="m.banom && m.banom.toLowerCase() !== m.position.toLowerCase()">
                                <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700"
                                      x-text="m.banom"></span>
                            </template>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[11px] font-semibold text-slate-400" x-text="m.id_pad"></span>
                            <span class="inline-block w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                            <span class="text-[11px]" x-text="m.status"></span>
                            <template x-if="m.wilayah">
                                <span class="hidden sm:inline-block truncate text-[11px]" x-text="'&middot; ' + m.wilayah"></span>
                            </template>
                        </div>
                    </div>

                    {{-- Aksi Cepat: Cetak Sendiri & Panah Pratinjau --}}
                    <div class="flex items-center gap-1.5 shrink-0" @click.stop>
                        <a :href="m.single_print_url"
                           title="Cetak satu kartu ini"
                           class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-emerald-600 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 transition-colors">
                            <span>Satu Kartu</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <button type="button"
                                @click="openPreview(m)"
                                title="Lihat Pratinjau Kartu"
                                class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 min-h-[44px] min-w-[44px] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>

            {{-- Empty State --}}
            <div x-show="filteredMembers.length === 0"
                 class="py-12 px-4 text-center rounded-2xl border space-y-3"
                 style="background:var(--bg-surface);border-color:var(--border-light)">
                <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center text-slate-400"
                     style="background:var(--bg-base)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-serif font-bold text-base" style="color:var(--text-primary)">
                        Tidak ada anggota yang cocok dengan "<span x-text="searchQuery"></span>"
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Coba periksa ejaan atau hapus filter Banom dan Status.
                    </p>
                </div>
                <div>
                    <button type="button"
                            @click="searchQuery = ''; filterBanom = ''; filterStatus = ''"
                            class="px-4 py-2 rounded-xl text-xs font-semibold border transition-all"
                            style="background:var(--bg-base);color:var(--text-primary);border-color:var(--border-medium)">
                        Hapus pencarian
                    </button>
                </div>
            </div>
        </div>
    </main>

    {{-- Bottom Sheet / Modal Pratinjau Kartu Saat Baris Ditekan (No Print) --}}
    <div x-show="previewMember !== null"
         x-cloak
         class="no-print fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
         style="background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="w-full max-w-md rounded-t-3xl sm:rounded-3xl p-5 sm:p-6 space-y-4 shadow-2xl border"
             style="background:var(--bg-overlay);border-color:var(--border-light)"
             @click.away="closePreview()">
            
            <div class="flex items-center justify-between pb-2 border-b" style="border-color:var(--border-light)">
                <div>
                    <h3 class="font-serif font-bold text-base" style="color:var(--text-primary)">Pratinjau Kartu Anggota</h3>
                    <p class="text-xs" style="color:var(--text-muted)" x-text="previewMember ? previewMember.name : ''"></p>
                </div>
                <button type="button" @click="closePreview()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Kartu Preview Sesuai Anggota yang Dipilih --}}
            <div class="w-full flex justify-center py-2" x-show="previewMember">
                <template x-for="a in members" :key="a.id">
                    <div x-show="previewMember && previewMember.id === a.id" class="w-full max-w-[380px]">
                        {{-- Kartu Dinamis Modal --}}
                        <div class="bg-white rounded-xl overflow-hidden shadow-lg border border-slate-200 text-slate-900 w-full"
                             style="aspect-ratio: 85.6 / 54; background: #ffffff !important; color: #0f172a !important;">
                            <div class="w-full shrink-0" style="height: 3.2mm; background: linear-gradient(90deg, #087A4B 0%, #10b981 65%, #d4a843 100%);"></div>
                            <div class="p-2.5 flex-1 flex flex-col justify-between overflow-hidden" style="height: calc(100% - 3.2mm)">
                                <div class="flex items-center justify-between pb-1 border-b border-slate-100 shrink-0 gap-1">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <img src="{{ asset('images/brand/logo-nu.png') }}" alt="Lambang Nahdlatul Ulama" width="16" height="16" class="w-4 h-4 object-contain shrink-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-4 h-4 rounded items-center justify-center font-bold text-[8px] text-white shrink-0 hidden" style="background: #087A4B; display:none;">NU</div>
                                        <div class="truncate text-[8px] font-extrabold uppercase tracking-wider" style="color: #087A4B;">NAHDLATUL ULAMA &middot; KALISARI</div>
                                    </div>
                                    <div class="font-mono text-[8px] font-bold text-slate-400 shrink-0" x-text="a.id_pad"></div>
                                </div>
                                <div class="flex items-center gap-2.5 flex-1 py-1 min-h-0">
                                    <div class="relative shrink-0 rounded-md overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center"
                                         style="width: 22mm; height: 29.3mm; aspect-ratio: 3 / 4; max-width: 26%;">
                                        <template x-if="a.photo_url">
                                            <img :src="a.photo_url" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!a.photo_url">
                                            <div class="w-full h-full flex items-center justify-center text-white font-extrabold text-xs tracking-wider"
                                                 style="background: #087A4B;" x-text="a.initials"></div>
                                        </template>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-between h-full min-w-0">
                                        <div>
                                            <h4 class="font-serif font-extrabold text-[13px] leading-tight text-slate-900 line-clamp-2" style="color:#0f172a !important;" x-text="a.name"></h4>
                                            <div class="flex items-center gap-1 mt-1 flex-wrap">
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[7.5px] font-bold" style="background: rgba(8, 122, 75, 0.1); color: #087A4B;" x-text="a.position"></span>
                                                <template x-if="a.banom && a.banom.toLowerCase() !== a.position.toLowerCase()">
                                                    <span class="inline-block px-1.5 py-0.5 rounded text-[7.5px] font-semibold text-slate-600 bg-slate-100 border border-slate-200" x-text="a.banom"></span>
                                                </template>
                                            </div>
                                            <div class="mt-1 space-y-0.5 text-[7.5px] text-slate-500">
                                                <template x-if="a.wilayah">
                                                    <div class="truncate" x-text="'Wilayah: ' + a.wilayah"></div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="flex items-end justify-between pt-0.5">
                                            <div class="flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span class="text-[7.5px] font-bold text-emerald-700" x-text="a.status"></span>
                                            </div>
                                            <span class="text-[7px] font-mono text-slate-400">QR Valid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Action Buttons Dalam Modal --}}
            <div class="flex items-center gap-2 pt-2" x-show="previewMember">
                <button type="button"
                        @click="toggleSelect(previewMember.id); closePreview();"
                        class="flex-1 py-2.5 px-4 rounded-xl text-xs font-semibold border text-center min-h-[44px] flex items-center justify-center gap-1"
                        :style="isSelected(previewMember.id)
                            ? 'background:rgba(225,29,72,0.1);color:#e11d48;border-color:rgba(225,29,72,0.2)'
                            : 'background:var(--bg-surface);color:var(--text-primary);border-color:var(--border-medium)'">
                    <span x-text="isSelected(previewMember.id) ? 'Batalkan Seleksi' : 'Pilih untuk Dicetak'"></span>
                </button>
                <a :href="previewMember ? previewMember.single_print_url : '#'"
                   class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white text-center shadow-sm min-h-[44px] flex items-center justify-center gap-1"
                   style="background:var(--accent)">
                    <span>Cetak Sendiri</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- HP Bottom Sheet Filter (No Print) --}}
    <div x-show="mobileFilterOpen"
         x-cloak
         class="no-print fixed inset-0 z-50 flex items-end justify-center p-0 md:hidden"
         style="background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="w-full rounded-t-3xl p-5 space-y-4 shadow-2xl border"
             style="background:var(--bg-overlay);border-color:var(--border-light)"
             @click.away="mobileFilterOpen = false">
            <div class="flex items-center justify-between pb-2 border-b" style="border-color:var(--border-light)">
                <h3 class="font-serif font-bold text-base" style="color:var(--text-primary)">Filter Anggota</h3>
                <button type="button" @click="mobileFilterOpen = false" class="p-1 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Badan Otonom (Banom)</label>
                    <select x-model="filterBanom"
                            class="w-full px-3 py-2 rounded-xl text-sm font-medium outline-none border min-h-[44px]"
                            style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Banom</option>
                        <template x-for="b in banomList" :key="b.id">
                            <option :value="b.id" x-text="b.singkatan + ' - ' + b.nama"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Status Keanggotaan</label>
                    <select x-model="filterStatus"
                            class="w-full px-3 py-2 rounded-xl text-sm font-medium outline-none border min-h-[44px]"
                            style="background:var(--bg-base);border-color:var(--border-medium);color:var(--text-primary)">
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                        <option value="Alumni">Alumni</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button type="button"
                        @click="filterBanom = ''; filterStatus = ''; mobileFilterOpen = false;"
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-semibold border text-center min-h-[44px]"
                        style="background:var(--bg-base);color:var(--text-secondary);border-color:var(--border-medium)">
                    Reset Filter
                </button>
                <button type="button"
                        @click="mobileFilterOpen = false"
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white text-center shadow-sm min-h-[44px]"
                        style="background:var(--accent)">
                    Terapkan
                </button>
            </div>
        </div>
    </div>

    {{-- HP Sticky Bottom Action Bar: HANYA Berisi "Cetak Terpilih (N)" (No Print) --}}
    <div class="no-print md:hidden fixed bottom-0 left-0 right-0 z-40 p-3 border-t shadow-2xl flex items-center gap-2"
         style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
        <button type="button"
                @click="printSelected()"
                :disabled="selected.length === 0"
                class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white text-center shadow-sm inline-flex items-center justify-center gap-2 min-h-[44px]"
                :class="selected.length === 0 ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:opacity-95'"
                style="background:var(--accent)">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Cetak Terpilih (<span x-text="selected.length">0</span>)</span>
        </button>
    </div>

    {{-- AREA KHUSUS CETAK: RENDER KARTU PENUH HANYA UNTUK ANGGOTA TERPILIH --}}
    <div class="print-cards-grid hidden print:grid">
        @foreach($anggotas as $anggota)
            <div class="print-card-item"
                 data-card-id="{{ $anggota->id }}"
                 :class="{ '!hidden': !isSelected({{ $anggota->id }}) }">
                @include('admin.anggota.partials.kartu-member', ['anggota' => $anggota])
            </div>
        @endforeach
    </div>

    {{-- Script QR Code Generator untuk Print Cards --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const boxes = document.querySelectorAll('.print-cards-grid .qr-canvas-box');
            boxes.forEach(function (box) {
                const targetUrl = box.getAttribute('data-qr-target');
                if (!targetUrl) return;

                new QRCode(box, {
                    text: targetUrl,
                    width: 52,
                    height: 52,
                    colorDark: '#087A4B',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M,
                });
            });
        });
    </script>
</body>
</html>
