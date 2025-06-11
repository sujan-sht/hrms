<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EducationDetail;

class EducationDetailImport
{
    public static function import($array)
    {
        foreach ($array as $rowIndex => $data) {
            $rowNumber = $rowIndex + 2;

            $employee = Employee::where('employee_code', $data[1])->first();

            if (!$employee) {
                return [
                    'success' => false,
                    'message' => "Error at Row $rowNumber, Column 'Employee': Employee does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                ];
            }

            $educationDetailData = [
                'employee_id' => $employee->id,
                'type_of_institution' => $data[3] ?? null,
                'institution_name' =>  $data[4] ?? null,
                'affiliated_to' => $data[5] ?? null,
                'attended_from' => $data[6] ?? null,
                'attended_to' => $data[7] ?? null,
                'passed_year' => $data[8] ?? null,
                'level' => $data[9] ?? null,
                'note' => $data[10] ?? null,
                'course_name' => $data[11] ?? null,
                'score' => $data[12] ?? null,
                'division' => $data[13] ?? null,
                'faculty' => $data[14] ?? null,
                'specialization' => $data[15] ?? null,
                'university_name' => $data[16] ?? null,
                'equivalent_certificates' => !empty($data[17]) ? self::uploadEquivalentCertificates($data[17]) : null,
                'major_subject' => $data[18] ?? null,
                'degree_certificates' => !empty($data[19]) ? self::uploadDegreeCertificates($data[19]) : null,
            ];

            $success = EducationDetail::create($educationDetailData);

            if (!$success) {
                return [
                    'success' => false,
                    'message' => "Error at Row $rowNumber, Bulk upload could not complete. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                ];
            }
        }

        return [
            'success' => true,
            'message' => "Bulk Upload Completed Successfully!"
        ];
    }

    public static function uploadEquivalentCertificates($equivalent_certificates)
    {
        if ($equivalent_certificates && !empty($equivalent_certificates) && $equivalent_certificates) {
            $data['equivalent_certificates'] = [];
            foreach ($equivalent_certificates as $file) {
                $fileName = time() . rand(1, 99) . '.' . $file->extension();
                $file->move(public_path('uploads/education/'), $fileName);
                $data['equivalent_certificates'][] = $fileName;
            }
            $data['equivalent_certificates'] = json_encode($data['equivalent_certificates']);

            return $data;
        }
    }

    public static function uploadDegreeCertificates($degree_certificates)
    {
        if ($degree_certificates && !empty($degree_certificates) && $degree_certificates) {
            $data['degree_certificates'] = [];
            foreach ($degree_certificates as $file) {
                $fileName = time() . rand(1, 99) . '.' . $file->extension();
                $file->move(public_path('uploads/education/'), $fileName);
                $data['degree_certificates'][] = $fileName;
            }
            $data['degree_certificates'] = json_encode($data['degree_certificates']);

            return $data;
        }
    }
}