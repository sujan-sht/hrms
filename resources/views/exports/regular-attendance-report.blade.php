{{-- @php
    if(isset($filter['date_range'])){
        $filterDates = explode(' - ', $filter['date_range']);
        $startDate = $filterDates[0];
        $endDate = $filterDates[1];
    }
@endphp --}}
@php
if (isset($filter['date_range'])) {
    // $filterDates = explode(' - ', $filter['date_range']);
    // $startDate = $filterDates[0];
    // $endDate = $filterDates[1];
    $fullDate = $filter['date_range'];
}
@endphp
@if (Route::is('downloadRegularAttendance'))
    <style>
        table, td, th {
            border: 1px solid;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
@endif
<tr>
    <td>Daily Attendance Report of {{ $fullDate }}</td>
 </tr>
 <tr>
    <td>A = Absent, D = Day Off, H = Holiday, L = Leave, P* = Partial, P = Present</td>
</tr>
<tr></tr>
<tr></tr>

<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Date</th>
            <th>Day</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Check In Medium</th>
            <th>Check Out Medium</th>
            <th>Check In Status</th>
            <th>Check Out Status</th>
            <th>Status</th>
            <th>Total Working Hours</th>
            <th>Actual Shift</th>
            <th>Updated Shift</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($filter['date_range']))
        {{-- @dd($filter['date_range']) --}}
            @foreach ($emps as  $key => $emp)
                @php
                    // if (isset($filter['date_range'])) {
                    //     $filterDates = explode(' - ', $filter['date_range']);
                    //     $startDate = $filterDates[0];
                    //     $endDate = $filterDates[1];
                    // }
                    $fullDate = $filter['date_range'];

                @endphp
                {{-- @while ($startDate <= $endDate)
                    @php
                        $fullDate = $startDate;
                    @endphp --}}

                    <tr>
                        <td>#{{ $key+1 }} </td>
                        <td>{{ $emp->employee_code }}</td>
                        <td>{{ $emp->full_name }}</td>
                        {{-- @php
                            $emp = $emp->date[$fullDate];
                        @endphp --}}
                        <td>{{ date('Y-m-d', strtotime($emp['date'])) }}</td>
                        {{-- <td>{{ date('l', strtotime($emp['date'])) }}</td> --}}
                       <td>{{ $emp['day'] }}</td>
                        <td>{{ $emp['checkin'] ? date('h:i A', strtotime($emp['checkin'])) : '' }}</td>
                        <td>{{ $emp['checkout'] ? date('h:i A', strtotime($emp['checkout'])) : '' }}</td>
                        <td>{{ ucfirst($emp['checkin_from']) }}</td>
                        <td>{{ ucfirst($emp['checkout_from']) }}</td>
                        <td>{{ $emp['checkinStatus'] }}</td>
                        <td>{{ $emp['checkoutStatus'] }}</td>
                        <td>{{ $emp['status'] }}</td>
                        <td>{{ $emp['total_working_hr'] }}</td>
                        <td>{{ $emp['actual_shift_name'] }}</td>
                        <td>{{ $emp['updated_shift_name'] }}</td>
                    </tr>

                    {{-- @php
                        $startDate = date('Y-m-d', strtotime('+1 day', strtotime($startDate)));
                    @endphp --}}
                {{-- @endwhile --}}

                    {{-- <tr>
                        <td>#{{ ++$key }}  </td>
                        <td>
                            {{ $emp->employee_code }}
                        </td>
                        <td>
                            {{ $emp->full_name }}
                        </td>

                        <td>
                            {{ date('Y-m-d') }}
                        </td>
                        <td>
                            {{ date('l', strtotime(date('Y-m-d'))) }}
                        </td>

                        <td>{{ $emp['date']['checkin'] ? date('h:i A', strtotime($emp['date']['checkin'])) : '' }}</td>
                        <td>{{ $emp['date']['checkout'] ? date('h:i A', strtotime($emp['date']['checkout'])) : '' }}</td>
                        <td>{{ $emp['date']['checkinStatus'] }}</td>
                        <td>{{ $emp['date']['checkoutStatus'] }}</td>
                        <td>{{ $emp['date']['status'] }}</td>
                        <td>{{ $emp['date']['total_working_hr'] }}</td>
                    </tr> --}}
            @endforeach
        @endif
    </tbody>
</table>
