<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class LabourWageExport implements FromView, WithEvents
{
    protected $labours;
    protected $nep_year;
    protected $nep_month;
    protected $startDate;
    protected $endDate;
    protected $days;



    
    public function __construct($data)
    {
        $this->labours = $data['labours']->get();
        $this->nep_year = $data['nep_year'];
        $this->nep_month = $data['nep_month'];
        $this->startDate = $data['startDate'];
        $this->endDate = $data['endDate'];
        $this->days = $data['days'];

    }

    public function view(): View
    {
        return view('exports.labour-wage', [
            'labours' => $this->labours,
            'nep_year' => $this->nep_year,
            'nep_month' => $this->nep_month,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
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

                // Get the number of rows based on employees
                $maxRows = count($this->labours) + 3; // Include header rows and dynamic rows

                // Loop through all columns from 1 to the highest column index
                for ($colIndex = 1; $colIndex <= $highestColumnIndex; $colIndex++) {
                    // Convert index to column name
                    $column = Coordinate::stringFromColumnIndex($colIndex);

                    // Variable to store max length
                    $maxLength = 0;

                    // Loop through the rows starting from row 4
                    for ($rowIndex = 3; $rowIndex <= $maxRows; $rowIndex++) {
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

