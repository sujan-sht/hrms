<div class="row">
    @if ($isEdit)
        <div class="col-md-8">
        @else
            <div class="col-md-12">
    @endif
    <div class="card">
        <div class="card-body">
            <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Applicant :<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            @if (isset($applicantId))
                                {!! Form::hidden('applicant_id', $applicantId, []) !!}
                                <div class="input-group">
                                    {!! Form::select('applicant_id', $applicantList, $applicantId, [
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                </div>
                            @else
                                <div class="input-group">
                                    {!! Form::select('applicant_id', $applicantList, null, [
                                        'placeholder' => 'Select Applicant',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('applicant_id'))
                                    <div class="error text-danger">{{ $errors->first('applicant_id') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Level of Interview :<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('interview_level_id', $interviewLevelList, null, [
                                    'placeholder' => 'Select Interview Level',
                                    'class' => 'form-control select-search',
                                ]) !!}
                            </div>
                            @if ($errors->has('interview_level_id'))
                                <div class="error text-danger">{{ $errors->first('interview_level_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Date :<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-calendar2"></i></span>
                                </span>
                                @php
                                    $dateData1 = null;
                                    if (setting('calendar_type') == 'BS') {
                                        $clData = 'form-control nepali-calendar';
                                        if ($isEdit && $interviewModel['date']) {
                                            $dateData1 = date_converter()->eng_to_nep_convert($interviewModel['date']);
                                        }
                                    } else {
                                        $clData = 'form-control daterange-single';
                                        if ($isEdit && $interviewModel['date']) {
                                            $dateData1 = $interviewModel['date'];
                                        }
                                    }
                                @endphp
                                {!! Form::text('date', $dateData1, ['placeholder' => 'e.g: YYYY-MM-DD', 'class' => $clData]) !!}
                            </div>
                            @if ($errors->has('date'))
                                <div class="error text-danger">{{ $errors->first('date') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Time :</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-watch2"></i></span>
                                </span>
                                {!! Form::time('time', null, ['id' => 'start-timepicker', 'class' => 'form-control', 'placeholder' => '00:00']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Venue :</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('venue', null, ['placeholder' => 'e.g: Kathmandu', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @if (isset($status))
                    {!! Form::hidden('status', 1, []) !!}
                @else
                    <div class="col-lg-6 mb-4">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Status :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', $statusList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@if ($isEdit)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">MRF Detail</legend>
                @if (isset($applicantModel))
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-lg-3">MRF ID :</label>
                                <div class="col-lg-9">{{ optional($applicantModel->mrfModel)->reference_number }}</div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-lg-3">Title :</label>
                                <div class="col-lg-9">{{ optional($applicantModel->mrfModel)->title }}</div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-lg-3">Organization :</label>
                                <div class="col-lg-9">
                                    {{ optional(optional($applicantModel->mrfModel)->organizationModel)->name }}</div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-lg-3">Sub-Function :</label>
                                <div class="col-lg-9">
                                    {{ optional(optional($applicantModel->mrfModel)->getDepartment)->title }}</div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="row">
                                <label class="col-lg-3">Designation :</label>
                                <div class="col-lg-9">
                                    {{ optional(optional($applicantModel->mrfModel)->getDesignation)->title }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/applicant.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <!-- Clock picker js -->
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // $('#start-timepicker').clockTimePicker();

        });
    </script>
@endSection
