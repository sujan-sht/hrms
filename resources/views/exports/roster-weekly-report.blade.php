<style>
    table,
    th,
    td {
        border: 1px solid;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .container {
        display: flex;
        justify-content: flex-end;
        /* Align items to the end of the container */
    }

    .col {
        width: auto;
        /* Adjust the width as needed */
    }

    .signature {
        font-weight: bold;
    }
</style>

@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')
@inject('holidayDetailModel', '\App\Modules\Holiday\Entities\HolidayDetail')
@inject('leaveModel', '\App\Modules\Leave\Entities\Leave')

<h3>Roster Weekly Report</h3>
<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee Name</th>
            @foreach ($dates as $date)
                @php
                    $dateArr[] = $date->format('Y-m-d');
                @endphp
                <th>
                    {{ $date->format('M d ') }}
                    ({{ $date->format('D') }})
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @php
            $holidayDetails = $holidayDetailModel
                ->with('holiday')
                ->whereIn('eng_date', $dateArr)
                ->get();
            $leaves = $leaveModel
                ->whereIn('date', $dateArr)
                ->where('status', 3)
                ->whereIn('employee_id', $emplists->pluck('employee_id'))
                // ->pluck('date');
                ->get();
            // dd($leaves->toArray(), $emplists->pluck('employee_id')->toArray());
            $color = ['success', 'danger', 'indigo', 'teal', 'warning'];
        @endphp
        @foreach ($emplists as $key => $empModel)
            <tr>
                <td>{{ $emplists->firstItem() + $key }}</td>
                <td>{{ $empModel->getFullName() }}</td>
                @php
                    $newShiftModel = $empModel->newShift->whereIn('eng_date', $dateArr);

                    $genderType = optional($empModel->getGender()->first())->dropvalue;
                    if ($genderType == 'Male') {
                        $genderType = 3;
                    } elseif ($genderType == 'Female') {
                        $genderType = 2;
                    }
                    $dayOffs = array_values($empModel->getEmployeeDayList());
                @endphp
                @foreach ($dates as $date)
                    @php
                        $shiftArr = ['D' => 'DayOff'];
                        $date = $date->format('Y-m-d');
                        $holiday = $holidayDetails
                            ->where('eng_date', $date)
                            ->whereIn('holiday.gender_type', [1, $genderType])
                            ->whereIn('holiday.religion_type', [1, $empModel->religion])
                            ->whereIn('holiday.organization_id', [null, $empModel->organization_id])
                            ->first();

                        $leave = $leaves
                            ->where('date', $date)
                            ->where('employee_id', $empModel->id)
                            ->first();
                        $shiftValue = '';

                        if ($leave) {
                            $shiftArr = array_merge($shiftArr, ['L' => $leave->getLeaveKind()]);
                            $shiftValue = 'L';
                        }

                        if ($holiday) {
                            $holdiayArr = ['H' => $holiday->sub_title];
                            $shiftArr = array_merge($shiftArr, $holdiayArr);
                            $shiftValue = 'H';
                        }
                        $shiftArr = array_merge($shiftArr, $shiftGrouplists);

                        $shift = $newShiftModel->where('eng_date', $date)->first();

                        if (isset($shift) && $shift->newShiftEmployeeDetails[0]) {
                            $shiftValue = in_array($shift->newShiftEmployeeDetails[0]['type'], ['D', 'H', 'L']) ? $shift->newShiftEmployeeDetails[0]['type'] : $shift->newShiftEmployeeDetails[0]['shift_group_id'];
                        } else {
                            if (in_array(date('l', strtotime($date)), $dayOffs)) {
                                $shiftValue = 'D';
                            } elseif ($shiftValue == 'H') {
                                $shiftValue = 'H';
                            } elseif ($shiftValue == 'L') {
                                $shiftValue = 'L';
                            } else {
                                $shiftValue = 1;
                            }
                        }

                        switch ($shiftValue) {
                            case 'D':
                                $backgroundColor = 'lightgray';
                                break;

                            case 'H':
                                $backgroundColor = 'yellowgreen';
                                break;

                            case 'L':
                                $backgroundColor = 'firebrick';
                                break;

                            default:
                                $backgroundColor = 'floralwhite';
                                break;
                        }
                    @endphp
                    
                    <td style ='background-color:{{ $backgroundColor }}'>
                        @if (isset($shift->newShiftEmployeeDetails) && count($shift->newShiftEmployeeDetails) > 0)
                            @foreach ($shift->newShiftEmployeeDetails as $key => $newShiftEmployeeDetail)
                                @if (isset($newShiftEmployeeDetail->type))
                                    @if (isset($newShiftEmployeeDetail->shift_group_id) && $newShiftEmployeeDetail->type == 'S')
                                    <span>{{ optional($newShiftEmployeeDetail->getShiftGroup)->group_name }}  
                                        {{-- ( {{optional($newShiftEmployeeDetail->getShift)->start_time}} - {{optional($newShiftEmployeeDetail->getShift)->end_time}})</span> --}}
                                    <br>
                                @else
                                        @if ($newShiftEmployeeDetail->type == 'D')
                                            <span>DayOff</span>
                                        @elseif($newShiftEmployeeDetail->type == 'H')
                                            @php
                                                $isHoliday = $atdReportRepo->isHoliday($empModel, 'date', $date);
                                                $holidayName = $atdReportRepo->getHolidayName('date', $date);
                                            @endphp
                                            @if ($isHoliday)
                                                <span>{{ $holidayName }}</span>
                                            @endif
                                        @elseif($newShiftEmployeeDetail->type == 'L')
                                            @php
                                                $leave = $leaveModel->where('date', $date)->where('status', 3)->where('employee_id', $empModel->id)->first();
                                            @endphp
                                            @if ($leave)
                                                <span>{{ $leave->getLeaveKind() }}</span>
                                            @endif   
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
