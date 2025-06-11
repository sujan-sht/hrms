@extends('admin::layout')
@section('title') View Survey @endSection
@section('breadcrum')
<a class="breadcrumb-item active">View Survey</a>
@stop

@section('content')
{!! Form::open([
    'route' => 'survey.storeResponse',
    'method' => 'POST',
    'class' => 'form-horizontal',
    'id' => 'surveyResponseFormSubmit',
    'role' => 'form',
]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-12">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Survey Title</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold mr-3">{{ $surveyModel->title }}</span>
                            </li>
                        </ul>
                        {!! Form::hidden('survey_id', $surveyModel->id, []) !!}
                        {!! Form::hidden('employee_id', optional(auth()->user()->userEmployer)->id, []) !!}
                        <br>
                        <legend class="text-uppercase font-size-sm font-weight-bold">List of Questions</legend>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="row">
                                    @foreach ($surveyModel->surveyQuestions as $key => $surveyQuestion)
                                        <div class="col-lg-12 mb-2">
                                            <div class="row">
                                                <div class="col-form-label col-lg-12">
                                                    <span class="font-weight-semibold mr-1">Q{{ ++$key }}.</span>
                                                    <span>{{ $surveyQuestion->question }}</span>
                                                </div>
                                                @if ($surveyQuestion->question_type == 1)
                                                    @if ($surveyQuestion->multiple_option_status == 10)
                                                        <div class="col-lg-12 mb-3">
                                                            <div class="row">
                                                                <div
                                                                    class="col-lg-12 form-group-feedback form-group-feedback-right">
                                                                    <div class="input-group">
                                                                        <div class="p-1 rounded">
                                                                            <div
                                                                                class="custom-control custom-radio custom-control-inline">
                                                                                {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', $surveyQuestion->option_a, '', ['class' => 'custom-control-input', 'id' => 'singleOptionA']) }}

                                                                                <label class="custom-control-label"
                                                                                    for="singleOptionA">{{$surveyQuestion->option_a}}</label>
                                                                            </div>

                                                                            <div
                                                                                class="custom-control custom-radio custom-control-inline">
                                                                                {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', $surveyQuestion->option_b, '', ['class' => 'custom-control-input', 'id' => 'singleOptionB']) }}

                                                                                <label class="custom-control-label"
                                                                                    for="singleOptionB">{{$surveyQuestion->option_b}}</label>
                                                                            </div>

                                                                            <div
                                                                                class="custom-control custom-radio custom-control-inline">
                                                                                {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', $surveyQuestion->option_c, '', ['class' => 'custom-control-input', 'id' => 'singleOptionC']) }}

                                                                                <label class="custom-control-label"
                                                                                    for="singleOptionC">{{$surveyQuestion->option_c}}</label>
                                                                            </div>

                                                                            <div
                                                                                class="custom-control custom-radio custom-control-inline">
                                                                                {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', $surveyQuestion->option_d, '', ['class' => 'custom-control-input', 'id' => 'singleOptionD']) }}

                                                                                <label class="custom-control-label"
                                                                                    for="singleOptionD">{{$surveyQuestion->option_d}}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($surveyQuestion->multiple_option_status == 11)
                                                        <div class="col-lg-12 mb-3">
                                                            <div class="row">
                                                                <div
                                                                    class="col-lg-12 form-group-feedback form-group-feedback-right">
                                                                    <div class="input-group">
                                                                        <div class="form-check mr-4">
                                                                            <input name="survey_questions[{{$surveyQuestion->id}}][]"
                                                                                class="form-check-input" type="checkbox"
                                                                                value={{$surveyQuestion->option_a}} id="multipleOptionA">
                                                                            <label class="form-check-label"
                                                                                for="multipleOptionA">
                                                                                {{$surveyQuestion->option_a}}
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check mr-4">
                                                                            <input name="survey_questions[{{$surveyQuestion->id}}][]"
                                                                                class="form-check-input" type="checkbox"
                                                                                value={{$surveyQuestion->option_b}} id="multipleOptionB">
                                                                            <label class="form-check-label"
                                                                                for="multipleOptionB">
                                                                                {{$surveyQuestion->option_b}}
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check mr-4">
                                                                            <input name="survey_questions[{{$surveyQuestion->id}}][]"
                                                                                class="form-check-input" type="checkbox"
                                                                                value={{$surveyQuestion->option_c}} id="multipleOptionC">
                                                                            <label class="form-check-label"
                                                                                for="multipleOptionC">
                                                                                {{$surveyQuestion->option_c}}
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check mr-4">
                                                                            <input name="survey_questions[{{$surveyQuestion->id}}][]"
                                                                                class="form-check-input" type="checkbox"
                                                                                value={{$surveyQuestion->option_d}} id="multipleOptionD">
                                                                            <label class="form-check-label"
                                                                                for="multipleOptionD">
                                                                                {{$surveyQuestion->option_d}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif ($surveyQuestion->question_type == 2)
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-12 form-group-feedback form-group-feedback-right">
                                                                <div class="input-group">
                                                                    <div class="p-1 rounded">
                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', 'True', '', ['class' => 'custom-control-input', 'id' => 'trueId']) }}

                                                                            <label class="custom-control-label"
                                                                                for="trueId">True</label>
                                                                        </div>

                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', 'False', '', ['class' => 'custom-control-input', 'id' => 'falseId']) }}

                                                                            <label class="custom-control-label"
                                                                                for="falseId">False</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($surveyQuestion->question_type == 3)
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-12 form-group-feedback form-group-feedback-right">
                                                                <div class="input-group">
                                                                    <div class="p-1 rounded">
                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', 'Yes', '', ['class' => 'custom-control-input', 'id' => 'yesId']) }}

                                                                            <label class="custom-control-label"
                                                                                for="yesId">Yes</label>
                                                                        </div>

                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            {{ Form::radio('survey_questions['.$surveyQuestion->id.'][]', 'No', '', ['class' => 'custom-control-input', 'id' => 'noId']) }}

                                                                            <label class="custom-control-label"
                                                                                for="noId">No</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($surveyQuestion->question_type == 4)
                                                    <div
                                                        class="col-lg-12 form-group-feedback form-group-feedback-right">
                                                        <div class="input-group">
                                                            {!! Form::textarea('survey_questions['.$surveyQuestion->id.'][]', null, [
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Write description here..',
                                                                'required'
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>Save Record</button>
</div>

{!! Form::close() !!}

@endSection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
@endsection
