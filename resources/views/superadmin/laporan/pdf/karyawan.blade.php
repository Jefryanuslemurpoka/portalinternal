<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Karyawan - PT Puri Digital Output</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #1a1a1a;
            background: #fff;
        }

        /* === KOP SURAT === */
        .kop-surat {
            border-bottom: 3px solid #0d9488;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: table;
            width: 100%;
        }

        .kop-logo {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }

        .logo-box {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #0d9488, #06b6d4);
            border-radius: 6px;
            text-align: center;
            line-height: 55px;
            color: white;
            font-size: 24pt;
            font-weight: bold;
        }

        .kop-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }

        .company-tagline {
            font-size: 8pt;
            color: #666;
            font-style: italic;
            margin-bottom: 4px;
        }

        .company-address {
            font-size: 7.5pt;
            color: #555;
            line-height: 1.3;
        }

        .company-contact {
            font-size: 7.5pt;
            color: #0d9488;
            margin-top: 2px;
        }

        /* === NOMOR SURAT === */
        .nomor-surat {
            text-align: right;
            margin: 8px 0;
            font-size: 8pt;
            color: #666;
        }

        /* === JUDUL === */
        .judul-dokumen {
            text-align: center;
            margin: 12px 0 10px 0;
            padding: 10px 0;
            background: linear-gradient(to bottom, #f0fdfa, #ffffff);
            border-left: 3px solid #0d9488;
            border-right: 3px solid #06b6d4;
        }

        .judul-dokumen h1 {
            font-size: 13pt;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 1.5px;
            margin-bottom: 5px;
        }

        .judul-dokumen .periode {
            font-size: 8.5pt;
            color: #555;
        }

        /* === INFO BOX === */
        .info-box {
            background: #f0fdfa;
            border-left: 4px solid #0d9488;
            padding: 8px 12px;
            margin: 10px 0;
            font-size: 8pt;
            display: table;
            width: 100%;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 2px 0;
        }

        .info-box .label {
            width: 140px;
            font-weight: 600;
            color: #0d9488;
        }

        /* === SUMMARY === */
        .summary-box {
            margin: 10px 0;
            padding: 10px;
            background: linear-gradient(to right, #f0fdfa, #ecfeff);
            border-radius: 6px;
            border: 2px solid #0d9488;
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 5px;
        }

        .summary-value {
            font-size: 16pt;
            font-weight: bold;
            color: #0d9488;
            display: block;
        }

        .summary-label {
            font-size: 7pt;
            color: #666;
            text-transform: uppercase;
            margin-top: 3px;
            display: block;
        }

        /* === TABLE === */
        .table-container {
            margin: 12px 0;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        table.data-table thead {
            background: linear-gradient(to right, #0d9488, #06b6d4);
            color: white;
        }

        table.data-table thead th {
            padding: 8px 5px;
            text-align: left;
            font-weight: 600;
            font-size: 6.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }

        table.data-table thead th:last-child {
            border-right: none;
        }

        table.data-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table.data-table tbody td {
            padding: 6px 5px;
            color: #374151;
            vertical-align: middle;
        }

        table.data-table tbody td:first-child {
            text-align: center;
            font-weight: 600;
            color: #6b7280;
        }

        /* === BADGE === */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 6.5pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-aktif {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #059669;
        }

        .status-nonaktif {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #dc2626;
        }

        /* === SIGNATURE === */
        .signature-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-container {
            width: 220px;
            float: right;
            text-align: center;
            padding: 10px;
            border: 1.5px solid #e5e7eb;
            border-radius: 6px;
            background: #fafafa;
        }

        .signature-location {
            font-size: 8pt;
            color: #666;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 8.5pt;
            font-weight: 600;
            color: #0d9488;
            margin-bottom: 35px;
        }

        .signature-line {
            border-top: 2px solid #0d9488;
            padding-top: 6px;
            margin: 0 15px;
        }

        .signature-name {
            font-size: 9pt;
            font-weight: bold;
            color: #1a1a1a;
        }

        .signature-nik {
            font-size: 7pt;
            color: #666;
            margin-top: 2px;
        }

        /* === FOOTER === */
        .document-footer {
            margin-top: 35px;
            padding-top: 10px;
            border-top: 1.5px solid #e5e7eb;
            text-align: center;
            clear: both;
            font-size: 6.5pt;
            color: #9ca3af;
            line-height: 1.4;
        }

        .footer-watermark {
            margin-top: 5px;
            font-size: 6pt;
            color: #d1d5db;
        }

        /* === NO DATA === */
        .no-data {
            text-align: center;
            padding: 30px 15px;
            color: #9ca3af;
            font-size: 8pt;
        }

        .text-bold {
            font-weight: 600;
            color: #1a1a1a;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Button Print & Close -->
    <div style="position: fixed; top: 10px; right: 10px; z-index: 9999; background: white; padding: 10px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);" class="no-print">
        <button onclick="window.print()" style="background: linear-gradient(to right, #0d9488, #06b6d4); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-right: 10px; font-weight: 600;">
            🖨️ Cetak PDF
        </button>
        <button onclick="window.close()" style="background: #6b7280; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600;">
            ✖️ Tutup
        </button>
    </div>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-logo">
            <div class="logo-box">P</div>
        </div>
        <div class="kop-info">
            <div class="company-name">PT PURI DIGITAL OUTPUT</div>
            <div class="company-tagline">Excellence in Human Resources Management</div>
            <div class="company-address">
                Jl. Raya Serpong No. 123, Tangerang Selatan, Banten 15310, Indonesia
            </div>
            <div class="company-contact">
                ☎ (021) 5588-9900 | ✉ hrd@puridigitaloutput.com | 🌐 www.puridigitaloutput.com
            </div>
        </div>
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-surat">
        <strong>No:</strong> {{ sprintf('%03d', rand(1, 999)) }}/HRD-LPR/PDO/{{ date('m/Y') }}
    </div>

    <!-- JUDUL -->
    <div class="judul-dokumen">
        <h1>LAPORAN DATA KARYAWAN</h1>
        <div class="periode">
            @if($divisi) Divisi: {{ $divisi }} @else Semua Divisi @endif
            @if($status) | Status: {{ ucfirst($status) }} @endif
        </div>
    </div>

    <!-- INFO -->
    <div class="info-box">
        <table>
            <tr>
                <td class="label">🏢 Filter Divisi</td>
                <td>: @if($divisi) {{ $divisi }} @else Semua Divisi @endif</td>
                @if($status)
                <td class="label" style="padding-left: 20px;">📋 Status</td>
                <td>: {{ ucfirst($status) }}</td>
                @endif
            </tr>
            <tr>
                <td class="label">👥 Total Karyawan</td>
                <td>: {{ $karyawan->count() }} karyawan</td>
                <td class="label" style="padding-left: 20px;">🖨️ Dicetak</td>
                <td>: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</td>
            </tr>
        </table>
    </div>

    <!-- SUMMARY -->
    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-value">{{ $karyawan->count() }}</span>
            <span class="summary-label">Total Karyawan</span>
        </div>
        <div class="summary-item">
            <span class="summary-value">{{ $karyawan->where('status', 'aktif')->count() }}</span>
            <span class="summary-label">Aktif</span>
        </div>
        <div class="summary-item">
            <span class="summary-value">{{ $karyawan->where('status', 'nonaktif')->count() }}</span>
            <span class="summary-label">Non-Aktif</span>
        </div>
        <div class="summary-item">
            <span class="summary-value">{{ $karyawan->pluck('divisi')->unique()->count() }}</span>
            <span class="summary-label">Divisi</span>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">NIK</th>
                    <th width="21%">Nama Karyawan</th>
                    <th width="23%">Email</th>
                    <th width="11%">Divisi</th>
                    <th width="14%">Jabatan</th>
                    <th width="11%">No. HP</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawan as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="text-bold">{{ $item->nik ?? '-' }}</td>
                    <td class="text-bold">{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->divisi }}</td>
                    <td>{{ $item->jabatan }}</td>
                    <td>{{ $item->no_hp ?? '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ $item->status }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="no-data">
                        📭 Tidak ada data karyawan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- SIGNATURE -->
    <div class="signature-section">
        <div class="signature-container">
            <div class="signature-location">Tangerang Selatan, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
            <div class="signature-title">HRD Manager</div>
            <div class="signature-line">
                <div class="signature-name">Euis Nurjanah, SE</div>
                <div class="signature-nik">NIK: 1234567890</div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="document-footer">
        Dokumen ini digenerate secara otomatis oleh Sistem HRMS PT Puri Digital Output<br>
        dan merupakan dokumen sah tanpa memerlukan tanda tangan basah
        <div class="footer-watermark">
            © {{ date('Y') }} PT Puri Digital Output. All Rights Reserved. | Confidential Document
        </div>
    </div>

</body>
</html>