@if (Route::is('downloadDailyAttendanceReport'))
    <style>
        table, td, th {
            border: 1px solid;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding:0;
        }
    </style>
@endif
<tr>
    <td>Monthly Attendance Report of {{ $year }} - {{ $month }}</td>
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
            @if (Route::is('downloadDailyAttendanceReport'))
                <th>Code </th>
            @else
                <th>Employee Code </th>
            @endif
            
            <th>Employee Name</th>
            @for ($i = 1; $i <= $days; $i++)
                @php
                    $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                @endphp
                <th>
                    {{ $date }}
                    @php
                        if (request()->get('calendar_type') == 'nep') {
                            $date = date_converter()->nep_to_eng_convert($date);
                        }
                    @endphp
                    {{ '(' . date('D', strtotime($date)) . ')' }}
                </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($emps as $key => $emp)
            <tr>
                <td>#{{ ++$key }} </td>
                <td>
                    {{ $emp->employee_code }}
                </td>
                <td>
                    {{ $emp->full_name }}
                </td>
                @for ($i = 1; $i <= $days; $i++)
                    @php
                        $date = $emp->date[$year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i)];
                    @endphp
                    <th>
                        @if ($log_type == 1)
                            {{ $date['status'] }}
                            @if ($date['leave_status'])
                                <sub class="text-danger">HL</sub>
                            @endif
                        @else
                            {{ $date['checkin'] ? 'In: ' . date('h:i A', strtotime($date['checkin'])) : '' }}
                            <br>
                            {{ $date['checkout'] ? 'Out: ' . date('h:i A', strtotime($date['checkout'])) : '' }}
                        @endif

                    </th>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
