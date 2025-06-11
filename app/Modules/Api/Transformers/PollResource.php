<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
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
            'leave_kind' => optional($this->leaveTypeModel)->name,
            'leave_type' => $this->getLeaveKind(),
            'no_of_days' => $this->getDateRangeWithCount()['count'],
            'date' => $this->getDateRangeWithCount()['range'],
            'status' => $this->getStatus(),
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

            ]

        ];
    }
}
