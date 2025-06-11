<?php

namespace App\Modules\BulkUpload\Service\Import;

use App\Helpers\DateTimeHelper;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Entities\EmployeeLeave;
use App\Modules\Holiday\Entities\HolidayDetail;
use App\Modules\Leave\Entities\LeaveType;
use App\Modules\Leave\Repositories\LeaveRepository;
use Carbon\Carbon;

class LeaveHistoryDetailImport
{

    public static function import($array)
    {
        $successCount = 0;
        $errorLog = [];

        try {
            $leaveObj = new LeaveRepository();

            foreach ($array as $rowIndex => $data) {
                $rowNumber = $rowIndex + 1; // Convert to 1-based index for user-friendly reporting
                $rowErrors = [];

                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }

                // Validate employee code
                if (empty($data[1])) {
                    $rowErrors[] = "Employee Code is required (Column B)";
                    continue;
                }
                $employeeCode = $data[1];
                $employee = Employee::findByEmployeeCode($data[1]);
                if (!$employee) {
                    $rowErrors[] = "Employee with code '{$data[1]}' not found (Column B)";
                    $errorLog[$rowNumber] = $rowErrors;
                    continue;
                }

                // Validate leave type
                if (empty($data[2])) {
                    $rowErrors[] = "Leave Type Code is required (Column C)";
                } else {
                    $leaveType = LeaveType::where('code', $data[2])
                        ->where('organization_id', $employee->organization_id)
                        ->where('status', 11)
                        ->where('leave_year_id', getCurrentLeaveYearId())
                        ->first();

                    if (!$leaveType) {
                        $rowErrors[] = "Leave Type with code '{$data[2]}' not found or not active (Column C)";
                    }
                }

                // Validate date range
                if (empty($data[3])) {
                    $rowErrors[] = "Date Range is required (Column D)";
                } else {
                    $dateRange = str_replace(['-', '_', '–'], '-', $data[3]);
                    $leaveDate = explode(' - ', $dateRange);
                    if (count($leaveDate) !== 2) {
                        $rowErrors[] = "Invalid Date Range format. Expected format: 'YYYY-MM-DD - YYYY-MM-DD' (Column D)";
                    } else {
                        try {
                            $startDate = date_converter()->nep_to_eng_convert($leaveDate[0]);
                            $endDate = date_converter()->nep_to_eng_convert($leaveDate[1]);
                        } catch (\Exception $e) {
                            $rowErrors[] = "Invalid date format in Date Range. Please use Nepali date format (Column D)";
                        }
                    }
                }

                // Validate leave kind
                if (empty($data[4])) {
                    $rowErrors[] = "Leave Kind is required (Column E)";
                } elseif (!in_array($data[4], ['Full Leave', 'First Half', 'Second Half'])) {
                    $rowErrors[] = "Invalid Leave Kind. Must be 'Full Leave', 'First Half', or 'Second Half' (Column E)";
                }

                // Validate status
                if (empty($data[5])) {
                    $rowErrors[] = "Status is required (Column F)";
                } elseif (!in_array($data[5], ['Pending', 'Forwarded', 'Accepted', 'Rejected'])) {
                    $rowErrors[] = "Invalid Status. Must be 'Pending', 'Forwarded', 'Accepted', or 'Rejected' (Column F)";
                }

                // If status is Accepted, validate accepted by and approved date
                if ($data[5] == 'Accepted') {
                    if (!empty($data[6])) {
                        $acceptedByEmp = Employee::findByEmployeeCode($data[6]);
                        if (!$acceptedByEmp) {
                            $rowErrors[] = "Accepted By employee with code '{$data[6]}' not found (Column G)";
                        }
                    }

                    if (!empty($data[7])) {
                        if (!is_numeric($data[7])) {
                            $rowErrors[] = "Approved Date must be a valid Excel date value (Column H)";
                        }
                    }
                }

                // If there are any errors for this row, log them and skip processing
                if (!empty($rowErrors)) {
                    $errorLog[$rowNumber] = $rowErrors;
                    continue;
                }

                // Process the row if no errors
                try {
                    $inputData['employee_id'] = $employee->id;
                    $inputData['organization_id'] = $employee->organization_id;
                    $inputData['leave_type_id'] = $leaveType->id;

                    // Set leave kind and half type
                    if ($data[4] == 'Full Leave') {
                        $inputData['leave_kind'] = 2;
                        $inputData['half_type'] = null;
                    } elseif ($data[4] == 'First Half') {
                        $inputData['leave_kind'] = 1;
                        $inputData['half_type'] = 1;
                    } elseif ($data[4] == 'Second Half') {
                        $inputData['leave_kind'] = 1;
                        $inputData['half_type'] = 2;
                    }

                    // Set status
                    if ($data[5] == 'Pending') {
                        $inputData['status'] = 1;
                    } elseif ($data[5] == 'Forwarded') {
                        $inputData['status'] = 2;
                    } elseif ($data[5] == 'Accepted') {
                        $inputData['status'] = 3;
                        if (!empty($data[6])) {
                            $acceptedByEmp = Employee::findByEmployeeCode($data[6]);
                            $inputData['accept_by'] = optional($acceptedByEmp->getUser)->id;
                        }

                        if (!empty($data[7])) {
                            $timestamp = ($data[7] - 25569) * 86400;
                            $approvedDateTime = gmdate('Y-m-d H:i:s', $timestamp);
                            $inputData['approved_date'] = $approvedDateTime;
                        }
                    } elseif ($data[5] == 'Rejected') {
                        $inputData['status'] = 4;
                    }

                    // Set created at date
                    if (!empty($data[8]) && is_numeric($data[8])) {
                        $excelDate = floatval($data[8]);
                        $timestamp = ($excelDate - 25569) * 86400;
                        $createdDateTime = gmdate('Y-m-d H:i:s', $timestamp);
                        $inputData['created_at'] = $createdDateTime;
                    } else {
                        $inputData['created_at'] = now();
                    }

                    $inputData['reason'] = $data[9] ?? null;

                    // Set alternative employee if provided
                    if (!empty($data[10])) {
                        $alternativeEmp = Employee::findByEmployeeCode($data[10]);
                        if ($alternativeEmp) {
                            $inputData['alt_employee_id'] = $alternativeEmp->id;
                        } else {
                            $rowErrors[] = "Alternative Employee with code '{$data[10]}' not found (Column K)";
                        }
                    }

                    $inputData['alt_employee_message'] = $data[11] ?? null;

                    // Calculate dates
                    $parentId = null;
                    $dateRange = str_replace(['-', '_', '–'], '-', $data[3]);
                    $leaveDate = explode(' - ', $dateRange);
                    $startDate = date_converter()->nep_to_eng_convert($leaveDate[0]);
                    $endDate = date_converter()->nep_to_eng_convert($leaveDate[1]);
                    $tempDate = $startDate;
                    $existingCount = 0;
                    $employeeDayOffs = $employee->getEmployeeDayList();

                    if ($inputData['leave_kind'] == 2) {
                        $days = DateTimeHelper::DateDiffInDay($startDate, $endDate) + 1;

                        for ($i = 1; $i <= $days; $i++) {
                            $inputData['date'] = $tempDate;
                            $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);

                            $holidayModel = HolidayDetail::whereHas('holiday', function ($query) use ($employee) {
                                $gender = $employee->getGender;
                                switch ($gender->dropvalue) {
                                    case 'Male':
                                        $gender_type = 3;
                                        break;
                                    case 'Female':
                                        $gender_type = 2;
                                        break;
                                    default:
                                        $gender_type = 1;
                                        break;
                                }


                                $query->where(function ($q) use ($employee) {
                                    $q->where('apply_for_all', 11)
                                        ->orWhere(function ($q) use ($employee) {
                                            $q->where('branch_id', $employee->branch_id)
                                                ->where('apply_for_all', '!=', 11);
                                        });
                                })->where('gender_type', $gender_type);
                            })
                                ->where('eng_date', '=', $inputData['date'])
                                ->first();

                            $check = $leaveObj->checkData($inputData);
                            if ($check) {
                                $existingCount++;
                            } elseif (in_array(Carbon::parse($inputData['date'])->format('l'), $employeeDayOffs) || $holidayModel) {
                                if ($leaveType->sandwitch_rule_status == '11') {
                                    $finalData = $inputData;
                                    $finalData['parent_id'] = $parentId;
                                    $leave_data = $leaveObj->create($finalData);
                                    if ($parentId == null) {
                                        $parentId = $leave_data->id;
                                    }
                                    if ($leave_data && ($leave_data['status'] == 1 || $leave_data['status'] == 2 || $leave_data['status'] == 3)) {
                                        $inputData['numberOfDays'] = 1;
                                        EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                    }
                                }
                            } else {
                                $inputData['parent_id'] = $parentId;
                                $leave_data = $leaveObj->create($inputData);
                                if ($parentId == null) {
                                    $parentId = $leave_data->id;
                                }
                                if ($leave_data && ($leave_data['status'] == 1 || $leave_data['status'] == 2 || $leave_data['status'] == 3)) {
                                    $inputData['numberOfDays'] = 1;
                                    EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                                }
                            }
                            $tempDate = date('Y-m-d', strtotime('+1 day', strtotime($tempDate)));
                        }
                    } elseif ($inputData['leave_kind'] == 1) {
                        $inputData['date'] = $tempDate;
                        $inputData['nepali_date'] = date_converter()->eng_to_nep_convert($tempDate);

                        $holidayModel = HolidayDetail::whereHas('holiday', function ($query) use ($employee) {
                            $gender = $employee->getGender;
                            switch ($gender->dropvalue) {
                                case 'Male':
                                    $gender_type = 3;
                                    break;
                                case 'Female':
                                    $gender_type = 2;
                                    break;
                                default:
                                    $gender_type = 1;
                                    break;
                            }


                            $query->where(function ($q) use ($employee) {
                                $q->where('apply_for_all', 11)
                                    ->orWhere(function ($q) use ($employee) {
                                        $q->where('branch_id', $employee->branch_id)
                                            ->where('apply_for_all', '!=', 11);
                                    });
                            })->where('gender_type', $gender_type);
                        })
                            ->where('eng_date', '=', $inputData['date'])
                            ->first();

                        $check = $leaveObj->checkData($inputData);
                        if ($check) {
                            $existingCount++;
                        } elseif ($holidayModel) {
                            $rowErrors[] = "Holiday found on {$tempDate}, leave not created";
                        } elseif (in_array(Carbon::parse($inputData['date'])->format('l'), $employeeDayOffs)) {
                            $rowErrors[] = "Day Off found on {$tempDate}, leave not created";
                        } else {
                            $leave_data = $leaveObj->create($inputData);
                            if ($leave_data && ($leave_data['status'] == 1 || $leave_data['status'] == 2 || $leave_data['status'] == 3)) {
                                $inputData['numberOfDays'] = 0.5;
                                EmployeeLeave::updateRemainingLeave($inputData, 'SUB');
                            }
                        }
                    }

                    if ($existingCount > 0) {
                        $rowErrors[] = "Employee {$employeeCode}: {$existingCount} leave days were duplicates and not created";
                    }

                    if (empty($rowErrors)) {
                        $successCount++;
                    } else {
                        $errorLog[$rowNumber] = $rowErrors;
                    }
                } catch (\Exception $e) {
                    $errorLog[$rowNumber] = ["Processing error: " . $e->getMessage()];
                }
            }

            // Prepare final response
            $message = "Bulk upload completed. Success: {$successCount}, Errors: " . count($errorLog);

            if (!empty($errorLog)) {
                $detailedErrors = [];
                foreach ($errorLog as $row => $errors) {
                    $detailedErrors[] = "Row {$row}: " . implode("; ", $errors);
                }

                return [
                    'success' => false,
                    'message' => $message,
                    'errors' => $detailedErrors
                ];
            }

            return [
                'success' => true,
                'message' => $message,
                'errors' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "System error during bulk upload",
                'errors' => [$e->getMessage()]
            ];
        }
    }
}
