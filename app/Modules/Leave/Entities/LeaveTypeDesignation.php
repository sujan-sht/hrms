<?php

namespace App\Modules\Leave\Entities;

use App\Modules\Setting\Entities\Designation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveTypeDesignation extends Model
{

    protected $fillable = [
        'leave_type_id',
        'designation_id'
    ];

    /**
     * Relation with leave type
     */
    public function leaveTypeModel()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    /**
     * Relation with leave type
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
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
