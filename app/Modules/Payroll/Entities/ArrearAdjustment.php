<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArrearAdjustment extends Model
{
    protected $fillable = [
        'emp_id',
        'name',
        'organization_id',
        'branch_id',
        'designation_id',
        'year',
        'month',
        'emp_status',
        'arrear_amt',
        'effective_date',
        'nep_effective_date',
        'status',
        'created_by',
        'updated_by'
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
    public function arrearAdjustmentDetail()
    {
        return $this->hasMany(ArrearAdjustmentDetail::class, 'arrear_adjustment_id');
    }
    public function userInfo(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function checkEditStatus(){
        $status=true;
        $payroll=Payroll::where([
            'year'=>$this->year,
            'month'=>$this->month
        ])->first();
        if($payroll){
            if($payroll->checkCompleted()){
                $status=false;
            }
        }
        return $status;
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
                ->log('Deleted post: ' . $model);
        });
    }
}
