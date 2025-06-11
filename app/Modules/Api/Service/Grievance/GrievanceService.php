<?php

namespace App\Modules\Api\Service\Grievance;

class GrievanceService
{
    public function remainAnonymous() {
        $list = [
            '11' => 'Yes',
            '10' => 'No'
        ];
        return $list;
    }

    public function subjectTypeList() {
        $list = [1 => 'Grievances', 2 => 'Disciplinary Action', 3 => 'Suggestions', 4 => 'Others'];
        return $list;
    }
    public function misconductTypeList() {
        $list = [1 => 'Theft', 2 => 'Harassment', 3 => 'Legal Compliance', 4 => 'Others'];
        return $list;
    }

    public function isWitness() {
        $list = [
            '1' => 'No',
            '2' => 'Yes',
        ];
        return $list;
    }
}
