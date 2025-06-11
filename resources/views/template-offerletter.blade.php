<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

<style>
    body {
        font-family: Roboto;
    }

    .print:last-child{
    page-break-after: avoid;
    page-break-inside: avoid;
    margin-bottom: 0px;
}

    @media print {
        .certificate-container {
            top: 0;
            bottom: 0;
            position: absolute !important;
            left: 0px;
            margin-top: 0px !important;
            margin-bottom:  0px !important;

        }
    }

    .mb-2 {
        margin-bottom: 20px;
    }

    .mt-3 {
        margin-top: 30px;
    }

    .certificate-container {
        margin: 0 auto;
        width: 21cm;
        height: 31.4cm;
        margin-top: 50px;
        margin-bottom: 100px;
    }

    .address {
        margin-left: auto;
        margin-right: 0;
        text-align: left;
    }

    .certificate {
        /* border: 10px solid #0C5280; */
        padding: 50px 50px 0px 50px;
        width: 21cm;
        height: 31.4cm;
        position: relative;
        border-radius: 10px;
        line-height: 1.5;
    }

    .certificate:after {
        content: '';
        top: 0px;
        left: 0px;
        bottom: 0px;
        right: 0px;
        position: absolute;
        background-image: url(https://i.pinimg.com/originals/56/e3/63/56e363f8db0457bcb61ccebd388b3f84.png);
        background-repeat: no-repeat !important;
        background-size: 25cm 33.5cm;
        z-index: -1;
    }

    .certificate-header {
        display: flex;
    }

    .certificate-header>.logo-head>.logo {
        width: 90px;
        height: 90px;
    }

    .certificate-title {
        display: table;
        /* keep the background color wrapped tight */
        margin: 0px auto 0px auto;
        /* keep the table centered */
        font-size: 18px;
        color: white;
        font-family: 'Roboto', sans-serif;
        text-align: center;
        background-color: #21517e;
        padding: 8px;
        border-radius: 5px;
    }

    .title {
        line-height: 1.5;
        font-size: 18px;
        font-family: 'Roboto', sans-serif;
        text-align: center;
    }

    .section-center {
        text-align: left;
        line-height: 1.5;
    }

    .certificate-body {
        text-align: left;
    }

    .border {
        box-shadow: rgba(9, 30, 66, 0.31) 0px 0px 1px 0px, rgba(9, 30, 66, 0.25) 0px 1px 1px 0px;
    }


    h1 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 400;
        font-size: 36px;
        color: #0C5280;
    }

    .student-name {
        text-align: left;
        margin-left: 20px;
        font-family: 'Montserrat', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #0C5280;
        padding-top: 30px;
    }

    .certificate-content {
        margin: 0 auto;
        width: 750px;
    }

    .about-certificate {
        margin: 0 auto;
    }

    .topic-description {
        text-align: center;
    }

    .signature{
        height: 80px;
        position: absolute;
        bottom: 350px;
    }

    .verified{
        position: absolute;
        bottom: 340px;
    }

    .stamp{
        height: 150px;
        position: absolute;
        bottom: 350px;
        right: 250px;
    }

    .text-title{
        margin-bottom: 25px;
    }

    .certificate-footer{
        text-align: left;
        margin-left: 24px;
    }


    /* @page {
        size: A4 landscape;
    } */
</style>

<body>
    <div class="certificate-container">
        <div class="certificate">
            <div class="water-mark-overlay"></div>
            <center>
                <div class="certificate-header">
                    <div class="logo-head">
                        <img src="https://bidhee.com/admin/logo.png" class="logo" alt="">
                    </div>
                    <div class="address">
                        <span class="title"> Phone: 9845124552</span> <br>
                        <span class="title"> Email: abc@gmail.com</span><br>
                        <span class="title"> Address: Old Baneshwar, Ktm, Nepal</span>
                    </div>

                </div>
            </center>
            <hr style="margin-top: 20px;">

            <div class="certificate-body">

                <p class="student-name">Dear [OFFERED_TO]</p>
                <div class="certificate-content">
                    <div class="about-certificate">
                        <p>
                            We are pleased to offer you the <b>[Position]</b> role within <b>[Company Name]</b>. We were impressed by your
                            qualifications,
                            experience, and enthusiasm for the position and are confident that you will be a valuable asset
                            to our team.
                        </p>
                    </div>
                    <p class="topic-center">
                        As outlined in our previous discussions, the terms of the offer include:

                    <div class="section-center">
                        Position: <b>[POSITION]</b> <br>
                        Starting Salary: <b>[STARTING_SALARY]</b> <br>
                        Benefits: <b>[BENEFITS]</b> <br>
                        Start Date: <b>[START_DATE]</b><br>
                    </div>
                    </p>
                    <div class="text-title">
                        We look forward to welcoming you to <b>[Company Name]</b> and seeing the contributions you will make to our
                        team.
                        Please let us know if you have any questions or
                        concerns about the offer. We are excited for you to join us and help drive the success of the company.
                    </div>
                    <div class="text-title">
                            Please let us know if you accept this offer by <b>[ACCEPTANCE_DATE]</b>.
                            If you accept, we will forward you any other necessary paperwork for you to complete prior to
                            your start date.
                    </div>
                    <div class="text-title">
                        <br>
                        Sincerely, <br>

                        [HR_NAME] <br>
                        [HR_DESIGNATION] <br>
                    </div>
                </div>
                <div class="certificate-footer text-muted mt-3">
                    <div class="row">
                        <div class="col-md-6" style="float: left;">
                            <img src="https://static.cdn.wisestamp.com/wp-content/uploads/2020/08/Marie-Curie-signature.png"
                                alt="" class="signature">
                            <span class="verified">Signature</span>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6" class="stampouter">
                                    <img src="https://i.pinimg.com/originals/ff/66/57/ff665740f4d20b618adcf4f096ed7b19.png" class="stamp" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
