{{-- Partial Kartu Anggota CR80 (85.6mm x 54mm) --}}
@php
    $statusLower = strtolower($anggota->status ?? 'aktif');
    $isAktif = in_array($statusLower, ['aktif', 'admin', 'administrator']);
    
    // Inisial avatar selalu 2 huruf kapital (AD, AN, BS, dll)
    $rawName = trim($anggota->name ?? '');
    $words = array_values(array_filter(preg_split('/[\s\-_]+/', $rawName)));
    if (count($words) >= 2) {
        $initials = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
    } elseif (count($words) === 1 && mb_strlen($words[0]) >= 2) {
        $initials = strtoupper(mb_substr($words[0], 0, 2));
    } elseif (count($words) === 1 && mb_strlen($words[0]) === 1) {
        $initials = strtoupper($words[0] . 'N');
    } else {
        $initials = 'NU';
    }

    // Jabatan & Aturan Tampil Badge Banom
    $jabatan = trim($anggota->position ?: 'Anggota');
    $banomSingkatan = trim($anggota->banom?->singkatan ?? '');
    $banomNamaLengkap = trim($anggota->banom?->nama ?? '');
    $banomDisplay = $banomSingkatan ?: ($banomNamaLengkap ?: trim($anggota->role ?? ''));

    $showBanom = false;
    if (!empty($banomDisplay)) {
        $posLower = strtolower($jabatan);
        $banomDisplayLower = strtolower($banomDisplay);
        $banomFullLower = strtolower($banomNamaLengkap);
        $banomShortLower = strtolower($banomSingkatan);

        $isSame = ($posLower === $banomDisplayLower) 
            || ($banomFullLower && $posLower === $banomFullLower)
            || ($banomShortLower && $posLower === $banomShortLower);

        $isContained = ($banomShortLower && str_contains($posLower, $banomShortLower))
            || ($banomFullLower && str_contains($posLower, $banomFullLower))
            || str_contains($posLower, $banomDisplayLower)
            || str_contains($banomDisplayLower, $posLower);

        if (!$isSame && !$isContained) {
            $showBanom = true;
        }
    }

    $verifyUrl = route('kartu.verify', $anggota->kartu_token ?: $anggota->id);
@endphp

<div class="member-card bg-white rounded-xl overflow-hidden shadow-sm relative flex flex-col border border-slate-200 text-slate-900 select-none print:shadow-none print:border-slate-300 w-full"
     data-member-id="{{ $anggota->id }}"
     data-verify-url="{{ $verifyUrl }}"
     style="aspect-ratio: 85.6 / 54; background: #ffffff !important; color: #0f172a !important;">

    {{-- Top Stripe Hijau NU --}}
    <div class="w-full shrink-0 print:!h-[3mm]"
         style="height: 3.2mm; background: linear-gradient(90deg, #087A4B 0%, #10b981 65%, #d4a843 100%);"></div>

    {{-- Card Content --}}
    <div class="p-2 sm:p-2.5 flex-1 flex flex-col justify-between overflow-hidden">
        
        {{-- Header Kartu --}}
        <div class="flex items-center justify-between pb-1 border-b border-slate-100 shrink-0 gap-1">
            <div class="flex items-center gap-1.5 min-w-0">
                <img src="{{ asset('images/brand/logo-nu.png') }}"
                     alt="Lambang Nahdlatul Ulama"
                     width="18"
                     height="18"
                     loading="eager"
                     class="w-4 h-4 sm:w-4.5 sm:h-4.5 object-contain shrink-0"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-4 h-4 sm:w-4.5 sm:h-4.5 rounded items-center justify-center font-bold text-[8px] sm:text-[8.5px] text-white shrink-0 hidden"
                     style="background: #087A4B; display:none;">NU</div>
                <div class="truncate text-[8px] sm:text-[8.5px] font-extrabold uppercase tracking-wider" style="color: #087A4B;">
                    NAHDLATUL ULAMA &middot; KALISARI
                </div>
            </div>
            <div class="font-mono text-[7.5px] sm:text-[8.5px] font-bold text-slate-400 shrink-0">
                ID-{{ str_pad($anggota->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        {{-- Body: Foto & Identitas & QR --}}
        <div class="flex items-center gap-2 sm:gap-2.5 flex-1 py-1 min-h-0">
            {{-- Foto Profil (Rasio 3:4, Object-cover) --}}
            <div class="relative shrink-0 rounded-md overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center"
                 style="width: 22mm; height: 29.3mm; aspect-ratio: 3 / 4; max-width: 26%;">
                @if(!empty($anggota->photo))
                    <img src="{{ asset('storage/' . $anggota->photo) }}"
                         alt="{{ $anggota->name }}"
                         loading="eager"
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full flex flex-col items-center justify-center text-white font-extrabold text-xs sm:text-sm tracking-wider hidden"
                         style="background: #087A4B;">
                        {{ $initials }}
                    </div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-white font-extrabold text-xs sm:text-sm tracking-wider"
                         style="background: #087A4B;">
                        {{ $initials }}
                    </div>
                @endif
            </div>

            {{-- Kolom Detail Identitas & QR --}}
            <div class="flex-1 flex flex-col justify-between h-full min-w-0">
                <div>
                    {{-- Nama (Lebih besar, tebal, maks 2 baris) --}}
                    <h4 class="font-serif font-extrabold text-[12px] sm:text-[14px] leading-tight text-slate-900 line-clamp-2"
                        title="{{ $anggota->name }}" style="color: #0f172a !important;">
                        {{ $anggota->name }}
                    </h4>

                    {{-- Badges: Jabatan selalu tampil; Banom tampil jika berbeda dari jabatan --}}
                    <div class="flex items-center gap-1 mt-1 flex-wrap">
                        <span class="inline-block px-1.5 py-0.5 rounded text-[7px] sm:text-[8px] font-bold"
                              style="background: rgba(8, 122, 75, 0.1); color: #087A4B;">{{ $jabatan }}</span>
                        @if($showBanom)
                        <span class="inline-block px-1.5 py-0.5 rounded text-[7px] sm:text-[8px] font-semibold text-slate-600 bg-slate-100 border border-slate-200">{{ $banomDisplay }}</span>
                        @endif
                    </div>

                    {{-- Informasi Wilayah & Tanggal --}}
                    <div class="mt-1 space-y-0.5 text-[7px] sm:text-[8px] text-slate-500">
                        @if($anggota->wilayah)
                        <div class="flex items-center gap-1 truncate">
                            <svg class="w-2.5 h-2.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="truncate">{{ $anggota->wilayah }}</span>
                        </div>
                        @endif
                        @if($anggota->joined_at)
                        <div class="flex items-center gap-1 truncate">
                            <svg class="w-2.5 h-2.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="truncate">Bergabung @tanggal($anggota->joined_at)</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Baris Bawah: Status & QR Container --}}
                <div class="flex items-end justify-between pt-0.5 gap-1">
                    <div class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isAktif ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        <span class="text-[7.5px] sm:text-[8px] font-bold {{ $isAktif ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ ucfirst($statusLower) }}
                        </span>
                    </div>

                    {{-- QR Code Box --}}
                    <div class="flex flex-col items-center shrink-0">
                        <div class="qr-canvas-box bg-white p-0.5 rounded border border-slate-200 overflow-hidden"
                             id="qrcode-box-{{ $anggota->id }}"
                             data-qr-target="{{ $verifyUrl }}"
                             style="width: 14mm; height: 14mm; min-width: 46px; min-height: 46px;"></div>
                        <span class="text-[6px] font-mono font-semibold text-slate-400 mt-0.5 leading-none">Verifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
