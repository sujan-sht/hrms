<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'organization' => optional($this->organizationModel)->name,
            'employee_code' => $this->employee_code,
            'full_name' => $this->full_name,
            'email'=>$this->official_email,
            // 'citizenship_no' => $this->citizenship_no,
            // 'date_of_birth' => $this->dob,
            // 'gender' => optional($this->getGender)->dropvalue,
            // 'maritial_status' => optional($this->getMaritalStatus)->dropvalue,
            // 'blood_group' => optional($this->getBloodGroup)->dropvalue,
            'profile_pic' => $this->getImage(),
            'mobile' => $this->mobile,
            'phone' => $this->phone,
        ];
    }
}
