<table class="table table-striped">
    <thead class="text-white">
        <tr>
            <th>S.N</th>
            <th>Leave Type</th>
            <th>Remaining Leave</th>
        </tr>
    </thead>
    <tbody>
        @if (count($employeeLeaveList) > 0)
            @foreach ($employeeLeaveList as $key => $employeeLeave)
            @php
             $leaveType = $employeeLeave['leave_type'];

            // if (!$leaveType) continue;

            // Only apply filter if code is 'sublv' and employee_ids is set
            // if ($leaveType->code === 'sublv' && !empty($leaveType->employee_ids)) {
            //     $employeeIds = is_array($leaveType->employee_ids)
            //         ? $leaveType->employee_ids
            //         : json_decode($leaveType->employee_ids, true);

            //     // If employee_id not in list, skip
            //     if (!in_array($employeeLeave->employee_id, $employeeIds)) {
            //         continue;
            //     }
            // }
            @endphp
                <tr class="rowList">
                    <td>#{{ ++$key }}</td>
                    <td>
                        {{ $employeeLeave['leave_type'] }}
                    </td>
                    <td id="leaveType-{{ $employeeLeave['leave_type_id'] }}"
                        data="{{ $employeeLeave['leave_remaining'] }}"
                        data-leave-type="{{ $employeeLeave['leaveTypeModel'] }}">{{ $employeeLeave['leave_remaining'] }}

                        Days

                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">No record found.</td>
            </tr>
        @endif
    </tbody>
</table>
