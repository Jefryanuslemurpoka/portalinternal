<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Cuti & Izin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 15mm 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #000;
            margin: 20px;
        }

        .header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .company-info {
            width: 100%;
            margin-bottom: 5px;
        }

        .company-info table {
            width: 100%;
            border: none;
        }

        .company-info td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 80px;
            text-align: left;
        }

        .logo-cell img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .company-text {
            text-align: center;
            padding: 0 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .company-address {
            font-size: 8px;
            margin-bottom: 3px;
            color: #333;
            line-height: 1.3;
        }

        .company-contact {
            font-size: 8px;
            color: #333;
        }

        .report-title {
            text-align: center;
            margin: 15px 0 10px 0;
        }

        .report-title h2 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-info {
            font-size: 9px;
            color: #333;
        }

        .summary-section {
            margin: 12px 0;
            padding: 8px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .summary-section table {
            width: 100%;
            border: none;
        }

        .summary-section td {
            border: none;
            padding: 4px 8px;
            font-size: 8px;
        }

        .summary-label {
            font-weight: 600;
            color: #333;
        }

        .summary-value {
            color: #000;
            font-weight: 600;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table thead th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #000;
            font-size: 8px;
            text-transform: uppercase;
        }

        .data-table tbody td {
            padding: 5px 4px;
            border: 1px solid #999;
            font-size: 8px;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .badge-disetujui {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }

        .badge-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #dc3545;
        }

        .badge-jenis {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #17a2b8;
            font-size: 7px;
            padding: 2px 5px;
        }

        .name-cell {
            font-weight: 600;
        }

        .footer {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .print-info {
            font-size: 8px;
            color: #666;
            margin-bottom: 10px;
        }

        .signature-area {
            margin-top: 15px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 180px;
        }

        .signature-text {
            font-size: 9px;
            margin-bottom: 50px;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            display: inline-block;
            min-width: 160px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <table>
                <tr>
                    <td class="logo-cell">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
                        @endif
                    </td>
                    <td class="company-text">
                        <div class="company-name">PT. PURI DIGITAL OUTPUT</div>
                        <div class="company-address">
                            Ruko Florite Blok FR-46, Gading Serpong, Pakulonan Barat, Kelapa Dua,<br>
                            Kab. Tangerang, Banten, 15810
                        </div>
                        <div class="company-contact">
                            Telp: +62-813-1010-672 | Email: support@purido.co.id | contact@purido.co.id
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">
        <h2>Laporan Cuti & Izin Karyawan</h2>
        <div class="report-info">
            Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d F Y') }}
            @if($status)
                | Status: {{ ucfirst($status) }}
            @endif
        </div>
    </div>

    <!-- Summary -->
    <div class="summary-section">
        <table>
            <tr>
                <td class="summary-label">Total Pengajuan:</td>
                <td class="summary-value">{{ $cutiIzin->count() }} pengajuan</td>
                <td class="summary-label">Pending:</td>
                <td class="summary-value">{{ $cutiIzin->where('status', 'pending')->count() }}</td>
            </tr>
            <tr>
                <td class="summary-label">Disetujui:</td>
                <td class="summary-value">{{ $cutiIzin->where('status', 'disetujui')->count() }}</td>
                <td class="summary-label">Ditolak:</td>
                <td class="summary-value">{{ $cutiIzin->where('status', 'ditolak')->count() }}</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 20%;">NAMA KARYAWAN</th>
                <th style="width: 13%;">DIVISI</th>
                <th style="width: 10%;">JENIS</th>
                <th style="width: 11%;">TGL MULAI</th>
                <th style="width: 11%;">TGL SELESAI</th>
                <th style="width: 8%;">DURASI</th>
                <th style="width: 11%;">STATUS</th>
                <th style="width: 12%;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cutiIzin as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-left name-cell">{{ $item->user->name }}</td>
                    <td class="text-left">{{ $item->user->divisi }}</td>
                    <td class="text-center">
                        <span class="badge-jenis">{{ strtoupper($item->jenis) }}</span>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $item->durasi }} hari</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->status }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                    <td class="text-left" style="font-size: 7px;">
                        {{ $item->keterangan ? Str::limit($item->keterangan, 35) : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">
                        Tidak ada data cuti/izin
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="print-info">
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB
        </div>
        
        <div class="signature-area">
            <div class="signature-box">
                <div class="signature-text">Mengetahui,</div>
                <div class="signature-name">HRD Manager</div>
            </div>
        </div>
    </div>

</body>
</html>