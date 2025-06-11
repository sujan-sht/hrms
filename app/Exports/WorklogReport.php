<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WorklogReport implements FromView
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function view(): View
    {
        return view('exports.worklog-report', [
            'worklogs' => $this->items,
        ]);
    }
}
