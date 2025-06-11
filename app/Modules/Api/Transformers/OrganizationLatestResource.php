<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationLatestResource extends JsonResource
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
            'address' => $this->address,
            'contact'=>$this->contact,
            'mobile'=>$this->mobile,
            'fax'=>$this->fax,
        ];
    }
}
