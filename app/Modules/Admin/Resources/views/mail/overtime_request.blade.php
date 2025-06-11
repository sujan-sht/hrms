<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Overtime Request Email</title>

        <style type="text/css">
            table, th, td {
                border: 1px solid;
                border-collapse: collapse;
            }
            .container {
                display: flex;
                width:100%;
                height:100%;
            }

            .section {
                flex: 1; /* Each section takes up equal space */
                border: 1px solid #ccc;
                box-sizing: border-box;
                padding: 20px;
            }

            th, td, .smallTable {
                border: 1px solid #dddddd;
                padding: 8px;
            }
        </style>
    </head>

    <body>
        <div>
            <div style=" padding-top: 10px;">
                Dear <b>{{ ucfirst($data['notified_user_fullname']) }}</b>, please find the overtime request details below.
            </div>
            <br>
            <br>
            <table style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
                <tbody>
                    <tr>
                        <td>
                            <img style=" height: 85px; max-height: 100px; text-align: center; color: #ffffff;"
                                alt="Logo" src="{{ asset('uploads/setting/' . $data['setting']['company_logo']) }}"
                                align="center">
                        </td>
                    </tr>
                </tbody>
            </table>
          
            <table style="vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
                <tbody>
                    <tr>
                        <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;">

                            <p>Request ID :  {{ $data['overtime_request']->id }}</p>
                            <p>Applied Date : {{ $data['setting']['calendar_type'] == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($data['overtime_request']->created_at))) : date('Y-m-d', strtotime($data['overtime_request']->created_at)) }}</p>
                            <p>Remarks : {!! $data['overtime_request']->remarks !!}</p>
                
                            <h4>Request Details</h4>
                            <table style="text-align:center; width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th class="smallTable">Date</th>
                                        <th class="smallTable">Start Time</th>
                                        <th class="smallTable">End Time</th>
                                        <th class="smallTable">Time (In minutes)</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr>
                                        <td class="smallTable">{{ setting('calendar_type') == 'BS' ? $data['overtime_request']->nepali_date :  $data['overtime_request']->date }}</td>
                                        <td class="smallTable">{{ date('h:i A', strtotime($data['overtime_request']->start_time)) }}</td>
                                        <td class="smallTable">{{ date('h:i A', strtotime($data['overtime_request']->end_time)) }}</td>
                                        <td class="smallTable">{{ $data['overtime_request']->ot_time }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            {{-- <p>Total Applied overtime request Days: <b>{{ $leaveCount }}</b></p> --}}
                            
                            <div class="container" style="margin-top: 30px">
                                <div class="section">
                                    <b>Employee Detail</b>
                                    <p>Name : {{ optional($data['overtime_request']->employee)->full_name .'('. optional($data['overtime_request']->employee)->employee_code . ')' }}</p>
                                    <p>Designation : {{ optional(optional($data['overtime_request']->employee)->designation)->title }}</p>
                                    <P>Branch : {{ optional(optional($data['overtime_request']->employee)->branchModel)->name }}</P>
                                    <p>JobName : {{ optional($data['overtime_request']->employee)->job_title }}</p>
                                    <p>LevelName : {{ optional(optional($data['overtime_request']->employee)->level)->title }}</p>
                                </div>
                
                                @if ($data['overtime_request']->forwarded_by)
                                    <div class="section">
                                        <b>Forwarded Detail</b>
                                        <p>Name : {{ optional(optional($data['overtime_request']->forwardUserModel)->userEmployer)->full_name .'('. optional(optional($data['overtime_request']->forwardUserModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['overtime_request']->forwardUserModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['overtime_request']->forwardUserModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['overtime_request']->forwardUserModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['overtime_request']->forwardUserModel)->userEmployer)->level)->title }}</p>
                                        <p>Remarks : {{ $data['overtime_request']->forwarded_remarks ?? '' }}</p>
                                    </div>
                                @endif
                
                                @if ($data['overtime_request']->rejected_by)
                                    <div class="section">
                                        <b>Rejected Detail</b>
                                        <p>Name : {{ optional(optional($data['overtime_request']->rejectedUserModel)->userEmployer)->full_name .'('. optional(optional($data['overtime_request']->rejectedUserModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['overtime_request']->rejectedUserModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['overtime_request']->rejectedUserModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['overtime_request']->rejectedUserModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['overtime_request']->rejectedUserModel)->userEmployer)->level)->title }}</p>
                                        <p>Remarks : {{ $data['overtime_request']->rejected_remarks ?? '' }}</p>
                                    </div>
                                @endif
                
                                @if ($data['overtime_request']->approved_by)
                                    <div class="section">
                                        <b>Approved Detail</b>
                                        <p>Name : {{ optional(optional($data['overtime_request']->approvedUserModel)->userEmployer)->full_name .'('. optional(optional($data['overtime_request']->approvedUserModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['overtime_request']->approvedUserModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['overtime_request']->approvedUserModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['overtime_request']->approvedUserModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['overtime_request']->approvedUserModel)->userEmployer)->level)->title }}</p>
                                    </div>
                                @endif
                                {{-- @if ($data['leave']->cancelled_by)
                                    <div class="section">
                                        <b>Cancelled Detail</b>
                                        <p>Name : {{ optional(optional($data['leave']->cancelModel)->userEmployer)->full_name .'('. optional(optional($data['leave']->cancelModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['leave']->cancelModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['leave']->cancelModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['leave']->cancelModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['leave']->cancelModel)->userEmployer)->level)->title }}</p>
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

