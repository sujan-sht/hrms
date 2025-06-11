<?php

namespace App\Modules\Employee\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{

    protected $fillable = [
        'employee_id',
        'bank_code',
        'bank_name',
        'bank_address',
        'bank_branch',
        'account_type',
        'account_number',
        'status',
        'approved_by'
    ];

    public function bankInfo()
    {
        return $this->belongsTo(Dropdown::class, 'bank_name');
    }

    public function accountTypeInfo()
    {
        return $this->belongsTo(Dropdown::class, 'account_type');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ');
        });
    }
}
