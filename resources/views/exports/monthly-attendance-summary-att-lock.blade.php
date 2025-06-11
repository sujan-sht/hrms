@if (Route::is('downloadMonthlySummary'))
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
<tr>
   <td>Monthly Attendance Summary of {{$year}} - {{$month}}</td>
</tr>
<tr></tr>
<tr></tr>

<table>
    <thead>
        <tr class="">
            <th>S.N</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            @foreach ($columns as $column)
                <th>{{  $column }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as  $key => $emp)
        <tr>
            <td class="sticky-col first-col">#{{  $key+1 }}</td>
            <td>{{ $emp->employee->employee_code }}</td>
            <td>{{ $emp->employee->full_name }}</td>
            @foreach ($columns as $key => $column)
                <th>
                    {{ $emp[$key] ?? 0 }}
                </th>
            @endforeach
        </tr>
        @endforeach

    </tbody>
</table>
