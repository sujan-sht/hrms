<?php

namespace App\Modules\Tada\Entities;

use Illuminate\Database\Eloquent\Model;

class TadaSubType extends Model
{
    protected $fillable = [
        'tada_type_id',
        'sub_type_title'
    ];
    
}
