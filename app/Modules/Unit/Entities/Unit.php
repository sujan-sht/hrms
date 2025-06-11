<?php

namespace App\Modules\Unit\Entities;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    

    protected $fillable = [
        'title',
        'status',
        'branch_id',
        'organization_id'
    ];

    public function organization(){
        return $this->hasOne(Organization::class,'id','organization_id');
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }
}
