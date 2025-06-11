<?php

namespace App\Modules\Template\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateType extends Model
{
    protected $fillable = ['title','slug'];

    public function template()
    {
        return $this->hasOne(Template::class,);
    }
}
