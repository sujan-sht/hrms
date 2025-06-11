<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceRequestResource extends JsonResource
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
            'id'=>$this->id,
            'employee_name' => optional($this->employee)->full_name,
            'department' => optional(optional($this->employee)->department)->dropvalue,
            'designation' => optional(optional($this->employee)->designation)->dropvalue,
            'date'=>getStandardDateFormat($this->date),
            'no_of_days'=> $this->kind && ($this->kind == 1 || $this->kind == 2) ? 0.5 : 1,
            'time'=>$this->time ? date('h:i A', strtotime($this->time)) : '',
            'kind'=> $this->kind ? $this->getKind() : '',
            'kind_id'=> $this->kind,
            'status'=>$this->getStatus(),
            'status_id'=>$this->status,
            'type_id'=>$this->type,
            'type'=>$this->getType(),
            'detail'=>$this->detail,
            'statusList'=>$this->status_list,
        ];
    }
}
