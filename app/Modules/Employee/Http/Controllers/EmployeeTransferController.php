<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\EmployeeTransfer;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Leave\Repositories\LeaveTypeInterface;
use App\Modules\Employee\Entities\EmployeeLeaveArchive;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Employee\Repositories\EmployeeTransferInterface;

class EmployeeTransferController extends Controller
{
    protected $employeeTransferObj; 
    protected $leaveTypeObj;
    protected $employeeObj;

    public function __construct(
        EmployeeTransferInterface $employeeTransferObj,
        LeaveTypeInterface $leaveTypeObj,
        EmployeeInterface $employeeObj
    ) {
        $this->employeeTransferObj = $employeeTransferObj;
        $this->leaveTypeObj = $leaveTypeObj;
        $this->employeeObj = $employeeObj;
    }

    public function appendAllTransferList(Request $request)
    {
        $filter['employee_id'] = $request->emp_id;
        $filter['type_id'] = '1';
        $filter['category'] = 'history';

        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['employeeTransferModels'] = $this->employeeTransferObj->findAll(null, $filter);
        return view('employee::employee.partial.ajaxlayouts.transfer-list', $data)->render();
    }

    public function appendAllPromotionList(Request $request)
    {
        $filter['employee_id'] = $request->emp_id;
        $filter['type_id'] = '2';
        $filter['category'] = 'history';

        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['employeePromotionModels'] = $this->employeeTransferObj->findAll(null, $filter);
        
        return view('employee::employee.partial.ajaxlayouts.promotion-list', $data)->render();
    }

    public function appendAllDemotionList(Request $request)
    {
        $filter['employee_id'] = $request->emp_id;
        $filter['type_id'] = '3';
        $filter['category'] = 'history';

        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['employeeDemotionModels'] = $this->employeeTransferObj->findAll(null, $filter);
        
        return view('employee::employee.partial.ajaxlayouts.demotion-list', $data)->render();
    }

    public function appendAllCarrierMobilityList(Request $request)
    {
        $filter['employee_id'] = $request->emp_id;
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['carrierMobilityModels'] = $this->employeeTransferObj->findCarrierMobilityList(null, $filter);
        
        return view('employee::employee.partial.ajaxlayouts.carrier-mobility-list', $data)->render();
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $oldLeaves = [];

        DB::beginTransaction();

        try {
            $currentLeaveYearModel = LeaveYearSetup::currentLeaveYear();
            $result = $this->employeeTransferObj->create($data);
            if($result) {
                // save to archive table before deleting
                $employeeLeaveModels = EmployeeLeave::where('leave_year_id', $currentLeaveYearModel->id)->where('employee_id', $result->employee_id)->get();
                if(count($employeeLeaveModels) > 0) {
                    foreach ($employeeLeaveModels as $key => $employeeLeaveModel) {
                        if(optional($employeeLeaveModel->leaveTypeModel)->carry_forward_status == 11) {
                            $leaveTypeCode = optional($employeeLeaveModel->leaveTypeModel)->code;
                            $oldLeaves[$leaveTypeCode] = $employeeLeaveModel->leave_remaining;
                        }
                        $employeeLeaveOpeningModel = EmployeeLeaveOpening::where('leave_year_id', $currentLeaveYearModel->id)->where('organization_id', $data['from_org_id'])->where('employee_id', $result->employee_id)->where('leave_type_id', $employeeLeaveModel->leave_type_id)->first();
                        if($employeeLeaveOpeningModel) {
                            $newData['employee_id'] = $result->employee_id;
                            $newData['organization_id'] = $employeeLeaveOpeningModel->organization_id;
                            $newData['leave_year_id'] = $employeeLeaveModel->leave_year_id;
                            $newData['leave_type_id'] = $employeeLeaveModel->leave_type_id;
                            $newData['opening_leave'] = $employeeLeaveOpeningModel->opening_leave;
                            $newData['leave_remaining'] = $employeeLeaveModel->leave_remaining;
                            if(EmployeeLeaveArchive::create($newData)) {
                                $employeeLeaveModel->delete();
                                $employeeLeaveOpeningModel->delete();
                            }
                        }
                    }
                }

                $employeeModel = $this->employeeObj->find($result->employee_id);
                $employeeModel->organization_id = $result->to_org_id;
                $employeeModel->save();
                $newEmployeeModel = $this->employeeObj->find($result->employee_id);

                $datas['transfer_date'] = $result->transfer_date;
                $datas['organization_id'] = $result->to_org_id;
                $datas['gender'] = $employeeModel->gender;
                $datas['marital_status'] = $employeeModel->marital_status;
                $datas['oldLeaves'] = $oldLeaves;
                
                $this->createEmployeeLeaveWithOpening($newEmployeeModel, $datas);
                
                // save employee timeline
                $timelineData['employee_id'] = $result->employee_id;
                $timelineData['date'] = date('Y-m-d');
                $timelineData['title'] = "Employee Transfer";
                $timelineData['description'] = "Transfer from " . optional($result->fromOrganizationModel)->name . " to " . optional($result->toOrganizationModel)->name;
                $timelineData['icon'] = "icon-truck";
                $timelineData['color'] = "secondary";
                $timelineData['reference'] = "employee-transfer";
                $timelineData['reference_id'] = $result->id;
                Employee::saveEmployeeTimelineData($result->employee_id, $timelineData);

                DB::commit();
                return ["status" => 1, "message" =>  "Data created successfully."];
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
            return ["status" => 0, "message" =>  "Oops! Something went wrong."];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        
        try {
            $this->employeeTransferObj->update($request->id, $data);
            return ["status" => 1, "message" =>  "Data updated successfully."];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Oops! Something went wrong."];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->employeeTransferObj->delete($request->id);
            return ["status" => 1, "message" =>  "Data deleted successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Oops! Something went wrong."];
        }
    }

    public function createEmployeeLeaveWithOpening($employee, $data = [])
    {
        $joinDate = $data['transfer_date'];
        $nepali_join_date = date_converter()->eng_to_nep_convert($joinDate);

        $currentDate = date('Y-m-d');
        $current_leave_year_data = LeaveYearSetup::currentLeaveYear();
        if (!is_null($current_leave_year_data)) {
            $leave_year_id = $current_leave_year_data['id'];
            $leave_year_start_date = $current_leave_year_data['start_date'];

            if ($nepali_join_date < $leave_year_start_date) {
                $nepali_join_date = $leave_year_start_date;
            }
            $months_diff = DateTimeHelper::DateDiff($leave_year_start_date, $nepali_join_date);
            $emp_remaining_month_in_Current_leave = 12 - $months_diff;

            $params['department_id'] = $employee->department_id;
            $params['level_id'] = $employee->level_id;

            $leave_types = $this->leaveTypeObj->getLeaveTypesFromOrganization($data['organization_id'], $leave_year_id, $params);

            foreach ($leave_types as $leave_type) {
                if ((is_null($leave_type->gender) || $leave_type->gender == $data['gender']) &&
                    (is_null($leave_type->marital_status) || $leave_type->marital_status == $data['marital_status'])
                ) {

                    $leave_type_days_in_month = round(($leave_type->number_of_days / 12), 2);
                    $leave_opening = round($leave_type_days_in_month * $emp_remaining_month_in_Current_leave, 2);
                    if($leave_type->prorata_status == '11') {
                        $newMonthsDiff = DateTimeHelper::DateDiff($joinDate, $currentDate);
                        if($newMonthsDiff > 12) {
                            $newMonthsDiff = DateTimeHelper::DateDiff($current_leave_year_data->start_date_english, $currentDate);
                        }
                        $leave_opening = round($leave_type_days_in_month * $newMonthsDiff, 2);
                    } 
                    $employeeLeaveOpening = [
                        'leave_year_id' => $leave_year_id,
                        // 'leave_type_id' => $leave_type->id,
                        'opening_leave' => $leave_opening,
                    ];
                    EmployeeLeaveOpening::saveData($data['organization_id'], $employee->id, $leave_type->id, $employeeLeaveOpening);

                    $employeeLeaveModel = EmployeeLeave::where([
                        'leave_year_id' => $leave_year_id,
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leave_type->id
                    ])->first();

                    if (empty($employeeLeaveModel)) {
                        $employeeLeaveModel = new EmployeeLeave();
                        $employeeLeaveModel->leave_year_id = $leave_year_id;
                        $employeeLeaveModel->employee_id = $employee->id;
                        $employeeLeaveModel->leave_type_id = $leave_type->id;
                    }

                    $employee_opening_leave = EmployeeLeaveOpening::getLeaveOpening($leave_year_id, $data['organization_id'], $employee->id, $leave_type->id);
                    $employeeLeaveModel->leave_remaining = $employee_opening_leave;
            
                    $leaveTaken = Leave::where([
                        'organization_id' => $data['organization_id'],
                        'employee_id' => $employee->id,
                        'leave_type_id' => $leave_type->id
                    ])
                    ->where('status', '!=', '4')
                    ->count();
            
                    if($leaveTaken > 0) {
                        $employeeLeaveModel->leave_remaining -= $leaveTaken;
                    }

                    // Add remaining leave data from past organization
                    if(isset($data['oldLeaves'][$leave_type->code])) {
                        $employeeLeaveModel->leave_remaining += $data['oldLeaves'][$leave_type->code];
                    }

                    $employeeLeaveModel->save();
                }
            }
        }
    }
}
