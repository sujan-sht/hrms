<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalDetailResource extends JsonResource
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
            'insurance_company_name' => $this->insurance_company_name,
            'medical_problem' => $this->medical_problem,
            'details' => $this->details,
            'medical_insurance_details' => $this->medical_insurance_details,
        ];
    }
}
