<?php

namespace App\Modules\Api\Transformers;

use App\Modules\Notice\Entities\Notice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NoticeResource extends JsonResource
{
    // public $collects = Notice::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $attachment = [];
        if ($this->file != null) {
           $attachment[0] = [
                'path'=> asset('uploads/notice/' . $this->file),
                'extension'=>\File::extension($this->file)
            ];
        } else {
            if(isset($this->files) && !empty($this->files)){
                foreach ($this->files as $key=>$file){
                    $attachment[$key] = [
                        'path'=> asset('uploads/notice/' . $file->file),
                        'extension'=>\File::extension($file->file)
                    ];
                }
            }
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'notice_date'=>$this->notice_date,
            'created_at'=>$this->created_at,
            'created_by'=>$this->employee->full_name,
            'type'=>$this->getType(),
            'image' => $this->when($this->image != null, function () {
                return [
                    'path'=> asset('uploads/notice/' . $this->image),
                    'extension'=>\File::extension($this->image)
                ];
            }),
            'attachment' => $attachment,
        ];
    }
}
