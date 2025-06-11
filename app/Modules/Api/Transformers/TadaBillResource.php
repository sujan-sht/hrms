<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Tada\Entities\TadaBill;
use Illuminate\Http\Resources\Json\JsonResource;

class TadaBillResource extends JsonResource
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
            'tada_id' => $this->tada_id,
            'image' => $this->image_src ? asset(TadaBill::FILE_PATH. $this->image_src) : ''
        ];
    }
}
