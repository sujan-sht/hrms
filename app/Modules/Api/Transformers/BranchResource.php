<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'organization_name' => optional($this->organizationModel)->name,
            'name' => $this->name,
            'location' => $this->location,
            'contact'=>$this->contact,
            'email'=>$this->email,
            'manager'=>optional($this->managerEmployeeModel)->full_name,
        ];
    }
}
