<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CareerMobilityResource extends JsonResource
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
            'date'=>getStandardDateFormat($this->date),
            'type_id'=>$this->type_id,
            'type'=>$this->getTypeList(),
            'to'=> $this->getTypewiseName($this),
        ];
    }
}
