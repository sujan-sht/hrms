<?php

namespace App\Modules\Api\Transformers;

use App\Helpers\DateTimeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'full_name'=>$this->full_name,
            'email' => $this->email,
            'user_type'=>$this->user_type,
            'emp_id'=>$this->emp_id,
            'tenure_days'=> DateTimeHelper::DateDiffInYearMonthDay(optional($this->userEmployer)->join_date, date('Y-m-d')) ,
            'employee'=>  new EmployeeResource($this->userEmployer),
            'moduleDetails' => $this->moduleDetails
            // 'organization'=>optional(optional($this->userEmployer)->organizationModel)->name,
        ];
    }
}
