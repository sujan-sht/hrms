<!DOCTYPE HTML
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>

    <!--[if gte mso 9]>
  <xml>
    <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
  </xml>
  <![endif]-->

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->

    <!-- Your title goes here -->
    <title>Invitation for Appraisal as Respondent</title>
    <!-- End title -->

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

        p,
        p:visited {
            /* Fallback paragraph style */
            font-size: 15px;
            line-height: 24px;
            font-family: 'Helvetica', Arial, sans-serif;
            font-weight: 300;
            text-decoration: none;
            color: #000000;
        }

        h1 {
            /* Fallback heading style */
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
                            alt="Logo" src="{{ asset('uploads/setting/' . $setting->company_logo) }}"
                            align="center">

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End container for logo -->

        <!-- Start single column section -->
        <table align="center"
            style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;"
            width="600">
            <tbody>
                <tr>
                    <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;"
                        width="596">

                        <h1
                            style="font-size: 20px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">
                            Dear {{ $name }} You have been sent this email from Bidhee HRM
                            who are compiling feedback on
                            {{ $respondent }}.
                        </h1>

                        <p
                            style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #919293;">
                            {{ $name }} would like you to give your honest feedback by
                            filling out a questionnaire accessible by clicking button below. <br><br>
                            You have to complete it all in one sitting, by clicking submit after filling question
                            score, you can submit the button.
                        </p>

                        <!-- Start button (You can change the background colour by the hex code below) -->
                        <a href="{{ route('appraisal.viewThroughInvitation') . '?invitation_code=' . $invitation_code }}"
                            target="_blank"
                            style="background-color: #e78719; font-size: 15px; line-height: 22px; font-family: 'Helvetica', Arial, sans-serif; font-weight: normal; text-decoration: none; padding: 12px 15px; color: #ffffff; border-radius: 5px; display: inline-block; mso-padding-alt: 0;">

                            <span style="mso-text-raise: 15pt; color: #ffffff;">Take the Questionnaire Now</span>

                        </a>
                        <!-- End button here -->

                        <p
                            style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #919293;">
                            If the button above is not visible, or does not work, please copy the following link into
                            your browser.
                        </p>
                        <p>
                            {{ route('appraisal.viewThroughInvitation') . '?invitation_code=' . $invitation_code }}
                        </p>

                        <p
                            style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #919293;">
                            The exact interpretation of the marking scale depend on the wording of the question, but
                            to help you choose which answer best applies you may find the table below useful.
                        </p>

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
                            {{ $setting->address1 }}
                        </p>
                        <p
                            style="font-size: 13px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #ffffff;">
                            {{ $setting->contact_no1 }} &nbsp; {{ $setting->company_email }}
                        </p>

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End footer -->


    </div>

</body>

</html>
