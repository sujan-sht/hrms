<?php

namespace App\Modules\Grievance\Exports;

use App\Modules\Grievance\Entities\Grievance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GrievanceReport implements WithMultipleSheets
{
    protected $models;
    protected $monthLists;

    public function __construct($data)
    {
        $this->models = $data['grievance'];
    }



    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->models as $key => $value) {
            $sheets[] = new SubjectWiseReport($key, $value);
        }


        return $sheets;
    }
}
