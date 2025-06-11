<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Grievance\Entities\GrievanceEmployee;
use Illuminate\Http\Resources\Json\JsonResource;

class GrievanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data_arr = [
            'related_grievances' => 'Subject related to grievances',
            'emp_name' => 'Employee involved in Misconduct',
            'dept' => 'Department',
            'misconduct_type' => 'Type of Misconduct',
            'date' => 'Date of Misconduct',
            'time' => 'Time',
            'location' => 'Location',
            'is_witness_present' => 'Was there any Witness in the place of Misconduct?',
            'witness_name' => 'Name of Witness',
            'detail' => 'Details',
        ];
        $grievanceDetails = $this->grievanceMetas()->pluck('value','key')->toArray();
        $detail = [];
        foreach ($data_arr as $key => $val) {
            if(array_key_exists($key, $grievanceDetails)){
                $detail[$val] = $grievanceDetails[$key]; 
            }
        }

        return [
            'id' => $this->id,
            'is_anonynous' => $this->is_anonymous == 11 ? 'Yes' : 'No',
            'subject_type' => $this->getSubjectType(),
            'attachment' => $this->attachment?asset('uploads/grievance/' . $this->attachment):'',
            'created_by' => optional(optional($this->user)->userEmployer)->full_name,
            'date' => getStandardDateFormat($this->created_at),
            'grievanceDetails' => $detail,
            'employeeDetails' =>[
                'id' =>  optional(optional($this->grievanceEmployee)->employee)->id,
                'employee' => optional(optional($this->grievanceEmployee)->employee)->full_name,
                'division' => optional(optional($this->grievanceEmployee)->division)->dropvalue,
                'department' => optional(optional($this->grievanceEmployee)->department)->dropvalue,
                'designation' => optional(optional($this->grievanceEmployee)->designation)->dropvalue
            ],

        ];
    }
}
