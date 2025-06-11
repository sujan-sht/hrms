@php
    $fields = [
        'company_name' => 'Company Name',
        'address' => 'Address',
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'job_title' => 'Functional Title',
        'designation_on_joining' => 'Designation on Joining',
        'designation_on_leaving' => 'Designation on Leaving',
        'industry_type' => 'Industry Type',
        'break_in_career' => 'Break in Career',
        'reason_for_leaving' => 'Reason for Leaving',
        'role_key' => 'Role Key',
    ];
@endphp

@foreach ($fields as $key => $label)
    <tr>
        <td>{{ $label }}</td>
        <td>{{ @$oldEntity->$key }}</td>
        <td>{{ @$newEntity->$key }}</td>
    </tr>
@endforeach
