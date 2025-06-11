<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BankDetailResource extends JsonResource
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
            'bank_name' => optional($this->bankInfo)->dropvalue,
            'bank_address' => $this->bank_address,
            'bank_branch' => $this->bank_branch,
            'account_type' => optional($this->accountTypeInfo)->dropvalue,
            'account_no' => $this->account_number,


        ];
    }
}
