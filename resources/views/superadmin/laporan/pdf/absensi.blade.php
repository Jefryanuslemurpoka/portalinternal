<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Karyawan - PT Puri Digital Output</title>
    <style>
        @page {
            margin: 15mm 15mm 20mm 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1a1a1a;
            background: #fff;
        }

        /* === KOP SURAT PROFESIONAL === */
        .kop-surat {
            position: relative;
            padding: 20px 0;
            margin-bottom: 15px;
        }

        .kop-header {
            display: table;
            width: 100%;
            border-bottom: 4px solid;
            border-image: linear-gradient(to right, #0d9488, #06b6d4) 1;
            padding-bottom: 15px;
        }

        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .logo-box {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #0d9488, #06b6d4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28pt;
            font-weight: bold;
        }

        .kop-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 20px;
        }

        .company-name {
            font-size: 20pt;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .company-tagline {
            font-size: 9pt;
            color: #666;
            font-style: italic;
            margin-bottom: 8px;
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
            margin: 15px 0;
            font-size: 9pt;
            color: #666;
        }

        .nomor-surat strong {
            color: #0d9488;
        }

        /* === JUDUL DOKUMEN === */
        .judul-dokumen {
            text-align: center;
            margin: 25px 0 20px 0;
            padding: 15px 0;
            background: linear-gradient(to bottom, #f0fdfa, #ffffff);
            border-left: 4px solid #0d9488;
            border-right: 4px solid #06b6d4;
        }

        .judul-dokumen h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .judul-dokumen .periode {
            font-size: 10pt;
            color: #555;
            font-weight: normal;
        }

        /* === INFO BOX === */
        .info-container {
            margin: 20px 0;
            background: #f0fdfa;
            border-left: 5px solid #0d9488;
            padding: 15px 20px;
            border-radius: 0 8px 8px 0;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 180px;
            padding: 5px 0;
            font-weight: 600;
            color: #0d9488;
            font-size: 9pt;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
            color: #333;
            font-size: 9pt;
        }

        /* === TABEL DATA === */
        .table-container {
            margin: 20px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        table.data-table thead {
            background: linear-gradient(to right, #0d9488, #06b6d4);
            color: white;
        }

        table.data-table thead th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        table.data-table tbody tr:hover {
            background-color: #f0fdfa;
        }

        table.data-table tbody td {
            padding: 10px 8px;
            color: #374151;
            vertical-align: middle;
        }

        table.data-table tbody td:first-child {
            text-align: center;
            font-weight: 600;
            color: #6b7280;
        }

        /* === STATUS BADGE === */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 7.5pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        /* === RINGKASAN === */
        .summary-box {
            margin: 20px 0;
            padding: 15px;
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
            padding: 10px;
        }

        .summary-value {
            font-size: 20pt;
            font-weight: bold;
            color: #0d9488;
            display: block;
        }

        .summary-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            margin-top: 5px;
            display: block;
        }

        /* === TANDA TANGAN === */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-container {
            width: 280px;
            float: right;
            text-align: center;
            padding: 15px;
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
            margin-bottom: 50px;
        }

        .signature-line {
            border-top: 2px solid #0d9488;
            padding-top: 10px;
            margin: 0 20px;
        }

        .signature-name {
            font-size: 11pt;
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
            margin-top: 60px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            clear: both;
        }

        .footer-text {
            font-size: 7.5pt;
            color: #9ca3af;
            line-height: 1.6;
        }

        .footer-watermark {
            margin-top: 8px;
            font-size: 7pt;
            color: #d1d5db;
        }

        /* === NO DATA === */
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }

        .no-data-icon {
            font-size: 48pt;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        /* === UTILITIES === */
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
        <div class="kop-header">
            <div class="kop-logo">
                <div class="logo-box">P</div>
            </div>
            <div class="kop-info">
                <div class="company-name">PT Puri Digital Output</div>
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

    <!-- JUDUL DOKUMEN -->
    <div class="judul-dokumen">
        <h1>Laporan Absensi Karyawan</h1>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->isoFormat('D MMMM Y') }} 
            s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->isoFormat('D MMMM Y') }}
        </div>
    </div>

    <!-- INFO DOKUMEN -->
    <div class="info-container">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">📅 Periode Laporan</div>
                <div class="info-value">: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}</div>
            </div>
            @if($divisi)
            <div class="info-row">
                <div class="info-label">🏢 Divisi</div>
                <div class="info-value">: {{ $divisi }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">📊 Total Data</div>
                <div class="info-value">: {{ $absensi->count() }} record absensi</div>
            </div>
            <div class="info-row">
                <div class="info-label">🖨️ Dicetak Tanggal</div>
                <div class="info-value">: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</div>
            </div>
        </div>
    </div>

    <!-- RINGKASAN STATISTIK -->
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

    <!-- TABEL DATA -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="11%">Tanggal</th>
                    <th width="20%">Nama Karyawan</th>
                    <th width="12%">Divisi</th>
                    <th width="15%">Jabatan</th>
                    <th width="10%">Status</th>
                    <th width="11%">Jam Masuk</th>
                    <th width="12%">Jam Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-bold">{{ $item->user->name }}</td>
                    <td>{{ $item->user->divisi }}</td>
                    <td>{{ $item->user->jabatan }}</td>
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
                    <td colspan="8">
                        <div class="no-data">
                            <div class="no-data-icon">📭</div>
                            <div>Tidak ada data absensi untuk periode yang dipilih</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- TANDA TANGAN -->
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
        <div class="footer-text">
            Dokumen ini digenerate secara otomatis oleh Sistem HRMS PT Puri Digital Output<br>
            dan merupakan dokumen sah tanpa memerlukan tanda tangan basah
        </div>
        <div class="footer-watermark">
            © {{ date('Y') }} PT Puri Digital Output. All Rights Reserved. | Confidential Document
        </div>
    </div>

</body>
</html>