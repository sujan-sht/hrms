<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave Email</title>

    <style type="text/css">
        /* a,
            a[href],
            a:hover,
            a:link,
            a:visited {
                text-decoration: none !important;
                color: #0000EE;
            }

            .link {
                text-decoration: underline !important;
            }

            p,
            p:visited {
                font-size: 15px;
                line-height: 24px;
                font-family: 'Helvetica', Arial, sans-serif;
                font-weight: 300;
                text-decoration: none;
                color: #000000;
            }

            h1 {
                font-size: 22px;
                line-height: 24px;
                font-family: 'Helvetica', Arial, sans-serif;
                font-weight: normal;
                text-decoration: none;
                color: #000000;
            }

            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td {
                line-height: 100%;
            }

            .ExternalClass {
                width: 100%;
            } */




        table,
        th,
        td {
            border: 1px solid;
            border-collapse: collapse;
        }

        /* table{
                width: 50%;
            }
            th, td{
                height: 30px;
                text-align: center;
            } */

        /* body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            } */

        .container {
            display: flex;
            width: 100%;
            height: 100%;
        }

        .section {
            flex: 1;
            /* Each section takes up equal space */
            border: 1px solid #ccc;
            box-sizing: border-box;
            padding: 20px;
        }

        th,
        td,
        .smallTable {
            border: 1px solid #dddddd;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div>
        <div style=" padding-top: 10px;">
            Dear <b>{{ ucfirst($data['notified_user_fullname']) }}</b>, please find the leave details below.
        </div>
        <br>
        <br>
        <table
            style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
            <tbody>
                <tr>
                    <td>
                        <img style=" height: 85px; max-height: 100px; text-align: center; color: #ffffff;" alt="Logo"
                            src="{{ asset('uploads/setting/' . $data['setting']['company_logo']) }}" align="center">
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
            <tbody>
                <tr>
                    <td
                        style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;">

                        <p>Request ID : {{ $data['leave']->id }}</p>
                        <p>Applied Date :
                            {{ $data['setting']['calendar_type'] == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($data['leave']->created_at))) : date('Y-m-d', strtotime($data['leave']->created_at)) }}
                        </p>
                        <p>Description : {!! $data['leave']->reason !!}</p>

                        @inject('employeeLeaveModel', '\App\Modules\Employee\Entities\EmployeeLeave')
                        @php
                            $dateRange = explode(' - ', $data['leave']->getDateRangeWithCount()['range']);
                            $employeeLeave = $employeeLeaveModel
                                ->select('leave_remaining')
                                ->where('employee_id', $data['leave']['employee_id'])
                                ->where('leave_type_id', $data['leave']['leave_type_id'])
                                ->where('leave_year_id', getCurrentLeaveYearId())
                                ->first();

                            if (isset($data['leave']->generated_by) && $data['leave']->generated_by == 11) {
                                $leaveCount = $data['leave']->generated_no_of_days;
                            } else {
                                $leaveCount = $data['leave']->getDateRangeWithCount()['count'];
                            }
                        @endphp
                        <h4>Request Details</h4>
                        <table style="text-align:center; width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th class="smallTable">Name</th>
                                    <th class="smallTable">Date From</th>
                                    <th class="smallTable">DateTo</th>
                                    <th class="smallTable">Balance</th>
                                    <th class="smallTable">Request Day(s)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="smallTable">{{ optional($data['leave']->leaveTypeModel)->name }}</td>
                                    <td class="smallTable">{{ $dateRange[0] ? $dateRange[0] : '-' }}</td>
                                    <td class="smallTable">{{ isset($dateRange[1]) ? $dateRange[1] : $dateRange[0] }}
                                    </td>
                                    <td class="smallTable">
                                        {{ $employeeLeave && $employeeLeave->leave_remaining ? $employeeLeave->leave_remaining : '0' }}
                                    </td>
                                    <td class="smallTable">{{ $leaveCount }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Total Applied leave Days: <b>{{ $leaveCount }}</b></p>
                        {{-- <p>Alternate Employee: <b>{{ $data['leave']['alt_employee_id'] ? optional($data['leave']->altEmployeeModel)->full_name : '-' }}</b></p> --}}
                        {{-- <p>Message to Alternate Employee:
                            <b>{{ $data['leave']['alt_employee_message'] ? $data['leave']['alt_employee_message'] : '-' }}</b>
                        </p> --}}

                        <div class="container" style="margin-top: 30px">
                            <div class="section">
                                <b>Employee Detail</b>
                                <p>Name :
                                    {{ optional($data['leave']->employeeModel)->full_name . '(' . optional($data['leave']->employeeModel)->employee_code . ')' }}
                                </p>
                                <p>Designation :
                                    {{ optional(optional($data['leave']->employeeModel)->designation)->title }}</p>
                                <P>Branch : {{ optional(optional($data['leave']->employeeModel)->branchModel)->name }}
                                </P>
                                <p>JobName : {{ optional($data['leave']->employeeModel)->job_title }}</p>
                                <p>LevelName : {{ optional(optional($data['leave']->employeeModel)->level)->title }}
                                </p>
                            </div>

                            @if ($data['leave']->forward_by)
                                <div class="section">
                                    <b>Forwarded Detail</b>
                                    <p>Name :
                                        {{ optional(optional($data['leave']->forwardUserModel)->userEmployer)->full_name . '(' . optional(optional($data['leave']->forwardUserModel)->userEmployer)->employee_code . ')' }}
                                    </p>
                                    <p>Designation :
                                        {{ optional(optional(optional($data['leave']->forwardUserModel)->userEmployer)->designation)->title }}
                                    </p>
                                    <P>Branch :
                                        {{ optional(optional(optional($data['leave']->forwardUserModel)->userEmployer)->branchModel)->name }}
                                    </P>
                                    <p>JobName :
                                        {{ optional(optional($data['leave']->forwardUserModel)->userEmployer)->job_title }}
                                    </p>
                                    <p>LevelName :
                                        {{ optional(optional(optional($data['leave']->forwardUserModel)->userEmployer)->level)->title }}
                                    </p>
                                    <p>Remarks : {{ $data['leave']->forward_message ?? '' }}</p>
                                </div>
                            @endif

                            @if ($data['leave']->reject_by)
                                <div class="section">
                                    <b>Rejected Detail</b>
                                    <p>Name :
                                        {{ optional(optional($data['leave']->rejectUserModel)->userEmployer)->full_name . '(' . optional(optional($data['leave']->rejectUserModel)->userEmployer)->employee_code . ')' }}
                                    </p>
                                    <p>Designation :
                                        {{ optional(optional(optional($data['leave']->rejectUserModel)->userEmployer)->designation)->title }}
                                    </p>
                                    <P>Branch :
                                        {{ optional(optional(optional($data['leave']->rejectUserModel)->userEmployer)->branchModel)->name }}
                                    </P>
                                    <p>JobName :
                                        {{ optional(optional($data['leave']->rejectUserModel)->userEmployer)->job_title }}
                                    </p>
                                    <p>LevelName :
                                        {{ optional(optional(optional($data['leave']->rejectUserModel)->userEmployer)->level)->title }}
                                    </p>
                                    <p>Remarks : {{ $data['leave']->reject_message ?? '' }}</p>
                                </div>
                            @endif

                            @if ($data['leave']->accept_by)
                                <div class="section">
                                    <b>Approved Detail</b>
                                    <p>Name :
                                        {{ optional(optional($data['leave']->acceptModel)->userEmployer)->full_name . '(' . optional(optional($data['leave']->acceptModel)->userEmployer)->employee_code . ')' }}
                                    </p>
                                    <p>Designation :
                                        {{ optional(optional(optional($data['leave']->acceptModel)->userEmployer)->designation)->title }}
                                    </p>
                                    <P>Branch :
                                        {{ optional(optional(optional($data['leave']->acceptModel)->userEmployer)->branchModel)->name }}
                                    </P>
                                    <p>JobName :
                                        {{ optional(optional($data['leave']->acceptModel)->userEmployer)->job_title }}
                                    </p>
                                    <p>LevelName :
                                        {{ optional(optional(optional($data['leave']->acceptModel)->userEmployer)->level)->title }}
                                    </p>
                                </div>
                            @endif
                            @if ($data['leave']->cancelled_by)
                                <div class="section">
                                    <b>Cancelled Detail</b>
                                    <p>Name :
                                        {{ optional(optional($data['leave']->cancelModel)->userEmployer)->full_name . '(' . optional(optional($data['leave']->cancelModel)->userEmployer)->employee_code . ')' }}
                                    </p>
                                    <p>Designation :
                                        {{ optional(optional(optional($data['leave']->cancelModel)->userEmployer)->designation)->title }}
                                    </p>
                                    <P>Branch :
                                        {{ optional(optional(optional($data['leave']->cancelModel)->userEmployer)->branchModel)->name }}
                                    </P>
                                    <p>JobName :
                                        {{ optional(optional($data['leave']->cancelModel)->userEmployer)->job_title }}
                                    </p>
                                    <p>LevelName :
                                        {{ optional(optional(optional($data['leave']->cancelModel)->userEmployer)->level)->title }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 30px;"
                                            width="596">

                                            <p
                                                style="font-size: 13px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #ffffff;">
                                                {{ $data['setting']['address1'] }}
                                            </p>
                                            <p
                                                style="font-size: 13px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #ffffff;">
                                                {{ $data['setting']['contact_no1'] }} &nbsp; {{ $data['setting']['company_email'] }}
                                            </p>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <h1
                                style="font-size: 20px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">
                                Thank You
                            </h1> --}}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>

</html>
