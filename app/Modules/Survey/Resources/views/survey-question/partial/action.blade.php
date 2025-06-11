<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Survey Title</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold mr-3">{{ $surveyModel->title }}</span>
                            </li>
                        </ul>
                        {!! Form::hidden('survey_id', $surveyModel->id, []) !!}
                        <br>
                        <legend class="text-uppercase font-size-sm font-weight-bold">List of Questions</legend>
                        <ul class="media-list">
                            @foreach($surveyModel->surveyQuestions as $key => $surveyQuestion)
                                <li class="media mt-2">
                                    <span class="font-weight-semibold mr-3">Q{{ ++$key }}.</span>
                                    <span>{{ $surveyQuestion->question }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Survey Question Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Question : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('question', null, ['class' => 'form-control', 'placeholder' => 'Write question here..']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Question Type : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('question_type', $questionType,null,['class' => 'form-control select-search questionType', 'placeholder' => 'Select Type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold optionSection" style="display:none;">Multiple Choice Option</legend>
                <div class="form-group optionSection" style="display:none;">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="" class="col-form-label col-lg-4">Option A: <span class="text-danger">*</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('option_a', null, ['class'=>'form-control', 'placeholder'=>'Enter Option A']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="" class="col-form-label col-lg-4">Option B: <span class="text-danger">*</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('option_b', null, ['class'=>'form-control', 'placeholder'=>'Enter Option B']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="" class="col-form-label col-lg-4">Option C: <span class="text-danger">*</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('option_c', null, ['class'=>'form-control', 'placeholder'=>'Enter Option C']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="" class="col-form-label col-lg-4">Option D: <span class="text-danger">*</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('option_d', null, ['class'=>'form-control', 'placeholder'=>'Enter Option D']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <label for="" class="col-form-label col-lg-4">Multiple Option Status: <span class="text-danger">*</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('multiple_option_status', $multipleOptionStatus, isset($_GET['multiple_option_status']) ?? null, [
                                            'class' => 'form-control select-search',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">Description</legend>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-1">Description : </label>
                            <div class="col-lg-11 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
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
    <a href="{{ route('surveyQuestion.index', $surveyModel->id) }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>

    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>


@section('script')
    <script src="{{ asset('admin/validation/survey-question.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>




    <script>
        $(document).ready(function() {
            $('.questionType').on('change', function () {
                let type = $(this).val()
                if(type == 1){
                    $('.optionSection').show()
                }else{
                    $('.optionSection').hide()
                }
            })

            $('.questionType').trigger('change')
        });
    </script>
@endSection
