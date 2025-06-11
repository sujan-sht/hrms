<?php

namespace App\Modules\Leave\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeaveMonthlyReport implements FromView
{
    protected $models;
    protected $monthLists;

    public function __construct($data)
    {
        $this->models = $data['employeeLeaveMonths'];
        $this->monthLists = $data['monthLists'];
    }

    public function view(): View
    {
        return view('leave::exports.leave-monthly-report', [
            'employeeLeaveMonths' => $this->models,
            'monthLists' => $this->monthLists,
        ]);
    }
}
