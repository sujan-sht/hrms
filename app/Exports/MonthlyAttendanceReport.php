<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MonthlyAttendanceReport implements FromView, WithEvents
{
    protected $emps;
    protected $year;
    protected $month;
    protected $days;

    public function __construct($data)
    {
        $this->emps = $data['emps'];
        $this->year = $data['year'];
        $this->month = $data['month'];
        $this->days = $data['days'];
    }

    public function view(): View
    {
        return view('exports.monthly-attendance-report', [
            'emps' => $this->emps,
            'year' => $this->year,
            'month' => $this->month,
            'days' => $this->days,
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
                $headerRange = 'A5:' . Coordinate::stringFromColumnIndex($highestColumnIndex) . '6';
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
                // Get the number of rows based on employees
                $maxRows = count($this->emps) + 6; // Include header rows and dynamic rows

                // Loop through all columns from 1 to the highest column index
                for ($colIndex = 1; $colIndex <= $highestColumnIndex; $colIndex++) {
                    // Convert index to column name
                    $column = Coordinate::stringFromColumnIndex($colIndex);

                    // Variable to store max length
                    $maxLength = 0;

                    // Loop through the rows starting from row 4
                    for ($rowIndex = 4; $rowIndex <= $maxRows; $rowIndex++) {
                        // Get the value in the current cell (skip first 3 rows)
                        $cellValue = $sheet->getCell($column.$rowIndex)->getValue();
                        
                        // Calculate max length for the current column
                        if ($cellValue !== null) {
                            $maxLength = max($maxLength, strlen((string) $cellValue));
                        }
                    }

                    // Set the column width based on the max length (add some padding for readability)
                    $sheet->getColumnDimension($column)->setWidth($maxLength + 2); // Add padding to width
                }
            },
        ];
    }
}
