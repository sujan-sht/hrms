<?php

namespace App\Modules\BusinessTrip\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelExpense extends Model
{
    protected $casts = [
        'expense_details' => 'array',
    ];

    protected $fillable = [
        'employee_name',
        'employee_id',
        'department',
        'designation',
        'expenses_type',
        'from_date',
        'to_date',
        'departure',
        'destination',
        'purpose',
        'total_amount',
        'expense_details'
    ];

    const TRAVEL_TYPES = [
        '1' => 'DOMESTIC',
        '2' => 'INTERNATIONAL'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
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
