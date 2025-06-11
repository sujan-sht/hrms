<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyDetailResource extends JsonResource
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
            'name' => $this->name,
            // 'relation' => optional($this->relationInfo)->dropvalue,
            'relation' => $this->relation_type_title,
            'phone' => $this->phone1,
            'address' => $this->address,
            'note' => $this->note,
        ];
    }
}
