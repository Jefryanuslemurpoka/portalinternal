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

class LaporanNotarisDetailExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithCustomStartCell, WithEvents
{
    protected $laporan;
    protected $notarisKey;
    protected $notarisNama;

    public function __construct($laporan, $notarisKey, $notarisNama)
    {
        $this->laporan = $laporan;
        $this->notarisKey = $notarisKey;
        $this->notarisNama = $notarisNama;
    }

    public function collection()
    {
        $data = [];
        $totalOrder = 0;
        
        // Generate data per tanggal
        foreach ($this->laporan->details as $index => $detail) {
            $tanggal = Carbon::parse($detail->tanggal);
            $order = $detail->{$this->notarisKey};
            $totalOrder += $order;
            
            $data[] = [
                'no' => $index + 1,
                'tanggal' => $tanggal->format('d/m/Y'),
                'total_order' => $order,
            ];
        }

        // Tambah baris total
        $data[] = [
            'no' => '',
            'tanggal' => 'TOTAL',
            'total_order' => $totalOrder,
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return ['NO', 'TANGGAL', 'TOTAL ORDER'];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function title(): string
    {
        return substr($this->notarisNama, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            9 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '14B8A6']
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
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(15);

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
                $sheet->mergeCells('B1:C1');
                $sheet->setCellValue('B1', 'PT. PURI DIGITAL OUTPUT');
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Alamat
                $sheet->mergeCells('B2:C2');
                $sheet->setCellValue('B2', 'Ruko Florite Blok FR-46, Gading Serpong, Pakulonan Barat, Kelapa Dua, Kab. Tangerang, Banten, 15810');
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Kontak
                $sheet->mergeCells('B3:C3');
                $sheet->setCellValue('B3', 'Telp: +62-813-1010-672 | Email: support@purido.co.id | contact@purido.co.id');
                $sheet->getStyle('B3')->applyFromArray([
                    'font' => ['size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Garis bawah header
                $sheet->mergeCells('A4:C4');
                $sheet->getStyle('A4:C4')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Judul Laporan
                $sheet->mergeCells('A6:C6');
                $sheet->setCellValue('A6', 'LAPORAN ORDER PER NOTARIS');
                $sheet->getStyle('A6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Nama Notaris
                $sheet->mergeCells('A7:C7');
                $sheet->setCellValue('A7', 'Nama Notaris: ' . $this->notarisNama);
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 11, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Periode
                $sheet->mergeCells('A8:C8');
                $sheet->setCellValue('A8', 'Periode: ' . $this->laporan->nama_bulan);
                $sheet->getStyle('A8')->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Styling untuk data
                $lastRow = $sheet->getHighestRow();
                $dataRange = 'A9:C' . $lastRow;

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
                $sheet->getStyle('A10:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Zebra striping
                for ($row = 10; $row < $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F0FDFA']
                            ]
                        ]);
                    }
                }

                // Styling untuk baris TOTAL
                $sheet->getStyle('A' . $lastRow . ':C' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '99F6E4']
                    ]
                ]);

                // Footer
                $footerRow = $lastRow + 2;
                $sheet->mergeCells('A' . $footerRow . ':C' . $footerRow);
                $sheet->setCellValue('A' . $footerRow, 'Dicetak pada: ' . Carbon::now()->format('d F Y, H:i') . ' WIB');
                $sheet->getStyle('A' . $footerRow)->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // Tanda Tangan
                $signRow = $footerRow + 2;
                $sheet->setCellValue('C' . $signRow, 'Mengetahui,');
                $sheet->getStyle('C' . $signRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $signRow2 = $signRow + 4;
                $sheet->setCellValue('C' . $signRow2, 'Annisa NurQobila');
                $sheet->getStyle('C' . $signRow2)->applyFromArray([
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