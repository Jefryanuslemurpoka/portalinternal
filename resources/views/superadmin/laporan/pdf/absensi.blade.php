<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Karyawan - PT Puri Digital Output</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1a1a1a;
            background: #fff;
        }

        /* === KOP SURAT === */
        .kop-surat {
            border-bottom: 3px solid #0d9488;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .kop-content {
            display: table;
            width: 100%;
        }

        .kop-logo {
            display: table-cell;
            width: 65px;
            vertical-align: middle;
        }

        .logo-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0d9488, #06b6d4);
            border-radius: 8px;
            text-align: center;
            line-height: 60px;
            color: white;
            font-size: 26pt;
            font-weight: bold;
        }

        .kop-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .company-tagline {
            font-size: 8.5pt;
            color: #666;
            font-style: italic;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 8pt;
            color: #555;
            line-height: 1.4;
        }

        .company-contact {
            font-size: 8pt;
            color: #0d9488;
            margin-top: 3px;
        }

        /* === NOMOR SURAT === */
        .nomor-surat {
            text-align: right;
            margin: 10px 0;
            font-size: 8.5pt;
            color: #666;
        }

        /* === JUDUL === */
        .judul-dokumen {
            text-align: center;
            margin: 15px 0;
            padding: 12px 0;
            background: linear-gradient(to bottom, #f0fdfa, #ffffff);
            border-left: 4px solid #0d9488;
            border-right: 4px solid #06b6d4;
        }

        .judul-dokumen h1 {
            font-size: 14pt;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }

        .judul-dokumen .periode {
            font-size: 9pt;
            color: #555;
        }

        /* === INFO BOX === */
        .info-section {
            margin: 15px 0;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .info-item {
            display: table-cell;
            width: 50%;
            padding: 8px 12px;
            background: #f0fdfa;
            border-left: 4px solid #0d9488;
            font-size: 8.5pt;
        }

        .info-item:last-child {
            padding-left: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #0d9488;
            display: inline-block;
            min-width: 120px;
        }

        .info-value {
            color: #333;
        }

        /* === SUMMARY === */
        .summary-box {
            margin: 15px 0;
            padding: 12px;
            background: linear-gradient(to right, #f0fdfa, #ecfeff);
            border-radius: 8px;
            border: 2px solid #0d9488;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 8px;
        }

        .summary-value {
            font-size: 20pt;
            font-weight: bold;
            color: #0d9488;
            display: block;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 7.5pt;
            color: #666;
            text-transform: uppercase;
            display: block;
        }

        /* === TABLE === */
        .table-container {
            margin: 15px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        table.data-table thead {
            background: linear-gradient(to right, #0d9488, #06b6d4);
            color: white;
        }

        table.data-table thead th {
            padding: 10px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.data-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table.data-table tbody td {
            padding: 8px 6px;
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
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 7pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-hadir {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #059669;
        }

        .status-izin, .status-sakit {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .status-alpha {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #dc2626;
        }

        /* === SIGNATURE === */
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-container {
            width: 250px;
            float: right;
            text-align: center;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: #fafafa;
        }

        .signature-location {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 10pt;
            font-weight: 600;
            color: #0d9488;
            margin-bottom: 45px;
        }

        .signature-line {
            border-top: 2px solid #0d9488;
            padding-top: 8px;
            margin: 0 20px;
        }

        .signature-name {
            font-size: 10pt;
            font-weight: bold;
            color: #1a1a1a;
        }

        .signature-nik {
            font-size: 8pt;
            color: #666;
            margin-top: 3px;
        }

        /* === FOOTER === */
        .document-footer {
            margin-top: 50px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            clear: both;
            font-size: 7pt;
            color: #9ca3af;
            line-height: 1.5;
        }

        .footer-watermark {
            margin-top: 6px;
            font-size: 6.5pt;
            color: #d1d5db;
        }

        /* === NO DATA === */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
            font-size: 9pt;
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
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-content">
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
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-surat">
        <strong>No:</strong> {{ sprintf('%03d', rand(1, 999)) }}/HRD-LPR/PDO/{{ date('m/Y') }}
    </div>

    <!-- JUDUL -->
    <div class="judul-dokumen">
        <h1>LAPORAN ABSENSI KARYAWAN</h1>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->isoFormat('D MMMM Y') }} 
            s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->isoFormat('D MMMM Y') }}
        </div>
    </div>

    <!-- INFO -->
    <div class="info-section">
        <div class="info-row">
            <div class="info-item">
                <span class="info-label">Periode Laporan</span>
                <span class="info-value">: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Data</span>
                <span class="info-value">: {{ $absensi->count() }} record</span>
            </div>
        </div>
        <div class="info-row">
            @if($divisi)
            <div class="info-item">
                <span class="info-label">Divisi</span>
                <span class="info-value">: {{ $divisi }}</span>
            </div>
            @endif
            <div class="info-item">
                <span class="info-label">Dicetak</span>
                <span class="info-value">: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</span>
            </div>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-value">{{ $absensi->count() }}</span>
                <span class="summary-label">Total Data</span>
            </div>
            <div class="summary-item">
                <span class="summary-value">{{ $absensi->where('status', 'hadir')->count() }}</span>
                <span class="summary-label">Hadir</span>
            </div>
            <div class="summary-item">
                <span class="summary-value">{{ $absensi->whereIn('status', ['izin', 'sakit'])->count() }}</span>
                <span class="summary-label">Izin/Sakit</span>
            </div>
            <div class="summary-item">
                <span class="summary-value">{{ $absensi->where('status', 'alpha')->count() }}</span>
                <span class="summary-label">Alpha</span>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Tanggal</th>
                    <th width="25%">Nama Karyawan</th>
                    <th width="15%">Divisi</th>
                    <th width="15%">Status</th>
                    <th width="14%">Jam Masuk</th>
                    <th width="14%">Jam Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-bold">{{ $item->user->name }}</td>
                    <td>{{ $item->user->divisi }}</td>
                    <td>
                        <span class="status-badge status-{{ $item->status }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $item->jam_masuk ?? '-' }}</td>
                    <td class="text-center">{{ $item->jam_keluar ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">
                        Tidak ada data absensi untuk periode yang dipilih
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

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

</body>
</html>