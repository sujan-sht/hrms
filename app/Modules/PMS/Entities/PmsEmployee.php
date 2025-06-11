<?php

namespace App\Modules\PMS\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PmsEmployee extends Model
{

    protected $fillable = [
        'fiscal_year_id',
        'employee_id',
        'status',
        'type',
        'rollout_date',
        'created_by'
    ];

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Rollout',
            '3' => 'Forwarded',
            '4' => 'Rejected',
            '5' => 'Completed',
        ];
    }

    public function getStatus()
    {
        $list = Self::statusList();
        return $list[$this->status];
    }

    /**
     *
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'primary';
                break;
            case '3':
                $color = 'info';
                break;
            case '4':
                $color = 'success';
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

    public static function checkAndStore($employee_id)
    {
        $model = PmsEmployee::where('employee_id', $employee_id)->first();

        if (!$model) {
            $currentFiscalYear = FiscalYearSetup::currentFiscalYear();
            if (!is_null($currentFiscalYear)) {
                $data = [
                    'employee_id' => $employee_id,
                    'fiscal_year_id' => $currentFiscalYear['id'],
                    'status' => 1,
                    'created_by' => Auth::user()->id
                ];
                PmsEmployee::create($data);
            }
        }
    }

    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function userModel()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
