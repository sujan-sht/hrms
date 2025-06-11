<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EmployeeDetailReport implements FromView, WithEvents
{
    protected $emps;

    public function __construct($data)
    {
        $this->emps = $data['employees'];
    }

    public function view(): View
    {
        return view('exports.employee-detail-report', [
            'emps' => $this->emps,
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

                // Loop through all columns from 1 to the highest column index
                for ($colIndex = 1; $colIndex <= $highestColumnIndex; $colIndex++) {
                    $column = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
