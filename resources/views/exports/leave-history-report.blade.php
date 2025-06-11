@if (Route::is('leave.downloadLeaveHistory'))
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
<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee</th>
            <th>Leave Date</th>
            <th>Number of Days</th>
            <th>Leave Type</th>
            <th>Leave Category</th>
            <th>Reason</th>
            <th>Applied Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($leaves))
            @foreach ($leaves as $key => $leave)
                <tr>
                    <td width="5%">#{{ $key + 1 }}</td>
                    <td>
                        {{ optional($leave->employeeModel)->full_name }}
                    </td>
                    <td>{{ $leave->getDateRangeWithCount()['range'] }}</td>
                    <td>
                        @if (isset($leave->generated_by) && $leave->generated_by == 11)
                            {{ $leave->generated_no_of_days }}
                        @else
                            {{ $leave->getDateRangeWithCount()['count'] }}
                        @endif
                    </td>
                    <td>{{ optional($leave->leaveTypeModel)->name }}</td>
                    <td>{{ $leave->getLeaveKind() }}
                        @if ($leave->leave_kind == 1)
                            ({{ $leave->getHalfType() }})
                        @endif
                    </td>
                    <td>{!! $leave->reason !!}</td>
                    <td>
                        @if (setting('calendar_type') == 'BS')
                            {{ date_converter()->eng_to_nep_convert($leave->created_at) }}
                        @else
                            {{ date('M d, Y', strtotime($leave->created_at)) }}
                        @endif
                    </td>
                    <td>
                        {{ $leave->getStatusWithColor()['status'] }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
