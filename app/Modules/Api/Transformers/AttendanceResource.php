<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            // 'id' => $this->id,
            'date' => $this->date,
            'checkin' => !is_null($this->checkin) ? date('h.i A', strtotime($this->checkin)) : null,
            'checkout' => !is_null($this->checkout) ? date('h.i A', strtotime($this->checkout)) : null,
            'total_working_hr' => $this->total_working_hr,
            // 'inout_mode'=>$this->inout_mode
        ];
    }
}
