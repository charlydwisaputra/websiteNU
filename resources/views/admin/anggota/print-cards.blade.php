<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Anggota - NU Kalisari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green: #087A4B;
            --green-light: #10b981;
            --green-faded: #d1fae5;
            --gold: #d4a843;
            --gold-light: #fef3c7;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        /* ====== CONTROL PANEL ====== */
        .control-panel {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        .control-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .control-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .control-subtitle {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .control-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-select {
            padding: 8px 32px 8px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            color: #374151;
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .filter-select:hover {
            border-color: #087A4B;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(8, 122, 75, 0.08);
        }

        .filter-select:focus {
            border-color: #087A4B;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(8, 122, 75, 0.15);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: var(--green);
            color: white;
        }

        .btn-primary:hover { background: #065f3a; }

        .btn-outline {
            background: white;
            color: #374151;
            border: 1.5px solid #e2e8f0;
        }

        .btn-outline:hover { background: #f8fafc; }

        .badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            background: var(--green-faded);
            color: var(--green);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        /* ====== CARD GRID ====== */
        .cards-area {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        /* ====== MEMBER CARD ====== */
        .member-card {
            width: 340px;
            height: 200px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
        }

        /* Gradient top strip */
        .card-header-stripe {
            height: 6px;
            background: linear-gradient(90deg, var(--green) 0%, var(--green-light) 60%, var(--gold) 100%);
            flex-shrink: 0;
        }

        .card-body {
            display: flex;
            flex: 1;
            padding: 14px 16px;
            gap: 12px;
            align-items: center;
        }

        /* Left: Logo + QR */
        .card-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .card-org-logo {
            width: 44px;
            height: 44px;
            background: var(--green-faded);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 900;
            color: var(--green);
            text-align: center;
            line-height: 1.2;
            border: 1.5px solid #a7f3d0;
        }

        .card-qr-wrapper {
            width: 72px;
            height: 72px;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 3px;
        }

        .card-qr-wrapper canvas,
        .card-qr-wrapper img {
            width: 100% !important;
            height: 100% !important;
        }

        /* Right: Member Info */
        .card-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            height: 100%;
        }

        .card-org-name {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--green);
        }

        .card-member-name {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        .card-role-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            background: var(--green-faded);
            color: var(--green);
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            margin-top: 4px;
            border: 1px solid #a7f3d0;
            width: fit-content;
        }

        .card-info-rows {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-top: 8px;
        }

        .card-info-row {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            color: #64748b;
            font-weight: 500;
        }

        .card-info-row svg {
            flex-shrink: 0;
            color: #94a3b8;
        }

        .card-footer-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 16px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            flex-shrink: 0;
        }

        .card-footer-id {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.05em;
            font-family: 'Courier New', monospace;
        }

        .card-status-dot {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 9px;
            font-weight: 700;
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .dot-aktif { background: #10b981; }
        .dot-alumni { background: #3b82f6; }
        .dot-pending { background: #f59e0b; }
        .dot-nonaktif { background: #94a3b8; }

        .status-aktif { color: #059669; }
        .status-alumni { color: #3b82f6; }
        .status-pending { color: #d97706; }
        .status-nonaktif { color: #94a3b8; }

        /* ====== EMPTY STATE ====== */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            color: #94a3b8;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            background: #f1f5f9;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        /* ====== PRINT STYLES ====== */
        @media print {
            @page {
                size: A4;
                margin: 12mm 10mm;
            }

            body {
                background: white;
            }

            .control-panel,
            .no-print {
                display: none !important;
            }

            .cards-area {
                padding: 0;
                max-width: 100%;
            }

            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10mm;
                page-break-inside: auto;
            }

            .member-card {
                width: 100%;
                height: 56mm;
                box-shadow: none;
                border: 1px solid #e2e8f0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

{{-- Control Panel --}}
<div class="control-panel no-print">
    <div class="control-left">
        <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline" style="padding: 8px 12px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <div class="control-title">Cetak Kartu Anggota</div>
            <div class="control-subtitle">
                Menampilkan <span class="badge-count">{{ $anggotas->count() }}</span> anggota
            </div>
        </div>
    </div>
    <div class="control-right">
        <form class="filter-form" method="GET" action="{{ route('admin.anggota.print-cards') }}">
            <select name="role" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Banom</option>
                @foreach(['Muslimat','Fatayat','GP Ansor','IPNU','IPPNU','PMII'] as $opt)
                    <option value="{{ $opt }}" {{ request('role') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['Aktif','Nonaktif','Alumni','Pending'] as $opt)
                    <option value="{{ $opt }}" {{ request('status') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </form>
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>
</div>

{{-- Cards Grid --}}
<div class="cards-area">
    <div class="cards-grid">
        @forelse($anggotas as $anggota)
            @php
                $statusLower = strtolower($anggota->status ?? 'nonaktif');
                $statusLabel = ucfirst($statusLower);
                $dotClass    = 'dot-' . $statusLower;
                $statusClass = 'status-' . $statusLower;

                // Build unique QR data for each member
                $qrData = json_encode([
                    'id'     => $anggota->id,
                    'nama'   => $anggota->name,
                    'banom'  => $anggota->role,
                    'status' => $anggota->status,
                    'org'    => 'NU Kalisari',
                ]);
            @endphp

            <div class="member-card" id="card-{{ $anggota->id }}">
                {{-- Top color strip --}}
                <div class="card-header-stripe"></div>

                {{-- Main body --}}
                <div class="card-body">
                    {{-- Left column: logo + QR --}}
                    <div class="card-left">
                        <div class="card-org-logo">NU<br>KALISARI</div>
                        <div class="card-qr-wrapper" id="qr-{{ $anggota->id }}"></div>
                    </div>

                    {{-- Right column: member info --}}
                    <div class="card-right">
                        <div>
                            <div class="card-org-name">Nahdlatul Ulama Â· Kalisari</div>
                            <div class="card-member-name" title="{{ $anggota->name }}">{{ $anggota->name }}</div>
                            <div class="card-role-badge">{{ $anggota->role ?? '-' }}</div>

                            <div class="card-info-rows">
                                @if($anggota->wilayah)
                                    <div class="card-info-row">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $anggota->wilayah }}
                                    </div>
                                @endif
                                @if($anggota->joined_at)
                                    <div class="card-info-row">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Bergabung @tanggal($anggota->joined_at)
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer-strip">
                    <span class="card-footer-id">ID-{{ str_pad($anggota->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="card-status-dot {{ $statusClass }}">
                        <span class="dot {{ $dotClass }}"></span>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="28" height="28" fill="none" stroke="#cbd5e1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p style="font-weight:700;color:#475569;margin-bottom:6px;">Tidak ada anggota ditemukan</p>
                <p style="font-size:13px;color:#94a3b8;">Coba ubah filter atau tambahkan data anggota terlebih dahulu.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Generate QR per anggota --}}
@php
    $membersJson = json_encode($anggotas->map(function ($a) {
        return [
            'id'      => $a->id,
            'nama'    => $a->name,
            'banom'   => $a->role,
            'wilayah' => $a->wilayah,
            'status'  => $a->status,
            'joined'  => $a->joined_at,
            'org'     => 'NU Kalisari',
        ];
    })->values());
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const members = {!! $membersJson !!};

        members.forEach(function (m) {
            const container = document.getElementById('qr-' + m.id);
            if (!container) return;

            // Unique QR data per member
            const qrText = [
                'NU KALISARI',
                'ID: ' + String(m.id).padStart(5, '0'),
                'Nama: ' + m.nama,
                'Banom: ' + (m.banom || '-'),
                'Status: ' + (m.status || '-'),
                m.wilayah ? ('Wilayah: ' + m.wilayah) : '',
            ].filter(Boolean).join('\n');

            new QRCode(container, {
                text: qrText,
                width: 66,
                height: 66,
                colorDark: '#087A4B',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M,
            });
        });
    });
</script>

</body>
</html>
