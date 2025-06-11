<?php

namespace App\Modules\Notice\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Setting\Entities\Department;
use Illuminate\Database\Eloquent\Model;

class NoticeDepartment extends Model
{
    protected $fillable = [
        'notice_id',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
