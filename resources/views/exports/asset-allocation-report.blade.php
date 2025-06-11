<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee</th>
            <th>Asset</th>
            <th>Quantity</th>
            <th>Allocated Date</th>
            <th>Return Date</th>
            <th>NOD</th>
            <th>Allocated By</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $dateTime = new App\Helpers\DateTimeHelper;
        @endphp
        @if (!empty($allocations))
            @foreach ($allocations as  $key => $allocation)
                <tr>
                    <td>#{{ $key+1 }} </td>
                    <td>{{ optional($allocation->employee)->full_name }}</td>
                    <td>{{ optional($allocation->asset)->title }}</td>
                    <td>{{ $allocation->quantity }}</td>
                    <td>{{ getStandardDateFormat($allocation->allocated_date) }}</td>
                    <td>{{ getStandardDateFormat($allocation->return_date) }}</td>
                    <td>{{ $allocation->return_date ? $dateTime->DateDiffInDay(date('Y-m-d'), $allocation->return_date) : ''}}</td>
                    <td>{{ optional(optional($allocation->user)->userEmployer)->full_name }}</td>
                    <td>{{ getStandardDateFormat($allocation->created_at) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
