<?php

namespace App\Service\Import;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Darbandi;
use App\Modules\Setting\Entities\Designation;

class DarbandiImport implements ImportInterface
{

    public function import($array)
    {
        $dropdown = new Dropdown();
        $designations = array_map('strtolower', $dropdown->getDesignations()->toArray());
        $organizations = array_map('strtolower', Organization::all()->pluck('name')->toArray());
        try {
            foreach ($array as $data) {
                if (in_array(strtolower($data[0]), $organizations)) {
                    $organization_id = Organization::where('name', $data[0])->first()->id;
                } else {
                    $organization_create = Organization::create([
                        'name' => $data[0]
                    ]);
                    if ($organization_create) {
                        $organization_id = $organization_create->id;
                    }
                }
                if (in_array(strtolower($data[1]), $designations)) {
                    // $designation_id = Dropdown::where('dropValue', $data[1])->first()->id;
                    $designation_id = Designation::where('title', $data[1])->first()->id;
                } 
                // else {
                //     $dropdown_create = $dropdown->create([
                //         'fid' => 3,
                //         'dropvalue' => $data[1]
                //     ]);
                //     if ($dropdown_create) {
                //         $designation_id = $dropdown_create->id;
                //     }
                // }
                $inputData = [
                    'organization_id' => $organization_id ?? null,
                    'designation_id' => $designation_id ?? null,
                    'no' => $data[2] ?? 0
                ];
                $darbandi = Darbandi::where('organization_id', $organization_id)->where('designation_id', $designation_id)->first();
                if ($darbandi) {
                    $darbandi->update($inputData);
                } else {
                    Darbandi::create($inputData);
                }
            }
            toastr()->success('Bulk Upload succesfully');
        } catch (\Exception $e) {
            toastr()->error('Something went wrong!!');
        }
    }
}
