<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UnitExport implements FromView
{

    protected $unitModels;

    public function __construct($data)
    {
        $this->unitModels = $data['unitModels'];
    }

    public function view(): View
    {
        return view('exports.unit-report', [
            'unitModels' => $this->unitModels,

        ]);
    }
}
