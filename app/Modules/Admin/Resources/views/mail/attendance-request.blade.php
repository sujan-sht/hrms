<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Request Email</title>

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
            Dear <b>{{ ucfirst($data['notified_user_fullname']) }}</b>, please find the attendance request details
            below.
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

                        {{-- <h1
                                style="font-size: 15px; line-height: 20px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">
                                Dear {{ $data['notified_user_fullname'] }},
                            </h1> --}}

                        <p>Request ID : {{ $data['attendance_request']->id }}</p>
                        <p>Applied Date :
                            {{ $data['setting']['calendar_type'] == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($data['attendance_request']->created_at))) : date('M d, Y', strtotime($data['attendance_request']->created_at)) }}
                        </p>
                        <p>Reason : {!! $data['attendance_request']->detail !!}</p>

                        <h4>Request Details</h4>
                        <table style="text-align:center; width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th class="smallTable">Type</th>
                                    <th class="smallTable">Requested Date</th>

                                    @if (isset($data['attendance_request']->kind))
                                        <th class="smallTable">Kind</th>
                                    @endif
                                    @if (isset($data['attendance_request']->time))
                                        <th class="smallTable">Time</th>
                                    @endif

                                    <th class="smallTable">Number of Day(s)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="smallTable">{{ $data['attendance_request']->getType() }}</td>
                                    <td class="smallTable">
                                        {{ $data['setting']['calendar_type'] == 'BS' ? $data['attendance_request']['nepali_date'] : $data['attendance_request']['date'] }}
                                    </td>

                                    @if (isset($data['attendance_request']->kind))
                                        <td class="smallTable">{{ $data['attendance_request']->getKind() }}</td>
                                    @endif

                                    @if (isset($data['attendance_request']->time))
                                        <td class="smallTable">
                                            {{ $data['attendance_request']->time ? date('h:i A', strtotime($data['attendance_request']->time)) : '' }}
                                        </td>
                                    @endif
                                    <td class="smallTable">
                                        {{ isset($data['attendance_request']->kind) && ($data['attendance_request']->kind == 1 || $data['attendance_request']->kind == 2) ? 0.5 : 1 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="container" style="margin-top: 30px">
                            <div class="section">
                                <b>Employee Detail</b>
                                <p>Name :
                                    {{ optional($data['attendance_request']->employee)->full_name . '(' . optional($data['attendance_request']->employee)->employee_code . ')' }}
                                </p>
                                <p>Designation :
                                    {{ optional(optional($data['attendance_request']->employee)->designation)->title }}
                                </p>
                                <P>Branch :
                                    {{ optional(optional($data['attendance_request']->employee)->branchModel)->name }}
                                </P>
                                <p>Job Name : {{ optional($data['attendance_request']->employee)->job_title }}</p>
                                <p>Level Name :
                                    {{ optional(optional($data['attendance_request']->employee)->level)->title }}</p>
                            </div>

                            @if ($data['attendance_request']->forwarded_remarks)
                                <div class="section">
                                    <b>Recommended Detail</b>
                                    <p>Remarks : {{ $data['attendance_request']->forwarded_remarks ?? '' }}</p>
                                </div>
                            @endif

                            @if ($data['attendance_request']->rejected_remarks)
                                <div class="section">
                                    <b>Rejected Detail</b>
                                    <p>Remarks : {{ $data['attendance_request']->rejected_remarks ?? '' }}</p>
                                </div>
                            @endif

                            @if ($data['attendance_request']->approved_by)
                                <div class="section">
                                    <b>Approved Detail</b>
                                    <p>Name :
                                        {{ optional(optional($data['attendance_request']->approvedByModel)->userEmployer)->full_name . '(' . optional(optional($data['attendance_request']->approvedByModel)->userEmployer)->employee_code . ')' }}
                                    </p>
                                    <p>Designation :
                                        {{ optional(optional(optional($data['attendance_request']->approvedByModel)->userEmployer)->designation)->title }}
                                    </p>
                                    <P>Branch :
                                        {{ optional(optional(optional($data['attendance_request']->approvedByModel)->userEmployer)->branchModel)->name }}
                                    </P>
                                    <p>JobName :
                                        {{ optional(optional($data['attendance_request']->approvedByModel)->userEmployer)->job_title }}
                                    </p>
                                    <p>LevelName :
                                        {{ optional(optional(optional($data['attendance_request']->approvedByModel)->userEmployer)->level)->title }}
                                    </p>
                                </div>
                            @endif
                            {{-- @if ($data['attendance_request']->cancelled_by)
                                    <div class="section">
                                        <b>Cancelled Detail</b>
                                        <p>Name : {{ optional(optional($data['attendance_request']->cancelModel)->userEmployer)->full_name .'('. optional(optional($data['attendance_request']->cancelModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['attendance_request']->cancelModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['attendance_request']->cancelModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['attendance_request']->cancelModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['attendance_request']->cancelModel)->userEmployer)->level)->title }}</p>
                                    </div>
                                @endif --}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>

</html>
