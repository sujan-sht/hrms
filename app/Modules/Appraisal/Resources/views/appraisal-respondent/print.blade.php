<!DOCTYPE html>
<html>

<head>
    <title>Appraisal Response Report</title>
</head>
<style type="text/css">
    body {
        font-family: 'Roboto Condensed', sans-serif;
    }

    .m-0 {
        margin: 0px;
    }

    .p-0 {
        padding: 0px;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center !important;
    }

    .w-100 {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .w-85 {
        width: 85%;
    }

    .w-15 {
        width: 15%;
    }

    .logo img {
        width: 45px;
        height: 45px;
        padding-top: 30px;
    }

    .logo span {
        margin-left: 8px;
        top: 19px;
        position: absolute;
        font-weight: bold;
        font-size: 25px;
    }

    .gray-color {
        color: #5D5D5D;
    }

    .text-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    table tr,
    th,
    td {
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    table tr th {
        background: #F4F4F4;
        font-size: 15px;
    }

    table tr td {
        font-size: 13px;
    }

    table {
        border-collapse: collapse;
    }

    .box-text p {
        line-height: 10px;
    }

    .float-left {
        float: left;
    }

    .total-part {
        font-size: 16px;
        line-height: 12px;
    }

    .total-right p {
        padding-right: 20px;
    }
</style>

<body onload="window.print();window.onmouseover = function() { self.close(); }">

    <div class="head-title">
        <h1 class="text-center m-0 p-0">Appraisal Response Report</h1>
    </div>
    <br>
    <br>

    <div  style="">
        <div style="float:left">
            Appraisee: {{ optional($respondent->appraisal)->employee->full_name }}
        </div>
        <div style="float:right">
            Responded By: {{ $respondent->name }}
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <p>
        The exact interpretation of the marking scale depend on the wording of the question, but
        to help you choose which answer best applies you may find the table below useful.
    </p>


    <div class="head-title">
        <h3 class="text-center m-0 p-0">Score Details</h3>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <thead>
                <tr class="btn-slate">
                    <th>Score</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td>{{ $fields[$i] }}</td>
                        @if ($fields[$i] == 'Frequency')
                            @foreach ($frequencies as $key => $score)
                                <td>{{ $frequencies[$key] }} </td>
                            @endforeach
                        @endif
                        @if ($fields[$i] == 'Ability')
                            @foreach ($frequencies as $key => $score)
                                <td>{{ $abilities[$key] }}</td>
                            @endforeach
                        @endif
                        @if ($fields[$i] == 'Effectiveness')
                            @foreach ($frequencies as $key => $score)
                                <td>{{ $effectiveness[$key] }}</td>
                            @endforeach
                        @endif
                    </tr>
                @endfor
            </tbody>

        </table>
    </div>

    <br>
    <br>
    <br>
    <div class="head-title">
        <h3 class="text-center m-0 p-0">Response Details</h3>
    </div>
    <table class="table w-100 mt-10">
        <thead>
            <th>SN.</th>
            <th>Question</th>
            <th>Score</th>
        </thead>
        <tbody>
            @foreach ($respondent->responses as $key => $response)
                <tr>
                    <td>#{{ ++$key }}</td>
                    <td>
                        <div class="font-weight-semibold">
                            {{ optional($response->competenceQuestion)->question }}</div>
                    </td>


                    <td>
                      {{ $response->score }}
                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>

</html>
