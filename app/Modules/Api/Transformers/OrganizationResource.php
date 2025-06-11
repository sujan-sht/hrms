<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'address' => $this->address,
            'contact' => $this->contact,
            'email' => $this->email,
            'image' => asset(Organization::IMAGE_PATH . $this->image),
            'vision' => $this->vision,
            'mission' => $this->mission,
            'code_of_conduct' => $this->code_of_conduct,
        ];
    }
}
