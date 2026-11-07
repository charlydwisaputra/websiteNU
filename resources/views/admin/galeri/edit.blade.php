@extends('layouts.admin')

@section('hide_bottom_nav', true)

@section('content')
@php
    $allPhotos = $galeri->all_photos;
@endphp
<div class="max-w-4xl mx-auto space-y-6 pb-28 md:pb-12" x-data="galeriEditUpload()">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.galeri.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold py-2 px-3 rounded-xl transition-all min-h-[44px] border"
           style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
           onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Galeri
        </a>
        <span class="badge badge-green">Edit Album</span>
    </div>

    {{-- Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Edit Album Dokumentasi
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Perbarui info album, hapus foto tertentu, atau tambah foto baru.
        </p>
    </div>

    {{-- Form Card --}}
    <div class="p-5 sm:p-7 rounded-2xl surface-card border" style="border-color:var(--border-light)">
        @if ($errors->any())
        <div class="mb-5 flex items-start gap-3 p-4 rounded-xl text-sm font-medium"
             style="background:rgba(239,68,68,0.08);color:#ef4444;border:1px solid rgba(239,68,68,0.25)">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold">Gagal menyimpan: terdapat {{ $errors->count() }} kolom yang perlu diperbaiki.</p>
                <ul class="space-y-0.5 text-xs list-disc list-inside mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data"
              class="space-y-5" @submit="submitting = true">
            @csrf
            @method('PUT')

            <!-- Kategori Organisasi -->
            <div class="space-y-1.5">
                <label for="category" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Kategori Organisasi <span class="font-normal" style="color:var(--text-muted)">(Opsional)</span>
                </label>
                <select name="category" id="category"
                        class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">— Pilih Kategori Organisasi / Banom —</option>
                    @foreach(config('organisasi.banom') as $b)
                        <option value="{{ $b['singkatan'] }}" {{ old('category', $galeri->category) == $b['singkatan'] ? 'selected' : '' }}>
                            {{ $b['nama'] }} ({{ $b['singkatan'] }})
                        </option>
                    @endforeach
                    <option value="Lainnya" {{ old('category', $galeri->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya / Umum</option>
                </select>
                @error('category')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>

            <!-- Judul Kegiatan -->
            <div class="space-y-1.5">
                <label for="title" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Judul Kegiatan / Dokumentasi <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $galeri->title) }}" required
                       placeholder="Contoh: Pengajian Rutin Ahad Pagi Masjid Jami'"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('title') border-red-500 is-invalid @enderror"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                @error('title')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="space-y-1.5">
                <label for="description" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Keterangan Kegiatan <span class="font-normal" style="color:var(--text-muted)">(Opsional)</span>
                </label>
                <textarea name="description" id="description" rows="3"
                          placeholder="Tambahkan catatan lokasi, penceramah, atau ringkasan suasana kegiatan..."
                          class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y"
                          style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                          onfocus="this.style.borderColor='var(--accent)'"
                          onblur="this.style.borderColor='var(--border-medium)'">{{ old('description', $galeri->description) }}</textarea>
            </div>

            <!-- Foto Saat Ini -->
            @if(count($allPhotos) > 0)
            <div class="space-y-2">
                <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Foto Saat Ini ({{ count($allPhotos) }} foto)
                    <span class="font-normal ml-1">— centang untuk hapus</span>
                </label>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                    @foreach($allPhotos as $i => $photoPath)
                    <label class="relative cursor-pointer group block"
                           x-data="{ checked: false }">
                        <input type="checkbox" name="delete_photos[]" value="{{ $photoPath }}"
                               class="sr-only" @change="checked = $event.target.checked">
                        <div class="relative rounded-xl overflow-hidden aspect-square border-2 transition-all"
                             :style="checked ? 'border-color:#ef4444;opacity:0.6' : 'border-color:transparent'"
                             style="background:var(--bg-surface-elevated)">
                            <img src="{{ asset('storage/'.$photoPath) }}" alt="Foto {{ $i+1 }}"
                                 class="w-full h-full object-cover">
                            @if($i === 0)
                            <div class="absolute top-1 left-1">
                                <span class="text-[9px] font-bold text-white px-1.5 py-0.5 rounded-md" style="background:var(--accent)">Cover</span>
                            </div>
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                 style="background:rgba(239,68,68,0.3)" x-show="!checked">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center" x-show="checked">
                                <svg class="w-7 h-7 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-center mt-1 font-medium"
                           :style="checked ? 'color:#ef4444' : 'color:var(--text-muted)'"
                           x-text="checked ? 'Akan dihapus' : 'Foto {{ $i+1 }}'"></p>
                    </label>
                    @endforeach
                </div>
                <p class="text-[11px]" style="color:var(--text-muted)">Klik foto untuk menandai akan dihapus. Perubahan baru diterapkan setelah simpan.</p>
            </div>
            @endif

            <!-- Tambah Foto Baru -->
            <div class="space-y-2">
                <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Tambah Foto Baru <span class="font-normal">(Opsional)</span>
                </label>
                <div class="border-2 border-dashed rounded-2xl p-5 text-center cursor-pointer transition-colors"
                     style="border-color:var(--border-medium);background:var(--bg-surface-elevated)"
                     @click="$refs.newImages.click()"
                     @dragover.prevent="dragOver=true"
                     @dragleave="dragOver=false"
                     @drop.prevent="handleDrop($event)"
                     :style="dragOver ? 'border-color:var(--accent)' : ''">
                    <input type="file" name="new_images[]" accept="image/*" multiple
                           class="hidden" x-ref="newImages" @change="handleFiles($event.target.files)">
                    <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <p class="text-xs font-medium" style="color:var(--text-secondary)">
                        <span x-show="newPreviews.length === 0">Klik atau seret foto baru ke sini</span>
                        <span x-show="newPreviews.length > 0" x-text="newPreviews.length + ' foto baru dipilih — klik untuk tambah lagi'"></span>
                    </p>
                    <p class="text-[11px] mt-1" style="color:var(--text-muted)">JPG, PNG, WEBP. Maks 5MB per foto.</p>
                </div>

                <!-- Preview foto baru -->
                <div x-show="newPreviews.length > 0" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2 mt-2">
                    <template x-for="(p, i) in newPreviews" :key="i">
                        <div class="relative group rounded-xl overflow-hidden aspect-square"
                             style="background:var(--bg-surface-elevated)">
                            <img :src="p.url" :alt="p.name" class="w-full h-full object-cover">
                            <div class="absolute top-1 left-1">
                                <span class="text-[9px] font-bold text-white px-1.5 py-0.5 rounded-md bg-blue-500">Baru</span>
                            </div>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" @click.stop="removeNew(i)"
                                        class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                @error('new_images.*')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>

            <!-- Desktop Action Buttons -->
            <div class="hidden md:flex items-center justify-end gap-3 pt-4 border-t" style="border-color:var(--border-light)">
                <a href="{{ route('admin.galeri.index') }}"
                   class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] inline-flex items-center border"
                   style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                    Batal
                </a>
                <button type="submit"
                        :disabled="submitting"
                        :class="submitting ? 'opacity-70 cursor-not-allowed' : ''"
                        class="px-7 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white transition-all min-h-[44px] inline-flex items-center gap-2 shadow-sm"
                        style="background:var(--accent)">
                    <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                </button>
            </div>

            <!-- Mobile Sticky Action Bar -->
            <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t p-3 flex items-center gap-2.5 shadow-2xl"
                 style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
                <a href="{{ route('admin.galeri.index') }}"
                   class="py-3 px-4 rounded-xl text-xs font-semibold text-center transition-all min-h-[48px] border inline-flex items-center justify-center shrink-0 w-24 sm:w-28"
                   style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                    Batal
                </a>
                <button type="submit"
                        :disabled="submitting"
                        :class="submitting ? 'opacity-70 cursor-not-allowed' : ''"
                        class="flex-1 py-3 px-5 rounded-xl text-xs sm:text-sm font-bold text-white text-center transition-all min-h-[48px] shadow-sm inline-flex items-center justify-center gap-2"
                        style="background:var(--accent)">
                    <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function galeriEditUpload() {
    return {
        submitting: false,
        newPreviews: [],
        newFiles: [],
        dragOver: false,

        handleFiles(newFiles) {
            Array.from(newFiles).forEach(file => {
                if (this.newFiles.some(f => f.name === file.name && f.size === file.size)) return;
                this.newFiles.push(file);
                const reader = new FileReader();
                reader.onload = (e) => this.newPreviews.push({ url: e.target.result, name: file.name });
                reader.readAsDataURL(file);
            });
            this.syncInput();
        },

        handleDrop(event) {
            this.dragOver = false;
            this.handleFiles(event.dataTransfer.files);
        },

        removeNew(index) {
            this.newPreviews.splice(index, 1);
            this.newFiles.splice(index, 1);
            this.syncInput();
        },

        syncInput() {
            const dt = new DataTransfer();
            this.newFiles.forEach(f => dt.items.add(f));
            this.$refs.newImages.files = dt.files;
        }
    };
}
</script>
@endsection