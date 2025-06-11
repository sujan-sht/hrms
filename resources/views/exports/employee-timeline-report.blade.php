<style>
    table, th, td {
        border: 1px solid;
    }
    table{
        width: 100%;
        border-collapse: collapse;
    }
</style>
<h5>Employee Timeline Report</h5>
<table>
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Date</th>
            <th>Title</th>
            <th>Description</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($employeeTimelines as $employeeTimeline)
            <tr>
                <td>
                    @php $employeeModel = App\Modules\Employee\Entities\Employee::getDetail($employeeTimeline->employee_id); @endphp
                    {{ $employeeModel->getFullName() }}
                </td>
                <td>{{ getStandardDateFormat($employeeTimeline->date) }}</td>
                <td>{{ $employeeTimeline->title }}</td>
                <td>{{ $employeeTimeline->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
