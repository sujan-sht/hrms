<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BenefitDetailResource extends JsonResource
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
            'plan' => $this->plan,
            'benefit_type' => optional($this->benefitTypeInfo)->dropvalue,
            'coverage' => $this->coverage,
            'effective_date' => $this->effective_date,
            'employee_contribution' => $this->employee_contribution,
            'company_contribution' => $this->company_contribution,

        ];
    }
}
