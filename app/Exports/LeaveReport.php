<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class LeaveReport implements FromView, WithEvents
{
    protected $models;
    protected $leaveTypeList;

    public function __construct($data)
    {
        $this->models = $data['models'];
        $this->leaveTypeList = $data['leaveTypeList'];
    }

    public function view(): View
    {
        return view('exports.leave-report', [
            'models' => $this->models,
            'leaveTypeList' => $this->leaveTypeList,
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
