@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#087A4B] text-xs font-bold mb-3 border border-emerald-100/60">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Edit Data Anggota
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Perbarui Data {{ $anggota->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">Ubah informasi organisasi, wilayah, status, dan foto anggota.</p>
        </div>
        <a href="{{ route('admin.anggota.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl transition-all active:scale-95 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200/80 text-red-700 px-6 py-4 rounded-3xl shadow-sm flex items-start gap-3">
            <div class="w-8 h-8 rounded-2xl bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-sm mb-1">Ada kesalahan pada formulir:</p>
                <ul class="text-xs space-y-0.5 list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Section 1: Identitas Anggota --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-900 uppercase tracking-wider">Identitas Anggota</p>
                    <p class="text-xs text-slate-400 font-medium">Nama dan foto profil</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- Foto Profil --}}
                    <div class="flex flex-col items-center gap-3 shrink-0">
                        <div class="w-28 h-28 rounded-3xl bg-slate-50 border-2 border-slate-200 flex items-center justify-center overflow-hidden relative shadow-sm">
                            @if($anggota->photo)
                                <img src="{{ asset('storage/' . $anggota->photo) }}" alt="{{ $anggota->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-[#087A4B] font-black text-xl">
                                    {{ strtoupper(substr($anggota->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-[#087A4B] hover:file:bg-emerald-100 cursor-pointer">
                        @error('photo') <p class="text-xs text-red-500 font-semibold text-center">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="flex-1 flex flex-col justify-center gap-1.5">
                        <label for="name" class="text-xs font-black text-slate-700 uppercase tracking-wider">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $anggota->name) }}" required
                               class="w-full px-4 py-3.5 bg-slate-50/60 border @error('name') border-red-400 @else border-slate-200/80 @enderror focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm font-bold text-slate-800 outline-none transition-all">
                        @error('name') <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Badan Otonom & Detail --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-900 uppercase tracking-wider">Organisasi & Detail</p>
                    <p class="text-xs text-slate-400 font-medium">Pilih Banom/Role, Wilayah, dan Status</p>
                </div>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Role / Banom (Animated Dropdown) --}}
                <div x-data="{ 
                    open: false, 
                    selected: '{{ old('role', $anggota->role) }}',
                    options: [
                        { val: '', label: '-- Pilih Banom --' },
                        { val: 'Muslimat', label: 'Muslimat' },
                        { val: 'Fatayat', label: 'Fatayat' },
                        { val: 'GP Ansor', label: 'GP Ansor' },
                        { val: 'IPNU', label: 'IPNU' },
                        { val: 'IPPNU', label: 'IPPNU' },
                        { val: 'PMII', label: 'PMII' }
                    ],
                    get currentLabel() {
                        let opt = this.options.find(o => o.val === this.selected);
                        return opt ? opt.label : '-- Pilih Banom --';
                    }
                }" class="space-y-1.5 relative">
                    <label class="text-xs font-black text-slate-700 uppercase tracking-wider block">Role / Banom</label>
                    <input type="hidden" name="role" :value="selected">

                    <button type="button" 
                            @click="open = !open" 
                            @click.outside="open = false"
                            class="w-full px-4 py-3.5 bg-slate-50/70 hover:bg-white border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-800 flex items-center justify-between transition-all duration-200 shadow-xs active:scale-[0.99]">
                        <span class="flex items-center gap-2.5 truncate" :class="selected ? 'text-slate-900' : 'text-slate-400 font-medium'">
                            <span class="w-2 h-2 rounded-full" :class="selected ? 'bg-[#087A4B] shadow-xs' : 'bg-slate-300'"></span>
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
                         class="absolute z-50 left-0 right-0 mt-2 bg-white/95 backdrop-blur-xl border border-slate-200/80 rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] p-1.5 space-y-1 overflow-hidden max-h-60 overflow-y-auto"
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
                    @error('role') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Wilayah --}}
                <div class="space-y-1.5">
                    <label for="wilayah" class="text-xs font-black text-slate-700 uppercase tracking-wider block">Wilayah / Cabang</label>
                    <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah', $anggota->wilayah) }}"
                           placeholder="Cth: Kalisari, Bandung Barat"
                           class="w-full px-4 py-3.5 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm font-bold text-slate-800 outline-none transition-all">
                    @error('wilayah') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal Bergabung --}}
                <div class="space-y-1.5">
                    <label for="joined_at" class="text-xs font-black text-slate-700 uppercase tracking-wider block">Tanggal Bergabung</label>
                    <input type="date" name="joined_at" id="joined_at" value="{{ old('joined_at', $anggota->joined_at ? \Carbon\Carbon::parse($anggota->joined_at)->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-3.5 bg-slate-50/60 border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm font-bold text-slate-800 outline-none transition-all">
                    @error('joined_at') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Status Keanggotaan (Animated Dropdown) --}}
                <div x-data="{ 
                    open: false, 
                    selected: '{{ old('status', $anggota->status) }}',
                    options: [
                        { val: 'Aktif', label: 'Aktif', dot: 'bg-emerald-500' },
                        { val: 'Pending', label: 'Pending', dot: 'bg-amber-500' },
                        { val: 'Alumni', label: 'Alumni', dot: 'bg-blue-500' },
                        { val: 'Nonaktif', label: 'Nonaktif', dot: 'bg-slate-400' }
                    ],
                    get currentOption() {
                        return this.options.find(o => o.val === this.selected) || this.options[0];
                    }
                }" class="space-y-1.5 relative">
                    <label class="text-xs font-black text-slate-700 uppercase tracking-wider block">Status Keanggotaan <span class="text-red-400">*</span></label>
                    <input type="hidden" name="status" :value="selected">

                    <button type="button" 
                            @click="open = !open" 
                            @click.outside="open = false"
                            class="w-full px-4 py-3.5 bg-slate-50/70 hover:bg-white border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-800 flex items-center justify-between transition-all duration-200 shadow-xs active:scale-[0.99]">
                        <span class="flex items-center gap-2.5 truncate">
                            <span class="w-2 h-2 rounded-full" :class="currentOption.dot"></span>
                            <span x-text="currentOption.label"></span>
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
                                    <span class="w-2 h-2 rounded-full" :class="opt.dot"></span>
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
                    @error('status') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Visibilitas Publik (Animated Dropdown) --}}
                <div x-data="{ 
                    open: false, 
                    selected: '{{ old('is_public', $anggota->is_public) }}',
                    options: [
                        { val: '1', label: 'Ya, Tampilkan (Publik)', dot: 'bg-emerald-500' },
                        { val: '0', label: 'Tidak, Sembunyikan (Privat)', dot: 'bg-slate-400' }
                    ],
                    get currentLabel() {
                        return this.selected == '1' ? 'Ya, Tampilkan (Publik)' : 'Tidak, Sembunyikan (Privat)';
                    }
                }" class="space-y-1.5 col-span-1 md:col-span-2 relative">
                    <label class="text-xs font-black text-slate-700 uppercase tracking-wider block">Tampil di Halaman Publik? <span class="text-red-400">*</span></label>
                    <input type="hidden" name="is_public" :value="selected">

                    <button type="button" 
                            @click="open = !open" 
                            @click.outside="open = false"
                            class="w-full px-4 py-3.5 bg-slate-50/70 hover:bg-white border border-slate-200/80 focus:bg-white focus:border-[#087A4B] focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-bold text-slate-800 flex items-center justify-between transition-all duration-200 shadow-xs active:scale-[0.99]">
                        <span class="flex items-center gap-2.5 truncate">
                            <span class="w-2 h-2 rounded-full" :class="selected == '1' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
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
                                 :class="selected == opt.val ? 'bg-emerald-50/80 text-[#087A4B]' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" :class="opt.dot"></span>
                                    <span x-text="opt.label"></span>
                                </span>
                                <template x-if="selected == opt.val">
                                    <svg class="w-4 h-4 text-[#087A4B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                            </div>
                        </template>
                    </div>
                    @error('is_public') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-4 bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
            <a href="{{ route('admin.anggota.index') }}" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-2xl transition-all">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-[#087A4B] hover:bg-emerald-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-500/25 transition-all active:scale-95">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection