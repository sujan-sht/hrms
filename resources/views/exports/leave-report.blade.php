@if (Route::is('leave.downloadLeaveReport'))
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
            <th>Employee Name</th>
            @if(!empty($leaveTypeList))
                @foreach ($leaveTypeList as $leaveType)
                <th>{{ $leaveType }}</th>
                @endforeach
            @endif
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @if(count($models) > 0)
            @php $loopCount = 1; @endphp
            @foreach($models as $employeeId => $subModels)
                <tr>
                    <td width="5%">#{{ $loopCount++ }}</td>
                    <td>
                        @php $employeeModel = App\Modules\Employee\Entities\Employee::getDetail($employeeId); @endphp
                        {{ $employeeModel->getFullName() }}
                    </td>
                    @php $totalLeave = 0; @endphp
                    @foreach ($leaveTypeList as $id => $leaveType)
                        @php $count = 0; @endphp
                        @foreach ($subModels as $leaveTypeId => $model)
                            @php
                                if($leaveTypeId == $id) {
                                    $count = count($model);
                                    $totalLeave += $count;
                                }
                            @endphp
                        @endforeach
                        <td>
                            <h5 class="text-secondary mt-2">{{ $count }}</h5>
                        </td>
                    @endforeach
                    <td>
                        {{ $totalLeave }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="{{ count($leaveTypeList) + 3 }}">No Record Found !!!</td>
            </tr>
        @endif
    </tbody>
</table>
