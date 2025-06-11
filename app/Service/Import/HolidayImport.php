<?php

namespace App\Service\Import;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Entities\Branch;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Organization\Entities\Organization;
use Carbon\Carbon;

class HolidayImport implements ImportInterface
{

    public function import($array)
    {

        try {
            $organization_id = null;
            $organizations = array_map('strtolower', Organization::all()->pluck('name')->toArray());
            $branchs = array_map('strtolower', Branch::all()->pluck('name')->toArray());
            foreach ($array as $data) {
                if (!empty($data['A'])) {
                    if ($data['A'] == 'All') {
                        $apply = 11;
                        $organization_id = null;
                        $branch_id = null;
                    } else {
                        $organization_id = Organization::where('name', $data['A'])->first()->id ?? null;
                    }
                    $branch_id = Branch::where('name', $data['B'])->first()->id ?? null;

                    $fiscalYear = FiscalYearSetup::where('fiscal_year', $data['G'])->first();
                    if ($fiscalYear) {
                        $fiscalId = $fiscalYear->id;
                    }
                    // gender check
                    if ($data['C'] == 'Male') {
                        $gender = 3;
                    } elseif ($data['C'] == 'Female') {
                        $gender = 2;
                    } else {
                        $gender = 1;
                    }
                    // religion check
                    if ($data['E'] == 'Hinduism') {
                        $religion = 2;
                    } elseif ($data['E'] == 'Buddhism') {
                        $religion = 3;
                    } elseif ($data['E'] == 'Christianity') {
                        $religion = 4;
                    } elseif ($data['E'] == 'Islam') {
                        $religion = 5;
                    } elseif ($data['E'] == 'Kirat') {
                        $religion = 6;
                    } else {
                        $religion = 1;
                    }


                    //
                    if ($data['F'] == 'Yes') {
                        $festival = 11;
                    } else {
                        $festival = 10;
                    }

                    $inputData = [
                        'organization_id' => $organization_id,
                        'branch_id' => $branch_id,
                        'gender_type' => $gender,
                        'religion_type' => $religion,
                        'is_festival' => $festival,
                        'apply_for_all' => $apply ?? null,
                        'fiscal_year_id' => $fiscalId,
                        'calendar_type' => 1,
                        'status' => 11,
                        'group_id' => Holiday::max('id') + 1
                    ];

                    $holiday = Holiday::create($inputData);
                    if ($holiday) {
                        $inputDate = $data['H'];
                        $nepDate = $inputDate;
                        $exploded_date = explode('-', $inputDate);
                        $engDate = date_converter()->nep_to_eng_convert($nepDate);
                        $detailInput = [
                            'holiday_id' => $holiday->id,
                            'sub_title' => $data['D'] ?? null,
                            'nep_date' => $nepDate,
                            'eng_date' => $engDate
                        ];
                        HolidayDetail::create($detailInput);
                    }
                }
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            throw $e;
            toastr()->error('Something went wrong!!');
        }
    }
}