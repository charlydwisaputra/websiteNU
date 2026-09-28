@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 pb-12">
        
        <!-- Header Banner -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#087A4B] text-xs font-bold mb-3 border border-emerald-100/60">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Modul Berita
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Tambah Berita Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Buat dan publikasikan artikel atau informasi kegiatan terbaru.</p>
            </div>
            <div>
                <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-2xl border border-slate-200 shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Judul -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Judul Berita *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Masukkan judul berita..." class="w-full px-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm transition-all text-slate-800 outline-none placeholder:text-slate-400 font-bold">
                    @error('title') <p class="text-xs text-red-600 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Kategori Berita (Animated Dropdown) -->
                <div x-data="{ 
                    open: false, 
                    selected: '{{ old('category', 'Kegiatan') }}',
                    options: [
                        { val: 'Kegiatan', label: 'Kegiatan' },
                        { val: 'Pengumuman', label: 'Pengumuman' },
                        { val: 'Organisasi', label: 'Organisasi' },
                        { val: 'Sosial', label: 'Sosial' }
                    ],
                    get currentLabel() {
                        let opt = this.options.find(o => o.val === this.selected);
                        return opt ? opt.label : '-- Pilih Kategori --';
                    }
                }" class="relative">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Kategori Berita *</label>
                    <input type="hidden" name="category" :value="selected">

                    <button type="button" 
                            @click="open = !open" 
                            @click.outside="open = false"
                            class="w-full px-4 py-3.5 bg-slate-50/70 hover:bg-white border @error('category') border-red-300 @else border-slate-200/80 @enderror focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-800 flex items-center justify-between transition-all duration-200 shadow-xs active:scale-[0.99]">
                        <span class="flex items-center gap-2.5 truncate">
                            <span class="w-2 h-2 rounded-full bg-[#087A4B] shadow-xs"></span>
                            <span x-text="currentLabel"></span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300 shrink-0 ml-2" :class="open ? 'rotate-180 text-[#087A4B]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute z-50 left-0 right-0 mt-2 bg-white/95 backdrop-blur-xl border border-slate-200/80 rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] p-1.5 space-y-1 overflow-hidden"
                         x-cloak>
                        <template x-for="opt in options" :key="opt.val">
                            <div @click="selected = opt.val; open = false"
                                 class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold cursor-pointer transition-all duration-150 flex items-center justify-between"
                                 :class="selected === opt.val ? 'bg-emerald-50/80 text-[#087A4B]' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="selected === opt.val ? 'bg-[#087A4B]' : 'bg-transparent'"></span>
                                    <span x-text="opt.label"></span>
                                </span>
                                <template x-if="selected === opt.val">
                                    <svg class="w-4 h-4 text-[#087A4B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                            </div>
                        </template>
                    </div>
                    @error('category') <p class="text-xs text-red-600 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Konten -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Isi Berita *</label>
                    <textarea name="content" rows="8" required placeholder="Tulis isi berita di sini..." class="w-full px-4 py-3 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm transition-all text-slate-800 outline-none placeholder:text-slate-400 font-medium">{{ old('content') }}</textarea>
                    @error('content') <p class="text-xs text-red-600 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Status (Animated Dropdown) -->
                <div x-data="{ 
                    open: false, 
                    selected: '{{ old('status', 'published') }}',
                    options: [
                        { val: 'published', label: 'Published (Terbit)' },
                        { val: 'draft', label: 'Draft (Simpan Sementara)' }
                    ],
                    get currentLabel() {
                        let opt = this.options.find(o => o.val === this.selected);
                        return opt ? opt.label : 'Published (Terbit)';
                    }
                }" class="relative">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Status Publikasi *</label>
                    <input type="hidden" name="status" :value="selected">

                    <button type="button" 
                            @click="open = !open" 
                            @click.outside="open = false"
                            class="w-full px-4 py-3.5 bg-slate-50/70 hover:bg-white border @error('status') border-red-300 @else border-slate-200/80 @enderror focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-800 flex items-center justify-between transition-all duration-200 shadow-xs active:scale-[0.99]">
                        <span class="flex items-center gap-2.5 truncate">
                            <span class="w-2 h-2 rounded-full" :class="selected === 'published' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                            <span x-text="currentLabel"></span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300 shrink-0 ml-2" :class="open ? 'rotate-180 text-[#087A4B]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute z-50 left-0 right-0 mt-2 bg-white/95 backdrop-blur-xl border border-slate-200/80 rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] p-1.5 space-y-1 overflow-hidden"
                         x-cloak>
                        <template x-for="opt in options" :key="opt.val">
                            <div @click="selected = opt.val; open = false"
                                 class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold cursor-pointer transition-all duration-150 flex items-center justify-between"
                                 :class="selected === opt.val ? 'bg-emerald-50/80 text-[#087A4B]' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="opt.val === 'published' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                    <span x-text="opt.label"></span>
                                </span>
                                <template x-if="selected === opt.val">
                                    <svg class="w-4 h-4 text-[#087A4B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                            </div>
                        </template>
                    </div>
                    @error('status') <p class="text-xs text-red-600 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Gambar -->
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Gambar / Thumbnail (Opsional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50/50 hover:bg-emerald-50/30 hover:border-emerald-300 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs text-slate-600 font-bold mb-1">Klik untuk upload gambar thumbnail</p>
                                <p class="text-[10px] text-slate-400">Format: JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" class="hidden">
                        </label>
                    </div>
                    @error('image') <p class="text-xs text-red-600 mt-1.5 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Action Button -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.berita.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#087A4B] hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-emerald-500/20 transition-all active:scale-95">
                        Simpan Berita
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection