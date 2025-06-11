<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Questions</legend>
                <div class="row">
                    @foreach($questionsList as $key => $questionsList)
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-form-label col-lg-10">Q{{ ++$key }}. {{ $questionsList->question }}</label>
                                <div class="col-lg-2 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::hidden('questions[]', $questionsList->id, []) !!}
                                        {!! Form::select('scores[]', $scoreList, null, ['class'=>'form-control select-search']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Applicant Detail</legend>
                <input type="hidden" name="applicant_id" value="{{ optional($interviewModel->applicantModel)->id }}">
                <input type="hidden" name="interview_id" value="{{ $interviewModel->id }}">
                <input type="hidden" name="interview_level_id" value="{{ $interviewModel->interview_level_id }}">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Level of Interview :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('interview_level', optional($interviewModel->interviewLevelModel)->title, ['class'=>'form-control', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Applicant :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('applicant', optional($interviewModel->applicantModel)->getFullName(), ['class'=>'form-control', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Interviewer :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee_id', $interviewerList, null, ['placeholder'=>'Select Interviewer', 'class'=>'form-control select-search']) !!}
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
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
<!-- validation js -->
<script src="{{ asset('admin/validation/applicant.js')}}"></script>

<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
<!-- Clock picker js -->
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script>
    $(document).ready(function () {

        $('#start-timepicker').clockTimePicker();

    });
</script>

@endSection
