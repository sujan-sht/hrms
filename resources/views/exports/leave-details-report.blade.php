<style>
    table, th, td {
        border: 1px solid;
    }
    table{
        width: 100%;
        border-collapse: collapse;
    }
    
    .container {
        display: flex;
        justify-content: flex-end; /* Align items to the end of the container */
    }

    .col {
        width: auto; /* Adjust the width as needed */
    }

    .signature {
        font-weight: bold;
    }

</style>

@php
    $createdDate = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($leaveModel->created_at))) : date('M d, Y', strtotime($leaveModel->created_at));
@endphp
<h3>Leave Detail</h3>
<table>
    <thead>
        <tr>
            <th>Applied Date</th>
            <th>Leave Type</th>
            <th>Leave Date</th>
            <th>Leave Duration</th>
            <th>Reason</th>
            @if (optional($leaveModel->leaveTypeModel)->code == 'SUBLV')
                <th>
                    Substitute Date
                </th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $createdDate }}</td>
            <td>{{ optional($leaveModel->leaveTypeModel)->name }}</td>
            <td>{{ $leaveModel->getDateRangeWithCount()['range'] }}</td>
            <td>
                @if(isset($leaveModel->generated_by) && $leaveModel->generated_by == 11)
                    {{ $leaveModel->generated_no_of_days }}
                @else
                    {{ $leaveModel->getDateRangeWithCount()['count'] }}
                @endif
                Days
            </td>
            <td>{{ $leaveModel->reason }}</td>
            @if (optional($leaveModel->leaveTypeModel)->code == 'SUBLV')
                <td>
                    {{ setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($leaveModel->substitute_date))) : date('M d, Y', strtotime($leaveModel->substitute_date)) }}
                </td>
            @endif
        </tr>
    </tbody>
</table>
<br>

{{-- @if(count($leaveModel->attachments) > 0)
<h5></h5>
    <table>
    </table>
@endif --}}
    
@if($leaveModel->alt_employee_id)
    <h3>Alternative Employee Detail</h3>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ optional($leaveModel->altEmployeeModel)->full_name }}</td>
                <td>{{ $leaveModel->alt_employee_message }}</td>
            </tr>
        </tbody>
    </table>
    <br>
@endif

<h3>Employee Detail</h3>
<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Designation</th>
            <th>Department</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ optional($leaveModel->employeeModel)->full_name }}</td>
            <td>{{ optional(optional($leaveModel->employeeModel)->designation)->title }}</td>
            <td>{{ optional(optional($leaveModel->employeeModel)->department)->title }}</td>
        </tr>
    </tbody>
</table>
<br>

@if($leaveModel->status == '2')
    <h3>Forwarded Detail</h3>
    <table>
        <thead>
            <tr>
                <th>Forwarded By</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ optional(optional($leaveModel->statusBy)->userEmployer)->full_name }}</td>
                <td>{{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->designation)->title }}</td>
                <td>{{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->department)->title }}</td>
                <td>{{ $leaveModel->forward_message }}</td>
            </tr>
        </tbody>
    </table>
    <br>
@endif

<br>
@if($leaveModel->status == '4')
    <h3>Rejected Detail</h3>
    <table>
        <thead>
            <tr>
                <th>Rejected By</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ (optional($leaveModel->statusBy))->full_name }}</td>
                <td>{{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->designation)->title }}</td>
                <td>{{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->department)->title }}</td>
                <td>{{ $leaveModel->reject_message }}</td>
            </tr>
        </tbody>
    </table>
    <br>
@endif
<br>
<br>
<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col">
            <span class="signature">Signature:</span>
        </div>
    </div>
</div>
