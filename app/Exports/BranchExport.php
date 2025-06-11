<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BranchExport implements FromView
{

    protected $branchModels;

    public function __construct($data)
    {
        $this->branchModels = $data['branchModels'];
    }

    public function view(): View
    {
        return view('exports.branch-report', [
            'branchModels' => $this->branchModels,

        ]);
    }
}
