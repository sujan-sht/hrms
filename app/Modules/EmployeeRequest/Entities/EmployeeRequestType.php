<?php

namespace App\Modules\EmployeeRequest\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeRequestType extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'status',
        'created_by',
        'updated_by'
    ];

    public function employeeRequest()
    {
        return $this->hasMany(EmployeeRequest::class, 'type_id', 'id');
    }

    public static function getTitleById($id)
    {
        $total = EmployeeRequestType::where('id','=',$id)
            ->first();
        return $total->title;
    }

}
