@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto space-y-6 pb-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#087A4B] text-xs font-bold mb-3 border border-emerald-100/60">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Tambah Anggota Baru
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Formulir Pendaftaran Anggota</h1>
            <p class="text-sm text-slate-500 mt-1">Isi data lengkap anggota organisasi di bawah ini.</p>
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
                <p class="font-bold text-sm mb-1">Ada {{ $errors->count() }} kesalahan pada formulir:</p>
                <ul class="text-xs space-y-0.5 list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.anggota.store') }}" method="POST" enctype="multipart/form-data" id="anggota-form">
        @csrf

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
                    {{-- Photo Upload --}}
                    <div class="flex flex-col items-center gap-3 shrink-0">
                        <div id="photo-preview-wrapper"
                             class="w-28 h-28 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition-all group overflow-hidden relative"
                             onclick="document.getElementById('photo').click()">
                            <img id="photo-preview" src="" alt="Preview" class="w-full h-full object-cover absolute inset-0 hidden rounded-3xl">
                            <div id="photo-placeholder" class="flex flex-col items-center gap-2 pointer-events-none">
                                <svg class="w-7 h-7 text-slate-300 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-500 text-center leading-tight">Klik untuk<br>upload foto</span>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewPhoto(this)">
                        <p class="text-[10px] text-slate-400 font-medium text-center leading-tight">JPG / PNG<br>Maks. 2MB</p>
                        @error('photo') <p class="text-xs text-red-500 font-semibold text-center">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="flex-1 flex flex-col justify-center gap-1.5">
                        <label for="name" class="text-xs font-black text-slate-700 uppercase tracking-wider">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Cth: Ahmad Fauzi Hidayatullah"
                               class="w-full px-4 py-3.5 bg-slate-50/60 border @error('name') border-red-400 @else border-slate-200/80 @enderror focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal outline-none transition-all">
                        @error('name') <p class="text-xs text-red-500 font-semibold flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
                        <p class="text-[11px] text-slate-400 font-medium">Masukkan nama lengkap sesuai KTP</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Badan Otonom --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-900 uppercase tracking-wider">Badan Otonom (Banom)</p>
                    <p class="text-xs text-slate-400 font-medium">Pilih organisasi / banom yang sesuai</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <input type="hidden" name="role" id="role-input" value="{{ old('role') }}" required>
                @php
                    $banoms = [
                        ['value' => 'Muslimat',  'emoji' => 'ðŸŒ¸', 'desc' => 'Organisasi perempuan dewasa'],
                        ['value' => 'Fatayat',   'emoji' => 'ðŸŒº', 'desc' => 'Organisasi perempuan muda'],
                        ['value' => 'GP Ansor',  'emoji' => 'âš¡', 'desc' => 'Gerakan Pemuda Ansor'],
                        ['value' => 'IPNU',      'emoji' => 'ðŸ“š', 'desc' => 'Ikatan Pelajar NU Putra'],
                        ['value' => 'IPPNU',     'emoji' => 'ðŸŽ“', 'desc' => 'Ikatan Pelajar NU Putri'],
                        ['value' => 'PMII',      'emoji' => 'ðŸ›ï¸', 'desc' => 'Pergerakan Mahasiswa Islam'],
                    ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="banom-grid">
                    @foreach($banoms as $b)
                        <button type="button"
                                onclick="selectBanom('{{ $b['value'] }}')"
                                data-banom="{{ $b['value'] }}"
                                class="banom-pill relative flex flex-col items-start gap-1 p-4 rounded-2xl border-2 border-slate-100 bg-slate-50/50 hover:border-emerald-300 hover:bg-emerald-50 transition-all text-left group {{ old('role') == $b['value'] ? 'border-[#087A4B] bg-emerald-50 selected' : '' }}">
                            <span class="text-2xl leading-none">{{ $b['emoji'] }}</span>
                            <span class="text-sm font-bold text-slate-800 group-[.selected]:text-[#087A4B]">{{ $b['value'] }}</span>
                            <span class="text-[10px] text-slate-400 leading-tight font-medium">{{ $b['desc'] }}</span>
                            <div class="absolute top-2 right-2 w-4 h-4 rounded-full border-2 border-slate-200 flex items-center justify-center banom-check {{ old('role') == $b['value'] ? 'border-[#087A4B] bg-[#087A4B]' : '' }}">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </button>
                    @endforeach
                </div>
                @error('role') <p class="text-xs text-red-500 font-semibold mt-3 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 3: Detail Keanggotaan --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#087A4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-900 uppercase tracking-wider">Detail Keanggotaan</p>
                    <p class="text-xs text-slate-400 font-medium">Wilayah, status, dan tanggal bergabung</p>
                </div>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Wilayah --}}
                <div class="space-y-1.5">
                    <label for="wilayah" class="text-xs font-black text-slate-700 uppercase tracking-wider">Wilayah / Cabang</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah') }}"
                               placeholder="Cth: Cikembulan, Bandung Barat"
                               class="w-full pl-10 pr-4 py-3.5 bg-slate-50/60 border @error('wilayah') border-red-400 @else border-slate-200/80 @enderror focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm font-bold text-slate-800 placeholder:text-slate-400 placeholder:font-normal outline-none transition-all">
                    </div>
                    @error('wilayah') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal Bergabung --}}
                <div class="space-y-1.5">
                    <label for="joined_at" class="text-xs font-black text-slate-700 uppercase tracking-wider">Tanggal Bergabung</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" name="joined_at" id="joined_at" value="{{ old('joined_at') }}"
                               class="w-full pl-10 pr-4 py-3.5 bg-slate-50/60 border @error('joined_at') border-red-400 @else border-slate-200/80 @enderror focus:bg-white focus:border-[#087A4B] focus:ring-2 focus:ring-emerald-500/20 rounded-2xl text-sm font-bold text-slate-700 outline-none transition-all">
                    </div>
                    @error('joined_at') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Status Keanggotaan --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-700 uppercase tracking-wider">
                        Status Keanggotaan <span class="text-red-400">*</span>
                    </label>
                    <div class="flex gap-2 flex-wrap">
                        @php
                            $statuses = [
                                ['val' => 'Aktif',    'color' => 'emerald', 'dot' => 'bg-emerald-500'],
                                ['val' => 'Nonaktif', 'color' => 'slate',   'dot' => 'bg-slate-400'],
                                ['val' => 'Alumni',   'color' => 'blue',    'dot' => 'bg-blue-500'],
                                ['val' => 'Pending',  'color' => 'amber',   'dot' => 'bg-amber-500'],
                            ];
                        @endphp
                        @foreach($statuses as $s)
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="{{ $s['val'] }}"
                                       class="sr-only peer"
                                       {{ old('status', 'Aktif') == $s['val'] ? 'checked' : '' }}>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border-2 border-slate-200 bg-slate-50 font-bold text-xs text-slate-600 peer-checked:border-[#087A4B] peer-checked:bg-emerald-50 peer-checked:text-[#087A4B] transition-all cursor-pointer hover:border-slate-300 select-none">
                                    <span class="w-2 h-2 rounded-full {{ $s['dot'] }}"></span>
                                    {{ $s['val'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('status') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Visibilitas Publik --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-700 uppercase tracking-wider">
                        Tampil di Halaman Publik? <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-4 mt-1">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" id="is_public_toggle" class="sr-only peer" onchange="togglePublic(this)"
                                       {{ old('is_public', '1') == '1' ? 'checked' : '' }}>
                                <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-[#087A4B] transition-colors duration-200"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-6"></div>
                            </div>
                            <span id="public-label" class="text-sm font-bold {{ old('is_public', '1') == '1' ? 'text-[#087A4B]' : 'text-slate-500' }}">
                                {{ old('is_public', '1') == '1' ? 'Ya, Tampilkan' : 'Sembunyikan' }}
                            </span>
                        </label>
                        <input type="hidden" name="is_public" id="is_public" value="{{ old('is_public', '1') }}">
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">Jika aktif, profil anggota akan muncul di halaman publik</p>
                    @error('is_public') <p class="text-xs text-red-500 font-semibold">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
            <button type="reset" onclick="resetForm()"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-2xl transition-all active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset Form
            </button>
            <button type="submit" id="submit-btn"
                    class="inline-flex items-center gap-2 px-8 py-3 bg-[#087A4B] hover:bg-emerald-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-500/25 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Data Anggota
            </button>
        </div>

    </form>
</div>

<script>
    // â”€â”€ Banom Pill Selector â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function selectBanom(value) {
        document.getElementById('role-input').value = value;
        document.querySelectorAll('.banom-pill').forEach(function(pill) {
            const isSelected = pill.dataset.banom === value;
            const check = pill.querySelector('.banom-check');
            pill.classList.toggle('border-[#087A4B]', isSelected);
            pill.classList.toggle('bg-emerald-50', isSelected);
            pill.classList.toggle('border-slate-100', !isSelected);
            pill.classList.toggle('bg-slate-50/50', !isSelected);
            if (check) {
                check.classList.toggle('border-[#087A4B]', isSelected);
                check.classList.toggle('bg-[#087A4B]', isSelected);
                check.classList.toggle('border-slate-200', !isSelected);
                check.classList.toggle('bg-transparent', !isSelected);
            }
            // Text color
            const nameEl = pill.querySelector('span:nth-child(2)');
            if (nameEl) nameEl.classList.toggle('text-[#087A4B]', isSelected);
        });
    }

    // â”€â”€ Photo Preview â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function previewPhoto(input) {
        const preview = document.getElementById('photo-preview');
        const placeholder = document.getElementById('photo-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // â”€â”€ Toggle Publik â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function togglePublic(checkbox) {
        const hidden = document.getElementById('is_public');
        const label = document.getElementById('public-label');
        if (checkbox.checked) {
            hidden.value = '1';
            label.textContent = 'Ya, Tampilkan';
            label.classList.remove('text-slate-500');
            label.classList.add('text-[#087A4B]');
        } else {
            hidden.value = '0';
            label.textContent = 'Sembunyikan';
            label.classList.remove('text-[#087A4B]');
            label.classList.add('text-slate-500');
        }
    }

    // â”€â”€ Reset Form â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function resetForm() {
        document.getElementById('role-input').value = '';
        document.querySelectorAll('.banom-pill').forEach(function(pill) {
            const check = pill.querySelector('.banom-check');
            pill.classList.remove('border-[#087A4B]', 'bg-emerald-50');
            pill.classList.add('border-slate-100', 'bg-slate-50/50');
            if (check) {
                check.classList.remove('border-[#087A4B]', 'bg-[#087A4B]');
                check.classList.add('border-slate-200');
            }
        });
        // reset photo preview
        document.getElementById('photo-preview').classList.add('hidden');
        document.getElementById('photo-placeholder').classList.remove('hidden');
    }

    // â”€â”€ Loading state on submit â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    document.getElementById('anggota-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menyimpan...';
        btn.classList.add('opacity-75');
    });
</script>

@endsection