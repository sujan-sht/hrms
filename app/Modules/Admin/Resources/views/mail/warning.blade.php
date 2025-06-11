<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Warning Email</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <style>
            /* General Body Styling */
            body {
                padding: 1rem; /* Equivalent to 'p-3' Bootstrap class */
            }

            /* Centering Image */
            .center-align {
                display: flex;
                justify-content: center;
            }

            .center-align img {
                height: 85px;
                max-height: 100px;
                /* No need for text-align and color as they don't affect images */
            }

            /* Aligning Date and Reference Info to the End */
            .right-align {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 1rem; /* Extra spacing below for separation */
            }

            /* Margins for Flex Containers */
            .username {
                display: flex;
                margin-bottom: 1rem; /* Equivalent to 'mb-3' Bootstrap class */
            }

            /* Styling for the Greeting Text */
            .username b {
                font-weight: bold;
            }

            /* General Text Styling */
            div {
                margin-bottom: 1rem; /* Provides space below elements */
            }

            /* Ensure Bootstrap’s CSS isn't overridden */
            * {
                box-sizing: border-box;
            }

        </style>
        
    </head>

    <body>
        <div class="center-align">
            <img style=" height: 85px; max-height: 100px; text-align: center; color: #ffffff;"
                                alt="Logo" src="{{ asset('uploads/setting/' . $data['setting']['company_logo']) }}" >
        </div>
        <div class="right-align">
            Date: {{ $data['date'] }} <br>
            Ref No. : {{ $data['ref_no'] }} <br>
            Reg No. : {{ $data['reg_no'] }} 
        </div>
        <div class="username">
            Dear <b> {{ ucfirst($data['notified_user_fullname']) }}</b>,
        </div>
        <div>
            {!! $data['message'] !!}
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
</html>

