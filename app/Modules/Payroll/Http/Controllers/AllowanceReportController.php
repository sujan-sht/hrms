<?php

namespace App\Modules\Payroll\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Setting\Entities\Level;
use App\Modules\Holiday\Entities\Holiday;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Setting\Entities\Department;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Shift\Entities\ShiftGroupMember;
use App\Modules\Leave\Repositories\LeaveInterface;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\Holiday\Repositories\HolidayInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Payroll\Repositories\EmployeeSetupInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Attendance\Repositories\AttendanceReportInterface;
use App\Modules\Event\Repositories\EventInterface;


class AllowanceReportController extends Controller
{

    protected $employee;
    protected $organization;
    private $employeeObj;
    public $holiday;
    public $event;


    public function __construct(
        EmployeeInterface $employee,
        OrganizationInterface $organization,
        EmployeeInterface $employeeObj,
        HolidayInterface $holiday,
        EventInterface $event
    ) {
        $this->employee = $employee;
        $this->organization = $organization;
        $this->employeeObj = $employeeObj;
        $this->holiday = $holiday;
        $this->event = $event;
    }

    public function index(Request $request)
    {
        $type = $request->type ?? 'food';
        $dataLists = [];
        $levels = $this->getAllowedLevels();
        $levelIds = $this->getLevelIds($levels);
        $supplyChain = $this->getSupplyChainDepartment();

        $rateArray = [
            'food' => 25,
            'shift' => 100,
            'night' => 30,
            'holiday' => 92,
        ];

        $rate = $rateArray[$type] ?? 0;


        $employeesData = collect();

        if ($supplyChain && $request->organization_id) {
            $employees = $this->getEmployees($request->organization_id, $levelIds, $supplyChain->id, $request->employee_id ?? null);

            $employeesData = $employees->map(function ($employee) use ($rate) {


                return (object)[
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'name' => trim("{$employee->first_name} {$employee->middle_name} {$employee->last_name}"),
                ];
            });
            if ($employeesData->count() > 0) {
                $dataLists = $this->getReportOfEmployee($employeesData, $request->all(), $type, $rateArray);
            }
        }
        $data = [
            'organizationList' => $this->organization->getList(),
            'type' => $type,
            'employeeData' => $dataLists,
        ];

        return view('payroll::allowance-report.index', $data);
    }

    public function getEmployee(Request $request)
    {
        $levels = $this->getAllowedLevels();
        $levelIds = $this->getLevelIds($levels);
        $supplyChain = $this->getSupplyChainDepartment();

        if (!($supplyChain && $request->organization_id)) {
            return response()->json([]);
        }

        $employees = $this->getEmployees($request->organization_id, $levelIds, $supplyChain->id, $employee_id ?? null);

        return response()->json($employees->map(function ($employee) {
            return (object)[
                'id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'name' => trim("{$employee->first_name} {$employee->middle_name} {$employee->last_name}"),
            ];
        }));
    }

    // --- Reusable Private Methods ---

    private function getAllowedLevels(): array
    {
        return [
            'WG1',
            'WG2',
            'WG3',
            'WG4',
            'G-1',
            'G-2',
            'G-3',
            'G-4',
        ];
    }

    public function getTimeSet(): array
    {
        return [
            'food' => 2, //this is time 2 hr 1 unit
            'shift' => 6,
            'night' => 6,
            'holiday' => 1,
        ];
    }

    private function getLevelIds(array $levels): \Illuminate\Support\Collection
    {
        return Level::whereIn('short_code', $levels)->pluck('id');
    }

    private function getSupplyChainDepartment(): ?Department
    {
        return Department::where('title', '50006744-Supply Chain')
            ->orWhere('short_code', 50006744)
            ->orWhere('title', 'Supply Chain')
            ->select('id', 'title')
            ->first();
    }

    private function getEmployees($organizationId, $levelIds, $departmentId, $empId)
    {
        return Employee::where('department_id', $departmentId)
            ->where('organization_id', $organizationId)
            ->whereIn('level_id', $levelIds)
            ->select('id', 'first_name', 'middle_name', 'last_name', 'employee_code')
            ->when($empId, function ($query, $empId) {
                return $query->where('id', $empId);
            })
            ->get();
    }

    public function getReportOfEmployee($employeeData, $filters, $type, $rateArray)
    {
        $employeeIds = $employeeData->pluck('id');

        $rate = $rateArray[$type];

        // Get holidays if type is holiday
        $holidays = [];
        if ($type == 'holiday') {
            $holidays = $this->getCalendarEventHolidayByAjax($filters);
            $holidayDates = collect($holidays)->where('type', 'holiday')->pluck('start')->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->toArray();
        }

        $attendanceQuery = Attendance::whereIn('emp_id', $employeeIds)
            ->when(!empty($filters['from_date']) && !empty($filters['to_date']), function ($query) use ($filters) {
                $query->whereBetween('date', [$filters['from_date'], $filters['to_date']]);
            });
        // Filter for holidays only if type is holiday
        if ($type == 'holiday') {
            $attendanceQuery->whereIn('date', $holidayDates);
        }

        $attendance = $attendanceQuery
            ->orderBy('emp_id')
            ->orderBy('date')
            ->orderBy('checkin', 'asc')
            ->get();

        $grouped = $attendance->groupBy(function ($item) {
            return $item->emp_id . '_' . $item->date;
        });

        $report = [];

        foreach ($employeeData as $employee) {
            $empId = $employee->id;
            $empName = $employee->name;

            // Skip shift info for holidays since we don't need it
            if ($type != 'holiday') {
                $shiftGroupMember = ShiftGroupMember::where('group_member', $employee->id)
                    ->whereHas('group.shift', function ($query) use ($type) {
                        if ($type == 'night') {
                            $query->where('custom_title', 'like', '%night%');
                        } else {
                            $query->where('custom_title', 'like', '%day%');
                        }
                    })
                    ->with([
                        'group',
                        'group.shift',
                        'group.shift.shiftDayWiseHasOne',
                    ])
                    ->first();

                if (!$shiftGroupMember || !$shiftGroupMember->group->shift->shiftDayWiseHasOne) {
                    $report[] = [
                        'employee_id' => $empId,
                        'employee_name' => $empName,
                        'employee_code' => $employee->employee_code,
                        'rate' => 0,
                        'unit' => 0,
                        'amount' => 0,
                        'total' => 0,
                        'tax' => 0,
                        'error' => 'Shift information not found',
                    ];
                    continue;
                }

                $shiftDay = $shiftGroupMember->group->shift->shiftDayWiseHasOne;
                $shiftStart = Carbon::createFromFormat('H:i', $shiftDay->start_time);
                $shiftEnd = Carbon::createFromFormat('H:i', $shiftDay->end_time);
                // Handle shifts that go past midnight
                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay();
                }

                $totalShiftSeconds = $shiftStart->diffInSeconds($shiftEnd);
                $shiftDurationFormatted = $shiftStart->diff($shiftEnd)->format('%H:%I:%S');
            } else {
                // For holidays, we consider all hours as overtime
                $totalShiftSeconds = 0;
                $shiftDurationFormatted = '00:00:00';
                $shiftDay = (object)['start_time' => '00:00', 'end_time' => '00:00'];
                $shiftGroupMember = (object)[
                    'group' => (object)[
                        'shift' => (object)[
                            'custom_title' => 'Holiday'
                        ]
                    ]
                ];
            }

            $empData = [];
            $totalWorkSeconds = 0;
            $totalOvertimeSeconds = 0;
            $totalOvertimeUnits = 0;

            foreach ($grouped as $key => $records) {
                if ($records->first()->emp_id !== $empId) {
                    continue;
                }

                $date = $records->first()->date;
                $checkin = $records->first()->checkin;
                $checkout = $records->last()->checkout;

                $checkinTime = Carbon::parse($checkin);
                $checkoutTime = Carbon::parse($checkout);

                // Handle overnight shifts
                if ($checkoutTime < $checkinTime) {
                    $checkoutTime->addDay();
                }

                $duration = $checkoutTime->diffInSeconds($checkinTime);
                $durationFormatted = gmdate('H:i:s', $duration);

                // For holidays, all hours are considered overtime
                $overtime = $type == 'holiday' ? $duration : max(0, $duration - $totalShiftSeconds);
                $overtimeFormatted = gmdate('H:i:s', $overtime);

                $unitSet = $this->getTimeSet()[$type];
                $overtimeHours = $overtime / 3600;
                if ($type == 'holiday') {
                    $overtimeUnits = 1;
                } elseif ($type == 'shift') {
                    $totalWork = $totalShiftSeconds / 3600;
                    $overtimeUnits =  (int)($totalWork / $unitSet);
                } elseif ($type == 'night') {
                    $totalWork = $totalShiftSeconds / 3600;
                    $overtimeUnits =  (int)($totalWork / $unitSet);
                } else {

                    $overtimeUnits = (int)($overtimeHours / $unitSet);
                }

                $totalWorkSeconds += $duration;
                $totalOvertimeSeconds += $overtime;
                $totalOvertimeUnits += $overtimeUnits;

                $empData[] = [
                    'date' => $date,
                    'shift_start' => $shiftDay->start_time,
                    'shift_end' => $shiftDay->end_time,
                    'shift_duration' => $shiftDurationFormatted,
                    'checkin' => $checkin,
                    'checkout' => $checkout,
                    'actual_duration' => $durationFormatted,
                    'overtime' => $overtimeFormatted,
                    'daily_units' => $overtimeUnits,
                    'entries' => $records->count(),
                    'is_holiday' => $type == 'holiday'
                ];
            }

            $totalWork = gmdate('H:i:s', $totalWorkSeconds);
            $totalOvertime = gmdate('H:i:s', $totalOvertimeSeconds);
            $amount = $totalOvertimeUnits * $rate;
            $tax = 0;
            $total = $amount - $tax;

            $report[] = [
                'employee_id' => $empId,
                'employee_name' => $empName,
                'employee_code' => $employee->employee_code,
                'shift_name' => $shiftGroupMember->group->shift->custom_title ?? ($type == 'holiday' ? 'Holiday' : 'N/A'),
                'shift_start' => $shiftDay->start_time,
                'shift_end' => $shiftDay->end_time,
                'shift_duration' => $shiftDurationFormatted,
                'total_work' => $totalWork,
                'total_overtime' => $totalOvertime,
                'total_units' => $totalOvertimeUnits,
                'rate' => $rate,
                'tax' => $tax,
                'amount' => $amount,
                'total' => $total,
                'days' => $empData,
                'report_type' => $type
            ];
        }

        return $report;
    }

    public function getCalendarEventHolidayByAjax($filter)
    {

        $filter['start'] = $filter['from_date'];
        $filter['end'] = $filter['to_date'];

        //   dd($filter);
        $data = $eventArray = $holidayArray = [];
        $eventResult = $this->event->findAll('', $filter);
        // dd($eventResult);
        foreach ($eventResult as $key => $event) {
            $eventArray[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => date('Y-m-d H:i:s', strtotime("$event->event_start_date $event->event_time")),
                // 'start' => $event->event_start_date,
                'end' => $event->event_end_date,
                'type' => 'event',
                'color' => auth()->user()->id == $event->created_by ? '#f58646' : '#3a87ad',
                'created_by' => $event->created_by,
                'description' => $event->description,
                // 'taggable_users'=>$event->users->pluck('id')
            ];
        }

        $holidays = $this->holiday->findAll('', $filter);

        foreach ($holidays as $key => $holiday) {
            foreach ($holiday->holidayDetail as $key => $value) {
                $holidayArray[] = [
                    'id' => $value['id'],
                    'title' => $value['sub_title'],
                    'start' => $value['eng_date'],
                    'end' => $value['eng_date'],
                    'type' => 'holiday',
                    'color' => '#eb1e4e',
                    'description' => '',

                ];
            }
        }
        $data = array_merge($eventArray, $holidayArray);
        return $data;
        // }
    }


    // get all report in same
    /**
     *
     * Get full
     */

    public function allReportIndex(Request $request)
    {
        $type = $request->type ?? 'food';
         $request['full_report'] = true;

        $dataLists = [];
        $levels = $this->getAllowedLevels();
        $levelIds = $this->getLevelIds($levels);
        $supplyChain = $this->getSupplyChainDepartment();

        $rateArray = [
            'food' => 25,
            'shift' => 100,
            'night' => 30,
            'holiday' => 92,
        ];

        $employeesData = collect();

        if ($supplyChain && $request->organization_id) {
            $employees = $this->getEmployees($request->organization_id, $levelIds, $supplyChain->id, $request->employee_id ?? null);

            $employeesData = $employees->map(function ($employee) {
                return (object)[
                    'id' => $employee->id,
                    'employee_code' => $employee->employee_code,
                    'name' => trim("{$employee->first_name} {$employee->middle_name} {$employee->last_name}"),
                ];
            });

            if ($employeesData->count() > 0) {
                if ($request->full_report) {
                    // Get full combined report
                    $dataLists = $this->getFullAllowanceReport($employeesData, $request->all(), $rateArray);
                } else {
                    // Get single type report (original behavior)
                    $dataLists = $this->getReportOfEmployee($employeesData, $request->all(), $type, $rateArray);
                }
            }
        }
        $data = [
            'organizationList' => $this->organization->getList(),
            'type' => $type,
            'employeeData' => $dataLists,
            'fullReport' => $request->full_report ?? false,
        ];

        return view('payroll::allowance-report.all-report', $data);
    }

    public function getFullAllowanceReport($employeeData, $filters, $rateArray)
    {
        // Initialize the full report array
        $fullReport = [];

        // Get all report types in order
        $reportTypes = ['holiday', 'night', 'food', 'shift'];

        // Get holidays once since they're used for holiday report
        $holidays = $this->getCalendarEventHolidayByAjax($filters);
        $holidayDates = collect($holidays)->where('type', 'holiday')->pluck('start')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        // Get attendance data once for all report types
        $employeeIds = $employeeData->pluck('id');
        $attendanceQuery = Attendance::whereIn('emp_id', $employeeIds)
            ->when(!empty($filters['from_date']) && !empty($filters['to_date']), function ($query) use ($filters) {
                $query->whereBetween('date', [$filters['from_date'], $filters['to_date']]);
            })
            ->orderBy('emp_id')
            ->orderBy('date')
            ->orderBy('checkin', 'asc')
            ->get();

        $groupedAttendance = $attendanceQuery->groupBy(function ($item) {
            return $item->emp_id . '_' . $item->date;
        });

        // Process each employee
        foreach ($employeeData as $employee) {
            $empId = $employee->id;
            $empName = $employee->name;
            $employeeCode = $employee->employee_code;

            // Initialize employee record with basic info
            $employeeRecord = [
                'employee_id' => $empId,
                'employee_name' => $empName,
                'employee_code' => $employeeCode,
                'reports' => []
            ];

            // Get shift information once (used for multiple report types)
            $shiftGroupMember = ShiftGroupMember::where('group_member', $employee->id)
                ->with([
                    'group',
                    'group.shift',
                    'group.shift.shiftDayWiseHasOne',
                ])
                ->first();

            // Process each report type
            foreach ($reportTypes as $type) {
                $rate = $rateArray[$type] ?? 0;

                // Skip if rate is 0 (type not defined in rate array)
                if ($rate === 0) continue;

                // Get the report for this type
                $typeReport = $this->getSingleTypeReport(
                    $employee,
                    $type,
                    $rate,
                    $groupedAttendance,
                    $shiftGroupMember,
                    $holidayDates,
                    $filters
                );

                // Add to employee's reports
                $employeeRecord['reports'][$type] = $typeReport;
            }

            // Add employee to full report
            $fullReport[] = $employeeRecord;
        }

        return $fullReport;
    }

    private function getSingleTypeReport($employee, $type, $rate, $groupedAttendance, $shiftGroupMember, $holidayDates, $filters)
    {
        $empId = $employee->id;

        // Skip shift info for holidays since we don't need it
        if ($type != 'holiday') {
            if (!$shiftGroupMember || !$shiftGroupMember->group->shift->shiftDayWiseHasOne) {
                return [
                    'rate' => 0,
                    'unit' => 0,
                    'amount' => 0,
                    'total' => 0,
                    'tax' => 0,
                    'error' => 'Shift information not found',
                    'days' => []
                ];
            }

            $shiftDay = $shiftGroupMember->group->shift->shiftDayWiseHasOne;
            $shiftStart = Carbon::createFromFormat('H:i', $shiftDay->start_time);
            $shiftEnd = Carbon::createFromFormat('H:i', $shiftDay->end_time);

            // Handle shifts that go past midnight
            if ($shiftEnd->lessThan($shiftStart)) {
                $shiftEnd->addDay();
            }

            $totalShiftSeconds = $shiftStart->diffInSeconds($shiftEnd);
            $shiftDurationFormatted = $shiftStart->diff($shiftEnd)->format('%H:%I:%S');
        } else {
            // For holidays, we consider all hours as overtime
            $totalShiftSeconds = 0;
            $shiftDurationFormatted = '00:00:00';
            $shiftDay = (object)['start_time' => '00:00', 'end_time' => '00:00'];
        }

        $empData = [];
        $totalWorkSeconds = 0;
        $totalOvertimeSeconds = 0;
        $totalOvertimeUnits = 0;

        foreach ($groupedAttendance as $key => $records) {
            if ($records->first()->emp_id !== $empId) {
                continue;
            }

            $date = $records->first()->date;

            // Skip if not a holiday date for holiday report
            if ($type == 'holiday' && !in_array($date, $holidayDates)) {
                continue;
            }

            // Skip if holiday date for non-holiday reports (optional)
            // if ($type != 'holiday' && in_array($date, $holidayDates)) {
            //     continue;
            // }

            $checkin = $records->first()->checkin;
            $checkout = $records->last()->checkout;

            $checkinTime = Carbon::parse($checkin);
            $checkoutTime = Carbon::parse($checkout);

            // Handle overnight shifts
            if ($checkoutTime < $checkinTime) {
                $checkoutTime->addDay();
            }

            $duration = $checkoutTime->diffInSeconds($checkinTime);
            $durationFormatted = gmdate('H:i:s', $duration);

            // For holidays, all hours are considered overtime
            $overtime = $type == 'holiday' ? $duration : max(0, $duration - $totalShiftSeconds);
            $overtimeFormatted = gmdate('H:i:s', $overtime);

            $unitSet = $this->getTimeSet()[$type];
            $overtimeHours = $overtime / 3600;

            if ($type == 'holiday') {
                $overtimeUnits = 1;
            } elseif ($type == 'shift') {
                $totalWork = $totalShiftSeconds / 3600;
                $overtimeUnits = (int)($totalWork / $unitSet);
            } elseif ($type == 'night') {
                $totalWork = $totalShiftSeconds / 3600;
                $overtimeUnits = (int)($totalWork / $unitSet);
            } else {
                $overtimeUnits = (int)($overtimeHours / $unitSet);
            }

            $totalWorkSeconds += $duration;
            $totalOvertimeSeconds += $overtime;
            $totalOvertimeUnits += $overtimeUnits;

            $empData[] = [
                'date' => $date,
                'shift_start' => $shiftDay->start_time,
                'shift_end' => $shiftDay->end_time,
                'shift_duration' => $shiftDurationFormatted,
                'checkin' => $checkin,
                'checkout' => $checkout,
                'actual_duration' => $durationFormatted,
                'overtime' => $overtimeFormatted,
                'daily_units' => $overtimeUnits,
                'entries' => $records->count(),
                'is_holiday' => $type == 'holiday'
            ];
        }

        $totalWork = gmdate('H:i:s', $totalWorkSeconds);
        $totalOvertime = gmdate('H:i:s', $totalOvertimeSeconds);
        $amount = $totalOvertimeUnits * $rate;
        $tax = 0;
        $total = $amount - $tax;

        return [
            'shift_name' => $type == 'holiday' ? 'Holiday' : ($shiftGroupMember->group->shift->custom_title ?? 'N/A'),
            'shift_start' => $shiftDay->start_time,
            'shift_end' => $shiftDay->end_time,
            'shift_duration' => $shiftDurationFormatted,
            'total_work' => $totalWork,
            'total_overtime' => $totalOvertime,
            'total_units' => $totalOvertimeUnits,
            'rate' => $rate,
            'tax' => $tax,
            'amount' => $amount,
            'total' => $total,
            'days' => $empData,
            'report_type' => $type
        ];
    }

    // end
}
