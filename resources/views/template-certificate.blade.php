    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <div class="certificate-container">
        <div class="certificate border">
            <div class="water-mark-overlay"></div>
            <center>
                <div class="certificate-header">
                    <img src="https://bidhee.com/admin/logo.png" class="logo" alt="">
                </div>
            </center>
            <div class="certificate-body">
                <h1>Certificate of Completion</h1>

                <p class="certificate-title"><strong>The Certificate is Proudly Presented to</strong></p>
                <p class="student-name">Kiran Aryal</p>
                <p class="title">Title: <b>Completion of Internship Program</b></p>
                <div class="certificate-content">
                    <div class="about-certificate">
                        <p>
                            We express our sincerest appreciation for your hard work and dedication to
                            <b>Bidhee Pvt. Ltd.</b>
                        </p>
                    </div>
                    <p class="topic-title">
                        Your willingness to go above and beyond in your duties has not only benefited the company, but also
                        your colleagues and clients. Your positive attitude and professionalism are greatly valued, and we
                        are lucky to have you as a member of our team.
                    </p>
                    <div class="text-center">
                        <p class="topic-description text-muted">Thank you for your outstanding commitment to excellence. We
                            look forward to continuing to work with you and witnessing your future successes.
                        </p>
                    </div>
                </div>
                <div class="certificate-footer text-muted mt-3">
                    <div class="row">
                        <div class="col-md-6" style="float: left;">
                            <img src="https://static.cdn.wisestamp.com/wp-content/uploads/2020/08/Marie-Curie-signature.png" alt="" style="height: 80px; position: absolute;left: 120px; bottom: 70px; ">
                            <span style="margin-right: 50px; position: relative; bottom: -30px; left: 40px;">Accredited by: <u>Sudip Tripathi</u></span>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6" style="float: left">

                                </div>
                                <div class="col-md-6" style="margin-top: 50px; position: relative; bottom: -25px;">
                                    <p>
                                        Endorsed by: <u>Subash Sapkota</u>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: Roboto;
        }

        .certificate-container {
            margin: 0 auto;
            padding: 50px;
            width: 1122.24px;
        }

        .certificate {
            /* border: 10px solid #0C5280; */
            padding: 50px;
            height: 600px;
            position: relative;
            border-radius: 10px;
        }

        .certificate:after {
            content: '';
            top: 0px;
            left: 0px;
            bottom: 0px;
            right: 0px;
            position: absolute;
            /* background-image: url(https://img.freepik.com/premium-vector/modern-simple-black-gold-abstract-background-suitable-business-card-presentation-flyer-brochure-print-certificate-template_181182-16602.jpg); */
            background-image: url(https://png.pngtree.com/png-vector/20220721/ourmid/pngtree-simple-elegant-blue-gold-certificate-border-design-png-image_6015461.png);
            background-repeat: no-repeat !important;
            /* background-size: 100%; */
            /* height: 500px; */
            background-size: 1122.24px 700px;
            z-index: -1;
        }

        .certificate-header>.logo {
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
            font-size: 18px;
            font-family: 'Roboto', sans-serif;
            text-align: center;
        }

        .certificate-body {
            text-align: center;
        }


        .border {
            box-shadow: rgba(9, 30, 66, 0.31) 0px 0px 1px 0px, rgba(9, 30, 66, 0.25) 0px 1px 1px 0px;
        }


        h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 42px;
            color: #0C5280;
        }

        .student-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #0C5280;
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

        .mt-3 {
            margin-top: 30px;
        }

        @page {
            size: A4 landscape;
        }


    </style>
