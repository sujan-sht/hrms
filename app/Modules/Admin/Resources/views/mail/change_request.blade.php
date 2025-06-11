<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Detail Changes Request</title>

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
            Dear , please check the employee's detail changes below.
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
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Previous Status</th>
                    <th>New Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>First Name</td>
                    <td><span>John</span></td>
                    <td><span>Mike</span></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><span>Doe</span></td>
                    <td><span>Smith</span></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><span>johndoe@example.com</span></td>
                    <td><span>mikesmith@example.com</span></td>
                </tr>

            </tbody>
        </table>

    </div>
</body>

</html>
