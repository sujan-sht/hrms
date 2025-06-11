@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')

<tr>
    <td>Attendance Overview Report of {{ $year }} - {{ $month }}</td>
</tr>
<tr>
    <td>A = Absent, D = Day Off, H = Holiday, L = Leave, P* = Partial, P = Present</td>
</tr>
<br>

<br>

<table>
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2">Attendance Date</th>
            <th></th>
            <th colspan="4">Check In Details</th>
            <th colspan="4">Check Out Details</th>
            <th colspan="2">Break Details</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="3">Check In Request Details</th>
            <th colspan="3">Check Out Request Details</th>
            <th colspan="4">Leave Details</th>
            <th></th>
            <th></th>
            <th colspan="3">Shift Details</th>
        </tr>
        <tr class="">
            <th>S.N</th>
            <th>Company</th>
            <th>Department</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>AD</th>
            <th>BS</th>
            <th>Day</th>
            <th>Actual</th>
            <th>Updated</th>
            <th>Medium</th>
            <th>Remarks</th>
            <th>Actual</th>
            <th>Updated</th>
            <th>Medium</th>
            <th>Remarks</th>
            <th>CheckIn</th>
            <th>CheckOut</th>
            <th>Late Arrival</th>
            <th>Early Departure</th>
            <th>Check In Status</th>
            <th>Check Out Status</th>
            <th>Status</th>

            <th>Apply By</th>
            <th>Remarks</th>
            <th>Approved By</th>

            <th>Apply By</th>
            <th>Remarks</th>
            <th>Approved By</th>

            <th>Apply Date</th>
            <th>Apply By</th>
            <th>Remarks</th>
            <th>Approved By</th>

            <th>Total Working Hours</th>
            <th>Total Worked Over Time</th>
            <th>Name</th>
            <th>From</th>
            <th>To</th>

        </tr>
    </thead>
    <tbody>
        {{-- @dd($emps); --}}
        @foreach ($emps as $key => $emp)
            @for ($i = 1; $i <= $days; $i++)
                <tr>
                    <td>#{{ $i }} </td>
                    <td>{{ $emp->organizationModel->name }}</td>
                    <td>{{ optional($emp->department)->title }}</td>
                    <td>
                        {{ $emp->employee_code }}
                    </td>
                    <td>
                        {{ $emp->full_name }}
                    </td>
                    @php
                        $dateVal = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                        $date = $emp->date[$year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i)];
                    @endphp
                    <td>
                        @if ($emp->calendarType=='eng')
                            {{ $dateVal }}
                        @else
                            {{ date_converter()->nep_to_eng_convert($dateVal) }}
                        @endif
                    </td>
                    <td>
                        @if ($emp->calendarType=='eng')
                            {{ date_converter()->eng_to_nep_convert($dateVal) }}
                        @else
                            {{ $dateVal }}
                        @endif
                    </td>
                    <td>
                        @if ($emp->calendarType == 'eng')
                            {{ date('l', strtotime($dateVal)) }}
                        @else
                            {{ date_converter()->nep_to_eng($year, $month, $i)['day'] }}
                        @endif
                    </td>

                    <td>{{ $date['checkin_original'] ? date('h:i A', strtotime($date['checkin_original'])) : '' }}</td>
                    @php
                        $reqTypeTitle = 'R: ';
                        if ($atdReportRepo->checkODDRequestExist($dateVal, $emp->id)) {
                            $reqTypeTitle = 'ODD: ';
                        } elseif ($atdReportRepo->checkWFHRequestExist($dateVal, $emp->id)) {
                            $reqTypeTitle = 'WFH: ';
                        }elseif ($atdReportRepo->checkForceAtdRequestExist($dateVal, $emp->id)) {
                            $reqTypeTitle = 'FAR: ';
                        }
                    @endphp

                    <td>
                        {{-- @if (date('h:i', strtotime($date['checkin'])) != date('h:i', strtotime($date['checkin_original']))) --}}
                        @if ($date['checkin'] != $date['checkin_original'])
                            {{ $date['checkin_from'] &&  $date['checkin_from'] == 'form' ? 'F: ' : $reqTypeTitle }} {{ date('h:i A', strtotime($date['checkin'])) }}
                        @endif
                    </td>
                    <td>{{ ucfirst($date['checkin_from']) }}</td>
                    <td></td>
                    <td>{{ $date['checkout_original'] ? date('h:i A', strtotime($date['checkout_original'])) : '' }}
                    </td>
                    

                    <td>
                        {{-- @if (date('h:i', strtotime($date['checkout'])) != date('h:i', strtotime($date['checkout_original']))) --}}
                        @if ($date['checkout'] != $date['checkout_original'])
                        {{ $date['checkout_from'] &&  $date['checkout_from'] == 'form' ? 'F: ' : $reqTypeTitle }} {{ date('h:i A', strtotime($date['checkout'])) }}
                        @endif
                    </td>
                    
                    <td>{{ ucfirst($date['checkout_from']) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>


                    {{-- @php
                        if($date['checkin_original'] != null || ($date['checkin'] != null && $date['checkin_from'] == null)){
                            $requested_checkin = 'R: '.date('h:i A', strtotime($date['checkin']));
                        }else{
                            $requested_checkin = '';
                        }
                    @endphp
                    <td>{{ $requested_checkin }}</td> --}}
                    <td>{{ $date['late_arrival'] }}</td>
                    <td>{{ $date['early_departure'] }}</td>
                    <td>{{ $date['checkinStatus'] }}</td>
                    <td>{{ $date['checkoutStatus'] }}</td>
                    <td>{{ $date['status'] }}
                        @if ($date['leave_status'])
                            <sub class="text-danger">HL</sub>
                        @endif
                    </td>

                    <td>{{ isset($date['checkin_req_applied_by']) ? $date['checkin_req_applied_by'] : '' }}</td>
                    <td>{{ isset($date['checkin_req_detail']) ? $date['checkin_req_detail'] : '' }}</td>
                    <td>{{ isset($date['checkin_req_approved_by']) ? $date['checkin_req_approved_by'] : '' }}</td>

                    <td>{{ isset($date['checkout_req_applied_by']) ? $date['checkout_req_applied_by'] : '' }}</td>
                    <td>{{ isset($date['checkout_req_detail']) ? $date['checkout_req_detail'] : '' }}</td>
                    <td>{{ isset($date['checkout_req_approved_by']) ? $date['checkout_req_approved_by'] : '' }}</td>

                    <td>
                        @if ($emp->calendarType == 'eng')
                            {{ isset($date['leave_apply_date']) ? $date['leave_apply_date'] : '' }}
                        @else
                            {{ isset($date['leave_apply_date']) ? date_converter()->eng_to_nep_convert($date['leave_apply_date']) : '' }}
                        @endif
                    </td>

                    <td>{{ isset($date['leave_apply_by']) ? $date['leave_apply_by'] : '' }}</td>
                    <td>{{ isset($date['leave_reason']) ? $date['leave_reason'] : '' }}</td>
                    <td>{{ isset($date['leave_approved_by']) ? $date['leave_approved_by'] : '' }}</td>

                    <td>{{ $date['total_working_hr'] }}</td>

                    @if ($date['total_working_hr'] != '' && $date['actual_working_hr'] != '')
                        <td>{{ $date['total_working_hr'] - $date['actual_working_hr'] }}</td>
                    @else
                        <td>{{ 0 }}</td>
                    @endif
                    <td>{{ $emp->shift_name ?? '' }}</td>
                    <td>{{ $emp->shift_start ?? '' }}</td>
                    <td>{{ $emp->shift_end ?? '' }}</td>

                </tr>
            @endfor
            <tr>
                <td>Employee Name :</td>
                <td>{{ $emp->full_name }}</td>
                <td>Total Late Arrival(in Mins) :</td>
                <td>{{ $emp->total_late_arrival }}</td>
                <td>Total Early Departure(in Mins) :</td>
                <td>{{ $emp->total_early_departure }}</td>
            </tr>
            <tr></tr>
            {{-- <td colspan="6" class="text-center">Total </td> --}}
        @endforeach

    </tbody>
</table>
