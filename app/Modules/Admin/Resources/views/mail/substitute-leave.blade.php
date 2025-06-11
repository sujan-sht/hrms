<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Substitute Leave Email</title>

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
                Dear <b>{{ ucfirst($data['notified_user_fullname']) }}</b>, please find the leave details below.
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
            <h4>Request Details</h4>
            <table style="text-align:center; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th class="smallTable">Name</th>
                        <th class="smallTable">Applied Date</th>
                        <th class="smallTable">Request For </th>
                        <th class="smallTable">Description</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td class="smallTable">{{ $data['req_name'] }}</td>
                        <td class="smallTable">{{ $data['setting']['calendar_type'] == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($data['leave']->created_at))) : date('Y-m-d', strtotime($data['leave']->created_at)) }}</td>
                        <td class="smallTable">{{ $data['setting']['calendar_type'] == "BS" ? $data['leave']->nepali_date : $data['leave']->date }}</td>
                        <td class="smallTable">{!! $data['leave']->remark !!}</td>
                    </tr>
                </tbody>
            </table>
            {{-- <table style="vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
                <tbody>
                    <tr>
                        <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;">
                            <b>Request Details</b>
                            <p>Applied Date : {{ $data['setting']['calendar_type'] == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($data['leave']->created_at))) : date('Y-m-d', strtotime($data['leave']->created_at)) }}</p>
                            <p>Description : {!! $data['leave']->remark !!}</p>
                            <p>Type : Substitute Leave Claim</p>
                            <p>Reqest For : {{ $data['setting']['calendar_type'] == "BS" ? $data['leave']->nepali_date : $data['leave']->date }}</p>
                        </td>
                    </tr>
                </tbody>
            </table> --}}
            <div class="container" style="margin-top: 30px">
                <div class="section">
                    <b>Employee Detail</b>
                    <p>Name : {{ optional($data['leave']->employee)->full_name .'('. optional($data['leave']->employee)->employee_code . ')' }}</p>
                    <p>Designation : {{ optional(optional($data['leave']->employee)->designation)->title }}</p>
                    <P>Branch : {{ optional(optional($data['leave']->employee)->branchModel)->name }}</P>
                    <p>JobName : {{ optional($data['leave']->employee)->job_title }}</p>
                    <p>LevelName : {{ optional(optional($data['leave']->employee)->level)->title }}</p>
                </div>
                @if ($data['leave']->forwarded_by)
                    <div class="section">
                        <b>Forwarded Detail</b>
                        <p>Name : {{ optional(optional($data['leave']->forwardedUser)->userEmployer)->full_name .'('. optional(optional($data['leave']->forwardedUser)->userEmployer)->employee_code . ')'}}</p>
                        <p>Designation : {{ optional(optional(optional($data['leave']->forwardedUser)->userEmployer)->designation)->title }}</p>
                        <P>Branch : {{ optional(optional(optional($data['leave']->forwardedUser)->userEmployer)->branchModel)->name }}</P>
                        <p>JobName : {{ optional(optional($data['leave']->forwardedUser)->userEmployer)->job_title }}</p>
                        <p>LevelName : {{ optional(optional(optional($data['leave']->forwardedUser)->userEmployer)->level)->title }}</p>
                        <p>Remarks : {{ $data['leave']->forwarded_remarks ?? '' }}</p>
                    </div>
                @endif

                @if ($data['leave']->rejected_by)
                    <div class="section">
                        <b>Rejected Detail</b>
                        <p>Name : {{ optional(optional($data['leave']->rejectedUser)->userEmployer)->full_name .'('. optional(optional($data['leave']->rejectedUser)->userEmployer)->employee_code . ')'}}</p>
                        <p>Designation : {{ optional(optional(optional($data['leave']->rejectedUser)->userEmployer)->designation)->title }}</p>
                        <P>Branch : {{ optional(optional(optional($data['leave']->rejectedUser)->userEmployer)->branchModel)->name }}</P>
                        <p>JobName : {{ optional(optional($data['leave']->rejectedUser)->userEmployer)->job_title }}</p>
                        <p>LevelName : {{ optional(optional(optional($data['leave']->rejectedUser)->userEmployer)->level)->title }}</p>
                        <p>Remarks : {{ $data['leave']->rejected_remarks ?? '' }}</p>
                    </div>
                @endif

                @if ($data['leave']->accepted_by)
                    <div class="section">
                        <b>Approved Detail</b>
                        <p>Name : {{ optional(optional($data['leave']->acceptedUser)->userEmployer)->full_name .'('. optional(optional($data['leave']->acceptedUser)->userEmployer)->employee_code . ')'}}</p>
                        <p>Designation : {{ optional(optional(optional($data['leave']->acceptedUser)->userEmployer)->designation)->title }}</p>
                        <P>Branch : {{ optional(optional(optional($data['leave']->acceptedUser)->userEmployer)->branchModel)->name }}</P>
                        <p>JobName : {{ optional(optional($data['leave']->acceptedUser)->userEmployer)->job_title }}</p>
                        <p>LevelName : {{ optional(optional(optional($data['leave']->acceptedUser)->userEmployer)->level)->title }}</p>
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
            
            
         
        </div>
    </body>
    {{-- <body
        style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #f2f4f6; color: #000000"
        align="center">

        <!-- Fallback force center content -->
        <div style="text-align: center;">

            <!-- Start container for logo -->
            <table align="center"
                style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;"
                width="600">
                <tbody>
                    <tr>
                        <td style="width: 596px; vertical-align: top; padding-left: 0; padding-right: 0; padding-top: 10px; padding-bottom: 5px;"
                            width="596">
                            <!-- Your logo is here -->
                            <img style=" height: 85px; max-height: 100px; text-align: center; color: #ffffff;"
                                alt="Logo" src="{{ asset('uploads/setting/' . $data['setting']['company_logo']) }}"
                                align="center">

                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- End container for logo -->

            <!-- Start single column section -->
            <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;" width="600">
                <tbody>
                    <tr>
                        <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;"
                            width="596">

                            <h1
                                style="font-size: 20px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">
                                Dear {{ $data['notified_user_fullname'] }},
                            </h1>
                            <br>
                            <p
                                style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #919293;">
                                {!! $data['message'] !!}
                            </p>
                            <br>
                            <h1
                                style="font-size: 20px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">
                                Thank You
                            </h1>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- End single column section -->

            <!-- Start footer -->
            <table align="center"
                style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #e78719;"
                width="600">
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
            <!-- End footer -->
        </div>
    </body> --}}
</html>

