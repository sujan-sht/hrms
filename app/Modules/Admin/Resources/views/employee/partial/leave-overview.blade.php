<div class="card">
    <div class="card-header bg-transparent header-elements-inline">
        <h4 class="card-title font-weight-semibold">
            Leave Overview
        </h4>
        <div class="header-elements">
            <div class="list-icons ml-3">
                <a href ="{{route('leave.index')}}" class="btn btn-success btn-sm rounded-pill">Apply Leave</a>
            </div>
        </div>
    </div>
    <div class="table-responsive card-body">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Leave Type</th>
                    <th>Previous Remaining Leave</th>
                    <th>Current Leave Opening</th>
                    <th>Current Leave Taken</th>
                    <th>Current Leave Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @if (!empty($empLeaveOverviewReports))
                    @foreach ($empLeaveOverviewReports as $leaveTypeId => $empLeaveOverviewReport)
                        <tr>
                            <td width="5%">{{ $i }}</td>
                           <td>{{ $empLeaveOverviewReport['leaveTypeName'] }}</td>
                           <td>{{ $empLeaveOverviewReport['previousLeaveRemaining'] }}</td>
                           <td>{{ $empLeaveOverviewReport['currentLeaveYearLeaveOpening'] }}</td>
                           <td>{{ $empLeaveOverviewReport['currentLeaveYearLeaveTaken'] }}</td>
                           <td>{{ $empLeaveOverviewReport['currentLeaveYearLeaveBalance'] }}</td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Leave Type Data Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>