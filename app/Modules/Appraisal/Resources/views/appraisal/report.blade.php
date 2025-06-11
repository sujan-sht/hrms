@extends('admin::layout')
@section('title') Appraisal Report @stop

@section('breadcrum')
    <a href="{{ route('appraisal.index') }}" class="breadcrumb-item">Appraisal</a>
    <a class="breadcrumb-item active">Report</a>
@endsection

@inject('competancyQuestions', 'App\Modules\Appraisal\Entities\CompetencyQuestion')

@section('content')
    <div class="card">
        <div class="card-body">
            <div style="">
                <h1 class="text-center mt-4">{{ optional(optional($appraisalModel->employee)->organizationModel)->name }}
                </h1>
                <h3 class="text-center">Performance Appraisal for FY {{ $fiscalYear->fiscal_year }}</h3>
                <div class="row">
                    <div class="col-md-9">
                        {{-- <h6>Appraisee: {{$appraisalModel->employee->getFullName()}} </h6> --}}
                    </div>
                    <div class="col-md-3">
                        {{-- <h6>Responded By:
                        @if ($employeeApproval)
                         {{optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->getFullname()}} </h6>@endif --}}
                        <h6>Name: {{ $appraisalModel->employee->getFullName() }} </h6>
                        <h6>Position:{{ optional(optional($appraisalModel->employee)->designation)->title }} </h6>
                        <h6>Sub-Function:{{ optional(optional($appraisalModel->employee)->department)->title }}</h6>
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

            <legend class="text-uppercase font-size-lg font-weight-bold">Section A: GENERAL PERFORMANCE REQUIREMENTS
            </legend>
            <ul type="none">
                {{-- <li class="mt-2">
                <b>Name Of Employee: {{$appraisalModel->employee->getFullName()}} </b>
            </li> --}}
            </ul>

            <table class="table table-striped table-responsive">
                <thead class="text-white">
                    <tr>
                        <th width="5%">S.N</th>
                        <th>Competancies</th>
                        @if (isset($appraisalModel->self_evaluation_type) && is_null($appraisalModel->supervisor_evaluation_type))
                            @if ($appraisalModel->self_evaluation_type == 1)
                                <th>Rating</th>
                            @elseif ($appraisalModel->self_evaluation_type == 2)
                                <th>Self Comment</th>
                            @else
                                <th>Rating</th>
                                <th>Self Comment</th>
                            @endif
                        @elseif (isset($appraisalModel->self_evaluation_type) && isset($appraisalModel->supervisor_evaluation_type))
                            @if ($appraisalModel->self_evaluation_type == 1 && $appraisalModel->supervisor_evaluation_type == 1)
                                <th width="5%">Self Rating[1-5]</th>
                                <th width="25%">Self Comment[1-5]</th>
                                <th width ="5%">Reviewer Rating</th>
                                <th width ="25">Reviewer Comment</th>
                            @elseif ($appraisalModel->self_evaluation_type == 1 && $appraisalModel->supervisor_evaluation_type == 2)
                                <th>Rating</th>
                                <th>Remarks on Rating (Repporting Officer)</th>
                            @elseif ($appraisalModel->self_evaluation_type == 2 && $appraisalModel->supervisor_evaluation_type == 2)
                                <th>Self Comment</th>
                                <th>Comment By Supervisior</th>
                            @else
                                <th>Rating</th>
                                <th>Self Comment</th>
                            @endif
                        @else
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $firstRating = 0;
                        $lastRating = 0;
                        $firstRatingAverage = 0;
                        $lastRatingAverage = 0;
                        $finalRatingAverage = 0;
                        $count = 1;
                    @endphp
                    @if (count($reportData) > 0)
                        @foreach ($reportData as $key => $data)
                            <tr>
                                <td width="5%">{{ $count++ }}</td>
                                <td>
                                    <div style="width: 500px">
                                        <div class="font-weight-bold" style="font-size:1.2em;">{{ $data['competency'] }}
                                        </div>
                                        <ul>
                                            @php
                                                $questions = $competancyQuestions->where('competency_id', $key)->get();
                                            @endphp
                                            @foreach ($questions as $question)
                                                <li>
                                                    {{ $question->question }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                @foreach ($data['answer'] as $key => $answer)
                                    @if ($answer['score'] != null)
                                        @php
                                            if ($key == 0) {
                                                $firstRating += $answer['score'];
                                            } else {
                                                $lastRating += $answer['score'];
                                            }
                                        @endphp
                                        @if (isset($appraisalModel->self_evaluation_type) && is_null($appraisalModel->supervisor_evaluation_type))
                                            @if ($appraisalModel->self_evaluation_type == 1)
                                                <td>{{ $answer['score'] }}</td>
                                            @elseif ($appraisalModel->self_evaluation_type == 2)
                                                <td>{{ $answer['comment'] }}</td>
                                            @else
                                                <td>{{ $answer['score'] }}</td>
                                                <td>{{ $answer['comment'] }}</td>
                                            @endif
                                        @elseif (isset($appraisalModel->self_evaluation_type) && isset($appraisalModel->supervisor_evaluation_type))
                                            @if ($appraisalModel->self_evaluation_type == 1 && $appraisalModel->supervisor_evaluation_type == 1)
                                                <td>
                                                    <div style="width: 20px">{{ $answer['score'] }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="width: 300px">{{ $answer['comment'] }}
                                                    </div>
                                                </td>
                                            @elseif ($appraisalModel->self_evaluation_type == 1 && $appraisalModel->supervisor_evaluation_type == 2)
                                                @if ($answer['score'] != '0')
                                                    <td>
                                                        <div style="width: 20px">{{ $answer['score'] }}
                                                        </div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div style="width: 300px">{{ $answer['comment'] }}
                                                        </div>
                                                    </td>
                                                @endif
                                            @elseif ($appraisalModel->self_evaluation_type == 2 && $appraisalModel->supervisor_evaluation_type == 2)
                                                <td>{{ $answer['comment'] }}</td>
                                            @else
                                            @endif
                                        @else
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <footer>
                    @if (count($reportData) > 0)
                        @php
                            $firstRatingAverage = round($firstRating / count($reportData), 2);
                            $lastRatingAverage = round($lastRating / count($reportData), 2);
                        @endphp
                    @endif


                    @if (count($reportData) > 0)
                        @if (optional($appraisalModel->questionnaire)->form == 1)
                            <tr>
                                <td>Rating:</td>
                                <td></td>
                                <td>{{ $firstRatingAverage }}</td>
                                <td></td>
                                <td>{{ $lastRatingAverage }}</td>
                                <td></td>
                            </tr>
                            @php
                                $finalRatingAverage = ($firstRatingAverage + $lastRatingAverage) / 2;
                            @endphp
                            <tr>
                                <td>Average Rating:</td>
                                <td class="text-center">{{ round($finalRatingAverage, 2) }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @elseif(optional($appraisalModel->questionnaire)->form == 2)
                            <tr>
                                <td></td>
                                <td>Overall Rating:</td>
                                <td>{{ $firstRatingAverage }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @elseif(optional($appraisalModel->questionnaire)->form == 3)
                            <tr>
                                <td>Total Score:</td>
                                <td></td>
                                <td>{{ $firstRatingAverage }}</td>
                                <td>{{ $lastRatingAverage }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $finalRatingAverage = ($firstRatingAverage + $lastRatingAverage) / 2;
                            @endphp
                            <tr>
                                <td>Final Score:</td>
                                <td class="text-center">{{ round($finalRatingAverage, 2) }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @endif
                </footer>
            </table>

            <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION B: DEVELOPMENT PLAN</legend>
            <label class="col-lg-12">
                <h6>Strength (Describe his/ her top strengths)
                </h6>
            </label>
            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                    {!! Form::textarea('strength', $firstApprovalComment->strength ?? '', [
                        'class' => 'form-control',
                        'rows' => 3,
                        'readonly',
                    ]) !!}
                </div>
                @if ($errors->has('strength'))
                    <div class="error text-danger">{{ $errors->first('strength') }}</div>
                @endif
            </div>

            <label class="col-lg-12 mt-3">
                <h6>Development Area (Describe the development that s/he needs)
                </h6>
            </label>
            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::textarea('development', $firstApprovalComment->development ?? '', [
                        'class' => 'form-control',
                        'rows' => 3,
                        'readonly',
                    ]) !!}
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
                    {!! Form::textarea('support', $firstApprovalComment->support ?? '', [
                        'class' => 'form-control',
                        'rows' => 3,
                        'readonly',
                    ]) !!}
                </div>
                @if ($errors->has('support'))
                    <div class="error text-danger">{{ $errors->first('support') }}</div>
                @endif
            </div>

            <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION C: REVIEW By NEXT LEVEL</legend>
            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                    {!! Form::textarea('reviewer_comment', $lastApprovalComment->reviewer_comment ?? '', [
                        'class' => 'form-control disabled',
                        'rows' => 3,
                        'readonly',
                    ]) !!}
                </div>
                @if ($errors->has('reviewer_comment'))
                    <div class="error text-danger">{{ $errors->first('reviewer_comment') }}</div>
                @endif
            </div>
            {{-- <div class="row mt-3">
            <div class="col-md-2 ml-2">
                <h6>Reviewer's Name and Signature: </h6>
            </div>
            @if ($employeeApproval)
                <div class="col-md-8">
                    <h6 style="text-decoration: underline;">{{optional(optional($employeeApproval->firstApprovalUserModel)->userEmployer)->getFullname()}}</h6>
                </div>
            @endif
        </div> --}}

            <legend class="text-uppercase font-size-lg font-weight-bold mt-3">SECTION D: HR Suggestion
            </legend>
            <div class="row">
                <div class="col-md-2 ml-2">
                    <h6>Average score:</h6>
                </div>
                <div class="col-md-8">
                    <h6 style="text-decoration: underline;">{{ round(($finalRatingAverage / 5) * 100) . '%' }}</h6>
                </div>
            </div>
            <label class="col-lg-12">
                <h6>Comments/Remarks:</h6>
            </label>
            <div class="col-lg-12 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- {!! Form::text('strength', null, ['class' => 'form-control']) !!} --}}
                    {!! Form::textarea('reviewer_comment', $hrComment->reviewer_comment ?? '', [
                        'class' => 'form-control',
                        'rows' => 3,
                        'readOnly',
                    ]) !!}
                </div>
                @if ($errors->has('reviewer_comment'))
                    <div class="error text-danger">{{ $errors->first('reviewer_comment') }}</div>
                @endif
            </div>

            <legend class="text-uppercase font-size-lg font-weight-bold my-3">SECTION E: ACKNOWLEDGEMENT</legend>
            <div class="row">
                <div class="col-md-2 ml-2">
                    <h6>Employee Name and Signature:</h6>
                </div>
                <div class="col-md-8">
                    <h6 style="text-decoration: underline;">{{ $appraisalModel->employee->getFullName() }}</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 ml-2">
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




    </div>
@endsection
