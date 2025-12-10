<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;


class RekapAbsensiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $absensi;
    protected $user;
    protected $periode;
    protected $startDate;
    protected $endDate;
    protected $jamMasuk;
    protected $totalHadir;
    protected $tepatWaktu;
    protected $terlambat;

    public function __construct($absensi, $user, $periode, $startDate, $endDate, $jamMasuk, $totalHadir, $tepatWaktu, $terlambat)
    {
        $this->absensi = $absensi;
        $this->user = $user;
        $this->periode = $periode;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->jamMasuk = $jamMasuk;
        $this->totalHadir = $totalHadir;
        $this->tepatWaktu = $tepatWaktu;
        $this->terlambat = $terlambat;
    }

    public function collection()
    {
        return $this->absensi->map(function($item, $key) {
            $a = is_array($item) ? (object) $item : $item;
            $tanggal = $a->tanggal instanceof Carbon ? $a->tanggal : Carbon::parse($a->tanggal);
            $jamMasukKaryawan = $a->jam_masuk ?? null;
            $jamKeluarKaryawan = $a->jam_keluar ?? null;
            $status = $a->status ?? 'hadir';
            
            // Hitung durasi
            $durasi = '-';
            if ($jamMasukKaryawan && $jamKeluarKaryawan) {
                $jamMasukTime = Carbon::parse($jamMasukKaryawan);
                $jamKeluarTime = Carbon::parse($jamKeluarKaryawan);
                $diff = $jamMasukTime->diff($jamKeluarTime);
                $durasi = $diff->h . 'j ' . $diff->i . 'm';
            }
            
            // Tentukan status
            $statusText = '-';
            if ($tanggal->dayOfWeek == 0 || $status == 'libur') {
                $statusText = 'LIBUR';
            } elseif ($status == 'cuti') {
                $statusText = 'CUTI';
            } elseif ($status == 'izin') {
                $statusText = 'IZIN';
            } elseif ($jamMasukKaryawan) {
                $batasJam = Carbon::createFromFormat('H:i', $this->jamMasuk)->format('H:i:s');
                $statusText = strtotime($jamMasukKaryawan) <= strtotime($batasJam) ? 'TEPAT WAKTU' : 'TERLAMBAT';
            }
            
            return [
                'no' => $key + 1,
                'tanggal' => $tanggal->format('d/m/Y'),
                'hari' => $tanggal->format('l'),
                'jam_masuk' => $jamMasukKaryawan ? Carbon::parse($jamMasukKaryawan)->format('H:i') : '-',
                'jam_keluar' => $jamKeluarKaryawan ? Carbon::parse($jamKeluarKaryawan)->format('H:i') : '-',
                'durasi' => $durasi,
                'status' => $statusText,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'HARI',
            'JAM MASUK',
            'JAM KELUAR',
            'DURASI',
            'STATUS'
        ];
    }

    public function startCell(): string
    {
        return 'A12';
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            12 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0']
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
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set kolom width
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(13);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(12);
                $sheet->getColumnDimension('G')->setWidth(15);

                // Logo
                if (file_exists(public_path('images/logo.png'))) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Logo');
                    $drawing->setDescription('Logo Perusahaan');
                    $drawing->setPath(public_path('images/logo.png'));
                    $drawing->setHeight(60);
                    $drawing->setCoordinates('A1');
                    $drawing->setWorksheet($sheet);
                }

                // Nama Perusahaan
                $sheet->mergeCells('B1:G1');
                $sheet->setCellValue('B1', 'PT. PURI DIGITAL OUTPUT');
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Alamat
                $sheet->mergeCells('B2:G2');
                $sheet->setCellValue('B2', 'Ruko Florite Blok FR-46, Gading Serpong, Pakulonan Barat, Kelapa Dua, Kab. Tangerang, Banten, 15810');
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Kontak
                $sheet->mergeCells('B3:G3');
                $sheet->setCellValue('B3', 'Telp: +62-813-1010-672 | Email: support@purido.co.id | contact@purido.co.id');
                $sheet->getStyle('B3')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Garis bawah header
                $sheet->mergeCells('A4:G4');
                $sheet->getStyle('A4:G4')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Judul Laporan
                $sheet->mergeCells('A6:G6');
                $sheet->setCellValue('A6', 'REKAP ABSENSI KARYAWAN');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Info Karyawan & Periode
                $periodeTeks = strtoupper($this->periode);
                $sheet->mergeCells('A7:G7');
                $sheet->setCellValue('A7', 'Periode: ' . $periodeTeks . ' (' . $this->startDate->format('d F Y') . ' s/d ' . $this->endDate->format('d F Y') . ')');
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $sheet->mergeCells('A8:G8');
                $sheet->setCellValue('A8', 'Nama: ' . $this->user->name . ' | Divisi: ' . $this->user->divisi . ' | Jabatan: ' . $this->user->jabatan);
                $sheet->getStyle('A8')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Summary
                $sheet->mergeCells('A10:G10');
                $sheet->setCellValue('A10', 'Summary: Total Hadir: ' . $this->totalHadir . ' | Tepat Waktu: ' . $this->tepatWaktu . ' | Terlambat: ' . $this->terlambat);
                $sheet->getStyle('A10')->applyFromArray([
                    'font' => ['size' => 10, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0F0F0']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '999999']
                        ]
                    ]
                ]);

                // Styling untuk data
                $lastRow = $sheet->getHighestRow();
                $dataRange = 'A12:G' . $lastRow;

                // Border untuk semua data
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '999999']
                        ]
                    ]
                ]);

                // Alignment
                $sheet->getStyle('A13:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B13:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C13:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D13:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E13:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F13:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G13:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Zebra striping & Color status
                for ($row = 13; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9F9F9']
                            ]
                        ]);
                    }

                    // Color code untuk status
                    $statusCell = $sheet->getCell('G' . $row)->getValue();
                    if ($statusCell === 'TEPAT WAKTU') {
                        $sheet->getStyle('G' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '155724'], 'bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D4EDDA']
                            ]
                        ]);
                    } elseif ($statusCell === 'TERLAMBAT') {
                        $sheet->getStyle('G' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '721C24'], 'bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8D7DA']
                            ]
                        ]);
                    } elseif ($statusCell === 'LIBUR') {
                        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'E3F2FD']
                            ]
                        ]);
                    }
                }

                // Footer
                $footerRow = $lastRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':G' . $footerRow);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak pada: ' . Carbon::now()->format('d F Y, H:i') . ' WIB');
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);
            },
        ];
    }
}