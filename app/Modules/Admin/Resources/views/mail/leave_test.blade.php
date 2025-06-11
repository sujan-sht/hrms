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
            } */
        /* th, td{
                height: 30px;
                text-align: center;
            } */
        /*
            body {
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
    </style>
</head>

<!-- You can change background colour here -->

<body>

    <!-- Fallback force center content -->
    <div>
        <!-- Start container for logo -->
        <table align="center"
            style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
            <tbody>
                <tr>
                    <td>
                        <!-- Your logo is here -->
                        <img style=" height: 85px; max-height: 100px; text-align: center; color: #ffffff;" alt="Logo"
                            src="{{ asset('uploads/setting/2022-12-05-06-09-37-download.jpeg') }}" align="center">

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End container for logo -->

        <!-- Start single column section -->
        <table align="center" style="vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;">
            <tbody>
                <tr>
                    <td
                        style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;">

                        {{-- <h1
                                style="font-size: 20px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">
                                Dear Sandhya,
                            </h1> --}}
                        {{-- <br> --}}
                        {{-- <p
                                style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #919293;">
                                {!! $data['message'] !!}
                            </p> --}}
                        <p>Request ID : 767</p>
                        <p>Applied Date : 2023-05-25</p>
                        <p>Description : reason</p>


                        <h4>Request Details</h4>
                        <table style="text-align:center">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date From</th>
                                    <th>DateTo</th>
                                    <th>Balance</th>
                                    <th>Request Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sandhya</td>
                                    <td>2023-02-26</td>
                                    <td>2023-02-07</td>
                                    <td>42</td>
                                    <td>2</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Total Applied leave Days: <b>2</b></p>
                        <p>Alternate Employee: <b>ravi</b></p>
                        <p>Message to Alternate Employee: Parul msg</b></p>

                        <div class="container" style="margin-top: 60px">
                            <div class="section">
                                <b>Employee Detail</b>
                                <p>Name : Sandhya (1002)</p>
                                <p>Designation : Software engineer</p>
                                <P>Branch : baneshwor</P>
                                <p>JobName : Technical fierld</p>
                                <p>LevelName : second</p>
                            </div>

                            {{-- @if ($data['leave']->forward_by) --}}
                            <div class="section">
                                <b>Forwarded Detail</b>
                                <p>Name : Dikshya (1002)</p>
                                <p>Designation : Software engineer</p>
                                <P>Branch : baneshwor</P>
                                <p>JobName : Technical fierld</p>
                                <p>LevelName : second</p>
                            </div>
                            {{-- @endif --}}

                            {{-- @if ($data['leave']->reject_by)
                                    <div class="section">
                                        <b>Rejected Detail</b>
                                        <p>Name : {{ optional(optional($data['leave']->rejectUserModel)->userEmployer)->full_name .'('. optional(optional($data['leave']->rejectUserModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['leave']->rejectUserModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['leave']->rejectUserModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['leave']->rejectUserModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['leave']->rejectUserModel)->userEmployer)->level)->title }}</p>
                                        <p>Remarks : {{ $data['leave']->reject_message }}</p>
                                    </div>
                                @endif

                                @if ($data['leave']->accept_by)
                                    <div class="section">
                                        <b>Approved Detail</b>
                                        <p>Name : {{ optional(optional($data['leave']->acceptModel)->userEmployer)->full_name .'('. optional(optional($data['leave']->acceptModel)->userEmployer)->employee_code . ')'}}</p>
                                        <p>Designation : {{ optional(optional(optional($data['leave']->acceptModel)->userEmployer)->designation)->title }}</p>
                                        <P>Branch : {{ optional(optional(optional($data['leave']->acceptModel)->userEmployer)->branchModel)->name }}</P>
                                        <p>JobName : {{ optional(optional($data['leave']->acceptModel)->userEmployer)->job_title }}</p>
                                        <p>LevelName : {{ optional(optional(optional($data['leave']->acceptModel)->userEmployer)->level)->title }}</p>
                                    </div>
                                @endif
                            </div> --}}

                            <!-- End single column section -->

                            <!-- Start footer -->
                            {{-- <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 30px;"
                                            width="596">

                                            <p
                                                style="font-size: 13px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #ffffff;">
                                                Kapan
                                            </p>
                                            <p
                                                style="font-size: 13px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #ffffff;">
                                                9866901157 &nbsp; olisandhya707@gmail.com
                                            </p>

                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
                            <!-- End footer -->
                            {{-- <br>
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
