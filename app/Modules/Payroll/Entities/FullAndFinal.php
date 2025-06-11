<?php

namespace App\Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FullAndFinal extends Model
{
    protected $fillable = [
        'employee_id',
        'form_data',
        'fine_penalty',
        'adjustment',
        'remarks'
    ];

    public function employee(){
        return $this->hasOne(Employee::class,'id','employee_id');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' );
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' );
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }

}
