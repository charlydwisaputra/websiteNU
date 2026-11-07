@extends('layouts.admin')

@section('hide_bottom_nav', true)

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-28 md:pb-12" x-data="galeriUpload()">

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
        <span class="badge badge-green">Dokumentasi</span>
    </div>

    {{-- Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Tambah Foto Dokumentasi
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Unggah satu atau lebih foto dokumentasi kegiatan untuk galeri publik NU Kalisari.
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

        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" @submit="submitting = true">
            @csrf

            <!-- Kategori Organisasi (Dropdown) -->
            <div class="space-y-1.5">
                <label for="category" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Kategori Organisasi <span class="font-normal" style="color:var(--text-muted)">(Opsional)</span>
                </label>
                <select name="category" id="category"
                        class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px]"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">— Pilih Kategori Organisasi / Banom —</option>
                    @foreach(config('organisasi.banom') as $b)
                        <option value="{{ $b['singkatan'] }}" {{ old('category') == $b['singkatan'] ? 'selected' : '' }}>
                            {{ $b['nama'] }} ({{ $b['singkatan'] }})
                        </option>
                    @endforeach
                    <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya / Umum</option>
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
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Pengajian Rutin Ahad Pagi Masjid Jami'"
                       class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('title') border-red-500 is-invalid @enderror"
                       style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                       onfocus="this.style.borderColor='var(--accent)'"
                       onblur="this.style.borderColor='var(--border-medium)'">
                @error('title')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan / Deskripsi -->
            <div class="space-y-1.5">
                <label for="description" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Keterangan Kegiatan <span class="font-normal" style="color:var(--text-muted)">(Opsional)</span>
                </label>
                <textarea name="description" id="description" rows="3"
                          placeholder="Tambahkan catatan lokasi, penceramah, atau ringkasan suasana kegiatan..."
                          class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y"
                          style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                          onfocus="this.style.borderColor='var(--accent)'"
                          onblur="this.style.borderColor='var(--border-medium)'">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Multi-Foto -->
            <div class="space-y-2">
                <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    File Foto Dokumentasi <span style="color:#ef4444">*</span>
                    <span class="font-normal ml-1">(bisa pilih banyak sekaligus)</span>
                </label>

                <!-- Drop Zone -->
                <div class="border-2 border-dashed rounded-2xl p-6 text-center cursor-pointer transition-colors relative"
                     style="border-color:var(--border-medium);background:var(--bg-surface-elevated)"
                     @click="$refs.fileInput.click()"
                     @dragover.prevent="dragOver = true"
                     @dragleave="dragOver = false"
                     @drop.prevent="handleDrop($event)"
                     :style="dragOver ? 'border-color:var(--accent);background:rgba(var(--accent-rgb),0.05)' : ''">

                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                           class="hidden" x-ref="fileInput" @change="handleFiles($event.target.files)" required>

                    <!-- Placeholder (saat belum ada foto) -->
                    <div x-show="previews.length === 0" id="upload-placeholder">
                        <svg class="mx-auto h-10 w-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs font-medium" style="color:var(--text-secondary)">Klik atau seret foto ke area ini</p>
                        <p class="text-[11px] mt-1" style="color:var(--text-muted)">Mendukung JPG, PNG, WEBP. Maks 5MB per foto. Bisa pilih banyak foto sekaligus.</p>
                    </div>

                    <!-- Info saat ada foto terpilih -->
                    <div x-show="previews.length > 0" class="text-xs font-medium" style="color:var(--accent)">
                        <svg class="mx-auto h-6 w-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Klik untuk menambah lebih banyak foto
                    </div>
                </div>

                @error('images')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror

                <!-- Preview Grid -->
                <div x-show="previews.length > 0" class="mt-3">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold" style="color:var(--text-secondary)">
                            <span x-text="previews.length"></span> foto dipilih
                        </p>
                        <button type="button" @click="clearAll()"
                                class="text-[11px] font-semibold px-3 py-1 rounded-lg transition-all"
                                style="color:#ef4444;background:rgba(239,68,68,0.08)">
                            Hapus Semua
                        </button>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group rounded-xl overflow-hidden aspect-square"
                                 style="background:var(--bg-surface-elevated)">
                                <img :src="preview.url" :alt="preview.name"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click.stop="removeFile(index)"
                                            class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 px-1.5 py-1 text-[9px] font-medium text-white truncate"
                                     style="background:linear-gradient(transparent,rgba(0,0,0,0.6))"
                                     x-text="preview.name"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Desktop Action Buttons (md+) -->
            <div class="hidden md:flex items-center justify-end gap-3 pt-4 border-t" style="border-color:var(--border-light)">
                <a href="{{ route('admin.galeri.index') }}"
                   class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all min-h-[44px] inline-flex items-center border"
                   style="color:var(--text-secondary);border-color:var(--border-medium);background:var(--bg-surface)">
                    Batal
                </a>
                <button type="submit"
                        :disabled="submitting || previews.length === 0"
                        :class="(submitting || previews.length === 0) ? 'opacity-70 cursor-not-allowed' : ''"
                        class="px-7 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white transition-all min-h-[44px] inline-flex items-center gap-2 shadow-sm"
                        style="background:var(--accent)">
                    <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan ' + previews.length + ' Foto'">Simpan Foto</span>
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
                        :disabled="submitting || previews.length === 0"
                        :class="(submitting || previews.length === 0) ? 'opacity-70 cursor-not-allowed' : ''"
                        class="flex-1 py-3 px-5 rounded-xl text-xs sm:text-sm font-bold text-white text-center transition-all min-h-[48px] shadow-sm inline-flex items-center justify-center gap-2"
                        style="background:var(--accent)">
                    <svg x-show="submitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan ' + previews.length + ' Foto'">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function galeriUpload() {
    return {
        previews: [],
        files: [],
        dragOver: false,
        submitting: false,

        handleFiles(newFiles) {
            Array.from(newFiles).forEach(file => {
                if (this.files.some(f => f.name === file.name && f.size === file.size)) return;
                this.files.push(file);
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push({ url: e.target.result, name: file.name });
                };
                reader.readAsDataURL(file);
            });
            this.syncInput();
        },

        handleDrop(event) {
            this.dragOver = false;
            this.handleFiles(event.dataTransfer.files);
        },

        removeFile(index) {
            this.previews.splice(index, 1);
            this.files.splice(index, 1);
            this.syncInput();
        },

        clearAll() {
            this.previews = [];
            this.files = [];
            this.syncInput();
        },

        syncInput() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            this.$refs.fileInput.files = dt.files;
        }
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const firstError = document.querySelector('.is-invalid, .border-red-500, [aria-invalid="true"]');
    if (firstError) {
        setTimeout(function () {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }
});
</script>
@endsection