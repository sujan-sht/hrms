<!DOCTYPE HTML
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="x-apple-disable-message-reformatting">
        <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->

        <!-- Start stylesheet -->
        <style type="text/css">
            a,
            a[href],
            a:hover,
            a:link,
            a:visited {
                /* This is the link colour */
                text-decoration: none !important;
                color: #0000EE;
            }

            .link {
                text-decoration: underline !important;
            }

            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td {
                line-height: 100%;
            }

            .ExternalClass {
                width: 100%;
            }
        </style>
        <!-- End stylesheet -->

    </head>

    <!-- You can change background colour here -->
    <body
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
            <table align="center" style="text-align: left; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;" width="600">
                <tbody>
                    <tr>
                        <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;" width="596">
                            Dear {{ $data['notified_user_fullname'] }},
                            <br><br>
                            We hope this email finds you well. We wanted to take a moment to personally thank you for sharing your CV and showing interest in {{ setting('company_name') }}. We appreciate the time and effort you put into your application.
                            <br><br>
                            Your skills and experience are impressive, and we believe that you would be a valuable addition to our team. We will carefully review your application and get back to you if your resume matches our requirement.
                            <br><br>
                            Thank you once again for considering {{ setting('company_name') }} as your potential employer. We wish you all the best in your job search.
                            <br><br>
                            Best regards,
                            <br>
                            {{ setting('company_name') }} HRD
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
    </body>
</html>

