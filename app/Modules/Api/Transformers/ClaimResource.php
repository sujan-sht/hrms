<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Tada\Entities\TadaBill;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
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

        return [
        
            'id' => $this->id,
            'title' => $this->title,
            'employee' => optional($this->employee)->full_name,
            'eng_from_date' => $this->eng_from_date,
            'eng_to_date' => $this->eng_to_date,
            'requested_amt' => $requested_amt,
            'settled_amt' => $settled_amt,
            'balance' => $balance,
            'status' => $this->getStatus(),
            'remarks' => $this->remarks,
            'created_date' => getStandardDateFormat($this->created_at),
            // 'excel_file' => asset('uploads/tada/excels/' . $this->excel_file),
            // 'bills' => $this->bills,
            'meta' => $this->when(true, function () {
                return [
                    'tadaDetails' => TadaDetailResource::collection($this->tadaDetails),
                    'employee' => new EmployeeResource($this->employee),
                    'excel_file' => $this->excel_file?asset('uploads/tada/excels/' . $this->excel_file):'',
                    'bills' => TadaBillResource::collection($this->bills),
                ];
            }),

        ];
    }
}
