<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Entities\Leave;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Leave\Entities\LeaveEncashmentLog;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\LeaveYearSetup\Repositories\LeaveYearSetupRepository;

class EmployeeLeave extends Model
{
    protected $fillable = [
        'fiscal_year_id',
        'employee_id',
        'leave_type_id',
        'leave_remaining',
        'leave_earned',
        'prorata_earned',
        'is_valid',
        'created_by',
        'updated_by',
        'leave_year_id',
        'initial_leave_remaining'

    ];

    /**
     * Relation with employee
     */
    public function employeeModel()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Relation with leave type
     */
    public function leaveTypeModel()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id')->where('status', 11);
    }


    /**
     *
     */
    public static function getList($params)
    {
        $employee = Employee::where('id', $params['employee_id'])->first();
        $params['organization_id'] = $employee->organization_id;
        $empleave = EmployeeLeave::with('leaveTypeModel')->when(true, function ($query) use ($params, $employee) {
            $query->where('is_valid', 11);
            if (isset($params['leave_year_id'])) {
                $query->where('leave_year_id', $params['leave_year_id']);
            }
            if (isset($params['half_leave_status'])) {
                $query->whereHas('leaveTypeModel', function ($qry) use ($params) {
                    $qry->where('half_leave_status', $params['half_leave_status']);
                });
            }
            if (isset($params['show_on_employee'])) {
                $query->whereHas('leaveTypeModel', function ($qry) use ($params) {
                    $qry->where('show_on_employee', $params['show_on_employee']);
                });
            }

            if (isset($params['organization_id'])) {
                $query->whereHas('employeeModel', function ($qry) use ($params) {
                    $qry->where('organization_id', $params['organization_id']);
                });
            }

            $query->whereHas('leaveTypeModel', function ($q) use ($params, $employee) {
                $q->where('leave_year_id', $params['leave_year_id']);

                // $qry->where('gender1', null)->where('marital_status', null);
                // $qry->orWhere('gender', $employee->gender)->orWhere('marital_status', $employee->marital_status);
                if(isset($params['leave_kind'])  && ($params['leave_kind']== 1)){
                    $q->where('code','!=','SUBLV');
                }

                $q->where(function ($qry) use ($employee) {
                    $qry->where('gender', $employee->gender);
                    $qry->orWhere('gender', null);
                });

                $q->where(function ($qry) use ($employee) {
                    $qry->where('marital_status', $employee->marital_status);
                    $qry->orWhere('marital_status', null);
                });
            });
        })
            ->where('employee_id', $params['employee_id'])
            ->get();
        return $empleave;
    }

    /**
     * function to track user
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = isset(Auth::user()->id) ? Auth::user()->id : 1;

             activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        Self::updating(function ($model) {
            $model->updated_by = isset(Auth::user()->id) ? Auth::user()->id : 1;

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


    /**
     *
     */
    public static function updateRemainingLeave($params, $flag)
    {
        $leave_year = LeaveYearSetup::currentLeaveYear();
        $forceLeave = LeaveType::where(['id'=> $params['leave_type_id'], 'leave_year_id' => $leave_year->id, 'code' => 'FRCLV'])->first();
        $model = EmployeeLeave::where([
            'leave_year_id' => $leave_year->id,
            'employee_id' => $params['employee_id'],
            'leave_type_id' => $params['leave_type_id']
        ])->first();

        if ($model) {
            if ($flag == 'ADD') {
                if(isset($forceLeave)){
                    $model->leave_remaining = $forceLeave->number_of_days;
                }else{
                    $model->leave_remaining = $model->leave_remaining + $params['numberOfDays'];
                }
            } else {
                if(isset($forceLeave)){
                    $model->leave_remaining = 0;
                }else{
                    $model->leave_remaining = $model->leave_remaining - $params['numberOfDays'];
                }
            }
            $model->save();

            $leaveEncashmentLog = LeaveEncashmentLog::where('leave_type_id', $params['leave_type_id'])->where('employee_id', $params['employee_id'])->where('is_valid', 11)->first();
            if(!empty($leaveEncashmentLog)){
                if(($model->leave_remaining >= 0) && (!is_null($leaveEncashmentLog->encashment_threshold)) && ($model->leave_remaining > $leaveEncashmentLog->encashment_threshold)){
                    $exceeded_balance = $model->leave_remaining - $leaveEncashmentLog->encashment_threshold;
                }else{
                    $exceeded_balance = 0;
                }
                $leaveEncashmentLog->leave_remaining = $model->leave_remaining;
                $leaveEncashmentLog->total_balance = $model->leave_remaining;
                $leaveEncashmentLog->exceeded_balance = $exceeded_balance;
                $leaveEncashmentLog->eligible_encashment = $exceeded_balance;

                $leaveEncashmentLog->save();
            }
        }
        return true;
    }

    public static function getLeaveRemaining($leave_year_id, $employee_id, $leave_type_id)
    {
        $result = EmployeeLeave::select('leave_remaining')->where('leave_year_id', $leave_year_id)->where('employee_id', $employee_id)->where('leave_type_id', $leave_type_id)->first();
        return $result;
    }
    public function employeeLeaveDetails($params,$employee_id)
    {
        $filter = $params;
        $employeeModel = Employee::find($employee_id);
        $leave_year = LeaveYearSetup::currentLeaveYear();
        $employee_leave_details = [];

        if (auth()->user()->user_type == 'employee') {
            $filter['showStatus'] = 11;
        }

        $leaveTypeModels = LeaveType::when(true, function ($query) use ($filter, $employeeModel) {
            $query->where('status', 11);
            $query->where('organization_id', $employeeModel->organization_id);
            $query->where('leave_year_id', getCurrentLeaveYearId());

            if (isset($filter['showStatus']) && !empty($filter['showStatus'])) {
                $query->where('show_on_employee', $filter['showStatus']);
            }

            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('gender', $employeeModel->gender);
                $qry->orWhere('gender', null);
            });

            $query->where(function ($qry) use ($employeeModel) {
                $qry->where('marital_status', $employeeModel->marital_status);
                $qry->orWhere('marital_status', null);
            });
            if (isset($filter['half_leave_status'])) {
                $query->whereHas('leaveTypeModel', function ($qry) use ($filter) {
                    $qry->where('half_leave_status', $filter['half_leave_status']);
                });
            }
        })->get();
        if (count($leaveTypeModels) > 0) {
            foreach ($leaveTypeModels as $key => $leaveTypeModel) {
                $employeeLeaveModel = EmployeeLeave::where([
                    'leave_year_id' => getCurrentLeaveYearId(),
                    'employee_id' => $employeeModel->id,
                    'leave_type_id' => $leaveTypeModel->id,
                    'is_valid' => 11
                    ])->first();
                    $dateConverter = new DateConverter();
                    $leaveYearList = (new LeaveYearSetupRepository())->getLeaveYearList();
                    if ($employeeLeaveModel) {
                        // if($employeeLeaveModel->leave_type_id==18){
                        //     dd($employeeLeaveModel);
                        // }
                        $employee_leave_details[$key]['id'] = $employeeLeaveModel->id;
                        $employee_leave_details[$key]['leave_type'] = $leaveTypeModel->name;
                        $employee_leave_details[$key]['leave_remaining'] = $employeeLeaveModel->leave_remaining;
                        // dd($employee_leave_details);

                        $leave_taken = Leave::where([
                            'organization_id' => $employeeModel->organization_id,
                            'employee_id' => $employeeModel->id,
                            'leave_type_id' => $leaveTypeModel->id
                            ])
                            ->where('date', '>=', $leave_year->start_date_english)
                            ->where('date', '<=', $leave_year->end_date_english)
                            ->whereNotIn('status', [4, 5])
                            ->selectRaw('SUM(CASE WHEN leave_kind = 1 THEN 0.5 ELSE 1 END) as total_leaves')
                            ->first()
                            ->total_leaves;
                            $employee_leave_details[$key]['leave_taken'] = $leave_taken;
                            $filter=[
                                "leave_year_id" => getCurrentLeaveYearId(),
                                "organization_id" => $employeeModel->organization_id,
                                "leave_type_id" => $leaveTypeModel->id,
                                "employee_id" => $employeeModel->id
                            ];
                            // dd($employee_leave_details[$key]['leave_remaining']);
                            $employeeLeaveRemaining=self::getLeaveSummariesMonthly($filter, $employeeModel->organization_id, 30, $leaveYearList[$filter['leave_year_id']], $dateConverter->getNepMonths(), $employeeModel->id);
                            // $employee_leave_details[$key]['total_leave'] = $employeeLeaveModel->leave_remaining + $leave_taken;
                            $employee_leave_details[$key]['leave_earned'] = $employeeLeaveRemaining->totalRemainingLeave-$employeeLeaveRemaining->opening_leave;
                            $openinigLeave=0;
                            $employeeLeaveOpening=EmployeeLeaveOpening::where([
                                'leave_year_id' => getCurrentLeaveYearId(),
                                'employee_id' => $employeeModel->id,
                                'leave_type_id' => $leaveTypeModel->id
                                ])->first();

                                if($employeeLeaveOpening){
                                    $openinigLeave=$employeeLeaveOpening->opening_leave ?? 0;
                                }
                                $employee_leave_details[$key]['opening_leave'] =$openinigLeave;
                                $employee_leave_details[$key]['total_leave'] =$employeeLeaveRemaining->totalRemainingLeave;
                                // $employee_leave_details[$key]['leave_remaining'] = $employee_leave_details[$key]['total_leave']-$leave_taken;
                                $employee_leave_details[$key]['leave_type_id'] = $leaveTypeModel->id;


                }
            }
        }
        return $employee_leave_details;
    }
}
