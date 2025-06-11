<?php

namespace App\Modules\Grievance\Exports;

use App\Modules\Grievance\Entities\Grievance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class SubjectWiseReport implements FromView, WithTitle
{
    protected $subjectId;
    protected $grievances;

    public function __construct($subjectId, $grievances)
    {
        $this->subjectId = $subjectId;
        $this->grievances = $grievances;
    }

    public function view(): View
    {
        $columnList = Grievance::SUBJECT_FIELD;
        return view('grievance::export.subjectwise', [
            'subjectId' => $this->subjectId,
            'grievances' => $this->grievances,
            'columnList' => $columnList
        ]);
    }


    /**
     * @return string
     */
    public function title(): string
    {
        $subjectList = Grievance::SUBJECT_TYPE;

        return  $subjectList[$this->subjectId];
    }
}
