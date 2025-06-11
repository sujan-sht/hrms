<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class IncomeSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'short_name',
        'description',
        'method',
        'amount',
        'percentage',
        'salary_type',
        'daily_basis_status',
        'monthly_income',
        'taxable_status',
        'leave_deduction',
        'pf_calculation',
        'order',
        'status',
    ];

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
            '3' => 'Manual'
        ];
    }

    public function getMethod()
    {
        $list = Self::methodList();

        // switch ($this->method) {
        //     case '1':
        //         $color = 'success';
        //     break;
        //     case '2':
        //         $color = 'secondary';
        //     break;
        //     default:
        //         $color = '';
        //     break;
        // }

        return [
            'method' => $list[$this->method],
            // 'color' => $color
        ];
    }
    public function incomeDetail(){
        return $this->hasMany(IncomeSetupReferenceSalaryType::class,'income_setup_id','id');
    }

    public static function getOrganizationwiseIncomeTypes($organizationId){
        return IncomeSetup::where('organization_id', $organizationId)->pluck( 'title','id')->toArray();
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
