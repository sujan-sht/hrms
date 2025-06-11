<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveAmountSetup extends Model
{
    protected $fillable = ['organization_id','income_setup_id'];

    public function organizationModel(){
        return $this->belongsTo(Organization::class,'organization_id');
    }

    public function leaveAmountDetail(){
        return $this->hasMany(LeaveAmountSetupDetail::class,'leave_amount_setup_id','id');
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
