@extends('layouts.admin')

@section('hide_bottom_nav', true)

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-28 md:pb-12"
     x-data="{
         submitting: false,
         previews: [],
         files: [],
         dragOver: false,
         handleFiles(newFiles) {
             Array.from(newFiles).forEach(file => {
                 if (this.files.some(f => f.name===file.name && f.size===file.size)) return;
                 this.files.push(file);
                 const reader = new FileReader();
                 reader.onload = (e) => this.previews.push({ url: e.target.result, name: file.name });
                 reader.readAsDataURL(file);
             });
             this.syncInput();
         },
         handleDrop(e) { this.dragOver=false; this.handleFiles(e.dataTransfer.files); },
         removeFile(i) { this.previews.splice(i,1); this.files.splice(i,1); this.syncInput(); },
         syncInput() { const dt=new DataTransfer(); this.files.forEach(f=>dt.items.add(f)); this.$refs.imgInput.files=dt.files; }
     }">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.berita.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold py-2 px-3 rounded-xl transition-all min-h-[44px] border"
           style="background:var(--bg-surface);color:var(--text-secondary);border-color:var(--border-medium)"
           onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'"
           onmouseout="this.style.color='var(--text-secondary)';this.style.borderColor='var(--border-medium)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Berita
        </a>
        <span class="badge badge-green">Publikasi Baru</span>
    </div>

    {{-- Header --}}
    <div>
        <h1 class="font-serif text-2xl md:text-3xl font-bold tracking-tight" style="color:var(--text-primary)">
            Tambah Berita Baru
        </h1>
        <p class="text-xs sm:text-sm mt-1" style="color:var(--text-muted)">
            Tulis dan publikasikan warta, artikel dakwah, atau pengumuman kegiatan ranting NU Kalisari.
        </p>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="flex items-start gap-3 p-4 rounded-xl text-sm font-medium"
         style="background:rgba(239,68,68,0.08);color:#ef4444;border:1px solid rgba(239,68,68,0.25)">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="font-bold">Gagal menyimpan: terdapat {{ $errors->count() }} kolom yang perlu diperbaiki.</p>
            <ul class="space-y-0.5 text-xs list-disc list-inside mt-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data"
          class="p-5 sm:p-7 rounded-2xl surface-card space-y-5 border" style="border-color:var(--border-light)"
          @submit="submitting = true">
        @csrf

        {{-- Judul Berita --}}
        <div class="space-y-1.5">
            <label for="title" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Judul Berita <span style="color:#ef4444">*</span>
            </label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                   placeholder="Cth: Peringatan Maulid Nabi di Masjid Kalisari Berjalan Khidmat"
                   class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('title') border-red-500 is-invalid @enderror"
                   style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                   onfocus="this.style.borderColor='var(--accent)'"
                   onblur="this.style.borderColor='var(--border-medium)'">
            @error('title')
            <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kategori & Status (Grid di Tablet/Desktop, 1 kolom di HP) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="category" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Kategori <span style="color:#ef4444">*</span>
                </label>
                <select name="category" id="category" required
                        class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all min-h-[44px] @error('category') border-red-500 is-invalid @enderror"
                        style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)">
                    <option value="">Pilih Kategori</option>
                    @foreach (\App\Models\Berita::CATEGORIES as $cat)
                        <option value="{{ $cat }}" {{ old('category', $berita->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')
                <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                    Status Publikasi <span style="color:#ef4444">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all min-h-[44px]"
                           style="background:var(--bg-surface-elevated);border-color:var(--border-medium)">
                        <input type="radio" name="status" value="published"
                               {{ old('status', 'published') === 'published' ? 'checked' : '' }}
                               class="text-[var(--accent)] focus:ring-[var(--accent)]">
                        <span class="text-xs font-semibold" style="color:var(--text-primary)">Terbit</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all min-h-[44px]"
                           style="background:var(--bg-surface-elevated);border-color:var(--border-medium)">
                        <input type="radio" name="status" value="draft"
                               {{ old('status') === 'draft' ? 'checked' : '' }}
                               class="text-[var(--accent)] focus:ring-[var(--accent)]">
                        <span class="text-xs font-semibold" style="color:var(--text-primary)">Draf</span>
                    </label>
                </div>
                @error('status')
                <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="space-y-1.5">
            <label for="excerpt" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Ringkasan / Sinopsis <span class="font-normal" style="color:var(--text-muted)">(Opsional)</span>
            </label>
            <textarea name="excerpt" id="excerpt" rows="2"
                      placeholder="Ringkasan 1-2 kalimat yang tampil di kartu berita beranda..."
                      class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y"
                      style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                      onfocus="this.style.borderColor='var(--accent)'"
                      onblur="this.style.borderColor='var(--border-medium)'">{{ old('excerpt') }}</textarea>
            @error('excerpt')
            <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Isi Lengkap Berita --}}
        <div class="space-y-1.5">
            <label for="content" class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Isi Lengkap Berita <span style="color:#ef4444">*</span>
            </label>
            <textarea name="content" id="content" rows="8" required
                      placeholder="Tulis uraian lengkap warta kegiatan, tanggal, lokasi, dan narasumber..."
                      class="w-full px-4 py-3 rounded-xl text-[16px] sm:text-sm font-medium outline-none transition-all resize-y @error('content') border-red-500 is-invalid @enderror"
                      style="background:var(--bg-surface-elevated);border:1px solid var(--border-medium);color:var(--text-primary)"
                      onfocus="this.style.borderColor='var(--accent)'"
                      onblur="this.style.borderColor='var(--border-medium)'">{{ old('content') }}</textarea>
            @error('content')
            <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Foto Sampul / Thumbnail --}}
        <div class="space-y-2">
            <label class="block text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)">
                Foto Sampul / Thumbnail (Utama)
            </label>
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full sm:w-36 h-28 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border font-bold text-xs"
                     style="background:var(--bg-surface-elevated);border-color:var(--border-medium);color:var(--text-muted)">
                    <template x-if="previews.length > 0">
                        <img :src="previews[0].url" class="w-full h-full object-cover" alt="Pratinjau Foto Utama">
                    </template>
                    <template x-if="previews.length === 0">
                        <span class="text-center px-2">Pratinjau Foto</span>
                    </template>
                </div>
                <div class="flex-1 w-full space-y-1.5">
                    <div class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-colors"
                         style="border-color:var(--border-medium);background:var(--bg-surface-elevated)"
                         @click="$refs.imgInput.click()"
                         @dragover.prevent="dragOver=true"
                         @dragleave="dragOver=false"
                         @drop.prevent="handleDrop($event)"
                         :style="dragOver ? 'border-color:var(--accent)' : ''">
                        <input type="file" name="images[]" id="images" accept="image/*" multiple
                               class="hidden" x-ref="imgInput" @change="handleFiles($event.target.files)">
                        <p class="text-xs font-medium" style="color:var(--text-secondary)">
                            <span x-show="previews.length === 0">Klik atau seret foto ke sini</span>
                            <span x-show="previews.length > 0" x-text="previews.length + ' foto dipilih — klik untuk tambah lagi'"></span>
                        </p>
                        <p class="text-[11px] mt-0.5" style="color:var(--text-muted)">JPG, PNG, WEBP. Maks 5MB per foto. Bisa pilih banyak sekaligus.</p>
                    </div>
                    @error('images')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                    <p class="text-xs font-medium" style="color:var(--status-danger-fg)">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Preview Grid --}}
            <div x-show="previews.length > 0" class="mt-2">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold" style="color:var(--text-secondary)">
                        <span x-text="previews.length"></span> foto dipilih
                        <span class="font-normal" style="color:var(--text-muted)">· Foto pertama jadi sampul</span>
                    </p>
                    <button type="button" @click="previews=[]; files=[]; syncInput()"
                            class="text-[11px] font-semibold px-3 py-1 rounded-lg"
                            style="color:#ef4444;background:rgba(239,68,68,0.08)">Hapus Semua</button>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                    <template x-for="(preview, i) in previews" :key="i">
                        <div class="relative group rounded-xl overflow-hidden aspect-square border-2"
                             :style="i===0 ? 'border-color:var(--accent)' : 'border-color:transparent'"
                             style="background:var(--bg-surface-elevated)">
                            <img :src="preview.url" :alt="preview.name" class="w-full h-full object-cover">
                            <div x-show="i===0" class="absolute top-1 left-1">
                                <span class="text-[9px] font-bold text-white px-1.5 py-0.5 rounded-md" style="background:var(--accent)">Utama</span>
                            </div>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" @click.stop="removeFile(i)"
                                        class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Desktop Action Buttons (md+) --}}
        <div class="hidden md:flex items-center justify-end gap-3 pt-4 border-t" style="border-color:var(--border-light)">
            <a href="{{ route('admin.berita.index') }}"
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
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Berita'">Simpan Berita</span>
            </button>
        </div>

        {{-- Mobile Sticky Action Bar (Hanya HP, md:hidden, Hormati Safe Area, Solid / Backdrop-blur) --}}
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t p-3 flex items-center gap-2.5 shadow-2xl"
             style="background:var(--bg-overlay);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-color:var(--border-light);padding-bottom:max(0.75rem,env(safe-area-inset-bottom))">
            <a href="{{ route('admin.berita.index') }}"
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
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan'">Simpan</span>
            </button>
        </div>
    </form>
</div>

<script>
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