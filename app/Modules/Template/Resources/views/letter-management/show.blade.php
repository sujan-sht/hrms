<link href="https://fonts.googleapis.com" rel="preconnect" />
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&amp;display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto&amp;display=swap" rel="stylesheet" />
<style type="text/css">
    body {
        font-family: Roboto;
    }

    .print:last-child {
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
            margin-bottom: 0px !important;

        }
        .printbtn {
            display: none !important;
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
    .salaryCertificateToWhom{
        display: flex;
        justify-content: center;
        margin-left: 20px;
        font-family: 'Montserrat', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #0C5280;
        padding-top: 30px;
        text-decoration: underline;
    }

    .date {
        text-align: left;
        margin-left: 20px;
        font-family: 'Montserrat', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #0C5280;
        padding-top: 30px;
    }
    .salaryCertificateDate{
        text-align: right;
        margin-left: 20px;
        font-family: 'Montserrat', sans-serif;
        font-size: 16px;
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

    .signature {
        height: 80px;
        position: absolute;
        bottom: 350px;
    }

    .verified {
        position: absolute;
        bottom: 340px;
    }

    .stamp {
        height: 150px;
        position: absolute;
        bottom: 350px;
        right: 250px;
    }

    .text-title {
        margin-bottom: 25px;
    }

    .certificate-footer {
        text-align: left;
        margin-left: 24px;
    }
    


    /* @page {
        size: A4 landscape;
    } */
</style>

@php
    $hr=App\Modules\Employee\Entities\Employee::where('organization_id',optional($letter->employee)->organization_id)->whereHas('user', function ($query) {
        $query->where('user_type', 'hr');
    })->first();
    $hr_name=$hr->full_name ?? '';
@endphp
<div class="certificate-container">
    <div class="certificate">
        {{-- <div class="water-mark-overlay"><a href="https://www.facebook.com/">https://www.facebook.com/</a></div> --}}

        <center>
            <div class="certificate-header">
                <div class="logo-head"><img alt="" class="logo" src="{{ asset(App\Modules\Organization\Entities\Organization::IMAGE_PATH.'/'.optional(optional($letter->employee)->organizationModel)->image) }}" />
                </div>

                <div class="address"><span class="title">Phone: {{ optional(optional($letter->employee)->organizationModel)->contact }}</span><br />
                    <span class="title">Email: {{ optional(optional($letter->employee)->organizationModel)->email }}</span><br />
                    <span class="title">Address: {{ optional(optional($letter->employee)->organizationModel)->address }}</span>
                </div>
            </div>
        </center>

        <hr style="margin-top: 20px;" />
        @if ($letter->getRawOriginal('type')==1)
            <div class="certificate-body">
                <p class="date">Date: {{ Carbon\Carbon::parse($letter->created_at)->format('d F Y') }}</p>
                <p class="student-name">TO WHOM IT MAY CONCERN</p>

                <div class="certificate-content">
                    <div class="about-certificate">
                        <p>This is to certify that {{ optional(optional($letter->employee)->getGender)->dropvalue == 'Male' ? 'Mr' : 'Miss' }}
                            {{ optional($letter->employee)->full_name }}, a permanent resident of
                            {{ optional($letter->employee)->permanentaddress }} Nepal has worked as a
                            {{ optional(optional($letter->employee)->designation)->title }} at
                            {{ optional(optional($letter->employee)->organizationModel)->name }}, located at
                            {{ optional(optional($letter->employee)->organizationModel)->address }} from
                            {{ Carbon\Carbon::parse(optional($letter->employee)->join_date)->format('d F Y') }} till
                            {{ Carbon\Carbon::parse(optional($letter->employee)->end_date)->format('d F Y') }}. During
                            {{ optional(optional($letter->employee)->getGender)->dropvalue == 'Male' ? 'his' : 'her' }} job tenure,
                            {{ optional(optional($letter->employee)->getGender)->dropvalue == 'Male' ? 'he' : 'she' }} was responsible for:
                        </p>
                    </div>

                    <p>{!! optional($letter->employee)->job_description !!}</p>
                    <p>{{ optional(optional($letter->employee)->getGender)->dropvalue == 'Male' ? 'Mr' : 'Miss' }}
                        {{ optional($letter->employee)->full_name }} is an enthusiastic learner, dedicated individual with excellent
                        skills that were instrumental for the growth of {{ optional(optional($letter->employee)->organizationModel)->name }}. A
                        true team player with remarkable expertise in communicating with all levels of staff and team
                        members.</p>
                    <p>We highly appreciate {{ optional($letter->employee)->first_name }}'s work and attitude towards life. We wish
                        {{ optional(optional($letter->employee)->getGender)->dropvalue == 'Male' ? 'him' : 'her' }} all the very best in
                        {{ optional(optional($letter->employee)->getGender)->dropvalue == 'Male' ? 'his' : 'her' }} future endeavors.</p>


                    <div class="text-title"><br>
                        Best Regards,<br>
                        {{ $hr_name }}<br>
                        Human Resources Department<br>
                        {{ optional(optional($letter->employee)->organizationModel)->name }}
                    </div>
                </div>

            </div>
        @else
            <div class="certificate-body">
                <p class="salaryCertificateDate">Date: {{ Carbon\Carbon::parse($letter->created_at)->format('d F Y') }}</p>
                <p class="salaryCertificateToWhom">TO WHOM IT MAY CONCERN</p>

                <div class="certificate-content">
                    <div class="about-certificate">
                        <p>This certifies that {{ (optional(optional($letter->employee)->getGender)->dropvalue == 'Male') ? 'Mr' : 'Miss' }} {{ optional($letter->employee)->full_name }} , a permanent resident of {{ optional($letter->employee)->permanentaddress }} is an employee of this organization working as a {{ optional(optional($letter->employee)->designation)->title }} in the {{ optional($letter->employee)->department->title }}</p>

                                <p>As per our record, gross monthly emoluments of {{ (optional(optional($letter->employee)->getGender)->dropvalue == 'Male') ? 'Mr' : 'Miss' }} {{ optional($letter->employee)->last_name }} is approximately Nrs {{ optional($letter->employee)->employeeGrossSalarySetup->gross_salary }} (convert in words) and annually is Nrs {{ optional($letter->employee)->employeeGrossSalarySetup->gross_salary * 12 }}(in words)</p>

                                <p>This letter has been issued at the request of {{ (optional(optional($letter->employee)->getGender)->dropvalue == 'Male') ? 'Mr' : 'Miss' }} {{ optional($letter->employee)->full_name }} and holds no risk or responsibility upon the undersigned.</p>
                    </div>

                    


                    <div class="text-title"><br>
                        Best Regards,<br>
                        {{ $hr_name }}<br>
                        Human Resources Department <br>
                        {{ optional(optional($letter->employee)->organizationModel)->name }}
                    </div>
                </div>

            </div>
        @endif
        
    </div>
</div>
<div style="display: flex; justify-content:center;">
    <a href="javascript:window.print();" class="printbtn"
    style="display: flex; justify-content:center; background-color: rgb(241, 146, 57); padding: 15px 50px; border: rgb(241, 146, 57); border-radius: 5px; color: #fff; text-decoration: none; text-align: center;">Print</a>
</div>


