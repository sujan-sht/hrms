<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetDetailResource extends JsonResource
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
            'asset' => optional($this->asset)->title,
            'quantity'=>$this->quantity,
            'allocated_by'=>optional(optional($this->user)->userEmployer)->full_name,
            'allocated_date'=>getStandardDateFormat($this->allocated_date),
            'return_date'=>getStandardDateFormat($this->return_date),
        ];
    }
}
