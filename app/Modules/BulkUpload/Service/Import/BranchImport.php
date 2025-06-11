<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\FamilyDetail;
use App\Modules\Organization\Entities\Organization;

class BranchImport
{
    public static function import($array)
    {
        // dd($array, 'sumit');
        $tempData = null;
        try {
            foreach ($array as $data) {
                $organization = Organization::where('name', $data[1])->first();
                $branch = Branch::where('name', $data[2])->first();
                if ($branch) {
                    continue;
                }
                if ($organization) {
                    $tempData[] = [
                        'organization_id' => $organization->id,
                        'name' => $data[2],
                        'branche_code' => $data[3]
                    ];
                }
            }
            if ($tempData && $tempData != null && is_array($tempData)) {
                Branch::insert($tempData);
                toastr()->success('Bulk Upload succesfully');
            } else {
                toastr()->success('Already Uploaded');
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
