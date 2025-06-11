<table class="table table-border">
    <thead>
        <tr>
            <th rowspan="2">S.N</th>
            <th rowspan="2">Employee Name</th>
            @if (!empty($allLeaveTypes))
                @if (request()->has('filters.leave_type_id') && request('filters.leave_type_id') !== '')
                    <!-- Display only the selected leave type column if a leave type filter is applied -->
                    @foreach ($allLeaveTypes as $leaveType)
                        @if ($leaveType->id == request('filters.leave_type_id'))
                            <th colspan="2">{{ $leaveType->name }}</th>
                        @endif
                    @endforeach
                @else
                    <!-- Display all leave type columns if no filter is applied -->
                    @foreach ($allLeaveTypes as $leaveType)
                        <th colspan="2">{{ $leaveType->name }}</th>
                    @endforeach
                @endif
            @endif
            <th rowspan="2">Total Leaves</th> <!-- Total Leaves column remains fixed -->
        </tr>
        <tr>
            @if (request()->has('filters.leave_type_id') && request('filters.leave_type_id') !== '')
                <!-- Display only the selected leave type's remain and open columns if the filter is applied -->
                @foreach ($allLeaveTypes as $leaveType)
                    @if ($leaveType->id == request('filters.leave_type_id'))
                        <th>Remain</th>
                        <th>Open</th>
                    @endif
                @endforeach
            @else
                <!-- Display all leave types' remain and open columns if no filter is applied -->
                @foreach ($allLeaveTypes as $leaveType)
                    <th>Remain</th>
                    <th>Open</th>
                @endforeach
            @endif
        </tr>
    </thead>
    <tbody>
        @if (count($employeeLeaveSummaries) > 0)
            @foreach ($employeeLeaveSummaries as $key => $employeeLeaveSummary)
                <tr>
                    <td>{{ $employeeLeaveSummaries->firstItem() + $key }}</td>
                    @php
                        if (!empty($employeeLeaveSummary->middle_name)) {
                            $full_name = $employeeLeaveSummary->first_name . ' ' . $employeeLeaveSummary->middle_name . ' ' . $employeeLeaveSummary->last_name;
                        } else {
                            $full_name = $employeeLeaveSummary->first_name . ' ' . $employeeLeaveSummary->last_name;
                        }
                    @endphp
                    <td>{{ $full_name }}</td>
                    @php
                        $totalRemaining = 0;
                        $totalOpening = 0;
                    @endphp
                    @if (request()->has('filters.leave_type_id') && request('filters.leave_type_id') !== '')
                        <!-- Display only the selected leave type's values if the filter is applied -->
                        @foreach ($allLeaveTypes as $leaveType)
                            @if ($leaveType->id == request('filters.leave_type_id'))
                                <td>
                                    @php
                                        $totalRemaining += $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0;
                                        $totalOpening += $employeeLeaveSummary->leaveOpening[$leaveType->id] ?? 0;
                                    @endphp
                                    {{ $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0 }}
                                </td>
                                <td>{{ $employeeLeaveSummary->leaveOpening[$leaveType->id] ?? 0 }}</td>
                            @endif
                        @endforeach
                    @else
                        <!-- Display all leave types' values if no filter is applied -->
                        @foreach ($allLeaveTypes as $leaveType)
                            <td>
                                @php
                                    $totalRemaining += $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0;
                                    $totalOpening += $employeeLeaveSummary->leaveOpening[$leaveType->id] ?? 0;
                                @endphp
                                {{ $employeeLeaveSummary->leaveRemaining[$leaveType->id] ?? 0 }}
                            </td>
                            <td>{{ $employeeLeaveSummary->leaveOpening[$leaveType->id] ?? 0 }}</td>
                        @endforeach
                    @endif
                    <!-- Display the Total Leaves (remain / opening) column -->
                    <td>
                        <h6>
                            <span>{{ $totalRemaining }}</span> / <span>{{ $totalOpening }}</span>
                        </h6>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="{{ count($allLeaveTypes) * 2 + 3 }}">No data available</td>
            </tr>
        @endif
    </tbody>
</table>
