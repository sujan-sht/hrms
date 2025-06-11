<?php

namespace App\Modules\Leave\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeaveOverViewReport implements FromView
{
    protected $models;
    protected $leaveTypeList;

    public function __construct($data)
    {
        $this->models = $data['employees'];
        $this->leaveTypeList = $data['leaveTypeArray'];
    }

    public function view(): View
    {
        return view('leave::exports.leave-overview-report', [
            'employees' => $this->models,
            'leaveTypeArray' => $this->leaveTypeList,
        ]);
    }
}
