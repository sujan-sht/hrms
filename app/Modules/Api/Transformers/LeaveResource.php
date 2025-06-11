<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Leave\Entities\Leave;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $allLeaves = Leave::getChildren($this->id)->map(function ($leave){
            $day[$leave->id] = date('D', strtotime($leave->date));
            foreach ($leave->childs as $child) {
                $day[$child->id] = date('D', strtotime($child->date));
            }
            return $day;
        });
        return [
            'id' => $this->id,
            'employeeName' => optional($this->employeeModel)->full_name,
            'department' => optional(optional($this->employeeModel)->department)->dropvalue,
            'designation' => optional(optional($this->employeeModel)->designation)->dropvalue,
            'leave_kind' => optional($this->leaveTypeModel)->name,
            'leave_type' => $this->leave_kind == 1 ? $this->getLeaveKind().'('.$this->getHalfType().')' : $this->getLeaveKind(),
            'no_of_days' => $this->getDateRangeWithCount()['count'],
            'days' => setObjectIdAndName($allLeaves[0]),
            'date' => $this->getDateRangeWithCount()['range'],
            'status' => $this->getStatus(),
            'status_id' => $this->status,
            'reason' => $this->reason,
            'attachments' => $this->attachments,
            'reject' => $this->when($this->status == '4', function () {
                return [
                    'reject_by' => optional($this->rejectUserModel)->full_name,
                    'reject_msg' => $this->reject_message,
                ];
            }),
            'atl_employee' => [
                'name' => optional($this->altEmployeeModel)->full_name,
                'msg' => $this->alt_employee_message,

            ],
            'created_date' => getStandardDateFormat($this->created_at),
            'statusList' => $this->status_list,
        ];
    }
}
