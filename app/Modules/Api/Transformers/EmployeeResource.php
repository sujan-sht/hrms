<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'first_name'=>$this->first_name,
            'middle_name'=>$this->middle_name,
            'last_name'=>$this->last_name,
            'full_name'=>$this->full_name,
            'organization_id' => $this->organization_id,
            'organization' => optional($this->organizationModel)->name,
            'employee_code' => $this->employee_code,
            'biometric_id' => $this->biometric_id,
            'citizenship_no' => $this->citizenship_no,
            'date_of_birth' => $this->dob,
            'gender' => optional($this->getGender)->dropvalue,
            'maritial_status' => optional($this->getMaritalStatus)->dropvalue,
            'blood_group' => optional($this->getBloodGroup)->dropvalue,
            'profile_pic' => $this->getImage(),
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'temporary_address' => [
                'address' => $this->temporaryaddress,
                'municipality/vdc' => $this->temporarymunicipality_vdc,
                'district' => optional($this->temporaryDistrictModel)->district_name,
                'province' => optional($this->temporaryProvinceModel)->province_name,

            ],
            'permanent_address' => [
                'address' => $this->permanentaddress,
                'municipality/vdc' => $this->permanentmunicipality_vdc,
                'district' => optional($this->permanentDistrictModel)->district_name,
                'province' => optional($this->permanentProvinceModel)->province_name,

            ],
            'official_detail' => [
                'email' => $this->official_email,
                'join_date' => $this->join_date,
                'day_off' => $this->employeeDayOff,
                'designation_id' => $this->designation_id,
                'designation' => optional($this->designation)->dropvalue,
                'department_id' => $this->department_id,
                'department' => optional($this->department)->dropvalue,
                'job_title' => $this->job_title,
            ]
        ];
    }
}
