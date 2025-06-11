<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Employee\Entities\EmployeeThresholdRelatedDetail;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeductionSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'short_name',
        'description',
        'method',
        'amount',
        'percentage',
        'income_id',
        'order',
        'status',
        'tax_deduction',
        'monthly_deduction'
    ];

    /**
     * Relation with income setup
     */
    public function income()
    {
        return $this->belongsTo(IncomeSetup::class, 'income_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public static function statusList()
    {
        return [
            '10' => 'Inactive',
            '11' => 'Active',
        ];
    }

    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '11':
                $color = 'success';
                break;
            case '10':
                $color = 'danger';
                break;
            default:
                $color = 'secondary';
                break;
        }

        return [
            'status' => $list[$this->status],
            'color' => $color
        ];
    }

    public static function methodList()
    {
        return [
            '1' => 'Fixed',
            '2' => 'Percentage',
            '3' => 'Manual',
        ];
    }

    public function getMethod()
    {
        $list = Self::methodList();

        // switch ($this->method) {
        //     case '1':
        //         $color = 'success';
        //         break;
        //     case '2':
        //         $color = 'secondary';
        //         break;
        //     default:
        //         $color = '';
        //         break;
        // }

        return [
            'method' => $list[$this->method],
            // 'color' => $color
        ];
    }

    public function thresholdBenefitSetup(){
        return $this->hasOne(ThresholdBenefitSetup::class,'deduction_setup_id','id');
    }

    public function employeeThresholdBenefit(){
        return $this->hasOne(EmployeeThresholdRelatedDetail::class,'deduction_setup_id','id');
    }
    public function deductionDetail(){
        return $this->hasMany(DeductionSetupReferenceSalaryType::class,'deduction_setup_id','id');
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
