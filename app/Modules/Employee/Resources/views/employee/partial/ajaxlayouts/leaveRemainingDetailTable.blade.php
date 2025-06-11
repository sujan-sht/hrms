@if(count($employee_leave_details) > 0)
    @foreach($employee_leave_details as $key => $item)
        <tr>
            <td width="5%">#{{ ++$key }}</td>
            <td>{{ $item['leave_type'] }}</td>
            <td>{{ $item['opening_leave'] }}</td>
            <td>{{ $item['leave_earned'] }}</td>
            <td>{{ $item['total_leave'] }}</td>
            <td>{{ $item['leave_taken'] }}</td>
            <td>{{ $item['leave_remaining'] }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5">No Leave Details Found !!!</td>
    </tr>
@endif
