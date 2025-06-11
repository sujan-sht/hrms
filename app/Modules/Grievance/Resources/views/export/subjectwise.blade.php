<table>
    <thead>
        <tr>
            <th>SN</th>
            <th>Is Anonymous</th>
            <th>Status</th>
            <th>Remark</th>
            <th>Created By</th>
            @if (array_key_exists($subjectId, $columnList))
                @foreach ($columnList[$subjectId] as $item)
                    <th>{{ $item }}</th>
                @endforeach
            @endif
            <th>Employee Name</th>
            <th>Division</th>
            <th>Sub-Function</th>
            <th>Designation</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($grievances as $grievKey => $grievance)
            <tr>
                <td>{{ $grievKey + 1 }}</td>
                <td>{{ $grievance->is_anonymous == 11 ? 'Yes' : 'No' }}</td>
                <td>{{ $grievance->getStatus() }}</td>
                <td>{!! $grievance->remark !!}</td>
                <td>{{ optional($grievance->user)->full_name }}</td>

                @foreach ($grievance['grievanceMetas'] as $key => $item)
                    @if (array_key_exists($key, $columnList[$subjectId]))
                        <td> {{ $item }}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach

                @if ($grievance->grievanceEmployee()->exists())
                    <td>{{ optional(optional($grievance->grievanceEmployee)->employee)->full_name }}</td>
                    <td>{{ optional(optional($grievance->grievanceEmployee)->division)->dropvalue }}</td>
                    <td>{{ optional(optional($grievance->grievanceEmployee)->department)->title }}</td>
                    <td>{{ optional(optional($grievance->grievanceEmployee)->designation)->title }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
