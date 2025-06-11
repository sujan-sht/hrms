<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HRMS</title>

    <!-- Global stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('admin/global/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <link href="{{ asset('admin/css/additional.css') }}" rel="stylesheet" type="text/css">

    <!-- Core JS files -->
    <script src="{{ asset('admin/global/js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/main/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>

    <!-- Theme JS files -->
    <script src="{{ asset('admin/global/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>

    <script src="{{ asset('admin/assets/js/app.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/dashboard.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>

    {{-- Nepali calendar --}}
    <link rel="stylesheet" href="{{ asset('admin/nepali_calender4/css/nepali.datepicker.v4.0.min.css') }}">
    <script type="text/javascript" src="{{ asset('admin/nepali_calender4/js/nepali.datepicker.v4.0.min.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('admin/js/nrj_custom.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('admin/assets/css/toastr.min.css') }}">
    <script src="{{ asset('admin/assets/js/plugins/toastr/toastr.min.js') }}"></script>
</head>

<body>


    <style type="text/css">
        .select2-results__option.select2-results__option--highlighted {
            background-color: #d0d0d0 !important;
        }
    </style>

    <style type="text/css">
        .table-responsive {
            height: 500px;
            overflow: scroll;
            background-image: url({{ asset('admin/hrms_background.png') }});
            background-position: center;
            background-size: cover;
        }

        thead tr:nth-child(1) th {
            background: #546e7a;
            position: sticky;
            top: 0px;
            z-index: 1;
        }

        thead tr:nth-child(2) th {
            background: #546e7a;
            position: sticky;
            top: 44px;
            z-index: 2;
        }

        thead tr:nth-child(3) th {
            background: #546e7a;
            position: sticky;
            top: 88px;
            z-index: 3;
        }

        .centered {
            background: rgb(241, 242, 243);
            margin: 0 auto;
            width: 70%;
        }
    </style>

    <div class="page-content centered">
        <!-- Main content -->
        <div class="content-wrapper">
            <div class="content-inner">

                <!-- Content area -->
                <div class="content">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ asset('uploads/setting/' . $setting->company_logo) }}" alt="Logo"
                                        style="height: 120px;">
                                </div>
                                <div class="col-md-4">
                                    <h5>
                                        {{ optional(optional(optional($respondent->appraisal)->employee)->organizationModel)->name }}
                                    </h5>
                                </div>

                                <div class="col-md-4 mt-4">
                                    {{-- <p>Email: {{ $setting->company_email }} </p>
                                    <p>Phone: {{ $setting->contact_no1 }}</p>
                                    <p>Address: {{ $setting->address1 }}</p> --}}
                                    <h6>Name: {{ optional(optional($respondent->appraisal)->employee)->full_name }}
                                    </h6>
                                    <h6>Position:
                                        {{ optional(optional(optional($respondent->appraisal)->employee)->designation)->title }}
                                    </h6>
                                    <h6>Sub-Function:
                                        {{ optional(optional(optional($respondent->appraisal)->employee)->department)->title }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        Comments on all Competancies are mandatory.
                    </div>
                    <div class="card">
                        {{-- <div class="card-header header-elements-inline">
                            <h5 class="card-title">Score Details</h5>
                        </div> --}}

                        <div class="card-body">
                            <h4>
                                Dear " {{ $respondent->name }} "
                                {{-- "this questionnaire is about your perception
                                of
                                 {{ optional(optional($respondent->appraisal)->employee)->full_name }}. --}}
                            </h4>

                            {{-- <h6>
                                Be Honest - <code>{{ $respondent->name }}</code> has asked for your feedback because
                                they want you to
                                be constructive and truthful.
                            </h6> --}}
                            <h6>
                                {{-- Be Honest - {{ $respondent->name }} has asked for your feedback because
                                they want you to
                                be constructive and truthful. --}}
                                This is the annual appraisal form. You are requested to provide truthful & constructive
                                feedback and make sure to attend all the questions with reasonable comment. The marking
                                scale is 1 – 5 where 1 is the lowest & 5 is the highest score.

                            </h6>

                            <h6>
                                Thank you for the participation.

                            </h6>

                            {{-- <h6 class="mb-4">
                                The exact interpretation of the marking scale depend on the wording of the question, but
                                to help you choose which answer best applies you may find the table below useful.
                            </h6> --}}

                            {{-- <p class="mb-3">You can use below <code>Score</code> to rate questions.</p> --}}

                            <div class="table-responsive" style=" height: 200px;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="text-light btn-slate">
                                            <th>Score</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form>
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
                                        </form>
                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <form method="post" action="{{ route('appraisal.responseViaInvitation') }}">
                        @csrf
                        <input type="hidden" name="invitation_code" value="{{ request()->invitation_code }}">
                        <input type="hidden" name="appraisal_id" value="{{ optional($respondent->appraisal)->id }}">
                        <input type="hidden" name="appraisee"
                            value="{{ optional($respondent->appraisal)->appraisee }}">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <legend class="text-uppercase font-size-lg font-weight-bold">SECTION A:GENERAL
                                    PERFORMANCE REQUIREMENTS</legend>
                            </div>


                            <div class="card-body">

                                <table class="table tasks-list table-lg">
                                    <thead>
                                        <tr class="text-light btn-slate">
                                            <th>S.N</th>
                                            <th>Competancies</th>
                                            @if (isset(optional($respondent->appraisal)->self_evaluation_type) &&
                                                    is_null(optional($respondent->appraisal)->supervisor_evaluation_type))
                                                @if (optional($respondent->appraisal)->self_evaluation_type == 1)
                                                    <th>Rating</th>
                                                @elseif (optional($respondent->appraisal)->self_evaluation_type == 2)
                                                    <th>Self Comment</th>
                                                @else
                                                    <th>Rating</th>
                                                    <th>Self Comment</th>
                                                @endif
                                            @elseif (isset(optional($respondent->appraisal)->self_evaluation_type) &&
                                                    isset(optional($respondent->appraisal)->supervisor_evaluation_type))
                                                @if (optional($respondent->appraisal)->self_evaluation_type == 1 &&
                                                        optional($respondent->appraisal)->supervisor_evaluation_type == 1)
                                                    @if (count($appraisal_response) > 0)
                                                        <th>Self Rating[1-5]</th>
                                                        <th>Self Comment</th>
                                                        <th style="padding:0px 40px">Reviewer Rating[1-5]</th>
                                                        <th>Reviewer Comment</th>
                                                    @else
                                                        <th style="padding:0px 80px">Rating</th>
                                                        <th style="padding:0px 80px">Comment</th>
                                                    @endif
                                                @elseif (optional($respondent->appraisal)->self_evaluation_type == 1 &&
                                                        optional($respondent->appraisal)->supervisor_evaluation_type == 2)
                                                    @if (count($appraisal_response) > 0)
                                                        <th>Rating</th>
                                                        <th>Remarks on Rating (Reporting Officer)</th>
                                                    @else
                                                        <th>Rating</th>
                                                    @endif
                                                @elseif (optional($respondent->appraisal)->self_evaluation_type == 2 &&
                                                        optional($respondent->appraisal)->supervisor_evaluation_type == 2)
                                                    @if (count($appraisal_response) > 0)
                                                        <th>Self Comment</th>
                                                        <th>Comment By Supervisior</th>
                                                    @else
                                                        <th>Self Comment</th>
                                                    @endif
                                                @else
                                                    <th>Rating</th>
                                                    <th>Self Comment</th>
                                                @endif
                                            @else
                                            @endif


                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{ Form::hidden('created_by', $respondent->employee_id, []) }}
                                        @if (count($appraisal_response) == 0)
                                            @php
                                                $finalRating = 0;
                                                $total = 0;
                                            @endphp
                                            @foreach ($competencies as $key => $competency)
                                                <tr>
                                                    <td>#{{ ++$key }}</td>
                                                    <td>
                                                        <div class="font-weight-bold" style="font-size:1.2em;">
                                                            {{ $competency->name }}
                                                        </div>
                                                        <input type="hidden" name="competency_ids[]"
                                                            value="{{ $competency->id }}">
                                                        <ul>
                                                            @foreach ($competency->questions as $question)
                                                                <li>
                                                                    {{ $question->question }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    {{-- <td></td> --}}
                                                    {{-- <td></td> --}}
                                                    {{-- <td></td> --}}
                                                    {{-- <td></td> --}}
                                                    {{-- <td></td>
                                                    <td></td> --}}
                                                    @if (isset(optional($respondent->appraisal)->self_evaluation_type) &&
                                                            is_null(optional($respondent->appraisal)->supervisor_evaluation_type))
                                                        @if (optional($respondent->appraisal)->self_evaluation_type == 1)
                                                            <th></th>
                                                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 2)
                                                            <th>
                                                                {!! Form::text('comment[]', null, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                ]) !!}
                                                            @else
                                                            <th style="padding: 0px 5px;">
                                                                <select name="score[]" class="form-control scoreSelect">
                                                                    <option value="0">Select Rating</option>
                                                                    </option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                </select>
                                                            </th>
                                                            <th>

                                                                {!! Form::text('comment[]', null, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                ]) !!}
                                                            </th>
                                                        @endif
                                                    @elseif (isset(optional($respondent->appraisal)->self_evaluation_type) &&
                                                            isset(optional($respondent->appraisal)->supervisor_evaluation_type))
                                                        @if (optional($respondent->appraisal)->self_evaluation_type == 1 &&
                                                                optional($respondent->appraisal)->supervisor_evaluation_type == 1)
                                                            <th>
                                                                <select name="score[]" class="form-control scoreSelect">
                                                                    <option value="0">Select Rating</option>
                                                                    </option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                </select>
                                                            </th>
                                                            <th>{!! Form::textarea('comment[]', null, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'rows' => 3,
                                                                'required',
                                                            ]) !!}</th>
                                                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 1 &&
                                                                optional($respondent->appraisal)->supervisor_evaluation_type == 2)
                                                            <th>
                                                                <select name="score[]" class="form-control scoreSelect">
                                                                    <option value="0">Select Rating</option>
                                                                    </option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                </select>
                                                            </th>
                                                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 2 &&
                                                                optional($respondent->appraisal)->supervisor_evaluation_type == 2)
                                                            <th>{!! Form::text('comment[]', null, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'required',
                                                            ]) !!}</th>
                                                        @else
                                                            <th>
                                                                <select name="score[]"
                                                                    class="form-control scoreSelect">
                                                                    <option value="0">Select Rating</option>
                                                                    </option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                </select>
                                                            </th>
                                                            <th>{!! Form::text('comment[]', null, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'required',
                                                            ]) !!}</th>
                                                        @endif
                                                    @else
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            @php
                                                $finalRating = 0;
                                                $total = 0;
                                            @endphp
                                            @foreach ($appraisal_response as $key => $response)
                                                <tr>
                                                    <td>#{{ $key + 1 }}</td>
                                                    <td>
                                                        <div class="font-weight-semibold">
                                                            {{ $response->competency->name }}
                                                        </div>
                                                        <input type="hidden" name="competency_ids[]"
                                                            value="{{ $response->competency_id }}">
                                                        <ul>
                                                            @foreach ($response->competency->questions as $question)
                                                                <li>
                                                                    {{ $question->question }}
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                    </td>
                                                    {{-- <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td> --}}
                                                    @if (isset(optional($respondent->appraisal)->self_evaluation_type) &&
                                                            is_null(optional($respondent->appraisal)->supervisor_evaluation_type))
                                                        @if (optional($respondent->appraisal)->self_evaluation_type == 1)
                                                            <th></th>
                                                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 2)
                                                            <th>
                                                                {!! Form::text('comment[]', null, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                ]) !!}
                                                            @else
                                                            <th>
                                                                <select name="score[]"
                                                                    class="form-control scoreSelect">
                                                                    <option value="0">Select Rating</option>
                                                                    </option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                </select>
                                                            </th>
                                                            <th>

                                                                {!! Form::text('comment[]', null, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                ]) !!}
                                                            </th>
                                                        @endif
                                                    @elseif (isset(optional($respondent->appraisal)->self_evaluation_type) &&
                                                            isset(optional($respondent->appraisal)->supervisor_evaluation_type))
                                                        @if (optional($respondent->appraisal)->self_evaluation_type == 1 &&
                                                                optional($respondent->appraisal)->supervisor_evaluation_type == 1)
                                                            <td>{!! Form::text('self_score', $response->score, [
                                                                'placeholder' => 'Enter Your Score',
                                                                'class' => 'form-control',
                                                                'disabled',
                                                            ]) !!}</td>
                                                            <td>{!! Form::textarea('self_comment', $response->comment, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'rows' => 3,
                                                                'disabled',
                                                            ]) !!}</td>

                                                            @if (optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->id == $respondent->employee_id)
                                                                <td>
                                                                    <select name="score[]"
                                                                        class="form-control scoreSelect">
                                                                        <option value="0">Select Rating</option>
                                                                        </option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                    </select>
                                                                </td>
                                                                <th>{!! Form::textarea('comment[]', null, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'rows' => 3,
                                                                    'required',
                                                                ]) !!}</th>
                                                            @else
                                                                <td>{!! Form::text('self_score', $firstApprovalResponse[$key]->score, [
                                                                    'class' => 'form-control',
                                                                    'disabled',
                                                                ]) !!}</td>
                                                                <td>{!! Form::textarea('self_comment', $firstApprovalResponse[$key]->comment, [
                                                                    'class' => 'form-control',
                                                                    'disabled',
                                                                    'rows' => 3,
                                                                ]) !!}</td>
                                                                @php
                                                                    $finalRating +=
                                                                        $response->score +
                                                                        $firstApprovalResponse[$key]->score;
                                                                    $total += 5 + 5;
                                                                @endphp
                                                            @endif
                                                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 1 &&
                                                                optional($respondent->appraisal)->supervisor_evaluation_type == 2)
                                                            <td>
                                                                {!! Form::text('self_score', $response->score, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'disabled',
                                                                ]) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('comment[]', null, [
                                                                    'placeholder' => 'Enter Your Comment',
                                                                    'class' => 'form-control',
                                                                    'required',
                                                                ]) !!}
                                                            </td>
                                                        @elseif (optional($respondent->appraisal)->self_evaluation_type == 2 &&
                                                                optional($respondent->appraisal)->supervisor_evaluation_type == 2)
                                                            <td>{!! Form::text('self_comment', $response->comment, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'disabled',
                                                            ]) !!}</td>

                                                            <td>{!! Form::text('comment[]', null, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'required',
                                                            ]) !!}</td>
                                                        @else
                                                            <th>
                                                                <select name="score[]"
                                                                    class="form-control scoreSelect">
                                                                    <option value="0">Select Rating</option>
                                                                    </option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                </select>
                                                            </th>
                                                            <th>{!! Form::text('comment[]', null, [
                                                                'placeholder' => 'Enter Your Comment',
                                                                'class' => 'form-control',
                                                                'required',
                                                            ]) !!}</th>
                                                        @endif
                                                    @else
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <footer>
                                        <tr>
                                            <td></td>
                                            @if (optional($respondent->appraisal)->self_evaluation_type == 2 && count($appraisal_response) == 0)
                                                <td colspan="4">
                                                    <h5 class="card-title">Rating:</h5>
                                                </td>
                                                <td> <select name="average_score" class="form-control scoreSelect">
                                                        <option value="0">Select Rating</option>
                                                        </option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </td>
                                            @endif
                                            @if (optional($respondent->appraisal)->supervisor_evaluation_type == 2 && count($appraisal_response) > 0)
                                                <td colspan="6">
                                                    <h5 class="card-title">Rating:</h5>
                                                </td>
                                                <td> {!! Form::text('self_score', $appraisal_response->first()->score, [
                                                    'class' => 'form-control',
                                                    'disabled',
                                                ]) !!}
                                                </td>
                                                <td> <select name="average_score" class="form-control scoreSelect">
                                                        <option value="0">Select Rating</option>
                                                        </option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </td>
                                            @endif
                                        </tr>
                                    </footer>
                                </table>
                            </div>
                        </div>
                        @if (optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->id == $respondent->employee_id)
                            <div class="card">
                                <div class="card-header">
                                    <legend class="text-uppercase font-size-lg font-weight-bold">SECTION B: DEVELOPMENT
                                        PLAN</legend>
                                </div>


                                <div class="card-body">
                                    <label class="col-lg-12">
                                        <h6>Strengths(Describe his/ her top strengths) <span
                                                class="text-danger">*</span></h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('strength', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                                        </div>
                                        @if ($errors->has('strength'))
                                            <div class="error text-danger">{{ $errors->first('strength') }}</div>
                                        @endif
                                    </div>

                                    <label class="col-lg-12 mt-3">
                                        <h6>Development Area(Describe the development that s/he needs) <span
                                                class="text-danger">*</span></h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('development', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                                        </div>
                                        @if ($errors->has('development'))
                                            <div class="error text-danger">{{ $errors->first('development') }}</div>
                                        @endif
                                    </div>

                                    <label class="col-form-label col-lg-12 mt-3">
                                        <h6>What can Reviewer do to support?(Describe how a reviewer help the employee
                                            in their development areas to overcome it?) <span
                                                class="text-danger">*</span></h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                                            {!! Form::textarea('support', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                                        </div>
                                        @if ($errors->has('support'))
                                            <div class="error text-danger">{{ $errors->first('support') }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endif

                        @if (optional(optional($employeeApproval->lastApprovalUserModel)->userEmployer)->id == $respondent->employee_id)
                            <div class="card">
                                <div class="card-header">
                                    <legend class="text-uppercase font-size-lg font-weight-bold">SECTION B: DEVELOPMENT
                                        PLAN</legend>
                                </div>


                                <div class="card-body">
                                    <label class="col-lg-12">
                                        <h6>Strengths(Describe his/ her top strengths)</h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('strength', $firstApprovalComment->strength ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('strength'))
                                            <div class="error text-danger">{{ $errors->first('strength') }}</div>
                                        @endif
                                    </div>

                                    <label class="col-lg-12 mt-3">
                                        <h6>Development Area(Describe the development that s/he needs)</h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('development', $firstApprovalComment->development ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('development'))
                                            <div class="error text-danger">{{ $errors->first('development') }}</div>
                                        @endif
                                    </div>

                                    <label class="col-form-label col-lg-12 mt-3">
                                        <h6>What can Reviewer do to support?(Describe how a reviewer help the employee
                                            in their development areas to overcome it?) </h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                                            {!! Form::textarea('support', $firstApprovalComment->support ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('support'))
                                            <div class="error text-danger">{{ $errors->first('support') }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <legend class="text-uppercase font-size-lg font-weight-bold">SECTION C: REVIEW By
                                        NEXT LEVEL</legend>
                                </div>
                                <div class="card-body">
                                    <label class="col-lg-12">
                                        <h6>Reviewer's Comments/Remarks: <span class="text-danger">*</span></h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                                            {!! Form::textarea('reviewer_comment', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                                        </div>
                                        @if ($errors->has('reviewer_comment'))
                                            <div class="error text-danger">{{ $errors->first('reviewer_comment') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3 ml-2">
                                            <h6>Reviewer's Name and Signature:</h6>
                                        </div>
                                        @if ($employeeApproval)
                                            <div class="col-md-8">
                                                <h6 style="text-decoration: underline;">
                                                    {{ optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->getFullname() }}
                                                </h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif


                        @if (optional(optional($respondent->employee)->getUser)->user_type == 'hr')
                            <div class="card">
                                <div class="card-header">
                                    <legend class="text-uppercase font-size-lg font-weight-bold">SECTION B: DEVELOPMENT
                                        PLAN</legend>
                                </div>


                                <div class="card-body">
                                    <label class="col-lg-12">
                                        <h6>Strengths(Describe his/ her top strengths)</h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('strength', $firstApprovalComment->strength ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('strength'))
                                            <div class="error text-danger">{{ $errors->first('strength') }}</div>
                                        @endif
                                    </div>

                                    <label class="col-lg-12 mt-3">
                                        <h6>Development Area(Describe the development that s/he needs)</h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::textarea('development', $firstApprovalComment->development ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('development'))
                                            <div class="error text-danger">{{ $errors->first('development') }}</div>
                                        @endif
                                    </div>

                                    <label class="col-form-label col-lg-12 mt-3">
                                        <h6>What can Reviewer do to support?(Describe how a reviewer help the employee
                                            in their development areas to overcome it?) </h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                                            {!! Form::textarea('support', $firstApprovalComment->support ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('support'))
                                            <div class="error text-danger">{{ $errors->first('support') }}</div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <legend class="text-uppercase font-size-lg font-weight-bold">SECTION C: REVIEW By
                                        NEXT LEVEL</legend>
                                </div>
                                <div class="card-body">
                                    <label class="col-lg-12">
                                        <h6>Reviewer's Comments/Remarks:</h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                                            {!! Form::textarea('reviewer_comment', $lastApprovalComment->reviewer_comment ?? '', [
                                                'class' => 'form-control',
                                                'rows' => 3,
                                                'readOnly',
                                            ]) !!}
                                        </div>
                                        @if ($errors->has('reviewer_comment'))
                                            <div class="error text-danger">{{ $errors->first('reviewer_comment') }}
                                            </div>
                                        @endif
                                    </div>
                                    {{-- <div class="row mt-3">
                                        <div class="col-md-3 ml-2">
                                            <h6>Reviewer's Name and Signature:</h6>
                                        </div>
                                        @if ($employeeApproval)
                                            <div class="col-md-8">
                                                <h6 style="text-decoration: underline;">
                                                    {{ optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->getFullname() }}
                                                </h6>
                                            </div>
                                        @endif
                                    </div> --}}
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <legend class="text-uppercase font-size-lg font-weight-bold">SECTION D: HR
                                        Suggestion
                                    </legend>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 ml-2">
                                            <h6>Average score:
                                                @php
                                                    $averageScore = round(($finalRating / $total) * 100);
                                                @endphp
                                                {!! Form::hidden('average_score', $averageScore, ['class' => 'form-control']) !!}
                                                @if ($total > 0)
                                                    {{ round(($finalRating / $total) * 100) }} %
                                                @endif
                                            </h6>
                                        </div>
                                        @if ($employeeApproval)
                                            <div class="col-md-8">
                                                <h6 style="text-decoration: underline;"></h6>
                                            </div>
                                        @endif
                                    </div>
                                    <label class="col-lg-12">
                                        <h6>Comments/Remarks: <span class="text-danger">*</span></h6>
                                    </label>
                                    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                                            {!! Form::textarea('reviewer_comment', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                                        </div>
                                        @if ($errors->has('reviewer_comment'))
                                            <div class="error text-danger">{{ $errors->first('reviewer_comment') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- <div class="card">
                            <div class="card-header">
                                <legend class="text-uppercase font-size-lg font-weight-bold">SECTION E: ACKNOWLEDGEMENT
                                </legend>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 ml-2">
                                        <h6>Employee Name and Signature:</h6>
                                    </div>
                                    <div class="col-md-8">
                                        <h6>{{ optional(optional($respondent->appraisal)->employee)->getFullName() }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 ml-2">
                                        <h6>Appraiser Name and Signature:</h6>
                                    </div>
                                    @if ($employeeApproval)
                                        <div class="col-md-8">
                                            <h6 style="text-decoration: underline;">
                                                {{ optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->getFullname() }}
                                            </h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}

                </div>

                <div class="text-center">
                    <button type="submit"
                        class="ml-2 btn btn-success btn-labeled btn-labeled-left center submit mb-5"><b><i
                                class="icon-database-insert "></i></b>Submit Form</button>
                </div>
                </form>



            </div>
            <!-- /content area -->

        </div>
    </div>
    <!-- /content wrapper -->
    </div>
</body>

{{-- <script>
    $('.submit').on('click',function(){

        let selcount = $( "select option:selected").length
        alert(selcount)


        if($('.scoreSelect').length < selcount)
        {
            alert('Fill All ')
        }
        e.preventDefault();

    })
</script> --}}

</html>
