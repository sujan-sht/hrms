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
            <h4>Claim Details</h4>
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
                        <td class="smallTable">{{ $data['setting']['calendar_type'] == "BS" ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($data['leave']->employeeSubstituteLeave->created_at))) : date('Y-m-d', strtotime($data['leave']->employeeSubstituteLeave->created_at)) }}</td>
                        <td class="smallTable">{{ $data['setting']['calendar_type'] == "BS" ? $data['leave']->employeeSubstituteLeave->nepali_date : $data['leave']->date }}</td>
                        <td class="smallTable">{!! $data['leave']->employeeSubstituteLeave->remark !!}</td>
                    </tr>
                </tbody>
            </table>
          
            <div class="container" style="margin-top: 30px">
                <div class="section">
                    <b>Employee Detail</b>
                    <p>Name : {{ optional($data['leave']->employeeSubstituteLeave->employee)->full_name .'('. optional($data['leave']->employee)->employee_code . ')' }}</p>
                    <p>Designation : {{ optional(optional($data['leave']->employeeSubstituteLeave->employee)->designation)->title }}</p>
                    <P>Branch : {{ optional(optional($data['leave']->employeeSubstituteLeave->employee)->branchModel)->name }}</P>
                    <p>JobName : {{ optional($data['leave']->employeeSubstituteLeave->employee)->job_title }}</p>
                    <p>LevelName : {{ optional(optional($data['leave']->employeeSubstituteLeave->employee)->level)->title }}</p>
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
              
            </div>
            
            
         
        </div>
    </body>

</html>

