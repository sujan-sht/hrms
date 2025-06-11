<?php

namespace App\Service\Import;

use App\Modules\Unit\Entities\Unit;
class UnitImport implements ImportInterface
{
    // protected $dropdown;

    // public function __construct(
    //     DropdownInterface $dropdown
    // ) {
    //     $this->dropdown = $dropdown;
    // }

    public function import($array)
    {
        // try {
            foreach ($array as $data) {
                
                $inputData = [
                    'title' => $data[1] ?? null,
                    'branch_id' => $data[2] ?? null,
                    'status' => $data[3] ?? null,
                ];
              

                Unit::create($inputData);
            }
            toastr()->success('Bulk Upload succesfully');
        // } catch (\Exception $e) {
        //     toastr()->error('Something went wrong!!');
        // }
    }
}
