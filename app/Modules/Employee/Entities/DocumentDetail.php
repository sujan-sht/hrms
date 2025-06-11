<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class DocumentDetail extends Model
{
    const Document_PATH = '/uploads/employee/document_details/';

    protected $fillable = [
        'employee_id',
        'document_name',
        'id_number',
        'issued_date',
        'expiry_date',
        'file',
    ];

    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }

}
