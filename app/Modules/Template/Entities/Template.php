<?php

namespace App\Modules\Template\Entities;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['title','template_type_id','text'];

    public function templateType()
    {
        return $this->belongsTo(TemplateType::class,'template_type_id');
    }
}