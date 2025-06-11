<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee Name</th>
            <th>Code</th>
            @if (!empty($leaveTypeArray))
                @foreach ($leaveTypeArray as $leaveType)
                    <th>{{ $leaveType }}</th>
                @endforeach
            @endif
        </tr>
    </thead>
    <tbody>

        @if (count($employees) > 0)
            @php $loopCount = 1; @endphp
            @foreach ($employees as $employeeId => $subModels)
                <tr>
                    <td>{{ $loopCount++ }}
                    </td>
                    <td>
                        {{ $subModels->getFullName() }}
                    </td>
                    <td>
                        {{ $subModels->employee_code }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                {{-- <td colspan="{{ count($leaveTypeList) + 3 }}">No Record Found !!!</td> --}}
            </tr>
        @endif
    </tbody>
</table>
