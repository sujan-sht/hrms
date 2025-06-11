<?php

namespace App\Modules\BulkUpload\Service\Import;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Modules\Product\Entities\Product;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Product\Entities\ProductVin;
// use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Shift\Repositories\ShiftRepository;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\Attendance\Repositories\AttendanceRepository;

class AttendanceLogDetailImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as  $index => $row) {
                $rowNumber = $index + 2;

                $employee = Employee::where('employee_code', $row['employee_code'])->first();
                if (!$employee) {
                    return [
                        'success' => false,
                        'message' => "Error at Row $rowNumber, Employee does not exist. Uploaded successfully up to Row " . ($rowNumber - 1) . "!!"
                    ];
                }
                if ($employee) {
                    $date = Date::excelToDateTimeObject($row['date'])->format('Y-m-d');
                    $daywiseShift = (new AttendanceRepository())->getDayWiseShift($employee->id, $date);
                    $newShiftEmp = NewShiftEmployee::getShiftEmployee($employee->id, $date);

                    if (isset($newShiftEmp)) {
                        $rosterShift = $newShiftEmp->newShiftEmployeeDetails->first();
                        if (isset($rosterShift) && isset($rosterShift->shift_id) && ($rosterShift->shift_id != null)) {
                            $employeeShift = (new ShiftRepository())->find($rosterShift->shift_id);
                            if ($employeeShift) {
                                $day = date('D', strtotime($date));
                                $daywiseShift = $employeeShift->getShiftDayWise($day);
                            }
                        }
                    }

                    if (isset($row->time)) {

                        $time = Date::excelToDateTimeObject($row['time'])->format('H:i:s');
                        if ($daywiseShift) {
                            if ($time < $daywiseShift->checkin_start_time) {
                                $convertedDate = Carbon::parse($date);
                                $date = $convertedDate->subDay()->toDateString();
                            }
                        }
                    }

                    $checkInRaw = $row['check_in'] ?? null;
                    $checkOutRaw = $row['checkout'] ?? null;

                    $checkInFormatted = $this->cleanAndFormatTime($checkInRaw);
                    $checkOutFormatted = $this->cleanAndFormatTime($checkOutRaw);
                    $log = [
                        'emp_id' => $employee->id,
                        'biometric_emp_id' => @$row['biometric_id'] ?? $employee->biometric_id,
                        'org_id' => $employee->organization_id,
                        'date' => $date,
                        'time' => @$time,
                        'inout_mode' => @$row['in_out'],
                        'punch_from' => @$row['punch_from'],
                        'source' => @$row['source'],
                        'check_in' => @$checkInFormatted,
                        'check_out' => @$checkOutFormatted,
                    ];

                    $attendance = AttendanceLog::where('emp_id', $employee->id)->where('date', @$row['date'])->first();
                    if ($attendance) {
                        $attd = $attendance->update($log);
                    } else {
                        $attd = AttendanceLog::create($log);
                    }

                    $success =  (new AttendanceRepository())->saveAttendance($employee, $log);

                    // AttendanceLog::updateOrCreate([
                    //     'date' => $date,
                    //     'time' => $time,
                    //     'biometric_emp_id' => $row['biometric_id'],
                    // ], $log);\


                }
            }
            if ($success) {
                return [
                    'success' => true,
                    'message' => "Bulk Upload Completed Successfully!"
                ];
            }
            return [
                'success' => false,
                'message' => "Error at Row $rowNumber, Bulk upload not completed. Uploaded successfull upto $rowNumber-1. !!"
            ];
            return true;
        } catch (\Exception $e) {
            dd($e);
            // dd($e->getMessage());
            // toastr($message, 'error');
            return false;
        }
        return true;
    }

    private function cleanAndFormatTime($rawTime)
    {
        $time = trim(preg_replace('/[^\x20-\x7E]/', '', $rawTime)); // Remove non-ASCII chars

        if (is_numeric($time)) {
            return Date::excelToDateTimeObject($time)->format('H:i:s');
        }

        // Try valid formats
        $formats = ['h:i:s A', 'h:i A', 'H:i:s', 'H:i'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $time)->format('H:i:s');
            } catch (\Exception $e) {
                continue;
            }
        }

        // If nothing works, return null or throw custom error
        throw new \Exception("Invalid time format: '$rawTime'");
    }
}
