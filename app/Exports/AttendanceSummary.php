<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class AttendanceSummary implements FromView, WithEvents
{
    protected $emps;
    protected $columns;
    protected $year;
    protected $month;

    public function __construct($emps,$columns, $year, $month)
    {
        $this->emps = $emps;
        $this->columns = $columns;
        $this->year = $year;
        $this->month = $month;
    }

    public function view(): View
    {
        return view('exports.monthly-attendance-summary', [
            'emps' => $this->emps,
            'columns' => $this->columns,
            'year' => $this->year,
            'month' => $this->month
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

                // Get the number of rows based on employees
                $maxRows = count($this->emps) + 4; // Include header rows and dynamic rows

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
