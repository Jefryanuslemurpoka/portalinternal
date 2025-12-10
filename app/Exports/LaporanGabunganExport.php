<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class LaporanGabunganExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithCustomStartCell, WithEvents
{
    protected $laporan;

    public function __construct($laporan)
    {
        $this->laporan = $laporan;
    }

    public function collection()
    {
        $data = [];
        
        // Generate data per tanggal
        foreach ($this->laporan->details as $index => $detail) {
            $tanggal = Carbon::parse($detail->tanggal);
            
            $data[] = [
                'no' => $index + 1,
                'tanggal' => $tanggal->format('d/m/Y'),
                'gamal' => $detail->gamal,
                'eny' => $detail->eny,
                'nyoman' => $detail->nyoman,
                'otty' => $detail->otty,
                'kartika' => $detail->kartika,
                'retno' => $detail->retno,
                'neltje' => $detail->neltje,
                'total' => $detail->gamal + $detail->eny + $detail->nyoman + $detail->otty + $detail->kartika + $detail->retno + $detail->neltje,
            ];
        }

        // Tambah baris total
        $data[] = [
            'no' => '',
            'tanggal' => 'TOTAL',
            'gamal' => $this->laporan->total_gamal,
            'eny' => $this->laporan->total_eny,
            'nyoman' => $this->laporan->total_nyoman,
            'otty' => $this->laporan->total_otty,
            'kartika' => $this->laporan->total_kartika,
            'retno' => $this->laporan->total_retno,
            'neltje' => $this->laporan->total_neltje,
            'total' => $this->laporan->grand_total,
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return ['NO', 'TANGGAL', 'GAMAL', 'ENY', 'NYOMAN', 'OTTY', 'KARTIKA', 'RETNO', 'NELTJE', 'TOTAL'];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function title(): string
    {
        return 'Laporan Gabungan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            9 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D9488']
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
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(10);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(10);
                $sheet->getColumnDimension('G')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(10);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('J')->setWidth(10);

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
                $sheet->setCellValue('A6', 'LAPORAN ORDER NOTARIS');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Periode
                $sheet->mergeCells('A7:J7');
                $sheet->setCellValue('A7', 'Periode: ' . $this->laporan->nama_bulan);
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

                // Alignment untuk kolom
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B10:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C10:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Zebra striping
                for ($row = 10; $row < $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0FDFA']
                            ]
                        ]);
                    }
                }

                // Styling untuk baris TOTAL
                $sheet->getStyle('A' . $lastRow . ':J' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'CCFBF1']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

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
                $sheet->setCellValue('H' . $signRow2, 'Annisa NurQobila');
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