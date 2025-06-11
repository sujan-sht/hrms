<!DOCTYPE html>
<html>

<head>
    <title>Employee Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 860px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .profile-wrapper {
            display: table;
            width: 100%;
        }

        .profile-left,
        .profile-right {
            display: table-cell;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-left {
            width: 180px;
            vertical-align: top;
        }

        .profile-right {
            vertical-align: top;
            width: calc(100% - 200px);
        }

        .profile-left img {
            width: 70%;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .profile-left h2 {
            margin: 0 0 15px 0;
            font-size: 20px;
        }

        .profile-left div {
            margin-bottom: 10px;
        }

        .profile-left div span {
            color: #666;
        }

        .profile-left .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 8px 0;
            color: #666;
        }

        table td:first-child {
            width: 150px;
        }

        a {
            color: #0066cc;
            text-decoration: none;
        }

        /* Adjustments for Print */
        @media print {
            body {
                padding: 0;
                background-color: white;
            }

            .container {
                width: 100%;
                margin: 0;
            }

            .profile-wrapper {
                display: block;
            }

            .profile-left,
            .profile-right {
                display: block;
                width: 100%;
                box-shadow: none;
                margin-bottom: 20px;
            }

            .profile-left {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Profile</h3>

        <div class="profile-wrapper">
            <!-- Left Profile Section -->
            <div class="profile-left">


                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('admin/default.png'))) }}"
                    alt="Profile Picture" />

                <h2>{{ $employee->fullname }}</h2>
                <div>
                    <span>{{ optional($employee->designation)->title }}</span>
                </div>
                <div>
                    <span>{{ $employee->employee_code }}</span>
                </div>
                <div>
                    <span>{{ $employee->nepali_join_date }}</span>
                </div>

                <div>
                    <span>{{ $employee->national_id }}</span>
                </div>
                <div>
                    <div>{{ $employee->blood_group }}</div>
                    <div>{{ $employee->citizenship_no }}</div>
                </div>
                <div>
                    <div class="bold">Official contact</div>
                    <a href="mailto:{{ $employee->official_email }}">{{ $employee->official_email }}</a>
                </div>

                <div>
                    <div class="bold">Personal contact</div>
                    <div>{{ $employee->mobile }}</div>
                </div>
            </div>

            <!-- Right Information Section -->
            <div class="profile-right">
                <h2>Job Information</h2>

                <table>
                    <tr>
                        <td>Grade</td>
                        <td>{{ optional($employee->level)->title }}</td>
                    </tr>
                    <tr>
                        <td>Designation</td>
                        <td> {{ optional($employee->designation)->title }} </td>
                    </tr>
                    <tr>
                        <td>Unit</td>
                        <td>{{ optional($employee->branchModel)->name }}</td>
                    </tr>
                    <tr>
                        <td>Province</td>
                        <td>{{ optional($employee->permanentProvinceModel)->province_name }}</td>
                    </tr>
                    <tr>
                        <td>Sub-Function</td>
                        <td>{{ optional($employee->department)->title }}</td>
                    </tr>
                    @if (!is_null($employee->educationDetail))
                        <tr>
                            <td>Education Detail</td>
                            <td>
                                <strong> {{ $employee->educationDetail->university_name }}</strong>
                                <p>{{ $employee->educationDetail->course_name }} -
                                    {{ $employee->educationDetail->passed_year }}</p>
                            </td>
                        </tr>
                    @endif
                    @if (!is_null($employee->previousJobDetail))
                        <tr>
                            <td>Previous Job Detail</td>
                            <td>
                                <strong> {{ $employee->previousJobDetail->company_name }}</strong>
                                <p>{{ $employee->previousJobDetail->job_title }}</p>
                            </td>
                        </tr>
                    @endif
                    @if (!is_null($employee->cit_no))
                        <tr>
                            <td>CIT Number</td>
                            <td>{{ $employee->cit_no }}</td>
                        </tr>
                    @endif
                    @if (!is_null($employee->pan_no))
                        <tr>
                            <td>PAN Number</td>
                            <td>{{ $employee->pan_no }}</td>
                        </tr>
                    @endif
                    @if (!is_null($employee->pf_no))
                        <tr>
                            <td>PF Number</td>
                            <td>{{ $employee->pf_no }}</td>
                        </tr>
                    @endif

                    @if (!is_null($employee->ssf_no))
                        <tr>
                            <td>SSF Number</td>
                            <td>{{ $employee->ssf_no }}</td>
                        </tr>
                    @endif

                </table>
            </div>
        </div>
    </div>
</body>

</html>
