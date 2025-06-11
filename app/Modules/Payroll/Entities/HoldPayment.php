<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HoldPayment extends Model
{
    const STATUS = [
        '1' => 'Hold',
        '2' => 'Release',
        '3' => 'Cancel',
    ];
    protected $fillable = ['organization_id','employee_id','calendar_type','year','month','notes','is_released','released_year','released_month','status','hold_status','created_by'];

    public function organizationModel()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getStatus()
    {
        return HoldPayment::STATUS[$this->status ?? 1];
    }
    public function getHold($employee_id,$year,$month){
        // dd($employee_id,$year,$month);
        return HoldPayment::where('employee_id',$employee_id)->where('year',$year)->where('month',$month)->where('status',1)->get();
    }

    public function checkCancelStatus(){
        $payroll=Payroll::where([
            'year'=>$this->year,
            'month'=>$this->month
        ])->first();
        if($payroll){
            if($payroll->checkCompleted()){
                return true;
            }
        }
        return false;
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
