@if (Route::is('employee.downloadPdf'))
    <style>
        table, td, th {
            border: 1px solid;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding:0;
        }
    </style>
@endif
<table>
    <thead>
        <tr>
            <td>Organization</td>
            <td>Biometric ID</td>
            <td>Employee Code</td>
            <td>Full Name</td>
            @if (!Route::is('employee.downloadPdf'))
            <td>Gender</td>
            <td>Day Off</td>
            <td>Joining Date</td>
            <td>DOB</td>
           
            <td>Marital Status</td>
            <td>Blood Group</td>
            <td>Phone(CUG No.)</td>
            @endif
            <td>Mobile No</td>
            @if (!Route::is('employee.downloadPdf'))
            <td>Personal Email</td>
            @endif
            <td>Official Email</td>
            <td>Citizenship No.</td>
            @if (!Route::is('employee.downloadPdf'))
                <td>Nationality</td>
                <td>Religion</td>

                <td>State</td>
                <td>District</td>
                <td>Municipality/VDC</td>
                <td>Ward No.</td>
            @endif
            
            <td>Address</td>

            <td>Branch</td>
            <td>Department</td>
            <td>Level</td>
            <td>Designation</td>
            <td>Job</td>

            {{-- <td>Contract Type</td>
            <td>Probation Status</td>
            <td>Probation Period days</td>
            <td>OT</td> --}}

            <td>Account Number</td>
            <td>PAN Number</td>
            <td>PF Number</td>
            <td>SSF Number</td>
            <td>CIT Number</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($emps as $key => $emp)
            @php
                $dayOff = ((optional($emp->employeeDayOff)->pluck('day_off')->toArray()));
                $implodeDayOff = implode(",", $dayOff);
            @endphp
            <tr>
                <td>{{ optional($emp->organizationModel)->name }}</td>
                <td>{{ $emp->biometric_id }}</td>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->getFullName() }}</td>
                @if (!Route::is('employee.downloadPdf'))
                <td>{{ optional($emp->getGender)->dropvalue }}</td>
                <td>{{ $implodeDayOff }}</td>
               
                <td>{{ $emp->join_date }}</td>
                <td>{{ $emp->dob }}</td>
                <td>{{ optional($emp->getMaritalStatus)->dropvalue }}</td>
                <td>{{ optional($emp->getBloodGroup)->dropvalue }}</td>
                <td>{{ $emp->phone }}</td>
                @endif
                <td>{{ $emp->mobile }}</td>
                @if (!Route::is('employee.downloadPdf'))
                <td>{{ $emp->personal_email }}</td>
                @endif
                <td>{{ $emp->official_email }}</td>
                <td>{{ $emp->citizenship_no }}</td>
                @if (!Route::is('employee.downloadPdf'))
                    <td>{{ $emp->nationality }}</td>
                    <td>{{ $emp->religion }}</td>

                    <td>{{ optional($emp->permanentProvinceModel)->province_name }}</td>
                    <td>{{ optional($emp->permanentDistrictModel)->district_name }}</td>
                    <td>{{ $emp->permanentmunicipality_vdc }}</td>
                    <td>{{ $emp->permanentward }}</td>
                @endif
                <td>{{ $emp->permanentaddress }}</td>

                <td>{{  optional($emp->branchModel)->name }}</td>
                <td>{{ optional($emp->department)->title }}</td>
                <td>{{ optional($emp->level)->title }}</td>
                <td>{{ optional($emp->designation)->title }}</td>
                <td>{{ $emp->job_title }}</td>

                {{-- <td>{{ $value->job_title }}</td>
                <td>{{ $value->job_title }}</td>
                <td>{{ optional($value->payrollRelatedDetailModel)->probation_period_days }}</td>
                <td>{{ optional($value->payrollRelatedDetailModel)->ot }}</td> --}}

                <td>{{ optional($emp->payrollRelatedDetailModel)->account_no }}</td>
                <td>{{ $emp->pan_no }}</td>
                <td>{{ $emp->pf_no }}</td>
                <td>{{ $emp->ssf_no }}</td>
                <td>{{ $emp->cit_no}}</td>
                </tr>
        @endforeach

    </tbody>
</table>
