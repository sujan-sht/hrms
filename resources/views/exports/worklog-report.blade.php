<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>Title</th>
            <th>Hours</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @if ($worklogs->total() != 0)
            @foreach ($worklogs as $key => $worklog)
                @foreach ($worklog->workLogDetail as $j => $item)
                    <tr>
                        <td>{{ optional($item->employee)->getFullName() }}</td>
                        <td>{{ $worklog->date }} </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->hours ?? 0 }}</td>
                        <td>{{ $item->getStatus() }}</td>
                        <td>{{ $item->detail ?? ''}}</td>
                    </tr>
                @endforeach
            @endforeach
        @else
            <tr>
                <td colspan="5">No Worklog Found !!!</td>
            </tr>
        @endif

        {{-- @foreach ($items as $key => $item)
            <tr>
                <td>{{ ++$key }}</td>
                <td>
                    {{ optional($item->employee)->full_name }}
                </td>
                <td>{{ $item->title }}</td>
                <td>{{ optional($item->project)->dropvalue }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->hours ?? 0 }}</td>
                <td>
                    {{ $item->getStatus() }}
                </td>
            </tr>
        @endforeach --}}
    </tbody>
</table>
