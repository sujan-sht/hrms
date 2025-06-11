<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveTodayResource extends JsonResource
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
            'image' => optional($this->employeeModel)->getImage(),
            'employeeName' => optional($this->employeeModel)->full_name,
            'department' => optional(optional($this->employeeModel)->department)->dropvalue,
            'designation' => optional(optional($this->employeeModel)->designation)->dropvalue,
            'leave_kind' => optional($this->leaveTypeModel)->name,
            'leave_type' => $this->leave_kind == 1 ? $this->getLeaveKind().'('.$this->getHalfType().')' : $this->getLeaveKind(),
        ];
    }
}
