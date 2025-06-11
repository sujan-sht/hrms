<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Employee\Entities\DocumentDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentDetailResource extends JsonResource
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
            'document_name' => $this->document_name,
            'file' => asset(DocumentDetail::Document_PATH  . $this->file),
        ];
    }
}
