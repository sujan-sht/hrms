<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee Name</th>
            @if (!empty($monthLists))
                @foreach ($monthLists as $monthList)
                    <th>{{ $monthList }}</th>
                @endforeach
            @endif
        </tr>
    </thead>
    <tbody>

        @if (count($employeeLeaveMonths) > 0)
            @php $loopCount = 1; @endphp
            @foreach ($employeeLeaveMonths as $employeeId => $subModels)
                <tr>
                    <td>{{ $loopCount++ }}
                    </td>
                    <td>
                        {{ $subModels->getFullName() }}
                    </td>
                    @foreach ($subModels['month_leave'] as $id => $month_leave)
                        <td>
                            <h5 class="text-secondary mt-2">{{ $month_leave }}</h5>
                        </td>
                    @endforeach

                </tr>
            @endforeach
        @else
            <tr>
                {{-- <td colspan="{{ count($leaveTypeList) + 3 }}">No Record Found !!!</td> --}}
            </tr>
        @endif
    </tbody>
</table>
