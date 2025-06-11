<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Leave\Entities\Leave;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveSubstituteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employeeName' => optional($this->employee)->full_name,
            'department' => optional(optional($this->employee)->department)->dropvalue,
            'designation' => optional(optional($this->employee)->designation)->dropvalue,
            // 'leave_kind' => $this->leave_kind,
            'status_id' => $this->status,
            'status' => $this->getStatus(),

            'reason' => $this->remark,
            'forwarded_remarks' => $this->forwarded_remarks,
            'rejected_remarks' => $this->rejected_remarks,
            'forwarded_by' => optional(optional($this->forwardedUser)->userEmployer)->full_name,
            'rejected_by' => optional(optional($this->rejectedUser)->userEmployer)->full_name,
            'created_date' => getStandardDateFormat($this->created_at),
            'applied_date' => getStandardDateFormat($this->date),
            'statusList' => setObjectIdAndName(json_decode($this->status_list)),
        ];
    }
}
