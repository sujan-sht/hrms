<tr>
    <td>Monthly Wage Detail {{ $nep_year }} - {{ date_converter()->_get_nepali_month($nep_month) }}</td>
</tr>

<tr></tr>
<tr></tr>
<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Name</th>
            <th>Organization</th>
            <th>Skill Type</th>
            <th>Rate Per Day</th>
            <th>Total Days</th>
            <th>Total Working Days</th>
            <th>Absent Days</th>
            <th>Payable Days</th>
        </tr>
    </thead>
    <tbody>
        @if ($labours->count()>0)
            @foreach ($labours as $key => $labour)
                @php
                    $presentDays = $labour->countPresentDays($labour->id,$startDate,$endDate);
                    
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $labour->full_name }}</td>
                    <td>{{ $labour->organizationModel->name }}</td>
                    <td>{{ $labour->skillType->category }}</td>
                    <td>{{ $labour->skillType->daily_wage }}</td>
                    <td>{{ $days }}</td>
                    <td>{{ $days }}</td>
                    <td>{{ $days - $presentDays }}</td>
                    <td>{{ $presentDays }}</td>
                </tr>
            
            @endforeach
        @endif
    </tbody>
</table>
