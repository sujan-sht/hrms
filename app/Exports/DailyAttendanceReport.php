<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DailyAttendanceReport implements FromView, WithEvents
{
    protected $emps;
    protected $days;
    protected $year;
    protected $month;
    protected $log_type;

    public function __construct($data)
    {
        $this->emps = $data['emps'];
        $this->days = $data['days'];
        $this->year = $data['year'];
        $this->month = $data['month'];
        $this->log_type = $data['log_type'];
    }

    public function view(): View
    {
        // dd($this->log_type);
        return view('exports.daily-attendance-report', [
            'emps' => $this->emps,
            'days' => $this->days,
            'year' => $this->year,
            'month' => $this->month,
            'log_type' => $this->log_type

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
                $maxRows = $this->days + 4; // Include header rows and dynamic rows

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
