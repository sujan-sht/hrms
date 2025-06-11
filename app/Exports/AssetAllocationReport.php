<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AssetAllocationReport implements FromView
{
    protected $allocations;

    public function __construct($data)
    {
        $this->allocations = $data['allocations'];
    }

    public function view(): View
    {
        return view('exports.asset-allocation-report', [
            'allocations' => $this->allocations,
        ]);
    }
}
