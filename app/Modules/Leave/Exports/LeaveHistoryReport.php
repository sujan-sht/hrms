<?php

namespace App\Modules\Leave\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeaveHistoryReport implements FromView
{
    protected $leaves;

    public function __construct($data)
    {
        $this->leaves = $data['leaves'];
    }

    public function view(): View
    {
        return view('exports.leave-history-report', [
            'leaves' => $this->leaves,
        ]);
    }
}
