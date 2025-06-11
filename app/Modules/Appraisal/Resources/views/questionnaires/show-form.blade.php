@extends('admin::layout')
@section('title')
    Questionnaires
@endSection

@section('breadcrum')
    <a href="{{ route('questionnaire.index') }}" class="breadcrumb-item">Questionnaires</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@section('content')
    {{-- <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Form Preview</h6>
            </div>
        </div>
    </div> --}}

    <div class="card card-body">
        <div style="">
            <h1 class="text-center mt-4">{{ setting('company_name') }}</h1>
            <h3 class="text-center">Performance Appraisal for FY {{ $fiscalYear->fiscal_year }}</h3>
            <div class="row">
                <div class="col-md-9">
                    {{-- <h6>Appraisee: </h6> --}}
                </div>
                <div class="col-md-3">
                    <h6>Name: </h6>
                    <h6>Position: </h6>
                    <h6>Sub-Function: </h6>
                </div>
            </div>
        </div>
        <br>
        <br>
        <p style="font-size:1.2em">
            The exact interpretation of the marking scale depend on the wording of the question, but
            to help you choose which answer best applies you may find the table below useful.
        </p>


        <div class="head-title">
            <h3 class="text-center m-0 p-0 mt-3">Score Details</h3>
        </div>
        <div class="table-section bill-tbl w-100 mt-10">
            <table class="table w-100 mt-10">
                <thead>
                    <tr class="btn-slate text-white">
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
        <legend class="text-uppercase font-size-lg font-weight-bold mt-3">Section A: GENERAL PERFORMANCE REQUIREMENTS
        </legend>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        {{-- <th>Competancy</th> --}}
                        <th>Competancies</th>
                        <th>Self-Rating[1-5]</th>
                        <th>Self Comment</th>
                        <th>Reviewer Rating[1-5]</th>
                        <th>Reviewer Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    {{-- @foreach ($competencyQuestions as $key => $question)
                        <tr>
                            <td>#{{ ++$key }}</td>
                            <td>
                                <div class="font-weight-semibold">{{ $question->question }}
                                </div>
                            </td>

                            <td>
                                {!! Form::text('score', null, ['class' => 'form-control', 'readonly']) !!}
                            </td>
                            <td>
                                {!! Form::text('score', null, ['class' => 'form-control', 'readonly']) !!}
                            </td>
                        </tr>
                    @endforeach --}}

                    @foreach ($competencies as $key => $competency)
                        <tr>
                            <td>#{{ $count++ }}</td>
                            <td>
                                <div class="font-weight-bold" style="font-size:1.2em;">{{ $competency->name }}
                                </div>
                                <ul>
                                    @foreach ($competency->questions as $question)
                                        <li>
                                            {{ $question->question }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                {!! Form::text('score', null, ['class' => 'form-control', 'readonly']) !!}
                            </td>
                            <td>
                                {!! Form::text('comment', null, ['class' => 'form-control', 'readonly']) !!}
                            </td>
                            <td>
                                {!! Form::text('score', null, ['class' => 'form-control', 'readonly']) !!}
                            </td>
                            <td>
                                {!! Form::text('comment', null, ['class' => 'form-control', 'readonly']) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION B: DEVELOPMENT PLAN</legend>
        <label class="col-lg-12">
            <h6>Strengths(Describe his/ her top strengths)</h6>
        </label>
        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                {!! Form::textarea('strength', null, ['class' => 'form-control', 'rows' => 3, 'readonly']) !!}
            </div>
            @if ($errors->has('strength'))
                <div class="error text-danger">{{ $errors->first('strength') }}</div>
            @endif
        </div>

        <label class="col-lg-12 mt-3">
            <h6>Development Area (Describe the development that s/he needs)</h6>
        </label>
        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::textarea('development', null, ['class' => 'form-control', 'rows' => 3, 'readonly']) !!}
            </div>
            @if ($errors->has('development'))
                <div class="error text-danger">{{ $errors->first('development') }}</div>
            @endif
        </div>

        <label class="col-form-label col-lg-12 mt-3">
            <h6>What can Reviewer do to support?(Describe how a reviewer help the employee in their development areas to
                overcome it?) </h6>
        </label>
        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                {!! Form::textarea('support', null, ['class' => 'form-control', 'rows' => 3, 'readonly']) !!}
            </div>
            @if ($errors->has('support'))
                <div class="error text-danger">{{ $errors->first('support') }}</div>
            @endif
        </div>

        <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION C: REVIEW By NEXT LEVEL</legend>
        <label class="col-lg-12">
            <h6>Reviewer's Comments/Remarks:</h6>
        </label>
        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                {!! Form::textarea('reviewer_comment', null, ['class' => 'form-control', 'rows' => 3, 'readonly']) !!}
            </div>
            @if ($errors->has('reviewer_comment'))
                <div class="error text-danger">{{ $errors->first('reviewer_comment') }}</div>
            @endif
        </div>
        {{-- <div class="row mt-3">
            <div class="col-md-3 ml-2">
                <h6>Reviewer's Name and Signature:</h6>
            </div>
            <div class="col-md-2">
                <hr style="border-top: 1px solid black;">
            </div>
        </div> --}}

        <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION D: HR SUGGESTION</legend>

        <div class="row mt-3">
            <div class="col-md-3 ml-2">
                <h6>Average Score:</h6>
            </div>
            <div class="col-md-2">
                <hr style="border-top: 1px solid black;">
            </div>
        </div>
        <label class="col-lg-12">
            <h6>Comments/Remarks:</h6>
        </label>
        <div class="col-lg-12 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                {!! Form::textarea('reviewer_comment', null, ['class' => 'form-control', 'rows' => 3, 'readonly']) !!}
            </div>
            @if ($errors->has('reviewer_comment'))
                <div class="error text-danger">{{ $errors->first('reviewer_comment') }}</div>
            @endif
        </div>


        <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION E: ACKNOWLEDGEMENT</legend>
        <div class="row">
            <div class="col-md-3 ml-2">
                <h6>Employee Name and Signature:</h6>
            </div>
            <div class="col-md-2">
                <hr style="border-top: 1px solid black;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 ml-2">
                <h6>Appraiser Name and Signature:</h6>
            </div>
            <div class="col-md-2">
                <hr style="border-top: 1px solid black;">
            </div>
        </div>





        {{-- <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $competencyQuestions->appends(request()->all())->links() }}
            </span>
        </div> --}}
    </div>

@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection
