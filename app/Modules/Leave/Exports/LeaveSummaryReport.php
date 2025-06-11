<?php

namespace App\Modules\Leave\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeaveSummaryReport implements FromView
{
    protected $models;
    protected $leaveTypeList;

    public function __construct($data)
    {
        $this->models = $data['employeeLeaveSummaries'];
        $this->leaveTypeList = $data['allLeaveTypes'];
    }

    public function view(): View
    {
        // dd($this->models->toArray());
        return view('leave::exports.leave-summary-report', [
            'employeeLeaveSummaries' => $this->models,
            'allLeaveTypes' => $this->leaveTypeList,
        ]);
    }
}
