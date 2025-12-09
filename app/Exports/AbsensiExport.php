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

class AbsensiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $absensi;
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $divisi;

    public function __construct($absensi, $tanggalMulai, $tanggalSelesai, $divisi = null)
    {
        $this->absensi = $absensi;
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->divisi = $divisi;
    }

    public function collection()
    {
        return $this->absensi->map(function($item, $key) {
            $tanggal = Carbon::parse($item->tanggal);
            $isLibur = $tanggal->dayOfWeek === 0;
            
            return [
                'no' => $key + 1,
                'tanggal' => $tanggal->format('d/m/Y'),
                'hari' => $tanggal->format('l'),
                'nama' => $item->user->name,
                'divisi' => $item->user->divisi,
                'jabatan' => $item->user->jabatan,
                'jam_masuk' => $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '-',
                'jam_keluar' => $item->jam_keluar ? Carbon::parse($item->jam_keluar)->format('H:i') : '-',
                'status' => $isLibur ? 'LIBUR' : 'HADIR',
                'keterangan' => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'HARI',
            'NAMA KARYAWAN',
            'DIVISI',
            'JABATAN',
            'JAM MASUK',
            'JAM KELUAR',
            'STATUS',
            'KETERANGAN'
        ];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function title(): string
    {
        return 'Laporan Absensi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            9 => [
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
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(12);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(12);
                $sheet->getColumnDimension('I')->setWidth(12);
                $sheet->getColumnDimension('J')->setWidth(20);

                // HEADER PERUSAHAAN
                // Logo (jika ada)
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
                $sheet->mergeCells('B1:J1');
                $sheet->setCellValue('B1', 'PT. PURI DIGITAL OUTPUT');
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Alamat
                $sheet->mergeCells('B2:J2');
                $sheet->setCellValue('B2', 'Ruko Florite Blok FR-46, Gading Serpong, Pakulonan Barat, Kelapa Dua, Kab. Tangerang, Banten, 15810');
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Kontak
                $sheet->mergeCells('B3:J3');
                $sheet->setCellValue('B3', 'Telp: +62-813-1010-672 | Email: support@purido.co.id | contact@purido.co.id');
                $sheet->getStyle('B3')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Garis bawah header
                $sheet->mergeCells('A4:J4');
                $sheet->getStyle('A4:J4')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Judul Laporan
                $sheet->mergeCells('A6:J6');
                $sheet->setCellValue('A6', 'LAPORAN ABSENSI KARYAWAN');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Periode
                $periode = 'Periode: ' . Carbon::parse($this->tanggalMulai)->format('d F Y') . ' s/d ' . Carbon::parse($this->tanggalSelesai)->format('d F Y');
                if ($this->divisi) {
                    $periode .= ' | Divisi: ' . $this->divisi;
                }
                $sheet->mergeCells('A7:J7');
                $sheet->setCellValue('A7', $periode);
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Styling untuk data
                $lastRow = $sheet->getHighestRow();
                $dataRange = 'A9:J' . $lastRow;

                // Border untuk semua data
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '999999']
                        ]
                    ]
                ]);

                // Alignment untuk kolom tertentu
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B10:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C10:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H10:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I10:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Zebra striping untuk baris genap
                for ($row = 10; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9F9F9']
                            ]
                        ]);
                    }

                    // Highlight LIBUR
                    $statusCell = $sheet->getCell('I' . $row)->getValue();
                    if ($statusCell === 'LIBUR') {
                        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'E3F2FD']
                            ]
                        ]);
                    }
                }

                // Footer
                $footerRow = $lastRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':J' . $footerRow);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak pada: ' . Carbon::now()->format('d F Y, H:i') . ' WIB');
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // Tanda Tangan
                $signRow = $footerRow + 2;
                $sheet->mergeCells('H' . $signRow . ':J' . $signRow);
                $sheet->setCellValue('H' . $signRow, 'Mengetahui,');
                $sheet->getStyle('H' . $signRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $signRow2 = $signRow + 4;
                $sheet->mergeCells('H' . $signRow2 . ':J' . $signRow2);
                $sheet->setCellValue('H' . $signRow2, 'HRD Manager');
                $sheet->getStyle('H' . $signRow2)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
            },
        ];
    }
}