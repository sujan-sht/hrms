<table>
    <thead>
        <tr>
            <th>S.N</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            @if ($select_columns && !in_array('organization', $select_columns))
            @else
                <th>Organization</th>
            @endif

            @if ($select_columns && !in_array('biometric_id', $select_columns))
            @else
                <th>Biometric ID</th>
            @endif

            @if ($select_columns && !in_array('gender', $select_columns))
            @else
                <th>Gender</th>
            @endif

            @if ($select_columns && !in_array('marital_status', $select_columns))
            @else
                <th>Marital Status</th>
            @endif
            @if ($select_columns && !in_array('citizenship_no', $select_columns))
            @else
                <th>Citizenship No.</th>
            @endif
            @if ($select_columns && !in_array('nationality', $select_columns))
            @else
                <th>Nationality</th>
            @endif
            @if ($select_columns && !in_array('religion', $select_columns))
            @else
                <th>Religion</th>
            @endif
            @if ($select_columns && !in_array('state', $select_columns))
            @else
                <th>State</th>
            @endif
            @if ($select_columns && !in_array('district', $select_columns))
            @else
                <th>District</th>
            @endif
            @if ($select_columns && !in_array('municipality', $select_columns))
            @else
                <th>Municipality/VDC</th>
            @endif
            @if ($select_columns && !in_array('ward', $select_columns))
            @else
                <th>Ward No.</th>
            @endif
            @if ($select_columns && !in_array('address', $select_columns))
            @else
                <th>Address</th>
            @endif
            @if ($select_columns && !in_array('branch', $select_columns))
            @else
                <th>Unit</th>
            @endif
            @if ($select_columns && !in_array('department', $select_columns))
            @else
                <th>Department</th>
            @endif
            @if ($select_columns && !in_array('account_number', $select_columns))
            @else
                <th>Account Number</th>
            @endif
            @if ($select_columns && !in_array('pan_number', $select_columns))
            @else
                <th>PAN Number</th>
            @endif
            @if ($select_columns && !in_array('pf_number', $select_columns))
            @else
                <th>PF Number</th>
            @endif
            @if ($select_columns && !in_array('ssf_number', $select_columns))
            @else
                <th>SSF Number</th>
            @endif
            @if ($select_columns && !in_array('cit_number', $select_columns))
            @else
                <th>CIT Number</th>
            @endif

            @if (auth()->user()->user_type != 'employee')
                @if ($select_columns && !in_array('mobile', $select_columns))
                @else
                    <th>Mobile</th>
                @endif
            @else
                @if ($select_columns && !in_array('official_email', $select_columns))
                @else
                    <th>Official Email</th>
                @endif
            @endif
            @if ($select_columns && !in_array('phone', $select_columns))
            @else
                <th>CUG Number</th>
            @endif

            @if (auth()->user()->user_type == 'admin' ||
                    auth()->user()->user_type == 'super_admin' ||
                    auth()->user()->user_type == 'hr')
                @if ($select_columns && !in_array('official_email', $select_columns))
                @else
                    <th>Official Email</th>
                @endif

                @if ($select_columns && !in_array('dob', $select_columns))
                @else
                    <th>Date of Birth</th>
                @endif
                @if ($select_columns && !in_array('level', $select_columns))
                @else
                    <th>Grade</th>
                @endif
                @if ($select_columns && !in_array('join_date', $select_columns))
                @else
                    <th>Join Date</th>
                @endif
            @endif

            @if ($select_columns && !in_array('group', $select_columns))
            @else
                <th>Blood Group</th>
            @endif
            @if ($select_columns && !in_array('designation', $select_columns))
            @else
                <th>Designation</th>
            @endif
            @if ($select_columns && !in_array('gpa_enable', $select_columns))
            @else
                <th>GPA</th>
            @endif
            @if ($select_columns && !in_array('gmi_enable', $select_columns))
            @else
                <th>GMI</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if ($employeeModels->isNotEmpty())
            @foreach ($employeeModels as $key => $employeeModel)
                <tr>
                    <td>
                        #{{ $key + 1 }}
                    </td>
                    <td>{{ $employeeModel['employee_code'] }}</td>
                    <td>{{ $employeeModel['full_name'] }}</td>

                    @if ($select_columns && !in_array('organization', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['organization_model'])['name'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('biometric_id', $select_columns))
                    @else
                        <td>{{ $employeeModel['biometric_id'] }}</td>
                    @endif
                    @if ($select_columns && !in_array('gender', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['get_gender'])['dropvalue'] }}</td>
                    @endif
                    @if ($select_columns && !in_array('marital_status', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['get_marital_status'])['dropvalue'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('citizenship_no', $select_columns))
                    @else
                        <td>{{ $employeeModel['citizenship_no'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('nationality', $select_columns))
                    @else
                        <td>{{ $employeeModel['nationality'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('religion', $select_columns))
                    @else
                        <td>{{ $employeeModel['religion'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('state', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['permanent_province_model'])['province_name'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('district', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['permanent_district_model'])['district_name'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('municipality', $select_columns))
                    @else
                        <td>{{ $employeeModel['permanentmunicipality_vdc'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('ward', $select_columns))
                    @else
                        <td>{{ $employeeModel['permanentward'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('address', $select_columns))
                    @else
                        <td>{{ $employeeModel['permanentaddress'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('branch', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['branch_model'])['name'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('department', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['department'])['title'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('account_number', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['payroll_related_detail_model'])['account_no'] }}</td>
                    @endif

                    @if ($select_columns && !in_array('pan_number', $select_columns))
                    @else
                        <td>{{ $employeeModel['pan_no'] }}</td>
                    @endif
                    @if ($select_columns && !in_array('pf_number', $select_columns))
                    @else
                        <td>{{ $employeeModel['pf_no'] }}</td>
                    @endif
                    @if ($select_columns && !in_array('ssf_number', $select_columns))
                    @else
                        <td>{{ $employeeModel['ssf_no'] }}</td>
                    @endif
                    @if ($select_columns && !in_array('cit_number', $select_columns))
                    @else
                        <td>{{ $employeeModel['cit_no'] }}</td>
                    @endif

                    @if (auth()->user()->user_type != 'employee')
                        @if ($select_columns && !in_array('mobile', $select_columns))
                        @else
                            <td>{{ $employeeModel['mobile'] }}</td>
                        @endif
                    @else
                        @if ($select_columns && !in_array('official_email', $select_columns))
                        @else
                            <td>{{ $employeeModel['official_email'] ?? '-' }}</td>
                        @endif
                    @endif

                    @if ($select_columns && !in_array('phone', $select_columns))
                    @else
                        <td>{{ $employeeModel['phone'] ?? '-' }}</td>
                    @endif

                    @if (auth()->user()->user_type == 'admin' ||
                            auth()->user()->user_type == 'super_admin' ||
                            auth()->user()->user_type == 'hr')
                        @if ($select_columns && !in_array('official_email', $select_columns))
                        @else
                            <td>{{ $employeeModel['official_email'] ?? '-' }}</td>
                        @endif

                        @if ($select_columns && !in_array('dob', $select_columns))
                        @else
                            <td>{{ $employeeModel['dob'] }}</td>
                        @endif

                        @if ($select_columns && !in_array('level', $select_columns))
                        @else
                            <td>{{ optional($employeeModel['level'])['title'] ?? '-' }}</td>
                        @endif

                        @if ($select_columns && !in_array('join_date', $select_columns))
                        @else
                            <td>{{ $employeeModel['join_date'] }}</td>
                        @endif
                    @endif
                    @if ($select_columns && !in_array('group', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['get_blood_group'])['dropvalue'] ?? '-' }}</td>
                    @endif

                    @if ($select_columns && !in_array('designation', $select_columns))
                    @else
                        <td>{{ optional($employeeModel['designation'])['title'] ?? '-' }}</td>
                    @endif
                    @php
                        $insuranceDetail = optional($employeeModel['insurance_detail']);
                    @endphp
                    @if ($select_columns && !in_array('gpa_enable', $select_columns))
                    @else
                        <td>{{ $insuranceDetail['gpa_enable'] == 11 ? 'Yes' : ($insuranceDetail['gpa_enable'] == 10 ? 'No' : '') }}
                        </td>
                    @endif

                    @if ($select_columns && !in_array('gmi_enable', $select_columns))
                    @else
                        <td>{{ $insuranceDetail['gmi_enable'] == 11 ? 'Yes' : ($insuranceDetail['gmi_enable'] == 10 ? 'No' : '') }}
                        </td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">No record found.</td>
            </tr>
        @endif
    </tbody>
</table>
