<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NewEmployeeListResource extends JsonResource
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
            'date' => $this->date,
            'profile_pic' => $this->profile_pic,
            'first_name'=>$this->first_name,
            'middle_name'=>$this->middle_name,
            'department' => optional($this->department)->dropvalue,
            'designation' => optional($this->designation)->dropvalue,
            'last_name' => $this->last_name,
            // 'type'=>$this->type,
            // 'diff_day'=>$this->diff_day,
            'full_name'=>$this->full_name,
        ];
    }
}
