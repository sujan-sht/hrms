<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $requested_amt =  (int)$this->billAmount() ?? 0;
        if($this->status == 'fully settled') {
            $settled_amt = $requested_amt;
        } elseif($this->status == 'request closed') {
            $settled_amt = (int)$this->request_closed_amt ?? 0;
        } else {
            $settled_amt = (int)$this->partiallySettledAmount() ?? 0;
        }
    
        $balance = $requested_amt - $settled_amt;
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'request_code' => $this->request_code,
            'employee' => optional($this->employee)->full_name,
            'eng_request_date' => $this->eng_request_date,
            'requested_amt' => $requested_amt,
            'settled_amt' => $settled_amt,
            'balance' => $balance,
            'status' => $this->getStatus(),
            'remarks' => $this->remarks,
            'created_date' => getStandardDateFormat($this->created_at),
            'meta' => $this->when(true, function () {
                return [
                    'requestDetails' => RequestDetailResource::collection($this->tadaDetails),
                    'employee' => new EmployeeResource($this->employee),
                ];
            }),

        ];
    }
}
