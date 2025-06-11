<?php

namespace App\Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;

class BranchDayOff extends Model
{
    // use HasFactory;

    protected $fillable = [
        'branch_id',
        'day_off'
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


}
