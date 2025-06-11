<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MrfResource extends JsonResource
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
            'vacancy_id' => $this->reference_number,
            'title' => $this->title,
            'division'=>$this->organizationModel->name,
            'position'=>$this->position,
            'job_description' => $this->description,
            'job_specification' => $this->specification,
            'description' => '',
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'minimum_age'=>$this->age,
            'years_experience' => $this->experience,
            'two_wheeler_required' => $this->two_wheeler_status == 10 ? false : true,
            'four_wheeler_required' => $this->four_wheeler_status == 10 ? false : true,
        ];
    }
}
