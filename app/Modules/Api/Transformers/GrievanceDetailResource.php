<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GrievanceDetailResource extends JsonResource
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
            'employee' => optional($this->employee)->full_name,
            'division' => optional($this->division)->dropvalue,
            'department' => optional($this->department)->dropvalue,
            'designation' => optional($this->designation)->dropvalue
        ];
    }
}
