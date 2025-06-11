<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Entities\Branch;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{

    protected $fillable = [
        'organization_id',
        'branch_id',
        'calendar_type',
        'year',
        'month'
    ];


    /**
     *
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     *
     */
    public function payrollEmployees()
    {
        return $this->hasMany(PayrollEmployee::class)->orderBy('employee_id', 'ASC');
    }

    /**
     *
     */
    public function payrollEmployee()
    {
        return $this->hasOne(PayrollEmployee::class);
    }

    /**
     *
     */
    public function getIncomes()
    {
        $result = [];

        $payrollIncomeModels = PayrollIncome::whereHas('incomeSetup', function($query) {
            $query->where('monthly_income', 11);
        })->where('payroll_id', $this->id)->where('payroll_employee_id', $this->payrollEmployee->id)->orderBy('id', 'ASC')->get();
        if($payrollIncomeModels) {
            foreach ($payrollIncomeModels as $payrollIncomeModel) {
                $result[$payrollIncomeModel->income_setup_id] = optional($payrollIncomeModel->incomeSetup)->title;
            }
        }

        return $result;
    }

    /**
     *
     */
    public function getDeductions()
    {
        $result = [];

        $payrollDeductionModels = PayrollDeduction::whereHas('deductionSetup', function($query) {
            $query->where('monthly_deduction', 11);
        })->where('payroll_id', $this->id)->where('payroll_employee_id', $this->payrollEmployee->id)->orderBy('id', 'ASC')->get();
        if($payrollDeductionModels) {
            foreach ($payrollDeductionModels as $payrollDeductionModel) {
                $result[$payrollDeductionModel->deduction_setup_id] = optional($payrollDeductionModel->deductionSetup)->title;
            }
        }

        return $result;
    }

    public function getTaxExcludeValues()
    {
        $result = [];

        $payrollTaxExcludeModels = PayrollTaxExcludeValue::where('payroll_id', $this->id)->where('payroll_employee_id', $this->payrollEmployee->id)->orderBy('id', 'ASC')->get();
        if($payrollTaxExcludeModels) {
            foreach ($payrollTaxExcludeModels as $payrollTaxExcludeModel) {
                $result[$payrollTaxExcludeModel->tax_exclude_setup_id] = optional($payrollTaxExcludeModel->taxExcludeSetup)->title;
            }
        }

        return $result;
    }

    /**
     *
     */
    public function getMonthTitleAttribute()
    {
        $title = '';
        $dateConverter = new DateConverter();

        if($this->calendar_type == 'eng') {
            $title = $dateConverter->_get_english_month($this->month);
        } else {
            $title = $dateConverter->_get_nepali_month($this->month);
        }

        return $title;
    }

    /**
     *
     */
    public function checkCompleted()
    {
        $model = Self::find($this->id);
        $result = PayrollEmployee::where(['payroll_id' => $model->id, 'status' => 2])->first();
        if($result) {
            return true;
        }
        return false;
    }

    public function holdPayment(){
        $holdPaymentEmployeeIds=HoldPayment::where([
            "organization_id" => $this->organization_id,
            "calendar_type" => $this->calendar_type,
            "year" => $this->year,
            "month" => $this->month,
            "hold_status"=>1
        ])->get()->pluck('employee_id')->toArray();
        return $holdPaymentEmployeeIds;
    }

    public function releasePayment(){
        $releasePaymentEmployeeIds=HoldPayment::where([
            "organization_id" => $this->organization_id,
            "calendar_type" => $this->calendar_type,
            "released_year" => $this->year,
            "released_month" => $this->month,
            "hold_status"=>1,
            "status"=>2
        ])->get()
        ->groupBy('employee_id')->map(function($item,$key){
            return $item->map(function($query) use($key){
               $payroll=Payroll::where([
                "organization_id" => $query->organization_id,
                "calendar_type" => $query->calendar_type,
                "year" => $query->year,
                "month" => $query->month
                ])->first();
                if($payroll){
                    $payrollEmployee=PayrollEmployee::where(
                        [
                            'payroll_id'=>$payroll->id,
                            'employee_id'=>$query->employee_id
                        ]
                    )->first();
                    $payrollIncomes=PayrollIncome::where([
                        'payroll_id'=>$payroll->id,
                        'payroll_employee_id'=>$payrollEmployee->id
                    ])->get()->pluck('value','income_setup_id');
                    $payrollDeductions=PayrollDeduction::where([
                        'payroll_id'=>$payroll->id,
                        'payroll_employee_id'=>$payrollEmployee->id
                    ])->get()->pluck('value','deduction_setup_id');

                    return[
                        'incomes'=>$payrollIncomes,
                        'deductions'=>$payrollDeductions
                    ];
                }
            });
        })
        ->toArray();
        $mergedReleasePaymentEmployeeIds = collect($releasePaymentEmployeeIds)->map(function ($entries) {
            $mergedData = [
                'incomes' => [],
                'deductions' => []
            ];
            foreach ($entries as $entry) {
                foreach ($entry['incomes'] as $incomeId => $value) {
                    $mergedData['incomes'][$incomeId] = ($mergedData['incomes'][$incomeId] ?? 0) + $value;
                }
                foreach ($entry['deductions'] as $deductionId => $value) {
                    $mergedData['deductions'][$deductionId] = ($mergedData['deductions'][$deductionId] ?? 0) + $value;
                }
            }
            return $mergedData;
        });

        return[
            'releaseData'=>$mergedReleasePaymentEmployeeIds->toArray(),
            'employeeIds'=>collect($releasePaymentEmployeeIds)->keys()->toArray()
        ];
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
                ->log('Deleted post: ' );
        });
    }
}
