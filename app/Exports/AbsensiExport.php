<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;
    protected $rowNumber = 0;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Query data berdasarkan filter
     */
    public function collection()
    {
        $query = Absensi::with('user');

        // Filter tanggal
        if (!empty($this->filters['tanggal_mulai']) && !empty($this->filters['tanggal_selesai'])) {
            $query->whereBetween('tanggal', [$this->filters['tanggal_mulai'], $this->filters['tanggal_selesai']]);
        } elseif (!empty($this->filters['tanggal'])) {
            $query->whereDate('tanggal', $this->filters['tanggal']);
        }

        // Filter divisi
        if (!empty($this->filters['divisi'])) {
            $query->whereHas('user', function($q) {
                $q->where('divisi', $this->filters['divisi']);
            });
        }

        // Filter karyawan
        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        return $query->orderBy('tanggal', 'desc')
                    ->orderBy('jam_masuk', 'desc')
                    ->get();
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Hari',
            'Nama Karyawan',
            'Email',
            'Divisi',
            'Jam Masuk',
            'Jam Keluar',
            'Durasi Kerja',
            'Status Keterlambatan',
            'Lokasi',
            'Status Absensi'
        ];
    }

    /**
     * Mapping data ke kolom
     */
    public function map($absensi): array
    {
        $this->rowNumber++;

        // Hitung durasi kerja
        $durasiKerja = '-';
        if ($absensi->jam_masuk && $absensi->jam_keluar) {
            $jamMasuk = Carbon::parse($absensi->jam_masuk);
            $jamKeluar = Carbon::parse($absensi->jam_keluar);
            $durasi = $jamMasuk->diff($jamKeluar);
            $durasiKerja = $durasi->h . ' jam ' . $durasi->i . ' menit';
        }

        // Status keterlambatan
        $statusTerlambat = '-';
        if ($absensi->jam_masuk) {
            $jamMasuk = strtotime($absensi->jam_masuk);
            $batasWaktu = strtotime('08:00:00');
            if ($jamMasuk <= $batasWaktu) {
                $statusTerlambat = 'Tepat Waktu';
            } else {
                $selisih = $jamMasuk - $batasWaktu;
                $menit = floor($selisih / 60);
                $statusTerlambat = 'Terlambat ' . $menit . ' menit';
            }
        }

        // Status absensi
        $statusAbsensi = 'Tidak Lengkap';
        if ($absensi->jam_masuk && $absensi->jam_keluar) {
            $statusAbsensi = 'Lengkap';
        } elseif ($absensi->jam_masuk) {
            $statusAbsensi = 'Check-in Saja';
        }

        return [
            $this->rowNumber,
            $absensi->tanggal->format('d/m/Y'),
            $absensi->tanggal->locale('id')->isoFormat('dddd'),
            $absensi->user->name,
            $absensi->user->email,
            $absensi->user->divisi,
            $absensi->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '-',
            $absensi->jam_keluar ? Carbon::parse($absensi->jam_keluar)->format('H:i') : '-',
            $durasiKerja,
            $statusTerlambat,
            $absensi->lokasi ?: '-',
            $statusAbsensi
        ];
    }

    /**
     * Styling Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '14b8a6'] // Teal color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style semua data
        $lastRow = $this->rowNumber + 1;
        $sheet->getStyle('A2:L' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Center align untuk kolom No
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 12,  // Tanggal
            'C' => 12,  // Hari
            'D' => 25,  // Nama
            'E' => 25,  // Email
            'F' => 15,  // Divisi
            'G' => 12,  // Jam Masuk
            'H' => 12,  // Jam Keluar
            'I' => 15,  // Durasi
            'J' => 20,  // Status Terlambat
            'K' => 30,  // Lokasi
            'L' => 15,  // Status Absensi
        ];
    }

    /**
     * Nama sheet
     */
    public function title(): string
    {
        return 'Data Absensi';
    }
}