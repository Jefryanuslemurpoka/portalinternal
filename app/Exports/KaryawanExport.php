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

class KaryawanExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $karyawan;
    protected $divisi;
    protected $status;

    public function __construct($karyawan, $divisi = null, $status = null)
    {
        $this->karyawan = $karyawan;
        $this->divisi = $divisi;
        $this->status = $status;
    }

    public function collection()
    {
        return $this->karyawan->map(function($item, $key) {
            return [
                'no' => $key + 1,
                'nik' => $item->nik ?? '-',
                'nama' => $item->name,
                'divisi' => $item->divisi,
                'jabatan' => $item->jabatan,
                'no_hp' => $item->no_hp ?? '-',
                'status' => strtoupper($item->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NIK',
            'NAMA KARYAWAN',
            'DIVISI',
            'JABATAN',
            'NO. HP',
            'STATUS'
        ];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function title(): string
    {
        return 'Laporan Data Karyawan';
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
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(18);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(12);

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
                $sheet->setCellValue('A6', 'LAPORAN DATA KARYAWAN');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Filter Info
                $filterInfo = '';
                if ($this->divisi) {
                    $filterInfo = 'Divisi: ' . $this->divisi;
                } else {
                    $filterInfo = 'Semua Divisi';
                }
                
                if ($this->status) {
                    $filterInfo .= ' | Status: ' . ucfirst($this->status);
                }

                $sheet->mergeCells('A7:G7');
                $sheet->setCellValue('A7', $filterInfo);
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Styling untuk data
                $lastRow = $sheet->getHighestRow();
                $dataRange = 'A9:G' . $lastRow;

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
                $sheet->getStyle('F10:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Zebra striping
                for ($row = 10; $row <= $lastRow; $row++) {
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
                    if ($statusCell === 'AKTIF') {
                        $sheet->getStyle('G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D4EDDA']
                            ],
                            'font' => ['color' => ['rgb' => '155724'], 'bold' => true]
                        ]);
                    } elseif ($statusCell === 'NONAKTIF') {
                        $sheet->getStyle('G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8D7DA']
                            ],
                            'font' => ['color' => ['rgb' => '721C24'], 'bold' => true]
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

                // Tanda Tangan
                $signRow = $footerRow + 2;
                $sheet->mergeCells('E' . $signRow . ':G' . $signRow);
                $sheet->setCellValue('E' . $signRow, 'Mengetahui,');
                $sheet->getStyle('E' . $signRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $signRow2 = $signRow + 4;
                $sheet->mergeCells('E' . $signRow2 . ':G' . $signRow2);
                $sheet->setCellValue('E' . $signRow2, 'HRD Manager');
                $sheet->getStyle('E' . $signRow2)->applyFromArray([
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