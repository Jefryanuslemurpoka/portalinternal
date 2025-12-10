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

class CutiIzinExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $cutiIzin;
    protected $tanggalMulai;
    protected $tanggalSelesai;
    protected $status;

    public function __construct($cutiIzin, $tanggalMulai, $tanggalSelesai, $status = null)
    {
        $this->cutiIzin = $cutiIzin;
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->status = $status;
    }

    public function collection()
    {
        return $this->cutiIzin->map(function($item, $key) {
            return [
                'no' => $key + 1,
                'nama' => $item->user->name,
                'divisi' => $item->user->divisi,
                'jenis' => strtoupper($item->jenis),
                'tanggal_mulai' => Carbon::parse($item->tanggal_mulai)->format('d/m/Y'),
                'tanggal_selesai' => Carbon::parse($item->tanggal_selesai)->format('d/m/Y'),
                'durasi' => $item->durasi . ' hari',
                'status' => strtoupper($item->status),
                'keterangan' => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA KARYAWAN',
            'DIVISI',
            'JENIS',
            'TGL MULAI',
            'TGL SELESAI',
            'DURASI',
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
        return 'Laporan Cuti & Izin';
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
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(12);
                $sheet->getColumnDimension('G')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(12);
                $sheet->getColumnDimension('I')->setWidth(30);

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
                $sheet->mergeCells('B1:I1');
                $sheet->setCellValue('B1', 'PT. PURI DIGITAL OUTPUT');
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Alamat
                $sheet->mergeCells('B2:I2');
                $sheet->setCellValue('B2', 'Ruko Florite Blok FR-46, Gading Serpong, Pakulonan Barat, Kelapa Dua, Kab. Tangerang, Banten, 15810');
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Kontak
                $sheet->mergeCells('B3:I3');
                $sheet->setCellValue('B3', 'Telp: +62-813-1010-672 | Email: support@purido.co.id | contact@purido.co.id');
                $sheet->getStyle('B3')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Garis bawah header
                $sheet->mergeCells('A4:I4');
                $sheet->getStyle('A4:I4')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Judul Laporan
                $sheet->mergeCells('A6:I6');
                $sheet->setCellValue('A6', 'LAPORAN CUTI & IZIN KARYAWAN');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Periode
                $periode = 'Periode: ' . Carbon::parse($this->tanggalMulai)->format('d F Y') . ' s/d ' . Carbon::parse($this->tanggalSelesai)->format('d F Y');
                if ($this->status) {
                    $periode .= ' | Status: ' . ucfirst($this->status);
                }
                $sheet->mergeCells('A7:I7');
                $sheet->setCellValue('A7', $periode);
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Styling untuk data
                $lastRow = $sheet->getHighestRow();
                $dataRange = 'A9:I' . $lastRow;

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
                $sheet->getStyle('D10:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E10:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F10:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H10:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Zebra striping
                for ($row = 10; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F9F9F9']
                            ]
                        ]);
                    }

                    // Color code untuk status
                    $statusCell = $sheet->getCell('H' . $row)->getValue();
                    if ($statusCell === 'PENDING') {
                        $sheet->getStyle('H' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFF3CD']
                            ],
                            'font' => ['color' => ['rgb' => '856404']]
                        ]);
                    } elseif ($statusCell === 'DISETUJUI') {
                        $sheet->getStyle('H' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D4EDDA']
                            ],
                            'font' => ['color' => ['rgb' => '155724']]
                        ]);
                    } elseif ($statusCell === 'DITOLAK') {
                        $sheet->getStyle('H' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8D7DA']
                            ],
                            'font' => ['color' => ['rgb' => '721C24']]
                        ]);
                    }
                }

                // Footer
                $footerRow = $lastRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':I' . $footerRow);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak pada: ' . Carbon::now()->format('d F Y, H:i') . ' WIB');
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // Tanda Tangan
                $signRow = $footerRow + 2;
                $sheet->mergeCells('G' . $signRow . ':I' . $signRow);
                $sheet->setCellValue('G' . $signRow, 'Mengetahui,');
                $sheet->getStyle('G' . $signRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $signRow2 = $signRow + 4;
                $sheet->mergeCells('G' . $signRow2 . ':I' . $signRow2);
                $sheet->setCellValue('G' . $signRow2, 'HRD Manager');
                $sheet->getStyle('G' . $signRow2)->applyFromArray([
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