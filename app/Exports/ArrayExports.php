<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ArrayExports implements
    FromArray,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [

            1 => [

                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ],
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '6F4E37'
                    ]
                ],

            ],

        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $highestColumn = $sheet->getHighestColumn();
                $highestRow    = $sheet->getHighestRow();

                // Bordes
                $sheet->getStyle(
                    "A1:{$highestColumn}{$highestRow}"
                )->applyFromArray([

                    'borders' => [

                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],

                    ],

                ]);

                // Centrar encabezado
                $sheet->getStyle(
                    "A1:{$highestColumn}1"
                )->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Congelar primera fila
                $sheet->freezePane('A2');

                // Filtros automáticos
                $sheet->setAutoFilter(
                    "A1:{$highestColumn}1"
                );

                // Buscar si la última fila contiene TOTAL
                $lastValue = strtoupper(
                    (string)$sheet->getCell("A{$highestRow}")->getValue()
                );

                if (str_contains($lastValue, 'TOTAL')) {

                    $sheet->getStyle(
                        "A{$highestRow}:{$highestColumn}{$highestRow}"
                    )->applyFromArray([

                        'font' => [
                            'bold' => true,
                        ],

                        'fill' => [

                            'fillType' => Fill::FILL_SOLID,

                            'startColor' => [
                                'rgb' => 'FFF2CC'
                            ]

                        ]

                    ]);

                }

            },

        ];
    }
}