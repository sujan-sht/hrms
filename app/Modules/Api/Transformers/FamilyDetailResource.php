<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FamilyDetailResource extends JsonResource
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
            'name' => $this->name,
            'relation' => $this->relation_type_title,
            'contact'=>$this->contact,
        ];
    }
}
