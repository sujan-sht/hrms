<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RawAttendanceReport implements FromView, WithEvents
{
    protected $attendances;

    public function __construct($data)
    {
        $this->attendances = $data['attendances'];
    }

    public function view(): View
    {
        return view('exports.raw-attendance-report', [
            'attendances' => $this->attendances,
        ]);
    }

       /**
     * Register events to modify the sheet after it's created
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Get the highest column (e.g., 'AF')
                $highestColumn = $sheet->getHighestColumn();

                // Convert the highest column label (e.g., 'AF') into an integer index
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                // Create the header range based on the highest column
                $headerRange = 'A1:' . Coordinate::stringFromColumnIndex($highestColumnIndex) . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => '808080'], // Replace 'FFFF00' with your desired hex color (Yellow in this case)
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'], // Text color (Black in this case)
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Border style (thin)
                        'color' => ['argb' => 'FFFFFF'], // Border color (black in this case)
                    ],
                ],
                ]);
               // Get the highest column (e.g., 'AF')
               $highestColumn = $sheet->getHighestColumn();

               // Convert the highest column label (e.g., 'AF') into an integer index
               $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

               // Loop through all columns from 1 to the highest column index
               for ($colIndex = 1; $colIndex <= $highestColumnIndex; $colIndex++) {
                   $column = Coordinate::stringFromColumnIndex($colIndex);
                   $sheet->getColumnDimension($column)->setAutoSize(true);
               }
            },
        ];
    }
}
