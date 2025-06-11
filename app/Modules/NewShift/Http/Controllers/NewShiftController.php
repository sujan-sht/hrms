<?php

namespace App\Modules\NewShift\Http\Controllers;

use PDF;
use DateTime;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Leave\Entities\Leave;
use App\Modules\Shift\Entities\ShiftGroup;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Shift\Entities\EmployeeShift;
use App\Modules\User\Services\CheckUserRoles;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Holiday\Entities\HolidayDetail;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Attendance\Entities\AttendanceLog;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\NewShift\Repositories\ShiftInterface;
use App\Modules\Shift\Repositories\ShiftGroupInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\NewShift\Entities\NewShiftEmployeeDetail;
use App\Modules\Setting\Repositories\DepartmentInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Attendance\Repositories\AttendanceReportRepository;
use Illuminate\Support\Facades\Log;;

class NewShiftController extends Controller
{
    private $shift, $employment, $dropdown, $organization, $department, $shiftGroup;

    public function __construct(
        ShiftInterface $shift,
        EmployeeInterface $employment,
        DropdownInterface $dropdown,
        OrganizationInterface $organization,
        DepartmentInterface $department,
        ShiftGroupInterface $shiftGroup
    ) {
        $this->employment = $employment;
        $this->shift = $shift;
        $this->dropdown = $dropdown;
        $this->organization = $organization;
        $this->department = $department;
        $this->shiftGroup = $shiftGroup;
    }

    public function index(Request $request)
    {
        $inputData = $request->all();

        $data['title'] = 'Shift';
        $data['shiftModels'] = $this->shift->findAll(null, $inputData);
        $data['employeeList'] = $this->employment->getList();

        return view('newshift::shift.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['title'] = "Shift";
        $data['titleList'] = [
            'Day' => 'Day',
            'Morning' => 'Morning',
            'Night' => 'Night',
            'Custom' => 'Custom'
        ];

        return view('newshift::shift.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        try {
            $this->shift->save($data);
            toastr()->success('Shift Added Successfully.');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('newShift.index');
    }

    public function edit($id)
    {
        $data['title'] = "Shift";

        $data['shiftModel'] = $this->shift->find($id);
        return view('newshift::shift.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $data['updated_by'] = auth()->user()->id;
            $this->shift->update($id, $data);
            toastr()->success('Shift Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->route('newShift.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->shift->delete($id);
            toastr()->success('Shift Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect()->back();
    }

    public function assignShift(Request $request)
    {
        $filter = $request->all();
        // dd($filter);
        // $data['weekRange'] = $weekRange = $this->getWeekRange('', 2023);
        $data['isDefault'] = 2;
        if (isset($filter['organization_id']) && !empty($filter['organization_id'])) {
            $data['isDefault'] = ShiftGroup::where('default', 'yes')->where('org_id', $filter['organization_id'])->value('id') ?? 3;
        }


        // Process date filter
        if (isset($filter['start_date']) && !empty($filter['start_date'])) {
            $explodeDate = [
                0 => $filter['start_date'],
                1 => $filter['end_date']
            ];
            $data['dates'] = CarbonPeriod::create($explodeDate[0], $explodeDate[1]);
        }

        $data['title'] = "New Shift Group";

        // Process shift groups
        $shiftGroupList = ['ShiftGroup' => $this->shiftGroup->getList($filter)];
        $filteredShiftGroupList = ['ShiftGroup' => []];
        if (!empty($shiftGroupList['ShiftGroup'])) {
            foreach ($shiftGroupList['ShiftGroup'] as $shiftGroupId => $shiftGroupName) {
                $filteredShiftGroupList['ShiftGroup'][$shiftGroupId] = $shiftGroupName;
            }
        }
        $data['shiftGrouplists'] = $filteredShiftGroupList;

        // Set pagination limit
        $limit = 20;
        if (isset($filter['sortBy']) && !empty($filter['sortBy'])) {
            $limit = $filter['sortBy'];
        }

        // Get dropdown lists
        $data['organizationList'] = $this->organization->getList();
        $data['designationList'] = $this->dropdown->getFieldBySlug('designation');
        $data['departmentList'] = $this->dropdown->getFieldBySlug('department');

        // Get active employees
        $allActiveEmployeeList = $this->employment->findAllForRoster($limit, $filter);
        $employees = [];
        foreach ($allActiveEmployeeList as $employee) {
            $isExists = $this->shiftGroup->checkShiftExists($employee->id);
            if ($isExists) {
                $employees[] = $this->employment->find($employee->id);
            }
        }

        // Paginate employees
        $page = request()->get('page', 1);
        $perPage = env('DEF_PAGE_LIMIT', 99999);
        $employeesCollection = collect($employees);
        $data['emplists'] = new LengthAwarePaginator(
            $employeesCollection->forPage($page, $perPage),
            $employeesCollection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Initialize services
        $menuRoles = app(CheckUserRoles::class);
        $holidayDetailModel = app(HolidayDetail::class);
        $leaveModel = app(Leave::class);
        $atdReportRepo = app(AttendanceReportRepository::class);

        // Prepare date array
        $dateArr = [];
        if (isset($data['dates'])) {
            foreach ($data['dates'] as $date) {
                $dateArr[] = $date->format('Y-m-d');
            }
        }

        // Get holiday and leave data
        $holidayDetails = $holidayDetailModel
            ->with('holiday')
            ->whereIn('eng_date', $dateArr)
            ->get();

        $leaves = $leaveModel
            ->whereIn('date', $dateArr)
            ->where('status', 3)
            ->whereIn('employee_id', $data['emplists']->pluck('employee_id'))
            ->get();

        // Prepare employee data for view
        $employeesData = [];
        $color = ['success', 'danger', 'indigo', 'teal', 'warning'];

        foreach ($data['emplists'] as $key => $empModel) {
            $employeeData = [
                'key' => $data['emplists']->firstItem() + $key,
                'empModel' => $empModel,
                'color' => $color[rand(0, 4)],
                'dates' => []
            ];

            if (isset($data['dates'])) {
                foreach ($data['dates'] as $date) {
                    $dateFormatted = $date->format('Y-m-d');
                    $newShift = NewShiftEmployee::with('newShiftEmployeeDetailOne')->where('emp_id', $empModel->id)
                        ->where('eng_date', $dateFormatted)
                        ->first();
                    if ($newShift && $newShift->newShiftEmployeeDetailOne) {
                        $employeeSelectedShift = $newShift->newShiftEmployeeDetailOne->shift_group_id ??  null;
                    } else {
                        $employeeSelectedShift =  $data['isDefault'];
                    }


                    // if(!$newShift){
                    //   $empShift = EmployeeShift::where('employee_id',$empModel->id)->select('id','group_id','employee_id')->first();
                    //  $employeeSelectedShift = $empShift->group_id ??  $data['isDefault'];

                    // }

                    // Get shift details
                    $shiftDetail = $atdReportRepo->getActualEmployeeShift($empModel, $date);
                    $employeeShift = $shiftDetail['empActualShift'];
                    $seasonalShiftId = $shiftDetail['seasonalShiftId'];
                    $shiftGroup = $shiftDetail['shiftGroup'];

                    // Process holiday and leave
                    $genderType = optional($empModel->getGender()->first())->dropvalue;
                    $genderType = $genderType == 'Male' ? 3 : ($genderType == 'Female' ? 2 : null);

                    $holiday = $holidayDetails
                        ->where('eng_date', $dateFormatted)
                        ->whereIn('holiday.gender_type', [1, $genderType])
                        ->whereIn('holiday.religion_type', [1, $empModel->religion])
                        ->whereIn('holiday.organization_id', [null, $empModel->organization_id])
                        ->first();

                    $leave = $leaves
                        ->where('date', $dateFormatted)
                        ->where('employee_id', $empModel->id)
                        ->first();

                    $shiftValue = '';
                    $shiftArr = ['D' => 'DayOff'];

                    if ($leave) {
                        $shiftArr = array_merge($shiftArr, ['L' => $leave->getLeaveKind()]);
                        $shiftValue = 'L';
                    }

                    if ($holiday) {
                        $holdiayArr = ['H' => $holiday->sub_title];
                        $shiftArr = array_merge($shiftArr, $holdiayArr);
                        $shiftValue = 'H';
                    }

                    $shiftArr = $shiftArr + $data['shiftGrouplists']['ShiftGroup'];



                    $dayOffs = array_values($empModel->getEmployeeDayList());

                    // Determine background color
                    $backgroundColor = 'floralwhite';
                    if ($shiftValue == 'D') {
                        $backgroundColor = 'lightgray';
                    } elseif ($shiftValue == 'H') {
                        $backgroundColor = 'yellowgreen';
                    } elseif ($shiftValue == 'L') {
                        $backgroundColor = 'firebrick';
                    }

                    $employeeData['dates'][] = [
                        'date' => $dateFormatted,
                        'display_date' => $date->format('M d (D)'),
                        'shiftArr' => $shiftArr,
                        'shiftValue' => $shiftValue == 1 ? (isset($shiftGroup) ? $shiftGroup->id : '') : $shiftValue,
                        'backgroundColor' => $backgroundColor,
                        'shiftGroup' => $shiftGroup,
                        'hasLeave' => (bool)$leave,
                        'hasHoliday' => (bool)$holiday,
                        'isDayOff' => in_array(date('l', strtotime($dateFormatted)), $dayOffs),
                        'selectedShift' => $employeeSelectedShift
                    ];
                }
            }

            $employeesData[] = $employeeData;
        }

        // Add all data to the view
        $data['employeesData'] = $employeesData;
        $data['holidayDetails'] = $holidayDetails;
        $data['leaves'] = $leaves;
        $data['dateArr'] = $dateArr;
        $data['menuRoles'] = $menuRoles;
        $data['holidayDetailModel'] = $holidayDetailModel;
        $data['leaveModel'] = $leaveModel;

        return view('newshift::shift.new-shift', $data);
    }

    public function getWeekRange($month = '', $year)
    {
        $year = date('Y');
        $dates = [];
        for ($i = 1; $i <= 52; $i++) {
            $start_date = (new DateTime())->setISODate($year, $i)->format('Y-m-d');
            $monthNum = date('m', strtotime($start_date));
            $range = ($start_date . '---' . (new DateTime())->setISODate($year, $i, 7)->format('Y-m-d'));
            $dates[$monthNum][$i] = $range;
        }

        return $dates;
    }

    public  function newShiftStore(Request $request)
    {

        try {
            DB::beginTransaction();

            $inputData = ($request->all());
            unset($inputData['empId'], $inputData['engDate'], $inputData['shiftArr']);
            foreach ($inputData['shift_group'] as $employeeId => $dates) {
                // dd($dates);
                foreach ($dates as $engDate => $shift_group_id) {
                    //this code for auto add attendance date time after shift assign
                    // $startTime = null;
                    // $endTime = null;
                    // $dayOfWeek = \Carbon\Carbon::parse($engDate)->format('D');
                    // Get employee shift for the specific day
                    // $employeeShift = EmployeeShift::where('employee_id', $employeeId)
                    //     ->where('group_id', $shift_group_id)
                    //     ->rightJoin('shift_day_wises', 'employee_shifts.shift_id', '=', 'shift_day_wises.shift_id')
                    //     ->where('shift_day_wises.day', $dayOfWeek)
                    //     ->first();
                    // check emp shift
                    // if ($employeeShift) {
                    //     $startTime = $employeeShift->start_time;
                    //     $endTime = $employeeShift->end_time;

                    //     // Get attendance record
                    //     $attendance = Attendance::where('emp_id', $employeeId)
                    //         ->where('date', $engDate)
                    //         ->first();


                    //     if ($attendance) {

                    //         $checkIn = AttendanceLog::where('emp_id', $employeeId)
                    //             ->where('date', $engDate)
                    //             ->where('inout_mode', 0)->first();


                    //         $checkOut = AttendanceLog::where('emp_id', $employeeId)
                    //             ->where('date', $engDate)
                    //             ->where('inout_mode', 1)->first();

                    //         if ($checkIn) {

                    //             $attendance->update([
                    //                 'checkin' => $checkIn->time ?? 0,
                    //                 'checkout' => $checkOut->time ?? 0
                    //             ]);
                    //         }




                    //     }
                    // }


                    if (isset($shift_group_id)) {

                        $newShiftEmp = NewShiftEmployee::with('newShiftEmployeeDetailOne')
                            ->where('emp_id', $employeeId)
                            ->where('eng_date', $engDate)
                            ->first();

                        if ($newShiftEmp) {
                            $shift_group_id = in_array($shift_group_id, ['D', 'H', 'L']) ? null : $shift_group_id;
                            $type = in_array($shift_group_id, ['D', 'H', 'L']) ? $shift_group_id : 'S';

                            $finalArr = [
                                'new_shift_employee_id' => $newShiftEmp->id,
                                'type' => $type,
                                'shift_group_id' => $shift_group_id,
                            ];

                            if ($newShiftEmp->newShiftEmployeeDetailOne) {
                                $detail = $newShiftEmp->newShiftEmployeeDetailOne;

                                // Only update if there's a change
                                if ($detail->shift_group_id !== $shift_group_id) {
                                    $updated = $detail->update([
                                        'shift_group_id' => $shift_group_id,
                                    ]);

                                    if (!$updated) {
                                        Log::warning("Update failed for detail ID {$detail->id} on emp_id {$employeeId} and date {$engDate}");
                                    }
                                } else {
                                    Log::info("No update needed for detail ID {$detail->id} (same shift_group_id) for emp_id {$employeeId} and date {$engDate}");
                                }
                            } else {
                                $created = NewShiftEmployeeDetail::create($finalArr);

                                if (!$created) {
                                    Log::error("Failed to create NewShiftEmployeeDetail for emp_id {$employeeId} and date {$engDate}");
                                }
                            }
                        } else {
                            $data = [
                                'emp_id' => $employeeId,
                                'eng_date' => $engDate,
                                'nep_date' => date_converter()->eng_to_nep_convert($engDate),
                            ];
                            $newShiftEmpData = NewShiftEmployee::create($data);

                            if (!$newShiftEmpData) {
                                Log::error("Failed to create NewShiftEmployee for emp_id {$employeeId} and date {$engDate}");
                            } else {
                                Log::info("Created NewShiftEmployee ID {$newShiftEmpData->id} for emp_id {$employeeId} and date {$engDate}");
                            }
                        }


                        if (isset($newShiftEmpId)) {
                            $finalArr = [
                                'new_shift_employee_id' => $newShiftEmpId,
                                'type' => in_array($shift_group_id, ['D', 'H', 'L']) ? $shift_group_id : "S",
                                'shift_group_id' => in_array($shift_group_id, ['D', 'H', 'L']) ? null : $shift_group_id
                            ];

                            $newShiftEmpDetail = NewShiftEmployeeDetail::where('new_shift_employee_id', $newShiftEmpId)->first();

                            if (!empty($newShiftEmpDetail)) {
                                if ($newShiftEmpDetail['type'] == $finalArr['type'] && $newShiftEmpDetail['shift_group_id'] == $finalArr['shift_group_id']) {
                                    // Only update if something actually changed
                                    // $newShiftEmpDetail->update(['shift_group_id' => $finalArr['shift_group_id']]);
                                } else {
                                    // Delete old and create new if type or shift_group_id changed
                                    NewShiftEmployeeDetail::where('new_shift_employee_id', $newShiftEmpId)->delete();
                                    NewShiftEmployeeDetail::create($finalArr);
                                }
                            } else {
                                NewShiftEmployeeDetail::create($finalArr);
                            }
                        }
                    }
                }
            }
            DB::commit();
            toastr()->success("Shift Store Succesfully");
            return redirect()->back();
        } catch (\Throwable $t) {

            dd($t);
            DB::rollBack();

            toastr()->error("Error to  Store Shift");
            return redirect()->back();
        }
    }

    public function cloneNewShift(Request $request)
    {
        $data = $request->all();
        $count = $data['count'] + 1;
        $empModel = $this->employment->find($data['emp_id']);
        $date = $data['date'];
        $shiftArr = $data['shiftArr'];

        return response()->json([
            'data' => view('newshift::shift.partial.clone', compact('count', 'empModel', 'date', 'shiftArr'))->render(),
        ]);
    }

    public function weeklyReport(Request $request)
    {
        $filter = $request->all();
        $data['weekRange'] = $weekRange = $this->getWeekRange('', 2025);

        // if (isset($filter['month']) && !empty($filter['month'])) {
        //     if (isset($filter['week_range']) && !empty($filter['week_range'])) {
        //         // $data['weekRange'] = $weekRange = $this->getWeekRange(sprintf("%02d", $filter['month']), 2023);
        //         $currentMonthWeekList = $weekRange[sprintf("%02d", $filter['month'])];

        //         $explodeDate = explode("---", $currentMonthWeekList[$filter['week_range']]);
        //         $data['dates'] = CarbonPeriod::create($explodeDate[0], $explodeDate[1]);
        //     }
        // }

        if (isset($filter['start_date']) && !empty($filter['start_date'])) {
            $explodeDate = [
                0 => $filter['start_date'],
                1 => $filter['end_date']
            ];
            $data['dates'] = CarbonPeriod::create($explodeDate[0], $explodeDate[1]);
        }
        $data['title'] = "New Shift Group";

        // $shiftList = ['Shift' => $this->shift->getList()];
        // $data['shiftlists'] = $shiftList;

        $shiftGroupList = ['ShiftGroup' => $this->shiftGroup->getList()];
        // $filteredShiftGroupList = ['ShiftGroup' => []];
        // if(!empty($shiftGroupList['ShiftGroup'])){
        //     foreach($shiftGroupList['ShiftGroup'] as $shiftGroupId => $shiftGroupName){
        //         $filteredShiftGroupList['ShiftGroup'][$shiftGroupId] = $shiftGroupName;
        //     }
        // }
        // $data['shiftGrouplists'] = $filteredShiftGroupList;
        $data['shiftGrouplists'] = $shiftGroupList;

        $limit = 50;
        if (isset($filter['sortBy']) && !empty($filter['sortBy'])) {
            $limit = $filter['sortBy'];
        }
        $data['organizationList'] = $this->organization->getList();
        $data['departmentList'] = $this->department->getList();

        $allActiveEmployeeList = $this->employment->findAll($limit, $filter);
        $employees = [];
        foreach ($allActiveEmployeeList as $employee) {
            $isExists = $this->shiftGroup->checkShiftExists($employee->id);
            if ($isExists) {
                $employees[] = $this->employment->find($employee->id);
            }
        }

        $page = request()->get('page', 1); // Get current page from request, default to 1
        $perPage = env('DEF_PAGE_LIMIT', 9999); // Define how many items per page
        $employeesCollection = collect($employees);

        // Manually create a LengthAwarePaginator instance
        $data['emplists'] = new LengthAwarePaginator(
            $employeesCollection->forPage($page, $perPage), // Slice the collection for the current page
            $employeesCollection->count(), // Total number of items
            $perPage, // Items per page
            $page, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Preserve query string
        );
        return view('newshift::shift.weekly-report', $data);
    }

    public function downloadWeeklyReport(Request $request)
    {
        $filter = $request->all();
        $data['weekRange'] = $weekRange = $this->getWeekRange('', 2025);

        if (isset($filter['month']) && !empty($filter['month'])) {
            if (isset($filter['week_range']) && !empty($filter['week_range'])) {
                // $data['weekRange'] = $weekRange = $this->getWeekRange(sprintf("%02d", $filter['month']), 2023);
                $currentMonthWeekList = $weekRange[sprintf("%02d", $filter['month'])];

                $explodeDate = explode("---", $currentMonthWeekList[$filter['week_range']]);
                $data['dates'] = CarbonPeriod::create($explodeDate[0], $explodeDate[1]);
            }
        }
        $data['title'] = "New Shift Group";

        // $shiftList = ['Shift' => $this->shift->getList()];
        // $data['shiftlists'] = $shiftList;

        $shiftGroupList = ['ShiftGroup' => $this->shiftGroup->getList()];
        // $filteredShiftGroupList = ['ShiftGroup' => []];
        // if(!empty($shiftGroupList['ShiftGroup'])){
        //     foreach($shiftGroupList['ShiftGroup'] as $shiftGroupId => $shiftGroupName){
        //         $filteredShiftGroupList['ShiftGroup'][$shiftGroupId] = $shiftGroupName;
        //     }
        // }
        // $data['shiftGrouplists'] = $filteredShiftGroupList;
        $data['shiftGrouplists'] = $shiftGroupList;

        $limit = 50;
        if (isset($filter['sortBy']) && !empty($filter['sortBy'])) {
            $limit = $filter['sortBy'];
        }
        $data['organizationList'] = $this->organization->getList();
        $data['departmentList'] = $this->department->getList();

        $allActiveEmployeeList = $this->employment->findAll($limit, $filter);
        $employees = [];
        foreach ($allActiveEmployeeList as $employee) {
            $isExists = $this->shiftGroup->checkShiftExists($employee->id);
            if ($isExists) {
                $employees[] = $this->employment->find($employee->id);
            }
        }

        $page = request()->get('page', 1); // Get current page from request, default to 1
        $perPage = env('DEF_PAGE_LIMIT', 9999); // Define how many items per page
        $employeesCollection = collect($employees);

        // Manually create a LengthAwarePaginator instance
        $data['emplists'] = new LengthAwarePaginator(
            $employeesCollection->forPage($page, $perPage), // Slice the collection for the current page
            $employeesCollection->count(), // Total number of items
            $perPage, // Items per page
            $page, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Preserve query string
        );

        $pdf = PDF::loadView('exports.roster-weekly-report', $data)->setPaper('a4', 'landscape');
        return $pdf->download('roster-weekly-report.pdf');
    }
}
