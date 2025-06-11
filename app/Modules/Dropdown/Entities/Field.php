<?php

namespace App\Modules\Dropdown\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Dropdown\Entities\Dropdown;

class Field extends Model
{
    protected $fillable = [
        
        'title',
        'slug'
    ];
    
    public function dropdownValue(){
        return $this->hasMany(Dropdown::class,'fid','id');
    }
}
