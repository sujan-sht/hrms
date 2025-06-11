<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Modules\Onboarding\Entities\Applicant;

class ApplicantExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        $model = Applicant::query();
        return $model;
    }

    public function map($model): array
    {
        $return = [
            optional($model->mrfModel)->title,
            $model->full_name,
            $model->address,
            $model->city,
            $model->province,
            $model->mobile,
            $model->experience,
            $model->expected_salary,
            $model->skills
        ];

        return $return;
    }

    public function headings(): array
    {
        return [
            'MRF',
            'Full Name',
            'Address',
            'City',
            'Province',
            'Mobile',
            'Experience',
            'Expected Salary',
            'Skills'
        ];
    }
}
