<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $slip->slip_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #0d9488;
        }
        .header {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .slip-number {
            background: white;
            color: #0d9488;
            padding: 8px 15px;
            display: inline-block;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px 30px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-box h3 {
            color: #0d9488;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .info-label {
            color: #6b7280;
            font-size: 11px;
        }
        .info-value {
            font-weight: bold;
            color: #111827;
        }
        .attendance-section {
            padding: 20px 30px;
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }
        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .attendance-box {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }
        .attendance-box .label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .attendance-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #0d9488;
        }
        .salary-section {
            padding: 20px 30px;
        }
        .salary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .salary-box h3 {
            font-size: 14px;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .salary-box.income h3 {
            color: #059669;
            border-bottom: 2px solid #059669;
        }
        .salary-box.deduction h3 {
            color: #dc2626;
            border-bottom: 2px solid #dc2626;
        }
        .salary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .salary-item:last-child {
            border-bottom: none;
        }
        .salary-item .name {
            color: #374151;
        }
        .salary-item .amount {
            font-weight: bold;
            color: #111827;
        }
        .salary-total {
            background: #f9fafb;
            padding: 12px;
            margin-top: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .salary-total.income {
            background: #d1fae5;
            color: #059669;
        }
        .salary-total.deduction {
            background: #fee2e2;
            color: #dc2626;
        }
        .summary-section {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .summary-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        .summary-amount {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .breakdown {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .breakdown-item {
            text-align: center;
        }
        .breakdown-label {
            font-size: 11px;
            opacity: 0.8;
        }
        .breakdown-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }
        .footer {
            padding: 20px 30px;
            background: #f9fafb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 30px;
            text-align: center;
        }
        .signature-box {
            border-top: 1px solid #e5e7eb;
            padding-top: 80px;
        }
        .signature-name {
            font-weight: bold;
            color: #111827;
        }
        .signature-title {
            font-size: 10px;
            color: #6b7280;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #0d9488; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SLIP GAJI</h1>
            <p>PT. NAMA PERUSAHAAN ANDA</p>
            <div class="slip-number">{{ $slip->slip_number }}</div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <h3>📋 INFORMASI KARYAWAN</h3>
                <div class="info-row">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $slip->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIK</span>
                    <span class="info-value">{{ $slip->user->nik }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Divisi</span>
                    <span class="info-value">{{ $slip->user->divisi ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">{{ $slip->user->jabatan ?? '-' }}</span>
                </div>
            </div>

            <div class="info-box">
                <h3>📅 PERIODE PENGGAJIAN</h3>
                <div class="info-row">
                    <span class="info-label">Bulan/Tahun</span>
                    <span class="info-value">{{ $slip->bulan_name }} {{ $slip->tahun }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Periode</span>
                    <span class="info-value">{{ $slip->periode_start->format('d/m/Y') }} - {{ $slip->periode_end->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Cetak</span>
                    <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Attendance Section -->
        <div class="attendance-section">
            <h3 style="color: #0d9488; margin-bottom: 10px;">📊 DATA KEHADIRAN</h3>
            <div class="attendance-grid">
                <div class="attendance-box">
                    <div class="label">Hari Kerja</div>
                    <div class="value">{{ $slip->hari_kerja }}</div>
                </div>
                <div class="attendance-box">
                    <div class="label">Hadir</div>
                    <div class="value" style="color: #059669;">{{ $slip->hari_hadir }}</div>
                </div>
                <div class="attendance-box">
                    <div class="label">Izin</div>
                    <div class="value" style="color: #3b82f6;">{{ $slip->hari_izin }}</div>
                </div>
                <div class="attendance-box">
                    <div class="label">Sakit</div>
                    <div class="value" style="color: #eab308;">{{ $slip->hari_sakit }}</div>
                </div>
                <div class="attendance-box">
                    <div class="label">Alpha</div>
                    <div class="value" style="color: #dc2626;">{{ $slip->hari_alpha }}</div>
                </div>
                <div class="attendance-box">
                    <div class="label">Lembur (Jam)</div>
                    <div class="value" style="color: #f97316;">{{ $slip->jam_lembur }}</div>
                </div>
            </div>
        </div>

        <!-- Salary Section -->
        <div class="salary-section">
            <div class="salary-grid">
                <!-- Income -->
                <div class="salary-box income">
                    <h3>💰 PENDAPATAN</h3>
                    <div class="salary-item">
                        <span class="name">Gaji Pokok</span>
                        <span class="amount">Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}</span>
                    </div>
                    @if($slip->tunjangan_detail)
                        @foreach($slip->tunjangan_detail as $tunjangan)
                            <div class="salary-item">
                                <span class="name">{{ $tunjangan['nama'] }}</span>
                                <span class="amount">Rp {{ number_format($tunjangan['nilai'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @endif
                    @if($slip->upah_lembur > 0)
                        <div class="salary-item">
                            <span class="name">Upah Lembur ({{ $slip->jam_lembur }} jam)</span>
                            <span class="amount">Rp {{ number_format($slip->upah_lembur, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="salary-total income">
                        <span>Total Pendapatan</span>
                        <span>Rp {{ number_format($slip->gaji_pokok + $slip->total_tunjangan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="salary-box deduction">
                    <h3>➖ POTONGAN</h3>
                    @if($slip->potongan_detail && count($slip->potongan_detail) > 0)
                        @foreach($slip->potongan_detail as $potongan)
                            <div class="salary-item">
                                <span class="name">{{ $potongan['nama'] }}</span>
                                <span class="amount">Rp {{ number_format($potongan['nilai'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 20px; color: #9ca3af;">
                            Tidak ada potongan
                        </div>
                    @endif
                    <div class="salary-total deduction">
                        <span>Total Potongan</span>
                        <span>Rp {{ number_format($slip->total_potongan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-label">TOTAL GAJI BERSIH (TAKE HOME PAY)</div>
            <div class="summary-amount">Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</div>
            <div class="breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-label">Gaji Kotor</div>
                    <div class="breakdown-value">Rp {{ number_format($slip->gaji_kotor, 0, ',', '.') }}</div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-label">Total Potongan</div>
                    <div class="breakdown-value">Rp {{ number_format($slip->total_potongan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-name">{{ $slip->user->name }}</div>
                <div class="signature-title">Karyawan</div>
            </div>
            <div class="signature-box">
                <div class="signature-name">HRD Department</div>
                <div class="signature-title">Authorized Signature</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Catatan:</strong> Slip gaji ini dibuat secara otomatis oleh sistem. Untuk pertanyaan lebih lanjut, silakan hubungi HRD.</p>
            <p style="margin-top: 10px;">Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB</p>
        </div>
    </div>
</body>
</html>