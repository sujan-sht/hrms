<?php

namespace App\Modules\Template\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LetterManagement extends Model
{
    protected $fillable = [
        'employee_id',
        'type'
    ];

    public static function typeList() {
        return [
            1 => 'Experience Letter',
            2 => 'Salary Certificate'
        ];
    }

    public function getTypeAttribute($attribute)
    {
        return in_array($attribute ?? null, [1, 2])
            ? [
                1 => 'Experience Letter',
                2 => 'Salary Certificate'
            ][$attribute] : null;
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
    
}
