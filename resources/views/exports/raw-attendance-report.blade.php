@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')


<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Check In</th>
            <th>Check In Medium</th>
            <th>Check Out</th>
            <th>Check Out Medium</th>
        </tr>
       
    </thead>
    <tbody>
        @foreach ($attendances as $attendance)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $attendance->employee->full_name }}</td>
                <td>{{ optional($attendance->employee->department)->title }}</td>
                <td>{{ optional($attendance->employee->designation)->title }}</td>
                <td>{{ $attendance->checkin }}</td>
                <td>{{ $attendance->checkin_from }}</td>
                <td>{{ $attendance->checkout }}</td>
                <td>{{ $attendance->checkout_from }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
