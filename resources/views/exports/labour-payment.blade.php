<style>
    table, th, td {
        border: 1px solid;
    }
    table{
        width: 100%;
        border-collapse: collapse;
    }
</style>
<h5>Payslip</h5>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Employee Name</th>
            <th>Total Worked Days</th>
            <th>Daily Wage</th>
            <th>Total Paid</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>{{ $date }}</td>
            <td>{{ $employee_name }}</td>
            <td>{{ $total_worked_days }}</td>
            <td>{{ $daily_wage }}</td>
            <td>{{ $total_paid_amount }}</td>
        </tr>
    </tbody>
</table>
