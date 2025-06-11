<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Dropdown\Repositories\DropdownRepository;
use App\Modules\Dropdown\Repositories\FieldRepository;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeCarrierMobility;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Employee\Entities\EmployeePayrollRelatedDetail;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\Leave\Entities\EmployeeLeaveOpening;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;

class CarrierMobilityImport
{
    public static function import($array)
    {
        try {
            // $emp = new EmployeeRepository();
            // $currentLeaveYearModel = LeaveYearSetup::currentLeaveYear();

            foreach ($array as $data) {
                if (isset($data[1]) && isset($data[3])) {
                    $employeeOldData = Employee::where('employee_code', $data[1])->where('status', 1)->first();
                    if($employeeOldData){
                        $type = array_search($data[3], EmployeeCarrierMobility::typeList());
                        $inputData = [
                            'employee_id' => $employeeOldData->id ?? null,
                            'date' =>$data[2] ?? null,
                            'type_id' => $type,
                        ];

                        // $inputData['organization_id'] = $employeeOldData->organization_id;
                        // if(isset($data[4])){
                        //     $organization = Organization::where('id', $data[4])->first();
                        //     if($organization){
                        //         $inputData['organization'] = $employeeNewData['organization_id'] = $organization->id;
                        //     }
                        // }

                        $inputData['branch_id'] = $employeeOldData->branch_id;
                        if(isset($data[4])){
                            $branch = Branch::where('name', trim($data[4]))->first();
                            if($branch){
                                $inputData['branch_id'] = $employeeNewData['branch_id'] = $branch->id;
                            }
                        }

                        $fieldObj = new FieldRepository();
                        $dropdownObj = new DropdownRepository();

                        $inputData['department_id'] = $employeeOldData->department_id;
                        if(isset($data[5])){
                            // $field = $fieldObj->findByTitle('Department');
                            // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[5]);
                            // if($dropvalue){
                            //     $inputData['department_id'] = $employeeNewData['department_id'] = $dropvalue->id;
                            // }

                            $department = Department::where('title', trim($data[5]))->first();
                            if(isset($department)){
                                $inputData['department_id'] = $employeeNewData['department_id'] = $department->id;
                            }
                        }

                        $inputData['level_id'] = $employeeOldData->level_id;
                        if(isset($data[6])){
                            // $field = $fieldObj->findByTitle('Level');
                            // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[6]);
                            // if($dropvalue){
                            //     $inputData['level_id'] = $employeeNewData['level_id'] = $dropvalue->id;
                            // }

                            $level = Level::where('title', trim($data[6]))->first();
                            if(isset($level)){
                                $inputData['level_id'] = $employeeNewData['level_id'] = $level->id;
                            }
                        }

                        $inputData['designation_id'] = $employeeOldData->designation_id;
                        if(isset($data[7])){
                            // $field = $fieldObj->findByTitle('Designation');
                            // $dropvalue = $dropdownObj->getModel($field[0]->id,$data[7]);
                            // if($dropvalue){
                            //     $inputData['designation_id'] = $employeeNewData['designation_id'] = $dropvalue->id;
                            // }

                            $designation = Designation::where('title', trim($data[7]))->first();
                            if(isset($designation)){
                                $inputData['designation_id'] = $employeeNewData['designation_id'] = $designation->id;
                            }
                        }

                        $inputData['job_title'] = $employeeOldData->job_title;
                        if(isset($data[8])){
                            $inputData['job_title'] = $employeeNewData['job_title'] = $data[8];
                        }

                        $inputData['probation_status'] = optional($employeeOldData->payrollRelatedDetailModel)->probation_status;
                        if(isset($data[9])){
                            $probationStatus = array_search($data[9], EmployeeCarrierMobility::probationStatusList());
                            $inputData['probation_status'] = $employeeNewPayrollData['probation_status'] = $probationStatus;
                        }

                        $inputData['payroll_change'] = optional($employeeOldData->payrollRelatedDetailModel)->payroll_change;
                        if(isset($data[10])){
                            $payrollChange = array_search($data[10], EmployeeCarrierMobility::payrollChangeList());
                            $inputData['payroll_change'] = $employeeNewPayrollData['payroll_change'] = $payrollChange;
                        }
                      
                        $mobilityData = EmployeeCarrierMobility::create($inputData);
                        if($mobilityData){
                            // save employee timeline
                            $timelineData['employee_id'] = $mobilityData['employee_id'];
                            $timelineData['date'] = $mobilityData['date'];
                            $timelineData['title'] = "Employee Career Mobility";
                            // $timelineData['description'] = "Transfer from " . optional($result->fromOrganizationModel)->name . " to " . optional($result->toOrganizationModel)->name;
                            $description = EmployeeCarrierMobility::getTypewiseName($mobilityData);
                            $timelineData['description'] = $mobilityData->getTypeList(). $description; 
                            $timelineData['icon'] = "icon-truck";
                            $timelineData['color'] = "secondary";
                            $timelineData['reference'] = "employee-carrier-mobility";
                            $timelineData['reference_id'] = $mobilityData->id;
                            $timelineData['carrier_mobility_id'] = $mobilityData->id;
                            Employee::saveEmployeeTimelineData($mobilityData['employee_id'], $timelineData);
                            
                            // if (isset($data[4]) && ($employeeOldData->organization_id != $data[4])) {
                            //     EmployeeLeave::where('leave_year_id', $currentLeaveYearModel->id)->where('employee_id', $employeeOldData->id)->delete();
                            //     EmployeeLeaveOpening::where('leave_year_id', $currentLeaveYearModel->id)->where('organization_id', $employeeOldData->organization_id)->where('employee_id', $employeeOldData->id)->delete();
                            // }

                            //Update employee data
                            // if(isset($employeeNewData) && !empty($employeeNewData)){
                            //     $emp->update($employeeOldData->id, $employeeNewData);
                            // }
    
                            //Update employee payroll data
                            // if(isset($employeeNewPayrollData) && !empty($employeeNewPayrollData)){
                            //     EmployeePayrollRelatedDetail::saveData(optional($employeeOldData->payrollRelatedDetailModel)->employee_id, $employeeNewPayrollData);
                            // }
                        }
                    }
                }
            }
            toastr()->success('Career Mobility Uploaded Succesfully');
        } catch (\Exception $e) {
            toastr('Data Format For Excel Upload Is Invalid ', 'error');
        }
    }
}
